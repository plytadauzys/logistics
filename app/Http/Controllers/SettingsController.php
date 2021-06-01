<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SettingsController extends Controller
{
    function index() {
        $settings = Setting::all();
        return view('settings', ['data' => $settings]);
    }
    function edit(request $req) {
        if($req->type == 'danger') {
            for($i = 1; $i < 6; $i++) {
                $setting = Setting::where('id',$i)->first();
                $setting->value = $req->input('value'.$i);
                $setting->save();
            }
        } elseif ($req->type == 'warning') {
            for($i = 6; $i < 11; $i++) {
                $setting = Setting::where('id',$i)->first();
                $setting->value = $req->input('value'.$i);
                $setting->save();
            }
        }
        return Redirect::back()->with('message', 'Nustatymai sėkmingai pakeisti.');
    }
}
