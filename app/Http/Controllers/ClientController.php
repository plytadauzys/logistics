<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Models\Client;
use App\Models\Expedition;

class ClientController extends Controller
{
    // index, getClient, getClients, createClient, editClient, removeClient,
    // searchForClient, filterClients
    function index() {
        return view('clients', ['data' => Client::all()]);
    }
    function createClient(request $req) {
        $validator = Validator::make(
            [
                'name' => $req->input('name'),
                'address' => $req->input('address'),
                'postal_code' => $req->input('postal_code'),
                'phone_no' => $req->input('phone_no'),
                'email' => $req->input('email')
            ],
            [
                'name' => 'required|unique:clients',
                'address' => 'required|unique:clients',
                'postal_code' => 'required',
                'phone_no' => 'required|unique:clients',
                'email' => 'required|unique:clients'
            ]
        );
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else {
            $client = new Client();
            $client->name = $req->name;
            $client->address = $req->address;
            $client->postal_code = $req->postal_code;
            $client->phone_no = $req->phone_no;
            $client->email = $req->email;
            $client->save();
        }
        return Redirect::to('clients')->with('message','Klientas sėkmingai pridėtas!');
    }
    function editClient(request $req) {
        $client = Client::where('id', '=', $req->id)->first();
        $validator = Validator::make(
            [
                'name' => $req->input('name'),
                'address' => $req->input('address'),
                'postal_code' => $req->input('postal_code'),
                'phone_no' => $req->input('phone_no'),
                'email' => $req->input('email')
            ],
            [
                'name' => 'required|unique:clients,name,'.$client->id,
                'address' => 'required|unique:clients,address,'.$client->id,
                'postal_code' => 'required',
                'phone_no' => 'required|unique:clients,phone_no,'.$client->id,
                'email' => 'required|unique:clients,email,'.$client->id
            ]
        );
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else {
            $client->name = $req->name;
            $client->address = $req->address;
            $client->postal_code = $req->postal_code;
            $client->phone_no = $req->phone_no;
            $client->email = $req->email;
            $client->save();
        }
        return Redirect::to('clients')->with('message','Duomenys pakeisti sėkmingai.');
    }
    function removeClient($id) {
        $expedition = Expedition::all();
        if($expedition->contains('client', $id))
            return Redirect::back()->with('error','Negalima ištrinti, nes vyksta bent viena ekspedicija su šiuo klientu.');
        else
            Client::where('id',$id)->first()->delete();
        return Redirect::back()->with('message','Klientas sėkmingai ištrintas.');
    }
}
