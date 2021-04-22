<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expedition;
use App\Models\Client;
use App\Models\Supplier;
use App\Models\ExpeditionHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
//use Psy\Util\Str;
use Illuminate\Support\Str;

class ExpeditionController extends Controller
{
    // index, getExpedition, getExpeditions, createExpedition, importData, calculateLDM,
    // changeState, endExpedition
    function index() {
        $expeditions = Expedition::all();
        $clients = Client::all();
        $suppliers = Supplier::all();
        return view('expeditions', ['data' => $expeditions, 'clients' => $clients, 'suppliers' => $suppliers]);
    }

    function createExpedition(request $req) {
        $expeditions = Expedition::all();
        $clients = Client::all();
        $suppliers = Supplier::all();

        $expedition = new Expedition();
        $expedition->date = Carbon::now()->toDateString();
        $expedition->client = $req->clientNew;
        $expedition->supplier = $req->supplierNew;
        $expedition->from = $req->fromNew;
        $expedition->to = $req->toNew;
        $expedition->cargo = $req->cargoNew;
        $expedition->amount = $req->amountNew;
        $expedition->profit = $req->profitNew;
        $expedition->state = 'order';
        $expedition->save();
        return Redirect::back();
        //return view('expeditions', ['data' => $expeditions, 'clients' => $clients, 'suppliers' => $suppliers]);
    }
    function importData(request $req) {
        $file = $req->file('file')->store('docs');
        $dir = storage_path('app');
        $txt = $this->read_docx($dir.'\\'.$file);
        File::delete(storage_path('app/').$file);
        $cargo = Str::before(Str::after($txt,'Krovinio aprašymas:'),'Krovinio svoris, tūris arba konteinerio ');
        $amount = Str::before(Str::after($txt,'konteinerio tipas:'),' (');
        $price = Str::before(Str::after($txt,'Paslaugos kaina:'),' EUR');
        return $amount;
    }
    function changeState(request $req) {
        $expedition = Expedition::where('order_no','=',$req->orderNoState)->first();
        if ($expedition->state == 'order') {
            $expedition->from = $req->fromState;
            $expedition->to = $req->toState;
            $expedition->cargo = $req->cargoState;
            $expedition->amount = $req->amountState;
            $expedition->profit = $req->profitState;
            $expedition->state = 'contact';
        }
        $expedition->save();
        return Redirect::back();
    }
    private function read_docx($filename){

        $striped_content = '';
        $content = '';

        $zip = zip_open($filename);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {

            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

            if (zip_entry_name($zip_entry) != "word/document.xml") continue;

            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            zip_entry_close($zip_entry);
        }// end while

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $striped_content = strip_tags($content);

        return $striped_content;
    }
}
