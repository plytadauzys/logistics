<?php

namespace App\Http\Controllers;

use App\Models\Expedition;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    // index, getSupplier, getSuppliers, createSupplier, editSupplier, removeSupplier,
    // searchForSupplier, filterSuppliers
    function index() {
        return view('suppliers', ['data' => Supplier::all()]);
    }
    function createSupplier(request $req) {
        $validator = Validator::make(
            [
                'name' => $req->input('name'),
                'address' => $req->input('address'),
                'postal_code' => $req->input('postal_code'),
                'phone_no' => $req->input('phone_no'),
                'email' => $req->input('email')
            ],
            [
                'name' => 'required|unique:suppliers',
                'address' => 'required|unique:suppliers',
                'postal_code' => 'required',
                'phone_no' => 'required|unique:suppliers',
                'email' => 'required|unique:suppliers'
            ]
        );
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else {
            $supplier = new Supplier();
            $supplier->name = $req->name;
            $supplier->address = $req->address;
            $supplier->postal_code = $req->postal_code;
            $supplier->phone_no = $req->phone_no;
            $supplier->email = $req->email;
            $supplier->save();
        }
        return Redirect::to('suppliers')->with('message','Tiekėjas sėkmingai pridėtas!');
    }
    function editSupplier(request $req) {
        $supplier = Supplier::where('id', '=', $req->id)->first();
        $validator = Validator::make(
            [
                'name' => $req->input('name'),
                'address' => $req->input('address'),
                'postal_code' => $req->input('postal_code'),
                'phone_no' => $req->input('phone_no'),
                'email' => $req->input('email')
            ],
            [
                'name' => 'required|unique:suppliers,name,'.$supplier->id,
                'address' => 'required|unique:suppliers,address,'.$supplier->id,
                'postal_code' => 'required',
                'phone_no' => 'required|unique:suppliers,phone_no,'.$supplier->id,
                'email' => 'required|unique:suppliers,email,'.$supplier->id
            ]
        );
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else {
            $supplier->name = $req->name;
            $supplier->address = $req->address;
            $supplier->postal_code = $req->postal_code;
            $supplier->phone_no = $req->phone_no;
            $supplier->email = $req->email;
            $supplier->save();
        }
        return Redirect::to('suppliers')->with('message','Duomenys pakeisti sėkmingai.');
    }
    function removeSupplier($id) {
        $expedition = Expedition::all();
        if($expedition->contains('supplier', $id))
            return Redirect::back()->with('error','Negalima ištrinti, nes vyksta bent viena ekspedicija su šiuo tiekėju.');
        else
            Supplier::where('id',$id)->first()->delete();
        return Redirect::back()->with('message','Tiekėjas sėkmingai ištrintas.');
    }
}
