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
        $datess = array();
        array_push($datess, $req->routeDateNew);
        $addresses = array();
        array_push($addresses, $req->routeAddressNew);
        $b = array();
        if ($req->has('routeDateNew1')) {
            for ($i = 1; $i < $req->fieldsNewCount + 1; $i++) {
                if ($datess[0] <= $req->input('routeDateNew'.$i))
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
                array_push($datess, $req->input('routeDateNew'.$i));
                array_push($addresses, $req->input('routeAddressNew'.$i));
            }
        }
        $expedition = new Expedition();
        $expedition->date = Carbon::now()->toDateString();
        $expedition->client = $req->clientNew;
        $expedition->supplier = $req->supplierNew;
        $expedition->dates = implode('!!', $datess);
        $expedition->addresses = implode('!!', $addresses);
        $expedition->route = $req->routeNew;
        $expedition->cargo = $req->cargoNew;
        $expedition->amount = $req->amountNew;
        $expedition->profit = $req->profitNew;
        $expedition->progress = 0;
        $expedition->state = 'order';
        //return $expedition;
        $expedition->save();
        return Redirect::back()->with('message', 'Ekspedicija sėkmingai sukurta.');
        //return view('expeditions', ['data' => $expeditions, 'clients' => $clients, 'suppliers' => $suppliers]);
    }
    function importData(request $req) {
        $file = $req->file('file')->store('docs');
        $dir = storage_path('app');
        $txt = $this->read_docx($dir.'\\'.$file);
        File::delete(storage_path('app/').$file);
        $supplier = Str::between($txt,'Siuntėjo pavadinimas: ','Pervežimo maršrutas');
        $client = Str::between($txt, 'Firmos pavadinimas: ', 'Pristatymo');
        // Dates and addresses -------------------------------------------------------------------
        $datesAndAddresses = Str::between($txt, 'Pasikrovimo adresas, data: ', 'GAVĖJAS');
        $destinationDate = Str::between($txt, 'Pristatymo data: ', 'Adresas:');
        $destinationAddress = Str::between($txt, 'Adresas: ', 'Pašto kodas:');

        $dates = array();
        $addresses = array();
        $datesAndAddresses = explode(PHP_EOL, $datesAndAddresses);
        foreach ($datesAndAddresses as $d) {
            array_push($dates, substr($d, 0, 10));
            array_push($addresses, substr($d, 11));
        }
        array_pop($dates);
        array_pop($addresses);
        array_push($dates, substr($destinationDate, 0, 10));
        array_push($addresses, $destinationAddress);
        $datesAndAddresses[0] = ltrim($datesAndAddresses[0], ' ');
        $datesAndAddresses = implode('!!',$datesAndAddresses);
        if(Str::endsWith($datesAndAddresses,'!!'))
            $datesAndAddresses = rtrim($datesAndAddresses, '!!');
        $dates = implode('!!', $dates);
        $addresses = implode('!!', $addresses);
        // -------------------------------------------------------------------------------------

        $route = Str::between($txt, 'Pervežimo maršrutas: ', 'Pasikrovimo adresas, data:');
        $cargo = Str::between($txt, 'Krovinio aprašymas: ', 'Krovinio svoris, tūris arba konteinerio ');
        $amount = Str::between($txt, 'konteinerio tipas: ', 'Vilkiko');
        $price = Str::between($txt, 'Paslaugos kaina: ', ' EUR');
        $order = collect([$client, $supplier, $route, $dates, $addresses, $cargo, $amount, $price]);
        session()->pull('neworder');
        session()->push('neworder',$order);
        return Redirect::back();
    }
    function changeState(request $req) {
        $expedition = Expedition::where('order_no','=',$req->orderNoState)->first();
        //return $expedition;
        $test = '';
        if ($expedition->state == 'order') {
            $datess = array();
            array_push($datess, $req->routeDateState);
            $addresses = array();
            array_push($addresses, $req->routeAddressState);
            $b = array();
            if ($req->has('routeDateState1')) {
                for ($i = 1; $i < $req->fieldsNewCountState + 1; $i++) {
                    if ($datess[0] <= $req->input('routeDateState'.$i))
                        continue;
                    else return Redirect::back()->with('error', 'Netinkamai suvestos datos: datos, esančios papildamuose laukeliuose, turi būti lygios arba didesnės už pirmąją datą');
                }
                for ($i = 1; $i < $req->fieldsNewCountState + 1; $i++) {
                    for ($j = 1; $j < $req->fieldsNewCountState + 1; $j++) {
                        if ($i === $j)
                            continue;
                        else if (!in_array($j, $b)) {
                            if ($req->input('routeDateState'.$i) <= $req->input('routeDateState'.$j))
                                continue;
                            else {
                                //return $i.' '.$j.' - cia negerai. Data '.$req->input('routeDateNew'.$i).' !< '.$req->input('routeDateNew'.$j);
                                return Redirect::back()->with('error', 'Netinkamai suvestos datos: '.$j.' papildomame laukelyje data '.
                                    'turėtų būti mažesnė arba lygi '.($j -1).' papildomo laukelio datai');
                            }
                        } else continue;
                    }
                    array_push($b, $i);
                    array_push($datess, $req->input('routeDateState'.$i));
                    array_push($addresses, $req->input('routeAddressState'.$i));
                }
            }
            $expedition->route = $req->routeState;
            $expedition->dates = implode('!!', $datess);
            $expedition->addresses = implode('!!', $addresses);
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
            $datesArray = explode('!!', $expedition->dates);
            $datesArray[0] = $req->loadedState;
            $datesArray = implode('!!', $datesArray);
            $expedition->dates = $datesArray;
            $expedition->progress += 1;
            $expedition->state = 'exporting';
            $expedition->save();
        }
        else if ($expedition->state == 'exporting') {
            if($expedition->progress == count(explode('!!', $expedition->dates)) - 1) {
                $expedition->unloaded = $req->unloadedState;
                $datesArray = explode('!!', $expedition->dates);
                $datesArray[$expedition->progress] = $req->unloadedState;
                $datesArray = implode('!!', $datesArray);
                $expedition->dates = $datesArray;
                $expedition->state = 'received';
                $expedition->save();
            } else {
                $datesArray = explode('!!', $expedition->dates);
                $datesArray[$expedition->progress] = $req->unloadedState;
                $datesArray = implode('!!', $datesArray);
                $expedition->dates = $datesArray;
                $expedition->progress += 1;
                $expedition->save();
            }
        }
        else if ($expedition->state == 'received') {
            $expeditionHistory = new ExpeditionHistory();
            $expeditionHistory->order_no = $expedition->order_no;
            $expeditionHistory->date = $expedition->date;
            $expeditionHistory->client = $expedition->client;
            $expeditionHistory->supplier = $expedition->supplier;
            $expeditionHistory->route = $expedition->route;
            $expeditionHistory->dates = $expedition->dates;
            $expeditionHistory->addresses = $expedition->addresses;
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
