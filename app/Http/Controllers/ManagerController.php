<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ManagerController extends Controller
{
    // login, logout
    function index() {
        $manager = Manager::where('email','=',session('user'))->first();
        return view('adminHome',['data' => $manager]);
    }
    function login (request $req) {
        $manager = Manager::select('email')->where('email',$req->email)->exists();
        if (!$manager) {
            return Redirect::back()->with('error', 'Neteisingas el. paštas arba slaptažodis.');
        }
        else {
            $req->session()->put('user', $req->email);
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
