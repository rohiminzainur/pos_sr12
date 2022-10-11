<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseDetailController extends Controller
{
    public function index()
    {
        $purchases_id = session('purchases_id');
        $product = Product::orderBy('name')->get();
        $supplier = Supplier::find(session('suppliers_id'));
        $discount = Purchase::find($purchases_id)->discount ?? 0;
        if (!$supplier) {
            abort(404);
        }
        return view('purchase-detail.index', compact('purchases_id', 'product', 'supplier', 'discount'));
    }

    public function data($id)
    {
        $detail = PurchaseDetail::with('product')
            ->where('purchases_id', $id)
            ->get();
        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['code_product'] = '<span class="label label-info">' . $item->product['code_product'] . '</span>';
            $row['name'] = $item->product['name'];
            $row['purchase_price'] = 'Rp. ' . format_uang($item->purchase_price);
            $row['total'] = '<input type="number" class="form-control input-sm quantity" data-id="' . $item->id . '" value="' . $item->total . '">';
            $row['subtotal'] = 'Rp. ' . format_uang($item->subtotal);
            $row['aksi'] = '<div class="btn-group"><button type="button" onclick="deleteData(`' . route('purchase-details.destroy', $item->id) . '`)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></div>';
            $data[] = $row;

            $total += $item->purchase_price * $item->total;
            $total_item += $item->total;
        }
        $data[] = [
            'code_product' => '<div class="total hide">' . $total .
                '</div> 
            <div class="total_item hide">' . $total_item . '</div>',
            'name' => '',
            'purchase_price' => '',
            'total' => '',
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
        $detail = new PurchaseDetail();
        $detail->purchases_id = $request->purchases_id;
        $detail->products_id = $product->id;
        $detail->purchase_price = $product->purchase_price;
        $detail->total = 1;
        $detail->subtotal = $product->purchase_price;
        $detail->save();
        // dd($detail);
        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = PurchaseDetail::find($id);
        $detail->total = $request->total;
        $detail->subtotal = $detail->purchase_price * $request->total;
        $detail->update();
    }

    public function destroy($id)
    {
        $detail = PurchaseDetail::findOrFail($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($discount, $total)
    {
        $paid = $total - ($discount / 100 * $total);
        $data = [
            'totalrp' => format_uang($total),
            'paid' => $paid,
            'paidrp' => format_uang($paid),
            'terbilang' => ucwords(terbilang($paid) . 'Rupiah')
        ];
        return response()->json($data);
    }
}