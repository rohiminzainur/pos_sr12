<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Yajra\DataTables\Facades\DataTables;


class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('expense.index');
    }

    public function data()
    {
        $expense = Expense::orderBy('id', 'desc')->get();
        return datatables()
            ->of($expense)
            ->addIndexColumn()
            ->addColumn('created_at', function ($expense) {
                return tanggal_indonesia($expense->created_at, false);
            })
            ->addColumn('nominal', function ($expense) {
                return 'Rp. ' . format_uang($expense->nominal);
            })
            ->addColumn('aksi', function ($expense) {
                return '<div class="btn-group"><button type="button" onclick="editForm(`' . route('expenses.update', $expense->id) . '`)" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i></button> <button type="button" onclick="deleteData(`' . route('expenses.destroy', $expense->id) . '`)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></div>
                ';
            })
            ->rawColumns(['aksi', 'created_at'])
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
        $expense = $request->all();
        Expense::create($expense);

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
        $expense = Expense::find($id);
        return response()->json($expense);
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

        $expense = Expense::findOrFail($id);

        $expense->update($data);

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
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return response(null, 204);
    }
}