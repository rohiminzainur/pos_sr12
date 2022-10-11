<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;

class SaleController extends Controller
{
    public function index()
    {
        return view('sale.index');
    }
    public function data()
    {
        $sale = Sale::orderBy('id', 'desc')->get();
        return datatables()
            ->of($sale)
            ->addIndexColumn()
            ->addColumn('date', function ($sale) {
                return tanggal_indonesia($sale->created_at, false);
            })
            ->addColumn('code_member', function ($sale) {
                $member = $sale->member->code_member ?? '';
                return '<span class="label label-success">' . $member . '</spa>';
            })
            ->addColumn('total_item', function ($sale) {
                return format_uang($sale->total_item);
            })
            ->addColumn('total_price', function ($sale) {
                return 'Rp. ' . format_uang($sale->total_price);
            })
            ->addColumn('paid', function ($sale) {
                return 'Rp. ' . format_uang($sale->paid);
            })
            ->editColumn('discount', function ($sale) {
                return $sale->discount . ' %';
            })
            ->editColumn('cashier', function ($sale) {
                return $sale->user->name ?? '';
            })
            ->addColumn('aksi', function ($sale) {
                return '<div class="btn-group"><button type="button" onclick="showDetail(`' . route('sales.show', $sale->id) . '`)" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></button><button type="button" onclick="deleteData(`' . route('sales.destroy', $sale->id) . '`)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></div>
                ';
            })
            ->rawColumns(['aksi', 'code_member'])
            ->make(true);
    }
    public function create()
    {
        $sale = new Sale();
        $sale->members_id = null;
        $sale->total_item = 0;
        $sale->total_price = 0;
        $sale->discount = 0;
        $sale->paid = 0;
        $sale->received = 0;
        $sale->users_id = auth()->id();
        $sale->save();

        session(['id' => $sale->id]);
        return redirect()->route('transactions.index');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $item = Sale::findOrFail($data['sales_id']);
        $data['members_id'] = $request->members_id;
        $data['total_item'] = $request->total_item;
        $data['total_price'] = $request->total;
        $data['discount'] = $request->discount;
        $data['paid'] = $request->paid;
        $data['received'] = $request->received;
        $item->update($data);


        $detail = SaleDetail::where('sales_id', $item['id'])->get();

        foreach ($detail as $item) {
            $product = Product::find($item['products_id']);
            $product->stock -= $item['total'];
            $product->update();
        }
        return redirect()->route('transactions.finish');
    }

    public function show($id)
    {
        $detail = SaleDetail::with('product')->where('sales_id', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('code_product', function ($detail) {
                return '<span class="label label-info">' . $detail->product->code_product . '</span>';
            })
            ->addColumn('name', function ($detail) {
                return $detail->product->name;
            })
            ->addColumn('selling_price', function ($detail) {
                return 'Rp. ' . format_uang($detail->selling_price);
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
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $detail = SaleDetail::where('sales_id', $sale->id)->get();
        foreach ($detail as $item) {
            $item->delete();
        }
        $sale->delete();

        return response(null, 204);
    }

    public function finish()
    {
        $setting = Setting::first();
        return view('sale.finish', compact('setting'));
    }

    public function notaSmall()
    {
        $setting = Setting::first();
        $sale = Sale::find(session('id'));
        if (!$sale) {
            abort(404);
        }
        $detail = SaleDetail::with('product')
            ->where('sales_id', session('id'))
            ->get();

        return view('sale.nota_small', compact('setting', 'sale', 'detail'));
    }
    public function notaBig()
    {
        $setting = Setting::first();
        $sale = Sale::find(session('id'));
        if (!$sale) {
            abort(404);
        }
        $detail = SaleDetail::with('product')
            ->where('sales_id', session('id'))
            ->get();

        $pdf = PDF::loadView('sale.nota_big', compact('setting', 'sale', 'detail'));
        $pdf->setPaper(0, 0, 609, 440, 'potrait');
        return $pdf->stream('Transaction-' . date('Y-m-d-his') . '.pdf');
    }
}