<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Expedition;
use App\Models\ExpeditionHistory;
use App\Models\Manager;
use App\Models\Supplier;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Testing\Fluent\Concerns\Has;

class ManagerController extends Controller
{
    // login, logout
    function index() {
        $manager = Manager::where('email','=',session('user'))->first();
        $expedition = Expedition::all();
        $expeditionHistory = ExpeditionHistory::all();
        $client = Client::all();
        $supplier = Supplier::all();
        $settings = Setting::all();
        return view('managerHome',['data' => $expedition, 'manager' => $manager, 'expHist' => $expeditionHistory,
            'client' => $client, 'supplier' => $supplier, 'settings' => $settings]);
    }
    function login (request $req) {
        $manager = Manager::select('email')->where('email',$req->email)->exists();
        if (!$manager) {
            return Redirect::back()->with('error', 'Neteisingas el. paštas arba slaptažodis.');
        }
        else {
            $manager = Manager::where('email',$req->email)->first();
            if(Hash::check($req->password,$manager->password))
                $req->session()->put('user', $req->email);
            else
                return Redirect::back()->with('error', 'Neteisingas el. paštas arba slaptažodis.');
        }
        return Redirect::to('managerHome');
    }
    function logout () {
        if(session()->has('admin') && session()->has('user')) {
            session()->pull('admin');
            session()->pull('user');
        } else if(session()->has('admin')) {
            session()->pull('admin');
        } else if(session()->has('user')) {
            session()->pull('user');
        }
        return Redirect('/');
    }
}
