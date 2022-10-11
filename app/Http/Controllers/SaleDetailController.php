<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Member;
use App\Models\Setting;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;

class SaleDetailController extends Controller
{
    public function index()
    {
        $product = Product::orderBy('name')->get();
        $member = Member::orderBy('name')->get();
        $discount = Setting::first()->discount ?? 0;
        // Cek apakah ada transaksi yang sedang berjalan
        if ($sales_id = session('id')) {
            $sale = Sale::find($sales_id);
            $memberSelected = $sale->member ?? new Member();


            return view('sale-detail.index', compact('product', 'member', 'discount', 'sales_id', 'sale', 'memberSelected'));
        } else {
            if (auth()->user()->level == 1) {
                return redirect()->route('transactions.new');
            } else {
                return redirect()->route('home');
            }
        }
    }

    public function data($id)
    {
        $detail = SaleDetail::with('product')
            ->where('sales_id', $id)
            ->get();


        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['code_product'] = '<span class="label label-info">' . $item->product['code_product'] . '</span>';
            $row['name'] = $item->product['name'];
            $row['selling_price'] = 'Rp. ' . format_uang($item->selling_price);
            $row['total'] = '<input type="number" class="form-control input-sm quantity" data-id="' . $item->id . '" value="' . $item->total . '">';
            $row['discount'] = $item->discount . ' %';
            $row['subtotal'] = 'Rp. ' . format_uang($item->subtotal);
            $row['aksi'] = '<div class="btn-group"><button type="button" onclick="deleteData(`' . route('transactions.destroy', $item->id) . '`)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></div>';
            $data[] = $row;

            $total += $item->selling_price * $item->total;
            $total_item += $item->total;
        }
        $data[] = [
            'code_product' => '<div class="total hide">' . $total .
                '</div> 
            <div class="total_item hide">' . $total_item . '</div>',
            'name' => '',
            'selling_price' => '',
            'total' => '',
            'discount' => '',
            'subtotal' => '',
            'aksi' => '',

        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'code_product', 'total'])
            ->make(true);
    }

    public function store(Request $request)
    {

        $product = Product::where('id', $request->products_id)->first();
        if (!$product) {
            return response()->json('Data gagal disimpan', 400);
        }
        $detail = new SaleDetail();
        $detail->sales_id = $request->sales_id;
        $detail->products_id = $product->id;
        $detail->selling_price = $product->selling_price;
        $detail->total = 1;
        $detail->discount = $product->discount;
        $detail->subtotal = $product->selling_price;
        $detail->save();
        // dd($detail);
        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = SaleDetail::find($id);
        $detail->total = $request->total;
        $detail->subtotal = $detail->selling_price * $request->total - (($detail->discount * $request->total) / 100 * $detail->selling_price);
        $detail->update();
    }

    public function destroy($id)
    {
        $detail = SaleDetail::findOrFail($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($discount = 0, $total = 0, $received = 0)
    {
        $paid = $total - ($discount / 100 * $total);
        $back = ($received != 0) ? $received - $paid : 0;
        $data = [
            'totalrp' => format_uang($total),
            'paid' => $paid,
            'paidrp' => format_uang($paid),
            'terbilang' => ucwords(terbilang($paid) . ' Rupiah'),
            'backrp' => format_uang($back),
            'back_terbilang' => ucwords(terbilang($back) . ' Rupiah')
        ];
        return response()->json($data);
    }
}