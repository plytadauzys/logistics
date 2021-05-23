<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AdministratorController extends Controller
{
    // login, logout, getUser, getUsers, createUser, editUser, removeUser,
    // searchForUser, filterUsers
    function index() {
        $admin = Administrator::all();
        $managers = Manager::all();
        return view('adminHome',['data' => $admin, 'managers' => $managers]);
    }
    function login (request $req) {
        $admin = Administrator::select('email')->where('email',$req->email)->exists();
        if (!$admin) {
            return Redirect::back()->with('error', 'Neteisingas el. paštas arba slaptažodis.');
        }
        else {
            $admin = Administrator::where('email',$req->email)->first();
            if(Hash::check($req->password,$admin->password))
                $req->session()->put('admin', $req->email);
            else
                return Redirect::back()->with('error', 'Neteisingas el. paštas arba slaptažodis.');
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
        if($req->role === 'manager')
            return $this->createManager($req);
        else if($req->role === 'admin')
            return $this->createAdmin($req);
        else
            return Redirect::back()->with('error','Vartotojas nesukurtas');
    }
    function createManager($req) {
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
                'password' => 'required'
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
            $manager->password = Hash::make($req->password);
            $manager->save();
        }
        return Redirect::back()->with('message', 'Vartotojas sėkmingai sukurtas.');
    }
    function createAdmin($req) {
        $validator = Validator::make(
            [
                'id' => $req->input('id'),
                'first_name' => $req->input('first_name'),
                'last_name' => $req->input('last_name'),
                'email' => $req->input('email'),
                'password' => $req->input('password')
            ],
            [
                'id' => 'unique:administrators,id',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|unique:administrators,email',
                'password' => 'required'
            ]
        );
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else {
            $admin = new Administrator();
            if($req->id != null)
                $admin->id = $req->id;
            $admin->first_name = $req->first_name;
            $admin->last_name = $req->last_name;
            $admin->email = $req->email;
            $admin->password = Hash::make($req->password);
            $admin->save();
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
                'password' => 'required'
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
            $manager->password = Hash::make($req->password);
            $manager->save();
        }
        return Redirect::to('adminHome')->with('message','Duomenys pakeisti sėkmingai.');
    }
    function editAdmin(request $req) {
        $admin = Administrator::where('id','=',$req->idH)->first();
        $validator = Validator::make(
            [
                'id' => $req->input('id'),
                'first_name' => $req->input('firstName'),
                'last_name' => $req->input('lastName'),
                'email' => $req->input('email'),
                'password' => $req->input('password')
            ],
            [
                'id' => 'unique:managers,id,'.$admin->id,
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|unique:managers,email,'.$admin->id,
                'password' => 'required'
            ]
        );
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else {
            if($req->id != null)
                $admin->id = $req->id;
            $admin->first_name = $req->firstName;
            $admin->last_name = $req->lastName;
            $admin->email = $req->email;
            $admin->password = Hash::make($req->password);
            $admin->save();
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
    function removeAdmin($id) {
        $admin = Administrator::where('id','=',$id)->first();
        if($admin != null && count(Administrator::all()) > 1) {
            $admin->delete();
            return Redirect::back()->with('message','Vartotojas sėkmingai pašalintas');
        }
        return Redirect::back()->with('error','Vartotojas neištrintas: negalima trinti administratoriaus, kai yra mažiau nei 2 administratoriai.');
    }
}
