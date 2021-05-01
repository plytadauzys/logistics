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
    // https://gist.github.com/ngugijames/d49fddd73d389d8834c2
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
        $d = $req->routeNewDate;
        $a = array();
        $b = array();
        if ($req->has('routeDateNew1')) {
            for ($i = 1; $i < $req->fieldsNewCount + 1; $i++) {
                if ($d <= $req->input('routeDateNew' . $i))
                    continue;
                else return Redirect::back()->with('error', 'Netinkamai suvestos datos: datos, esančios papildamuose laukeliuose, turi būti lygios arba didesnės už pirmąją datą');
            }
            for ($i = 1; $i < $req->fieldsNewCount + 1; $i++) {
                for ($j = 1; $j < $req->fieldsNewCount + 1; $j++) {
                    if ($i === $j)
                        continue;
                    else if (!in_array($j, $b)) {
                        if ($req->input('routeDateNew'.$i) <= $req->input('routeDateNew'.$j))
                            continue;
                        else {
                            //return $i.' '.$j.' - cia negerai. Data '.$req->input('routeDateNew'.$i).' !< '.$req->input('routeDateNew'.$j);
                            return Redirect::back()->with('error', 'Netinkamai suvestos datos: '.$j.' papildomame laukelyje data '.
                            'turėtų būti mažesnė arba lygi '.($j -1).' papildomo laukelio datai');
                        }
                    } else continue;
                }
                array_push($b, $i);
            }
        }
        else return 'n3ra';
        return $b;
        /*$expedition = new Expedition();
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
        return Redirect::back();*/
        //return view('expeditions', ['data' => $expeditions, 'clients' => $clients, 'suppliers' => $suppliers]);
    }
    function importData(request $req) {
        $file = $req->file('file')->store('docs');
        $dir = storage_path('app');
        //explode('.',$file)[1]
        $txt = $this->read_docx($dir.'\\'.$file);
        File::delete(storage_path('app/').$file);
        //$supplier = Str::before(Str::after($txt,'Siuntėjo pavadinimas:'),'Pervežimo maršrutas');
        $supplier = Str::between($txt,'Siuntėjo pavadinimas:','Pervežimo maršrutas');
        //$client = Str::before(Str::after($txt,'Firmos pavadinimas:'),'Pristatymo');
        $client = Str::between($txt, 'Firmos pavadinimas:', 'Pristatymo');
        //$datesAndAddresses = Str::before(Str::after($txt,'Pasikrovimo adresas, data:'),'GAVĖJAS');
        $datesAndAddresses = Str::between($txt, 'Pasikrovimo adresas, data:', 'GAVĖJAS');

        ltrim($datesAndAddresses,' ');
        $datesAndAddresses = explode(PHP_EOL, $datesAndAddresses);
        $datesAndAddresses[0] = ltrim($datesAndAddresses[0], ' ');
        $datesAndAddresses = implode('!!',$datesAndAddresses);
        if(Str::endsWith($datesAndAddresses,'!!'))
            $datesAndAddresses = rtrim($datesAndAddresses, '!!');

        //$route = Str::before(Str::after($txt,'Pervežimo maršrutas:'),'Pasikrovimo adresas, data:');
        $route = Str::between($txt, 'Pervežimo maršrutas:', 'Pasikrovimo adresas, data:');
        //$cargo = Str::before(Str::after($txt,'Krovinio aprašymas:'),'Krovinio svoris, tūris arba konteinerio ');
        $cargo = Str::between($txt, 'Krovinio aprašymas:', 'Krovinio svoris, tūris arba konteinerio ');
        //$amount = Str::before(Str::after($txt,'konteinerio tipas:'),' (');
        $amount = Str::between($txt, 'konteinerio tipas:', ' (');
        //$price = Str::before(Str::after($txt,'Paslaugos kaina:'),' EUR');
        $price = Str::between($txt, 'Paslaugos kaina:', ' EUR');
        $order = collect([$client, $supplier, $datesAndAddresses, $route, $cargo, $amount, $price]);

        session()->pull('neworder');
        session()->push('neworder',$order);
        return Redirect::back();
    }
    function changeState(request $req) {
        $expedition = Expedition::where('order_no','=',$req->orderNoState)->first();
        //return $expedition;
        $test = '';
        if ($expedition->state == 'order') {
            $expedition->from = $req->fromState;
            $expedition->to = $req->toState;
            $expedition->cargo = $req->cargoState;
            $expedition->amount = $req->amountState;
            $expedition->profit = $req->profitState;
            $expedition->state = 'contact';
            $expedition->save();
        }
        else if ($expedition->state == 'contact') {
            $expedition->carrier = $req->carrierState;
            $expedition->carrier_price = $req->carrierPriceState;
            $expedition->total_profit = $expedition->profit - $expedition->carrier_price;
            $expedition->state = 'transport';
            $expedition->save();
        }
        else if ($expedition->state == 'transport') {
            $expedition->loaded = $req->loadedState;
            $expedition->state = 'exporting';
            $expedition->save();
        }
        else if ($expedition->state == 'exporting') {
            $expedition->unloaded = $req->unloadedState;
            $expedition->state = 'received';
            $expedition->save();
        }
        else if ($expedition->state == 'received') {
            $expeditionHistory = new ExpeditionHistory();
            $expeditionHistory->order_no = $expedition->order_no;
            $expeditionHistory->date = $expedition->date;
            $expeditionHistory->client = $expedition->client;
            $expeditionHistory->supplier = $expedition->supplier;
            $expeditionHistory->from = $expedition->from;
            $expeditionHistory->to = $expedition->to;
            $expeditionHistory->cargo = $expedition->cargo;
            $expeditionHistory->amount = $expedition->amount;
            $expeditionHistory->profit = $expedition->profit;
            $expeditionHistory->loaded = $expedition->loaded;
            $expeditionHistory->unloaded = $expedition->unloaded;
            $expeditionHistory->carrier = $expedition->carrier;
            $expeditionHistory->carrier_price = $expedition->carrier_price;
            $expeditionHistory->total_profit = $expedition->total_profit;
            $expeditionHistory->closed_date = Carbon::now()->toDateString();
            if ($expeditionHistory->save()) {
                $expedition->delete();
            }
        }
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
        }

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $striped_content = strip_tags($content);

        return $striped_content;
    }
}
