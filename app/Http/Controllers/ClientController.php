<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Models\Client;

class ClientController extends Controller
{
    // index, getClient, getClients, createClient, editClient, removeClient,
    // searchForClient, filterClients
    function index() {
        return view('clients', ['data' => Client::all()]);
    }
    function createClient(request $req) {
        $client = new Client();
        $client->name = $req->name;
        $client->address = $req->address;
        $client->postal_code = $req->postal_code;
        $client->phone_no = $req->phone_no;
        $client->email = $req->email;
        $client->save();
        return Redirect::to('clients')->with('message','Klientas sėkmingai pridėtas!');
    }
    function editClient(request $req) {
        $client = Client::where('id','=',$req->id)->first();
        $client->name = $req->name;
        $client->address = $req->address;
        $client->postal_code = $req->postal_code;
        $client->phone_no = $req->phone_no;
        $client->email = $req->email;
        $client->save();
        return Redirect::to('clients')->with('message','Duomenys pakeisti sėkmingai.');
    }
    function searchForClient($string) {
        //return Client::where()
    }
}
