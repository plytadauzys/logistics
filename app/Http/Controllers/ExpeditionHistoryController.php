<?php

namespace App\Http\Controllers;

use App\Models\ExpeditionHistory;
use Illuminate\Http\Request;

class ExpeditionHistoryController extends Controller
{
    // getExpedition, getExpeditions, searchForExpedition, filterExpeditions
    function index() {
        $expeditionHistory = ExpeditionHistory::all();
        //$clients = Client::all();
        //$suppliers = Supplier::all();
        return view('expeditionsHistory', ['data' => $expeditionHistory]);
    }
}
