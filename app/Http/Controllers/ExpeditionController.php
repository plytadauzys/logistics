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
            for ($i = 1; $i < $req->fieldsNewCount; $i++) {
                if ($datess[0] <= $req->input('routeDateNew'.$i))
                    continue;
                else return Redirect::back()->with('error', 'Netinkamai suvestos datos: datos, esančios papildamuose laukeliuose, turi būti lygios arba didesnės už pirmąją datą');
            }
            for ($i = 1; $i < $req->fieldsNewCount; $i++) {
                for ($j = 1; $j < $req->fieldsNewCount; $j++) {
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
        $expedition->save();
        return Redirect::back()->with('message', 'Ekspedicija sėkmingai sukurta.');
    }
    function importData(request $req) {
        $file = $req->file('file')->store('docs');
        $dir = storage_path('app');
        $txt = $this->read_docx($dir.'\\'.$file);
        File::delete(storage_path('app/').$file);
        $client = trim(preg_replace('/\s\s+/', ' ', Str::between($txt, 'Firmos pavadinimas: ', 'Pristatymo')));
        if(strlen($client) > 30 || $client == null)
            return Redirect::back()->with('error','Gavėjo pavadinimas nerastas arba per ilgas');
        $clientObject = Client::where('name','=',$client)->first();
        if($clientObject == null) {
            $newClient = new Client();
            $newClient->name = $client;

            $adresas = trim(preg_replace('/\s\s+/', ' ', Str::between($txt, 'Adresas: ', 'Pašto kodas:')));
            if(empty($adresas)) $newClient->address = $client;
            else $newClient->address = $adresas;

            $postal_code = trim(preg_replace('/\s\s+/', ' ', Str::between($txt, 'Pašto kodas:', 'Telefonas')));
            if(empty($postal_code)) $newClient->postal_code = 0;
            else $newClient->postal_code = $postal_code;

            $phone_no = trim(preg_replace('/\s\s+/', ' ', Str::between($txt, 'Telefonas:', 'El. paštas:')));
            if(empty($phone_no) && !empty(Client::all())) $newClient->phone_no = (Client::orderBy('id','desc')->first()->id + 1).'. Reikia keisti';
            else if(empty(Client::all())) $newClient = '1. Reikia keisti';
            else $newClient->phone_no = $phone_no;

            $email = trim(preg_replace('/\s\s+/', ' ', Str::between($txt, 'El. paštas:', 'KROVINIO APRAŠYMAS')));
            if(empty($email)) $newClient->email = $client;
            else $newClient->email = $email;;

            $newClient->save();
        }
        $supplier = trim(preg_replace('/\s\s+/', ' ', Str::between($txt,'Siuntėjo pavadinimas: ',PHP_EOL.'Pervežimo maršrutas')));
        if(strlen($supplier) > 30)
            return Redirect::back()->with('error','Tiekėjo pavadinimas nerastas arba per ilgas');
        $supplierObject = Supplier::where('name','=',$supplier)->first();
        if($supplierObject == null) {
            $newSupplier = new Supplier();
            $newSupplier->name = $supplier;
            $newSupplier->address = $supplier;
            $newSupplier->postal_code = 0;
            if(empty(Supplier::all()) || Supplier::all() == null) $newSupplier->phone_no = '1. Reikia keisti';
            else $newSupplier->phone_no = (Supplier::orderBy('id','desc')->first()->id + 1).'. Reikia keisti';
            $newSupplier->email = $supplier;
            $newSupplier->save();
        }
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

        $route = trim(preg_replace('/\s\s+/', ' ', Str::between($txt, 'Pervežimo maršrutas: ', PHP_EOL.'Pasikrovimo adresas, data:')));
        $cargo = trim(preg_replace('/\s\s+/', ' ', Str::between($txt, 'Krovinio aprašymas: ', PHP_EOL.'Krovinio svoris, tūris arba konteinerio ')));
        $amount = trim(preg_replace('/\s\s+/', ' ', Str::between($txt, 'konteinerio tipas: ', 'Vilkiko')));
        $price = trim(preg_replace('/\s\s+/', ' ', Str::between($txt, 'Paslaugos kaina: ', ' EUR')));
        $order = collect([$client, $supplier, $route, $dates, $addresses, $cargo, $amount, $price]);
        session()->pull('neworder');
        session()->push('neworder',$order);
        return Redirect::back();
        //return $order;
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
    function changeState(request $req) {
        $expedition = Expedition::where('order_no','=',$req->orderNoState)->first();
        if ($expedition->state == 'order') {
            $datess = array();
            array_push($datess, $req->routeDateState);
            $addresses = array();
            array_push($addresses, $req->routeAddressState);
            $b = array();
            if ($req->has('routeDateState1')) {
                for ($i = 1; $i < $req->fieldsNewCountState; $i++) {
                    if ($datess[0] <= $req->input('routeDateState'.$i))
                        continue;
                    else return Redirect::back()->with('error', 'Netinkamai suvestos datos: datos, esančios papildamuose laukeliuose, turi būti lygios arba didesnės už pirmąją datą');
                }
                for ($i = 1; $i < $req->fieldsNewCountState; $i++) {
                    for ($j = 1; $j < $req->fieldsNewCountState; $j++) {
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
            $expedition->date = Carbon::now()->toDateString();
            $expedition->save();
        }
        else if ($expedition->state == 'contact') {
            $expedition->carrier = $req->carrierState;
            $expedition->carrier_price = $req->carrierPriceState;
            $expedition->total_profit = $expedition->profit - $expedition->carrier_price;
            $expedition->state = 'transport';
            $expedition->date = Carbon::now()->toDateString();
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
            $expedition->date = Carbon::now()->toDateString();
            $expedition->save();
        }
        else if ($expedition->state == 'exporting') {
            if($expedition->progress == count(explode('!!', $expedition->dates))-1) {
                $datesArray = explode('!!', $expedition->dates);
                $datesArray[$expedition->progress] = $req->unloadedState;
                $datesArray = implode('!!', $datesArray);
                $expedition->dates = $datesArray;
                $expedition->progress += 1;
                $expedition->date = Carbon::now()->toDateString();
                //return $expedition;
                $expedition->save();
            }
            else if($expedition->progress == count(explode('!!', $expedition->dates))) {
                $expedition->unloaded = $req->loadedState;
                $expedition->state = 'received';
                $expedition->date = Carbon::now()->toDateString();
                return $expedition;
                //$expedition->save();
            } else {
                $datesArray = explode('!!', $expedition->dates);
                $datesArray[$expedition->progress] = $req->unloadedState;
                $datesArray = implode('!!', $datesArray);
                $expedition->dates = $datesArray;
                $expedition->progress += 1;
                $expedition->date = Carbon::now()->toDateString();
                //return $expedition;
                $expedition->save();
            }
        }
        else if ($expedition->state == 'received') {
            $expeditionHistory = new ExpeditionHistory();
            $expeditionHistory->order_no = $expedition->order_no;
            $expeditionHistory->date = $expedition->date;
            $expeditionHistory->client = $expedition->klientas->name;
            $expeditionHistory->supplier = $expedition->tiekejas->name;
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
            return Redirect::back()->with('message','Ekspedicija sėkmingai uždaryta.');
            }
        }
        return Redirect::back()->with('message','Ekspedicijos būsena sėkmingai pakeista.');
    }
    function edit(request $req) {
        $exp = Expedition::where('order_no',$req->id)->first();
        if($exp->state == 'order' || $exp->state == 'contact') {
            return $this->editOrder($req);
        }
        else if($exp->state == 'transport' || $exp->state == 'received') {
            $this->editTransport($req);
            return Redirect::back()->with('message','Ekspedicija Nr. '.$req->id. ' redaguota sėkmingai.');
        }
        else if($exp->state == 'exporting') {
            $this->editExporting($req);
            return Redirect::back()->with('message','Ekspedicija Nr. '.$req->id. ' redaguota sėkmingai.');
        }
    }
    function editOrder($req) {
        $expedition = Expedition::where('order_no',$req->id)->first();
        $expedition->route = $req->routeState;
        $datess = array();
        array_push($datess, $req->routeDateState);
        $addresses = array();
        array_push($addresses, $req->routeAddressState);
        $b = array();
        if ($req->has('routeDateEdit1')) {
            for ($i = 1; $i < $req->input('fieldsEditCount'.$req->id); $i++) {
                if ($datess[0] <= $req->input('routeDateEdit'.$i))
                    continue;
                else return Redirect::back()->with('error', 'Netinkamai suvestos datos: datos, esančios papildamuose laukeliuose, turi būti lygios arba didesnės už pirmąją datą');
            }
            for ($i = 1; $i < $req->input('fieldsEditCount'.$req->id); $i++) {
                for ($j = 1; $j < $req->input('fieldsEditCount'.$req->id); $j++) {
                    if ($i === $j)
                        continue;
                    else if (!in_array($j, $b)) {
                        if ($req->input('routeDateEdit'.$i) <= $req->input('routeDateEdit'.$j))
                            continue;
                        else {
                            //return $i.' '.$j.' - cia negerai. Data '.$req->input('routeDateNew'.$i).' !< '.$req->input('routeDateNew'.$j);
                            return Redirect::back()->with('error', 'Netinkamai suvestos datos: '.$j.' papildomame laukelyje data '.
                                'turėtų būti mažesnė arba lygi '.($j -1).' papildomo laukelio datai');
                        }
                    } else continue;
                }
                array_push($b, $i);
                array_push($datess, $req->input('routeDateEdit'.$i));
                array_push($addresses, $req->input('routeAddressEdit'.$i));
            }
        }
        $expedition->dates = implode('!!', $datess);
        $expedition->addresses = implode('!!', $addresses);
        $expedition->route = $req->routeState;
        $expedition->cargo = $req->cargoState;
        $expedition->amount = $req->amountState;
        $expedition->profit = $req->profitState;
        $expedition->save();
        return Redirect::back()->with('message','Ekspedicija Nr. '.$req->id. ' redaguota sėkmingai.');
    }
    function editTransport($req) {
        $expedition = Expedition::where('order_no',$req->id)->first();
        $expedition->route = $req->routeState;
        $datess = array();
        array_push($datess, $req->routeDateState);
        $addresses = array();
        array_push($addresses, $req->routeAddressState);
        $b = array();
        if ($req->has('routeDateEdit1')) {
            for ($i = 1; $i < $req->input('fieldsEditCount'.$req->id); $i++) {
                if ($datess[0] <= $req->input('routeDateEdit'.$i))
                    continue;
                else return Redirect::back()->with('error', 'Netinkamai suvestos datos: datos, esančios papildamuose laukeliuose, turi būti lygios arba didesnės už pirmąją datą');
            }
            for ($i = 1; $i < $req->input('fieldsEditCount'.$req->id); $i++) {
                for ($j = 1; $j < $req->input('fieldsEditCount'.$req->id); $j++) {
                    if ($i === $j)
                        continue;
                    else if (!in_array($j, $b)) {
                        if ($req->input('routeDateEdit'.$i) <= $req->input('routeDateEdit'.$j))
                            continue;
                        else {
                            //return $i.' '.$j.' - cia negerai. Data '.$req->input('routeDateNew'.$i).' !< '.$req->input('routeDateNew'.$j);
                            return Redirect::back()->with('error', 'Netinkamai suvestos datos: '.$j.' papildomame laukelyje data '.
                                'turėtų būti mažesnė arba lygi '.($j -1).' papildomo laukelio datai');
                        }
                    } else continue;
                }
                array_push($b, $i);
                array_push($datess, $req->input('routeDateEdit'.$i));
                array_push($addresses, $req->input('routeAddressEdit'.$i));
            }
        }
        $expedition->dates = implode('!!', $datess);
        $expedition->addresses = implode('!!', $addresses);
        $expedition->route = $req->routeState;
        $expedition->cargo = $req->cargoState;
        $expedition->amount = $req->amountState;
        $expedition->profit = $req->profitState;
        $expedition->carrier = $req->carrierState;
        $expedition->carrier_price = $req->carrierPriceState;
        $expedition->total_profit = $req->totalPriceState;
        $expedition->save();
    }
    function editExporting($req) {
        $expedition = Expedition::where('order_no',$req->id)->first();
        $expedition->route = $req->routeState;
        $datess = array();
        array_push($datess, $req->routeDateState);
        $addresses = array();
        array_push($addresses, $req->routeAddressState);
        $b = array();
        if ($req->has('routeDateEdit1')) {
            for ($i = 1; $i < $req->input('fieldsEditCount'.$req->id); $i++) {
                if ($datess[0] <= $req->input('routeDateEdit'.$i))
                    continue;
                //else return Redirect::back()->with('error', 'Netinkamai suvestos datos: datos, esančios papildamuose laukeliuose, turi būti lygios arba didesnės už pirmąją datą');
            }
            for ($i = 1; $i < $req->input('fieldsEditCount'.$req->id); $i++) {
                for ($j = 1; $j < $req->input('fieldsEditCount'.$req->id); $j++) {
                    if ($i === $j)
                        continue;
                    else if (!in_array($j, $b)) {
                        if ($req->input('routeDateEdit'.$i) <= $req->input('routeDateEdit'.$j))
                            continue;
                        else {
                            //return $i.' '.$j.' - cia negerai. Data '.$req->input('routeDateNew'.$i).' !< '.$req->input('routeDateNew'.$j);
                            return Redirect::back()->with('error', 'Netinkamai suvestos datos: '.$j.' papildomame laukelyje data '.
                                'turėtų būti mažesnė arba lygi '.($j -1).' papildomo laukelio datai');
                            continue;
                        }
                    } else continue;
                }
                array_push($b, $i);
                array_push($datess, $req->input('routeDateEdit'.$i));
                array_push($addresses, $req->input('routeAddressEdit'.$i));
            }
        }
        $expedition->dates = implode('!!', $datess);
        $expedition->addresses = implode('!!', $addresses);
        $expedition->route = $req->routeState;
        $expedition->cargo = $req->cargoState;
        $expedition->amount = $req->amountState;
        $expedition->profit = $req->profitState;
        $expedition->carrier = $req->carrierState;
        $expedition->carrier_price = $req->carrierPriceState;
        $expedition->total_profit = $req->totalPriceState;
        $expedition->progress = $req->progressCount;
        $expedition->save();
    }
    function editReceived($req) {

    }
}
