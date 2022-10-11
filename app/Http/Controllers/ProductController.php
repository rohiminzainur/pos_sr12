<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Category;
use Illuminate\Support\Str;
use PDF;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return view('product.index', compact('categories'));
    }

    public function data()
    {
        $product = Product::leftJoin('categories', 'categories.id', 'products.categories_id')
            ->select('products.*', 'categories.name as name_category')
            ->orderBy('id', 'desc')
            ->get();
        return datatables()
            ->of($product)
            ->addIndexColumn()
            ->addColumn('select_all', function ($product) {
                return '<input type="checkbox" name="id[]" value="' . $product->id . '">';
            })
            ->addColumn('code_product', function ($product) {
                return '<span class="label label-primary">' . $product->code_product . '</span>';
            })
            ->addColumn('purchase_price', function ($product) {
                return format_uang($product->purchase_price);
            })
            ->addColumn('selling_price', function ($product) {
                return format_uang($product->selling_price);
            })
            ->addColumn('stock', function ($product) {
                return format_uang($product->stock);
            })
            ->addColumn('aksi', function ($product) {
                return '<div class="btn-group"><button type="button" onclick="editForm(`' . route('products.update', $product->id) . '`)" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i></button> <button type="button" onclick="deleteData(`' . route('products.destroy', $product->id) . '`)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></div>
                ';
            })
            ->rawColumns(['aksi', 'code_product', 'select_all'])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $product = $request->all();
        $product = Product::latest()->first() ?? new Product();
        $request['code_product'] = 'SR' . tambah_nol_didepan((int)$product->id + 1, 6);

        $request['slug'] = Str::slug($product->name);
        // dd($product);
        $product = Product::create($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        return response()->json($product);
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
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $product = Product::findOrFail($id);
        $data['slug'] = Str::slug($request->name);


        $product->update($data);

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id as $id) {
            $product = Product::findOrFail($id);
            $product->delete();
        }
        return response(null, 204);
    }
    public function printBarcode(Request $request)
    {
        $dataproduct = array();
        foreach ($request->id as $id) {
            $product = Product::findOrFail($id);
            $dataproduct[] = $product;
        }
        $no = 1;
        $pdf = PDF::loadView('product.barcode', compact('dataproduct', 'no'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('product.pdf');
    }
}