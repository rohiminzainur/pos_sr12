<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use Yajra\DataTables\Facades\DataTables;


class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supplier = Supplier::orderBy('name')->get();
        return view('purchase.index', compact('supplier'));
    }

    public function data()
    {
        $purchase = Purchase::orderBy('id', 'desc')->get();
        return datatables()
            ->of($purchase)
            ->addIndexColumn()
            ->addColumn('date', function ($purchase) {
                return tanggal_indonesia($purchase->created_at, false);
            })
            ->addColumn('supplier', function ($purchase) {
                return $purchase->supplier->name;
            })
            ->addColumn('total_item', function ($purchase) {
                return format_uang($purchase->total_item);
            })
            ->addColumn('total_price', function ($purchase) {
                return 'Rp. ' . format_uang($purchase->total_price);
            })
            ->addColumn('paid', function ($purchase) {
                return 'Rp. ' . format_uang($purchase->paid);
            })
            ->editColumn('discount', function ($purchase) {
                return $purchase->discount . ' %';
            })
            ->addColumn('aksi', function ($purchase) {
                return '<div class="btn-group"><button type="button" onclick="showDetail(`' . route('purchases.show', $purchase->id) . '`)" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></button><button type="button" onclick="deleteData(`' . route('purchases.destroy', $purchase->id) . '`)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></div>
                ';
            })
            ->rawColumns(['aksi', 'date'])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $purchase = new Purchase();
        $purchase->suppliers_id = $id;
        $purchase->total_item = 0;
        $purchase->total_price = 0;
        $purchase->discount = 0;
        $purchase->paid = 0;
        $purchase->save();

        session(['purchases_id' => $purchase->id]);
        session(['suppliers_id' => $purchase->suppliers_id]);

        return redirect()->route('purchase-details.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->all();
        // dd($data);
        $item = Purchase::findOrFail($data['purchases_id']);
        $data['total_item'] = $request->total_item;
        $data['total_price'] = $request->total;
        $data['discount'] = $request->discount;
        $data['paid'] = $request->paid;
        $item->update($data);


        $detail = PurchaseDetail::where('purchases_id', $item['id'])->get();

        foreach ($detail as $item) {
            $product = Product::find($item['products_id']);
            $product->stock += $item['total'];
            $product->update();
        }
        return redirect()->route('purchases.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detail = PurchaseDetail::with('product')->where('purchases_id', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('code_product', function ($detail) {
                return '<span class="label label-info">' . $detail->product->code_product . '</span>';
            })
            ->addColumn('name', function ($detail) {
                return $detail->product->name;
            })
            ->addColumn('purchase_price', function ($detail) {
                return 'Rp. ' . format_uang($detail->purchase_price);
            })
            ->addColumn('total', function ($detail) {
                return format_uang($detail->total);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. ' . format_uang($detail->subtotal);
            })

            ->rawColumns(['code_product'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);
        $detail = PurchaseDetail::where('purchases_id', $purchase->id)->get();
        foreach ($detail as $item) {
            $item->delete();
        }
        $purchase->delete();

        return response(null, 204);
    }
}