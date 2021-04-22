<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SupplierController extends Controller
{
    // index, getSupplier, getSuppliers, createSupplier, editSupplier, removeSupplier,
    // searchForSupplier, filterSuppliers
    function index() {
        return view('suppliers', ['data' => Supplier::all()]);
    }
    function createSupplier(request $req) {
        $client = new Supplier();
        $client->name = $req->name;
        $client->address = $req->address;
        $client->postal_code = $req->postal_code;
        $client->phone_no = $req->phone_no;
        $client->email = $req->email;
        $client->save();
        return Redirect::to('suppliers')->with('message','Tiekėjas sėkmingai pridėtas!');
    }
    function editSupplier(request $req) {
        $client = Supplier::where('id','=',$req->id)->first();
        $client->name = $req->name;
        $client->address = $req->address;
        $client->postal_code = $req->postal_code;
        $client->phone_no = $req->phone_no;
        $client->email = $req->email;
        $client->save();
        return Redirect::to('suppliers')->with('message','Duomenys pakeisti sėkmingai.');
    }
}
