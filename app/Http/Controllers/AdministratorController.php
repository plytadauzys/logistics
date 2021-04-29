<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Redirect;

class AdministratorController extends Controller
{
    // login, logout, getUser, getUsers, createUser, editUser, removeUser,
    // searchForUser, filterUsers
    function index() {
        $admin = Administrator::where('email','=',session('admin'))->first();
        $managers = Manager::all();
        return view('adminHome',['data' => $admin, 'managers' => $managers]);
    }
    function login (request $req) {
        $admin = Administrator::select('email')->where('email',$req->email)->exists();
        if (!$admin) {
            return Redirect::back()->with('error', 'Neteisingas el. paštas arba slaptažodis.');
        }
        else {
            $req->session()->put('admin', $req->email);
        }
        return Redirect::to('adminHome');
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
    function editUser(request $req) {
        $manager = Manager::where('id','=',$req->id)->first();
        $manager->first_name = $req->firstName;
        $manager->last_name = $req->lastName;
        $manager->email = $req->email;
        $manager->password = $req->password;
        $manager->save();
        return Redirect::to('adminHome')->with('message','Duomenys pakeisti sėkmingai.');
    }
}
