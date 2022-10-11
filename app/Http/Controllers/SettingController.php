<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        return view('setting.index');
    }

    public function show()
    {
        return Setting::first();
    }
    public function update(Request $request)
    {
        $setting = Setting::first();
        $setting->company_name = $request->company_name;
        $setting->phone_number = $request->phone_number;
        $setting->address = $request->address;
        $setting->discount = $request->discount;
        $setting->type_nota = $request->type_nota;

        if ($request->hasFile('path_logo')) {
            $file = $request->file('path_logo');
            $name = 'logo-' . date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/images'), $name);

            $setting->path_logo = "/images/$name";
        }

        if ($request->hasFile('path_member_card')) {
            $file = $request->file('path_member_card');
            $name = 'logo-' . date('Y-m-dHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/images'), $name);

            $setting->path_member_card = "/images/$name";
        }

        $setting->update();

        return response()->json('Data berhasil disimpan', 200);
    }
}