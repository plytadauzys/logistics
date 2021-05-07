<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

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
    function createUser(request $req) {
        $validator = Validator::make(
            [
                'id' => $req->input('id'),
                'first_name' => $req->input('first_name'),
                'last_name' => $req->input('last_name'),
                'email' => $req->input('email'),
                'password' => $req->input('password')
            ],
            [
                'id' => 'unique:managers,id',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|unique:managers,email',
                'password' => 'required|min:8'
            ]
        );
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else {
            $manager = new Manager();
            if($req->id != null)
                $manager->id = $req->id;
            $manager->first_name = $req->first_name;
            $manager->last_name = $req->last_name;
            $manager->email = $req->email;
            $manager->password = $req->password;
            $manager->save();
        }
        return Redirect::back()->with('message', 'Vartotojas sėkmingai sukurtas.');
    }
    function editUser(request $req) {
        $manager = Manager::where('id','=',$req->idH)->first();
        $validator = Validator::make(
            [
                'id' => $req->input('id'),
                'first_name' => $req->input('firstName'),
                'last_name' => $req->input('lastName'),
                'email' => $req->input('email'),
                'password' => $req->input('password')
            ],
            [
                'id' => 'unique:managers,id,'.$manager->id,
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|unique:managers,email,'.$manager->id,
                'password' => 'required|min:8'
            ]
        );
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else {
            if($req->id != null)
                $manager->id = $req->id;
            $manager->first_name = $req->firstName;
            $manager->last_name = $req->lastName;
            $manager->email = $req->email;
            $manager->password = $req->password;
            $manager->save();
        }
        return Redirect::to('adminHome')->with('message','Duomenys pakeisti sėkmingai.');
    }
    function removeUser($id) {
        $manager = Manager::where('id','=',$id)->first();
        if($manager != null) {
            $manager->delete();
            return Redirect::back()->with('message','Vartotojas sėkmingai ištrintas.');
        }
        return Redirect::back()->with('error','Vartotojas neištrintas');
    }
}
