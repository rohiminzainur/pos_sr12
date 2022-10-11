<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Setting;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use PDF;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('member.index');
    }

    public function data()
    {
        $member = Member::orderBy('code_member')->get();
        return datatables()
            ->of($member)
            ->addIndexColumn()
            ->addColumn('select_all', function ($member) {
                return '<input type="checkbox" name="id[]" value="' . $member->id . '">';
            })
            ->addColumn('code_member', function ($member) {
                return '<span class="label label-primary">' . 'M' . $member->code_member . '</span>';
            })
            ->addColumn('aksi', function ($member) {
                return '<div class="btn-group"><button type="button" onclick="editForm(`' . route('members.update', $member->id) . '`)" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i></button> <button type="button" onclick="deleteData(`' . route('members.destroy', $member->id) . '`)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></div>
                ';
            })
            ->rawColumns(['aksi', 'select_all', 'code_member'])
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
        $member = Member::latest()->first() ?? new Member();
        $code_member = (int)$member->code_member + 1;

        $member = new Member();
        $member->code_member = tambah_nol_didepan($code_member, 6);
        $member->name = $request->name;
        $member->phone_number = $request->phone_number;
        $member->address = $request->address;
        $member->save();

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
        $member = Member::find($id);
        return response()->json($member);
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
        $member = Member::findOrFail($id)->update($request->all());

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
        $member = Member::findOrFail($id);
        $member->delete();

        return response(null, 204);
    }
    public function printMember(Request $request)
    {
        $datamember = collect(array());
        foreach ($request->id as $id) {
            $member = Member::findOrFail($id);
            $datamember[] = $member;
        }
        $datamember = $datamember->chunk(2);
        $setting = Setting::first();

        $no = 1;
        $pdf = PDF::loadView('member.card_member', compact('datamember', 'no', 'setting'));
        $pdf->setPaper(array(0, 0, 566.93, 850.39), 'potrait');
        return $pdf->stream('member.pdf');
    }
}