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
                $otherSetting = Setting::where('id',$i+5)->first();
                if($req->input('value'.$i) >= $otherSetting->value) {
                    $setting->value = $req->input('value'.$i);
                    $setting->save();
                } else
                    return Redirect::back()->with('error','Negali būti mažiau negu mažiau svarbaus įspėjimo.');
            }
        } elseif ($req->type == 'warning') {
            for($i = 6; $i < 11; $i++) {
                $setting = Setting::where('id',$i)->first();
                $otherSetting = Setting::where('id',$i-5)->first();
                if($req->input('value'.$i) <= $otherSetting->value) {
                    $setting->value = $req->input('value'.$i);
                    $setting->save();
                } else
                    return Redirect::back()->with('error','Negali būti daugiau negu svarbaus įspėjimo.');
            }
        }
        return Redirect::back()->with('message', 'Nustatymai sėkmingai pakeisti.');
    }
}
