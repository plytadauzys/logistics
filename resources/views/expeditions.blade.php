@extends('layouts.app')
@section('content')
@if(session('message'))
    <p style="color: green">{{session('message')}}</p>
@endif
@if(session('error'))
    <p style="color: red">{{session('error')}}</p>
@endif

<!-- Modal NAUJAS -->
<div class="modal fade" id="naujas" tabindex="-1" role="dialog" aria-labelledby="naujas" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Pradėti naują ekspediciją</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/expeditions/new" method="POST">
                    @csrf
                    @if(session()->has('neworder'))
                        <div class="form-group">
                            <label for="clientNew">Klientas</label>
                            <select class="custom-select" name="clientNew" id="clientNew">
                                <option selected value="nothing">Pasirinkite klientą</option>
                                @foreach($clients as $c)
                                    @if($c->name == session('neworder')[0][0])
                                        <option value="{{$c->id}}" selected>{{$c->name}}</option>
                                    @else
                                        <option value="{{$c->id}}">{{$c->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="supplierNew">Tiekėjo pavadinimas</label>
                            <select class="custom-select" name="supplierNew" id="supplierNew">
                                <option selected value="nothing">Pasirinkite tiekėją</option>
                                @foreach($suppliers as $s)
                                    @if($s->name == session('neworder')[0][1])
                                        <option value="{{$s->id}}" selected>{{$s->name}}</option>
                                    @else
                                        <option value="{{$s->id}}">{{$s->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="routeNew">Maršrutas</label>
                            <input type="text" class="form-control" id="routeNew" name="routeNew" placeholder="Įveskite krovinio atvykimo vietą"
                                   value="{{session('neworder')[0][2]}}" required>
                        </div>
                        <div class="form-group">
                            <label>Data ir pasikrovimo adresas</label>
                            <input type="date" class="form-control" id="routeDateNew" name="routeDateNew"
                                   placeholder="Pasirinkite datą" value="{{explode('!!',session('neworder')[0][3])[0]}}" required>
                            <input type="text" class="form-control" id="routeAddressNew" name="routeAddressNew"
                                   placeholder="Įveskite adresą" value="{{explode('!!',session('neworder')[0][4])[0]}}" required>
                            <input type="button" onclick="createFieldsNew()" value="Pap">
                            <hr>

                            <span id="fooBar">&nbsp;
                                @for($i = 1; $i < count(explode('!!',session('neworder')[0][3])); $i++)
                                    <input type="date" class="form-control" id="{{'routeDateNew'.$i}}" name="{{'routeDateNew'.$i}}"
                                           placeholder="Pasirinkite datą" value="{{explode('!!',session('neworder')[0][3])[$i]}}" required>
                                    <input type="text" class="form-control" id="{{'routeAddressNew'.$i}}" name="{{'routeAddressNew'.$i}}"
                                           placeholder="Įveskite adresą" value="{{explode('!!',session('neworder')[0][4])[$i]}}" required>
                                    <button type="button" id="{{'delNew'.$i}}" onclick="deleteFieldNew({{'routeDateNew'.$i}},{{'routeAddressNew'.$i}},
                                            {{'delNew'.$i}},{{'lineNew'.$i}})">Trinti</button>
                                    <hr id="{{'lineNew'.$i}}">
                                @endfor
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="cargoNew">Krovinys</label>
                            <input type="text" class="form-control" id="cargoNew" name="cargoNew" placeholder="Įveskite krovinį"
                                   value="{{session('neworder')[0][5]}}" required>
                        </div>
                        <div class="form-group">
                            <label for="amountNew">Kiekis</label>
                            <input type="text" class="form-control" id="amountNew" name="amountNew" placeholder="Įveskite krovinio kiekį"
                                   value="{{session('neworder')[0][6]}}" required>
                        </div>
                        <div class="form-group">
                            <label for="profitNew">Pelnas</label>
                            <input type="number" class="form-control" id="profitNew" name="profitNew" placeholder="Įveskite pelną"
                                   value="{{(int)session('neworder')[0][7]}}" required>
                        </div>
                        <input id="fieldsNewCount" name="fieldsNewCount" type="number" hidden value="{{count(explode('!!', session('neworder')[0][3]))-1}}">
                        <!--php(session()->pull('neworder'))-->
                    @else
                        <div class="form-group">
                            <label for="clientNew">Klientas</label>
                            <select class="custom-select" name="clientNew" id="clientNew">
                                <option selected value="nothing">Pasirinkite klientą</option>
                                @foreach($clients as $c)
                                    <option value="{{$c->id}}">{{$c->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="supplierNew">Tiekėjo pavadinimas</label>
                            <select class="custom-select" name="supplierNew" id="supplierNew">
                                <option selected value="nothing">Pasirinkite tiekėją</option>
                                @foreach($suppliers as $s)
                                    <option value="{{$s->id}}">{{$s->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="routeNew">Maršrutas</label>
                            <input type="text" class="form-control" id="routeNew" name="routeNew"
                                   placeholder="Įveskite maršrutą" required>
                        </div>
                        <div class="form-group">
                            <label for="routeAddressNew">Data ir pasikrovimo adresas</label>
                            <input type="date" class="form-control" id="routeDateNew" name="routeDateNew"
                                   placeholder="Pasirinkite datą" required>
                            <input type="text" class="form-control" id="routeAddressNew" name="routeAddressNew"
                                   placeholder="Įveskitę adresą" required>
                            <input type="button" onclick="createFieldsNew()" value="Naujas laukelis">
                            <span id="fooBarNew">&nbsp;</span>
                        </div>
                        <div class="form-group">
                            <label for="cargoNew">Krovinys</label>
                            <input type="text" class="form-control" id="cargoNew" name="cargoNew"
                                   placeholder="Įveskite krovinį" required>
                        </div>
                        <div class="form-group">
                            <label for="amountNew">Kiekis</label>
                            <input type="text" class="form-control" id="amountNew" name="amountNew"
                                   placeholder="Įveskite krovinio kiekį" required>
                        </div>
                        <div class="form-group">
                            <label for="profitNew">Pelnas</label>
                            <input type="number" class="form-control" id="profitNew" name="profitNew"
                                   placeholder="Įveskite pelną" required>
                        </div>
                        <input id="fieldsNewCount" name="fieldsNewCount" type="number" hidden value="0">
                    @endif
                    <button type="submit" class="btn btn-primary">Pridėti</button>
                </form>
            </div>
        </div>
    </div>
</div>
<form action="/expeditions/file" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        <div class="col-md-2">
            <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#naujas">
                Naujas
            </button>
        </div>
        <div class="custom-file col-md-4">
            <input class="custom-file-input" type="file" id="file" name="file" lang="en">
            <label class="custom-file-label" for="file">Pasirinkite failą</label>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-outline-info">Importuoti duomenis</button>
        </div>
    </div>
</form>
@if(count($data) != 0)
<div class="row col-md-6">
    <input class="form-control" type="text" id="search" onkeyup="search()" placeholder="Ieškoti ekspedicijų" title="Įveskite norimą tekstą">
</div>
<table class="table">
    <thead>
    <tr>
        <th scope="col">Gautas užsakymas (order)</th>
        <th scope="col">Sukontaktuota su tiekėju (contact)</th>
        <th scope="col">Surastas transportas (transport)</th>
        <th scope="col">Gabenama (exporting)</th>
        <th scope="col">Pristatyta (received)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $d)
        @if($d->state == 'order')
            <tr>
                <td>
                    <div class="card" style="width: 12rem;">
                        <div class="card-body">
                            <h5 class="card-title">Eksp. {{$d->order_no}}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{$d->route}}</h6>

                            <button class="btn btn-primary" data-toggle="modal" data-target="{{'#exp'.$d->order_no}}">Peržiūrėti</button>
                            <div class="modal fade" id="{{'exp'.$d->order_no}}" tabindex="-1" role="dialog" aria-labelledby="{{'exp'.$d->order_no}}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">{{'Ekspedicija nr. '.$d->order_no}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form>
                                                @csrf
                                                <div class="form-group">
                                                    <label for="clientState">Klientas</label>
                                                    <select class="custom-select" name="clientState" id="clientState" disabled>
                                                        <option value="{{$d->client}}">{{$d->klientas->name}}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="supplierState">Tiekėjo pavadinimas</label>
                                                    <select class="custom-select" name="supplierState" id="supplierState" disabled>
                                                        <option value="{{$d->supplier}}">{{$d->tiekejas->name}}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="routeState">Maršrutas</label>
                                                    <input type="text" class="form-control" id="routeState" name="routeState"
                                                           value="{{$d->route}}" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="routeDateAddressState">Datos ir adresai</label>
                                                    <input type="text" class="form-control" id="routeDateAddressState" name="routeDateAddressState"
                                                           value="{{explode('!!',$d->dates)[0].' '.explode('!!',$d->addresses)[0]}}" readonly>
                                                    @for($i = 1; $i < count(explode('!!',$d->dates)); $i++)
                                                        <input type="text" class="form-control" id="{{'routeDateState'}}" name="{{'routeDateState'}}"
                                                        value="{{explode('!!',$d->dates)[$i].' '.explode('!!',$d->addresses)[$i]}}" readonly>
                                                    @endfor
                                                </div>
                                                <div class="form-group">
                                                    <label for="cargoState">Krovinys</label>
                                                    <input type="text" class="form-control" id="cargoState" name="cargoState" placeholder="Įveskite krovinį"
                                                           value="{{$d->cargo}}" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="amountState">Kiekis</label>
                                                    <input type="text" class="form-control" id="amountState" name="amountState" placeholder="Įveskite krovinio kiekį"
                                                           value="{{$d->amount}}" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="profitState">Pelnas</label>
                                                    <input type="number" class="form-control" id="profitState" name="profitState" placeholder="Įveskite pelną"
                                                           value="{{$d->profit}}" readonly>
                                                </div>
                                            </form>
                                            <button class="btn btn-primary" data-toggle="modal" data-target="{{'#expstate'.$d->order_no}}">Keisti būseną</button>
                                            <button class="btn btn-info" data-toggle="modal" data-target="{{'#expedit'.$d->order_no}}" onclick="changeFieldNo({{count(explode('!!',$d->dates))}})">Redaguoti</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Uždaryti</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="{{'expedit'.$d->order_no}}" tabindex="-1" role="dialog" aria-labelledby="{{'expedit'.$d->order_no}}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">{{'Ekspedicija nr. '.$d->order_no}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="expeditions/edit" method="POST">
                                                @csrf
                                                <input type="text" name="id" value="{{$d->order_no}}" hidden>
                                                <div class="form-group">
                                                    <label for="clientState">Klientas</label>
                                                    <select class="custom-select" name="clientState" id="clientState">
                                                        <option value="{{$d->client}}">{{$d->klientas->name}}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="supplierState">Tiekėjo pavadinimas</label>
                                                    <select class="custom-select" name="supplierState" id="supplierState">
                                                        <option value="{{$d->supplier}}">{{$d->tiekejas->name}}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="routeState">Maršrutas</label>
                                                    <input type="text" class="form-control" id="routeState" name="routeState"
                                                           value="{{$d->route}}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="routeDateState">Datos ir adresai</label>
                                                    <input type="date" class="form-control" id="routeDateState" name="routeDateState"
                                                           value="{{explode('!!',$d->dates)[0]}}" required>
                                                    <input type="text" class="form-control" id="routeAddressState" name="routeAddressState"
                                                           value="{{explode('!!',$d->addresses)[0]}}" required>
                                                    <hr>
                                                    <span id="{{'foobar'.$d->order_no}}">
                                                            @for($i = 1; $i < count(explode('!!',$d->dates)); $i++)
                                                            <input type="date" class="form-control" id="{{'routeDateState'.$d->order_no.$i}}" name="{{'routeDateState'.$i}}"
                                                                   value="{{explode('!!',$d->dates)[$i]}}" required>
                                                            <input type="text" class="form-control" id="{{'routeAddressState'.$d->order_no.$i}}" name="{{'routeAddressState'.$i}}"
                                                                   value="{{explode('!!',$d->addresses)[$i]}}" required>
                                                            <button type="button" id="{{'delEdit'.$d->order_no.$i}}"
                                                                    onclick="deleteField({{'routeDateState'.$d->order_no.$i}},{{'routeAddressState'.$d->order_no.$i}},{{'delEdit'.$d->order_no.$i}},{{'line'.$d->order_no.$i}},
                                                                    {{$d->order_no}})">Trinti</button>
                                                            <hr id="{{'line'.$d->order_no.$i}}">
                                                        @endfor
                                                        </span>
                                                    <button type="button" onclick="createFields({{'foobar'.$d->order_no}},'routeDateState','routeAddressState', {{$i+1}},{{$d->order_no}})">Naujas laukelis</button>
                                                </div>
                                                <div class="form-group">
                                                    <label for="cargoState">Krovinys</label>
                                                    <input type="text" class="form-control" id="cargoState" name="cargoState" placeholder="Įveskite krovinį"
                                                           value="{{$d->cargo}}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="amountState">Kiekis</label>
                                                    <input type="text" class="form-control" id="amountState" name="amountState" placeholder="Įveskite krovinio kiekį"
                                                           value="{{$d->amount}}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="profitState">Pelnas</label>
                                                    <input type="number" class="form-control" id="profitState" name="profitState" placeholder="Įveskite pelną"
                                                           value="{{$d->profit}}" required>
                                                </div>

                                                <input id="{{'fieldsEditCount'.$d->order_no}}" name="{{'fieldsEditCount'.$d->order_no}}" type="number" hidden value="{{count(explode('!!',$d->dates))}}">
                                                <button type="submit" class="btn btn-success">Redaguoti</button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Atšaukti</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="{{'expstate'.$d->order_no}}" tabindex="-1" role="dialog" aria-labelledby="{{'expstate'.$d->order_no}}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">{{'Ekspedicijos '.$d->order_no.' būsenos keitimas'}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/expeditions/changeState" method="POST">
                                                @csrf
                                                <input type="hidden" name="orderNoState" value="{{$d->order_no}}">
                                                <div class="form-group">
                                                    <label for="clientState">Klientas</label>
                                                    <select class="custom-select" name="clientState" id="clientState" disabled>
                                                        @foreach($clients as $c)
                                                            @if($d->client == $c->id)
                                                                <option value="{{$c->id}}" selected>{{$c->name}}</option>
                                                                <option value="{{$c->id + $d->client}}">{{$c->id}} {{$d->client}}</option>
                                                            @else
                                                                <option value="{{$c->id}}">{{$c->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="supplierState">Tiekėjo pavadinimas</label>
                                                    <select class="custom-select" name="supplierState" id="supplierState" disabled>
                                                        @foreach($suppliers as $s)
                                                            @if($d->supplier == $s->id)
                                                                <option value="{{$s->id}}" selected>{{$s->name}}</option>
                                                            @else
                                                                <option value="{{$s->id}}">{{$s->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="routeState">Maršrutas</label>
                                                    <input type="text" class="form-control" id="routeState" name="routeState"
                                                           value="{{$d->route}}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Data ir pasikrovimo adresas</label>
                                                    <input type="date" class="form-control" id="routeDateState" name="routeDateState"
                                                           placeholder="Pasirinkite datą" value="{{explode('!!',$d->dates)[0]}}" required>
                                                    <input type="text" class="form-control" id="routeAddressState" name="routeAddressState"
                                                           placeholder="Įveskite adresą" value="{{explode('!!',$d->addresses)[0]}}" required>
                                                    <input type="button" onclick="addField({},null)" value="Pap">
                                                    <input type="button" onclick="removeField()" value="Naikinti">
                                                    <hr>

                                                    <span id="fooBar">&nbsp;
                                                        @for($i = 1; $i < count(explode('!!',$d->dates)); $i++)
                                                            <input type="date" class="form-control" id="{{'routeDateState'.$i}}" name="{{'routeDateState'.$i}}"
                                                                   placeholder="Pasirinkite datą" value="{{explode('!!',$d->dates)[$i]}}" required>
                                                            <input type="text" class="form-control" id="{{'routeAddressState'.$i}}" name="{{'routeAddressState'.$i}}"
                                                                   placeholder="Įveskite adresą" value="{{explode('!!',$d->addresses)[$i]}}" required>
                                                            <hr>
                                                        @endfor
                                                    </span>
                                                </div>
                                                <div class="form-group">
                                                    <label for="cargoState">Krovinys</label>
                                                    <input type="text" class="form-control" id="cargoState" name="cargoState" placeholder="Įveskite krovinį"
                                                           value="{{$d->cargo}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="amountState">Kiekis</label>
                                                    <input type="text" class="form-control" id="amountState" name="amountState" placeholder="Įveskite krovinio kiekį"
                                                           value="{{$d->amount}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="profitState">Pelnas</label>
                                                    <input type="number" class="form-control" id="profitState" name="profitState" placeholder="Įveskite pelną"
                                                           value="{{$d->profit}}">
                                                </div>
                                                <input id="fieldsNewCountState" name="fieldsNewCountState" type="number" hidden
                                                       value="{{count(explode('!!', $d->dates))-1}}">
                                                <button type="submit" class="btn btn-success">Keisti būseną</button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Atšaukti</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </td>
                <td></td><td></td><td></td><td></td>
            </tr>
        @elseif($d->state == 'contact')
            <tr>
                <td></td>
                    <td>
                        <div class="card" style="width: 12rem;">
                            <div class="card-body">
                                <h5 class="card-title">Eksp. {{$d->order_no}}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{$d->route}}</h6>

                                <button class="btn btn-primary" data-toggle="modal" data-target="{{'#exp'.$d->order_no}}">Peržiūrėti</button>
                                <div class="modal fade" id="{{'exp'.$d->order_no}}" tabindex="-1" role="dialog" aria-labelledby="{{'exp'.$d->order_no}}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">{{'Ekspedicija nr. '.$d->order_no}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form>
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="clientState">Klientas</label>
                                                        <select class="custom-select" name="clientState" id="clientState" disabled>
                                                            <option value="{{$d->client}}">{{$d->client}}</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="supplierState">Tiekėjo pavadinimas</label>
                                                        <select class="custom-select" name="supplierState" id="supplierState" disabled>
                                                            <option value="{{$d->supplier}}">{{$d->supplier}}</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="routeState">Maršrutas</label>
                                                        <input type="text" class="form-control" id="routeState" name="routeState"
                                                               value="{{$d->route}}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="routeDateAddressState">Datos ir adresai</label>
                                                        <input type="text" class="form-control" id="routeDateAddressState" name="routeDateAddressState"
                                                               value="{{explode('!!',$d->dates)[0].' '.explode('!!',$d->addresses)[0]}}" readonly>
                                                        <hr>
                                                            @for($i = 1; $i < count(explode('!!',$d->dates)); $i++)
                                                                <input type="text" class="form-control" id="{{'routeDateState'}}" name="{{'routeDateState'}}"
                                                                       value="{{explode('!!',$d->dates)[$i].' '.explode('!!',$d->addresses)[$i]}}" readonly>
                                                            @endfor
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="cargoState">Krovinys</label>
                                                        <input type="text" class="form-control" id="cargoState" name="cargoState" placeholder="Įveskite krovinį"
                                                               value="{{$d->cargo}}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="amountState">Kiekis</label>
                                                        <input type="text" class="form-control" id="amountState" name="amountState" placeholder="Įveskite krovinio kiekį"
                                                               value="{{$d->amount}}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="profitState">Pelnas</label>
                                                        <input type="number" class="form-control" id="profitState" name="profitState" placeholder="Įveskite pelną"
                                                               value="{{$d->profit}}" readonly>
                                                    </div>
                                                </form>
                                                <button class="btn btn-primary" data-toggle="modal" data-target="{{'#expstate'.$d->order_no}}">Keisti būseną</button>
                                                <button class="btn btn-info" data-toggle="modal" data-target="{{'#expedit'.$d->order_no}}"
                                                        onclick="changeFieldNo({{count(explode('!!',$d->dates))}})">Redaguoti</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Uždaryti</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="{{'expedit'.$d->order_no}}" tabindex="-1" role="dialog" aria-labelledby="{{'expedit'.$d->order_no}}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">{{'Ekspedicija nr. '.$d->order_no}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="expeditions/edit" method="POST">
                                                    @csrf
                                                    <input type="text" name="id" value="{{$d->order_no}}" hidden>
                                                    <div class="form-group">
                                                        <label for="clientState">Klientas</label>
                                                        <select class="custom-select" name="clientState" id="clientState">
                                                            <option value="{{$d->client}}">{{$d->klientas->name}}</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="supplierState">Tiekėjo pavadinimas</label>
                                                        <select class="custom-select" name="supplierState" id="supplierState">
                                                            <option value="{{$d->supplier}}">{{$d->tiekejas->name}}</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="routeState">Maršrutas</label>
                                                        <input type="text" class="form-control" id="routeState" name="routeState"
                                                               value="{{$d->route}}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="routeDateState">Datos ir adresai</label>
                                                        <input type="date" class="form-control" id="routeDateState" name="routeDateState"
                                                               value="{{explode('!!',$d->dates)[0]}}" required>
                                                        <input type="text" class="form-control" id="routeAddressState" name="routeAddressState"
                                                               value="{{explode('!!',$d->addresses)[0]}}" required>
                                                        <hr>
                                                        <span id="{{'foobar'.$d->order_no}}">
                                                            @for($i = 1; $i < count(explode('!!',$d->dates)); $i++)
                                                                <input type="date" class="form-control" id="{{'routeDateState'.$d->order_no.$i}}" name="{{'routeDateState'.$i}}"
                                                                       value="{{explode('!!',$d->dates)[$i]}}" required>
                                                                <input type="text" class="form-control" id="{{'routeAddressState'.$d->order_no.$i}}" name="{{'routeAddressState'.$i}}"
                                                                       value="{{explode('!!',$d->addresses)[$i]}}" required>
                                                                <button type="button" id="{{'delEdit'.$d->order_no.$i}}"
                                                                        onclick="deleteField({{'routeDateState'.$d->order_no.$i}},{{'routeAddressState'.$d->order_no.$i}},{{'delEdit'.$d->order_no.$i}},{{'line'.$d->order_no.$i}},
                                                                            {{$d->order_no}})">Trinti</button>
                                                                <hr id="{{'line'.$d->order_no.$i}}">
                                                            @endfor
                                                        </span>
                                                        <button type="button" onclick="createFields({{'foobar'.$d->order_no}},'routeDateState','routeAddressState', {{$i+1}},{{$d->order_no}})">Naujas laukelis</button>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="cargoState">Krovinys</label>
                                                        <input type="text" class="form-control" id="cargoState" name="cargoState" placeholder="Įveskite krovinį"
                                                               value="{{$d->cargo}}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="amountState">Kiekis</label>
                                                        <input type="text" class="form-control" id="amountState" name="amountState" placeholder="Įveskite krovinio kiekį"
                                                               value="{{$d->amount}}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="profitState">Pelnas</label>
                                                        <input type="number" class="form-control" id="profitState" name="profitState" placeholder="Įveskite pelną"
                                                               value="{{$d->profit}}" required>
                                                    </div>

                                                    <input id="{{'fieldsEditCount'.$d->order_no}}" name="{{'fieldsEditCount'.$d->order_no}}" type="number" hidden value="{{count(explode('!!',$d->dates))}}">
                                                    <button type="submit" class="btn btn-success">Redaguoti</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Atšaukti</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="{{'expstate'.$d->order_no}}" tabindex="-1" role="dialog" aria-labelledby="{{'expstate'.$d->order_no}}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">{{'Ekspedicijos '.$d->order_no.' būsenos keitimas'}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/expeditions/changeState" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="orderNoState" value="{{$d->order_no}}">
                                                    <div class="form-group">
                                                        <label for="carrierState">Vežėjas</label>
                                                        <input type="text" class="form-control" id="carrierState" name="carrierState" placeholder="Įveskite vežėją"
                                                               required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="carrierPriceState">Vežėjo kaina</label>
                                                        <input type="text" class="form-control" id="carrierPriceState" name="carrierPriceState" placeholder="Įveskite vežėjo kainą"
                                                               required>
                                                    </div>
                                                    <button type="submit" class="btn btn-success">Keisti būseną</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Atšaukti</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </td>
                <td></td><td></td><td></td>
            </tr>
        @elseif($d->state == 'transport')
            <tr>
                <td></td><td></td>
                    <td>
                        <div class="card" style="width: 12rem;">
                            <div class="card-body">
                                <h5 class="card-title">Eksp. {{$d->order_no}}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{$d->route}}</h6>

                                <button class="btn btn-primary" data-toggle="modal" data-target="{{'#exp'.$d->order_no}}">Peržiūrėti</button>
                                <div class="modal fade" id="{{'exp'.$d->order_no}}" tabindex="-1" role="dialog" aria-labelledby="{{'exp'.$d->order_no}}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">{{'Ekspedicija nr. '.$d->order_no}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form>
                                                    @csrf
                                                    <a class="btn btn-light" data-toggle="collapse" href="{{'#uzsakymas'.$d->order_no}}" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                        Užsakymas
                                                    </a>
                                                    <p></p>
                                                    <div class="collapse" id="{{'uzsakymas'.$d->order_no}}">
                                                        <div class="card card-body">
                                                            <div class="form-group">
                                                                <label for="clientState">Klientas</label>
                                                                <select class="custom-select" name="clientState" id="clientState" disabled>
                                                                    <option value="{{$d->client}}">{{$d->klientas->name}}</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="supplierState">Tiekėjo pavadinimas</label>
                                                                <select class="custom-select" name="supplierState" id="supplierState" disabled>
                                                                    <option value="{{$d->supplier}}">{{$d->tiekejas->name}}</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="routeDateAddressState">Datos ir adresai</label>
                                                                <input type="text" class="form-control" id="routeDateAddressState" name="routeDateAddressState"
                                                                       value="{{explode('!!',$d->dates)[0].' '.explode('!!',$d->addresses)[0]}}" readonly>
                                                                @for($i = 1; $i <= count(explode('!!',$d->dates))-1; $i++)
                                                                    <input type="text" class="form-control" id="{{'routeDateState'}}" name="{{'routeDateState'}}"
                                                                           value="{{explode('!!',$d->dates)[$i].' '.explode('!!',$d->addresses)[$i]}}" readonly>
                                                                @endfor
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="routeState">Maršrutas</label>
                                                                <input type="text" class="form-control" id="routeState" name="routeState"
                                                                       value="{{$d->route}}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="cargoState">Krovinys</label>
                                                                <input type="text" class="form-control" id="cargoState" name="cargoState" placeholder="Įveskite krovinį"
                                                                       value="{{$d->cargo}}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="amountState">Kiekis</label>
                                                                <input type="text" class="form-control" id="amountState" name="amountState" placeholder="Įveskite krovinio kiekį"
                                                                       value="{{$d->amount}}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="profitState">Pelnas</label>
                                                                <input type="number" class="form-control" id="profitState" name="profitState" placeholder="Įveskite pelną"
                                                                       value="{{$d->profit}}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="carrierState">Vežėjas</label>
                                                        <input type="text" class="form-control" id="carrierState" name="carrierState" placeholder="Įveskite vežėją"
                                                               value="{{$d->carrier}}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="carrierPriceState">Vežėjo kaina</label>
                                                        <input type="number" class="form-control" id="carrierPriceState" name="carrierPriceState" placeholder="Įveskite vežėjo kainą"
                                                               value="{{$d->carrier_price}}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="totalPriceState">Visas pelnas</label>
                                                        <input type="number" class="form-control" id="totalPriceState" name="totalPriceState" placeholder="Įveskite visą pelną"
                                                               value="{{$d->total_profit}}" readonly>
                                                    </div>
                                                </form>
                                                <button class="btn btn-primary" data-toggle="modal" data-target="{{'#expstate'.$d->order_no}}">Keisti būseną</button>
                                                <button class="btn btn-info" data-toggle="modal" data-target="{{'#expedit'.$d->order_no}}" onclick="changeFieldNo({{count(explode('!!',$d->dates))}})">Redaguoti</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Uždaryti</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="{{'expedit'.$d->order_no}}" tabindex="-1" role="dialog" aria-labelledby="{{'expedit'.$d->order_no}}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">{{'Ekspedicija nr. '.$d->order_no}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="expeditions/edit" method="POST">
                                                    @csrf
                                                    <input type="text" name="id" value="{{$d->order_no}}" hidden>
                                                    <div class="form-group">
                                                        <label for="clientState">Klientas</label>
                                                        <select class="custom-select" name="clientState" id="clientState">
                                                            <option value="{{$d->client}}">{{$d->klientas->name}}</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="supplierState">Tiekėjo pavadinimas</label>
                                                        <select class="custom-select" name="supplierState" id="supplierState">
                                                            <option value="{{$d->supplier}}">{{$d->tiekejas->name}}</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="routeState">Maršrutas</label>
                                                        <input type="text" class="form-control" id="routeState" name="routeState"
                                                               value="{{$d->route}}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="routeDateState">Datos ir adresai</label>
                                                        <input type="date" class="form-control" id="routeDateState" name="routeDateState"
                                                               value="{{explode('!!',$d->dates)[0]}}" required>
                                                        <input type="text" class="form-control" id="routeAddressState" name="routeAddressState"
                                                               value="{{explode('!!',$d->addresses)[0]}}" required>
                                                        <hr>
                                                        <span id="{{'foobar'.$d->order_no}}">
                                                            @for($i = 1; $i < count(explode('!!',$d->dates)); $i++)
                                                                <input type="date" class="form-control" id="{{'routeDateState'.$d->order_no.$i}}" name="{{'routeDateState'.$i}}"
                                                                       value="{{explode('!!',$d->dates)[$i]}}" required>
                                                                <input type="text" class="form-control" id="{{'routeAddressState'.$d->order_no.$i}}" name="{{'routeAddressState'.$i}}"
                                                                       value="{{explode('!!',$d->addresses)[$i]}}" required>
                                                                <button type="button" id="{{'delEdit'.$d->order_no.$i}}"
                                                                        onclick="deleteField({{'routeDateState'.$d->order_no.$i}},{{'routeAddressState'.$d->order_no.$i}},{{'delEdit'.$d->order_no.$i}},{{'line'.$d->order_no.$i}},
                                                                        {{$d->order_no}})">Trinti</button>
                                                                <hr id="{{'line'.$d->order_no.$i}}">
                                                            @endfor
                                                        </span>
                                                        <button type="button" onclick="createFields({{'foobar'.$d->order_no}},'routeDateState','routeAddressState', {{$i+1}},{{$d->order_no}})">Naujas laukelis</button>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="cargoState">Krovinys</label>
                                                        <input type="text" class="form-control" id="cargoState" name="cargoState" placeholder="Įveskite krovinį"
                                                               value="{{$d->cargo}}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="amountState">Kiekis</label>
                                                        <input type="text" class="form-control" id="amountState" name="amountState" placeholder="Įveskite krovinio kiekį"
                                                               value="{{$d->amount}}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="profitState">Pelnas</label>
                                                        <input type="number" class="form-control" id="{{'profitState'.$d->order_no}}" name="profitState" placeholder="Įveskite pelną"
                                                               value="{{$d->profit}}" required oninput="calcTotalProfit({{$d->order_no}})">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="carrierState">Vežėjas</label>
                                                        <input type="text" class="form-control" id="carrierState" name="carrierState" placeholder="Įveskite vežėją"
                                                               value="{{$d->carrier}}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="carrierPriceState">Vežėjo kaina</label>
                                                        <input type="number" class="form-control" id="{{'carrierPriceState'.$d->order_no}}" name="carrierPriceState" placeholder="Įveskite vežėjo kainą"
                                                               value="{{$d->carrier_price}}" oninput="calcTotalProfit({{$d->order_no}})" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="totalPriceState">Visas pelnas</label>
                                                        <input type="number" class="form-control" id="{{'totalPriceState'.$d->order_no}}" name="totalPriceState" placeholder="Įveskite visą pelną"
                                                               value="{{$d->total_profit}}" readonly>
                                                    </div>

                                                    <input id="{{'fieldsEditCount'.$d->order_no}}" name="{{'fieldsEditCount'.$d->order_no}}" type="number" hidden value="{{count(explode('!!',$d->dates))}}">
                                                    <button type="submit" class="btn btn-success">Redaguoti</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Atšaukti</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="{{'expstate'.$d->order_no}}" tabindex="-1" role="dialog" aria-labelledby="{{'expstate'.$d->order_no}}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">{{'Ekspedicijos '.$d->order_no.' būsenos keitimas'}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/expeditions/changeState" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="orderNoState" value="{{$d->order_no}}">
                                                    <div class="form-group">
                                                        <label for="routeDateAddressState">Planuotas pasikrovimas</label>
                                                        <input type="text" class="form-control" id="routeDateAddressState" name="routeDateAddressState"
                                                               value="{{explode('!!',$d->dates)[0].' '.explode('!!',$d->addresses)[0]}}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="loadedState">Pasikrovimo data</label>
                                                        <input type="date" class="form-control" id="loadedState" name="loadedState" placeholder="Įveskite datą"
                                                               value="{{\Carbon\Carbon::now()->toDateString()}}" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-success">Keisti būseną</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Atšaukti</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </td>
                <td></td><td></td>
            </tr>
        @elseif($d->state == 'exporting')
            <tr>
                <td></td><td></td><td></td>
                    <td>
                        <div class="card" style="width: 12rem;">
                            <div class="card-body">
                                <h5 class="card-title">Eksp. {{$d->order_no}}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{$d->route}}</h6>

                                <button class="btn btn-primary" data-toggle="modal" data-target="{{'#exp'.$d->order_no}}">Peržiūrėti</button>
                                <div class="modal fade" id="{{'exp'.$d->order_no}}" tabindex="-1" role="dialog" aria-labelledby="{{'exp'.$d->order_no}}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">{{'Ekspedicija nr. '.$d->order_no}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form>
                                                    @csrf
                                                    <a class="btn btn-light" data-toggle="collapse" href="{{'#uzsakymas'.$d->order_no}}" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                        Užsakymas
                                                    </a>
                                                    <p></p>
                                                    <div class="collapse" id="{{'uzsakymas'.$d->order_no}}">
                                                        <div class="card card-body">
                                                            <div class="form-group">
                                                                <label for="clientState">Klientas</label>
                                                                <select class="custom-select" name="clientState" id="clientState" disabled>
                                                                    <option value="{{$d->client}}">{{$d->client}}</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="supplierState">Tiekėjo pavadinimas</label>
                                                                <select class="custom-select" name="supplierState" id="supplierState" disabled>
                                                                    <option value="{{$d->supplier}}">{{$d->supplier}}</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="routeDateAddressState">Datos ir adresai</label>
                                                                <input type="text" class="form-control" id="routeDateAddressState" name="routeDateAddressState"
                                                                       value="{{explode('!!',$d->dates)[0].' '.explode('!!',$d->addresses)[0]}}" readonly>
                                                                @for($i = 1; $i <= count(explode('!!',$d->dates))-1; $i++)
                                                                    <input type="text" class="form-control" id="{{'routeDateState'}}" name="{{'routeDateState'}}"
                                                                           value="{{explode('!!',$d->dates)[$i].' '.explode('!!',$d->addresses)[$i]}}" readonly>
                                                                @endfor
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="routeState">Maršrutas</label>
                                                                <input type="text" class="form-control" id="routeState" name="routeState"
                                                                       value="{{$d->route}}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="cargoState">Krovinys</label>
                                                                <input type="text" class="form-control" id="cargoState" name="cargoState" placeholder="Įveskite krovinį"
                                                                       value="{{$d->cargo}}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="amountState">Kiekis</label>
                                                                <input type="text" class="form-control" id="amountState" name="amountState" placeholder="Įveskite krovinio kiekį"
                                                                       value="{{$d->amount}}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="profitState">Pelnas</label>
                                                                <input type="number" class="form-control" id="profitState" name="profitState" placeholder="Įveskite pelną"
                                                                       value="{{$d->profit}}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="carrierState">Vežėjas</label>
                                                        <input type="text" class="form-control" id="carrierState" name="carrierState" placeholder="Įveskite vežėją"
                                                               value="{{$d->carrier}}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="carrierPriceState">Vežėjo kaina</label>
                                                        <input type="number" class="form-control" id="carrierPriceState" name="carrierPriceState" placeholder="Įveskite vežėjo kainą"
                                                               value="{{$d->carrier_price}}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="totalPriceState">Visas pelnas</label>
                                                        <input type="number" class="form-control" id="totalPriceState" name="totalPriceState" placeholder="Įveskite visą pelną"
                                                               value="{{$d->total_profit}}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="loadedState">Pasikrovimo data</label>
                                                        <input type="date" class="form-control" id="loadedState" name="loadedState" placeholder="Įveskite visą pelną"
                                                               value="{{$d->loaded}}" readonly>
                                                    </div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-info" role="progressbar" style="{{'width: '.($d->progress/count(explode('!!',$d->dates))*100).'%'}}" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    @for($i = 0; $i < count(explode('!!',$d->dates))-1; $i++)
                                                        @if($i <= $d->progress - 1)
                                                            <p style="color: green">{{explode('!!',$d->dates)[$i].' '.explode('!!',$d->addresses)[$i].' = pasikrauta'}}</p>
                                                        @else
                                                            <p>{{explode('!!',$d->dates)[$i].' '.explode('!!',$d->addresses)[$i].' = vyksta'}}</p>
                                                        @endif
                                                    @endfor
                                                </form>
                                                <button class="btn btn-primary" data-toggle="modal" data-target="{{'#expstate'.$d->order_no}}">Keisti būseną</button>
                                                <button class="btn btn-info" data-toggle="modal" data-target="{{'#expedit'.$d->order_no}}"
                                                        onclick="changeFieldNo({{count(explode('!!',$d->dates))}})">Redaguoti</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Uždaryti</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="{{'expedit'.$d->order_no}}" tabindex="-1" role="dialog" aria-labelledby="{{'expedit'.$d->order_no}}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">{{'Ekspedicija nr. '.$d->order_no}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="expeditions/edit" method="POST">
                                                    @csrf
                                                    <input type="text" name="id" value="{{$d->order_no}}" hidden>
                                                    <div class="form-group">
                                                        <label for="clientState">Klientas</label>
                                                        <select class="custom-select" name="clientState" id="clientState">
                                                            <option value="{{$d->client}}">{{$d->klientas->name}}</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="supplierState">Tiekėjo pavadinimas</label>
                                                        <select class="custom-select" name="supplierState" id="supplierState">
                                                            <option value="{{$d->supplier}}">{{$d->tiekejas->name}}</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="routeState">Maršrutas</label>
                                                        <input type="text" class="form-control" id="routeState" name="routeState"
                                                               value="{{$d->route}}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="routeDateState">Datos ir adresai</label>
                                                        <input type="date" class="form-control" id="routeDateState" name="routeDateState"
                                                               value="{{explode('!!',$d->dates)[0]}}" required>
                                                        <input type="text" class="form-control" id="routeAddressState" name="routeAddressState"
                                                               value="{{explode('!!',$d->addresses)[0]}}" required>
                                                        <input id="{{'lastIndex'.$d->order_no}}" value="{{count(explode('!!',$d->dates))}}" hidden>
                                                        @if($d->progress > 2)
                                                            <input type="checkbox" id="{{'progressState'.$d->order_no}}" name="progressState" checked disabled oninput="changeProgress({{$d->order_no}},0,'document.getElementById({{'lastIndex'.$d->order_no}}).value')">
                                                        @elseif($d->progress == 1)
                                                            <input type="checkbox" id="{{'progressState'.$d->order_no}}" name="progressState" checked oninput="changeProgress({{$d->order_no}},0,'document.getElementById({{'lastIndex'.$d->order_no}}).value')">
                                                        @else
                                                            <input type="checkbox" id="{{'progressState'.$d->order_no}}" name="progressState" oninput="changeProgress({{$d->order_no}},0,'document.getElementById({{'lastIndex'.$d->order_no}}).value')">
                                                        @endif
                                                        <label class="form-check-label" for="{{'progressState'.$d->order_no}}">Pasikrauta</label>
                                                        <hr>
                                                        <span id="{{'foobar'.$d->order_no}}">
                                                            @for($i = 1; $i < count(explode('!!',$d->dates)); $i++)
                                                                <input type="date" class="form-control" id="{{'routeDateState'.$d->order_no.$i}}" name="{{'routeDateState'.$i}}"
                                                                       value="{{explode('!!',$d->dates)[$i]}}" required>
                                                                <input type="text" class="form-control" id="{{'routeAddressState'.$d->order_no.$i}}" name="{{'routeAddressState'.$i}}"
                                                                       value="{{explode('!!',$d->addresses)[$i-1]}}" required>
                                                                <button type="button" id="{{'delEdit'.$d->order_no.$i}}" class="btn btn-danger"
                                                                        onclick="deleteFieldsExporting({{'routeDateState'.$d->order_no.$i}},{{'routeAddressState'.$d->order_no.$i}},{{'delEdit'.$d->order_no.$i}},
                                                                        {{'line'.$d->order_no.$i}},{{$d->order_no}},{{'progressState'.$d->order_no.$i}},
                                                                        {{'progressLabel'.$i}})">Trinti</button>
                                                                @if($i + 1 < $d->progress)
                                                                    <input type="checkbox" id="{{'progressState'.$d->order_no.$i}}" name="{{'progressState'.$i}}" checked disabled
                                                                           oninput="changeProgress({{$d->order_no}},{{$i}},'document.getElementById({{'lastIndex'.$d->order_no}}).value')">
                                                                @elseif($i + 1 == $d->progress)
                                                                    <input type="checkbox" id="{{'progressState'.$d->order_no.$i}}" name="{{'progressState'.$i}}" checked
                                                                           oninput="changeProgress({{$d->order_no}},{{$i}},'document.getElementById({{'lastIndex'.$d->order_no}}).value')">
                                                                @elseif($i + 1 > $d->progress + 1)
                                                                    <input type="checkbox" id="{{'progressState'.$d->order_no.$i}}" name="{{'progressState'.$i}}" disabled
                                                                           oninput="changeProgress({{$d->order_no}},{{$i}},'document.getElementById({{'lastIndex'.$d->order_no}}).value')">
                                                                @else
                                                                    <input type="checkbox" id="{{'progressState'.$d->order_no.$i}}" name="{{'progressState'.$i}}"
                                                                           oninput="changeProgress({{$d->order_no}},{{$i}},'document.getElementById({{'lastIndex'.$d->order_no}}).value')">
                                                                @endif
                                                                <label class="form-check-label" id="{{'progressLabel'.$i}}" for="{{'progressState'.$d->order_no.$i}}">Pasikrauta</label>
                                                                <hr id="{{'line'.$d->order_no.$i}}">
                                                            @endfor
                                                        </span>
                                                        <button type="button" class="btn btn-success" onclick="createFieldsExporting({{'foobar'.$d->order_no}},'routeDateState','routeAddressState', {{$i+1}},{{$d->order_no}})">Naujas laukelis</button>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="cargoState">Krovinys</label>
                                                        <input type="text" class="form-control" id="cargoState" name="cargoState" placeholder="Įveskite krovinį"
                                                               value="{{$d->cargo}}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="amountState">Kiekis</label>
                                                        <input type="text" class="form-control" id="amountState" name="amountState" placeholder="Įveskite krovinio kiekį"
                                                               value="{{$d->amount}}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="profitState">Pelnas</label>
                                                        <input type="number" class="form-control" id="{{'profitState'.$d->order_no}}" name="profitState" placeholder="Įveskite pelną"
                                                               value="{{$d->profit}}" required oninput="calcTotalProfit({{$d->order_no}})">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="carrierState">Vežėjas</label>
                                                        <input type="text" class="form-control" id="carrierState" name="carrierState" placeholder="Įveskite vežėją"
                                                               value="{{$d->carrier}}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="carrierPriceState">Vežėjo kaina</label>
                                                        <input type="number" class="form-control" id="{{'carrierPriceState'.$d->order_no}}" name="carrierPriceState" placeholder="Įveskite vežėjo kainą"
                                                               value="{{$d->carrier_price}}" oninput="calcTotalProfit({{$d->order_no}})" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="totalPriceState">Visas pelnas</label>
                                                        <input type="number" class="form-control" id="{{'totalPriceState'.$d->order_no}}" name="totalPriceState" placeholder="Įveskite visą pelną"
                                                               value="{{$d->total_profit}}" readonly>
                                                    </div>

                                                    <input id="{{'fieldsEditCount'.$d->order_no}}" name="{{'fieldsEditCount'.$d->order_no}}" type="number" hidden value="{{count(explode('!!',$d->dates))}}">
                                                    <input id="progressCount" name="progressCount" value="{{$d->progress}}" hidden>
                                                    <button type="submit" class="btn btn-success">Redaguoti</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Atšaukti</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="{{'expstate'.$d->order_no}}" tabindex="-1" role="dialog" aria-labelledby="{{'expstate'.$d->order_no}}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">{{'Ekspedicijos '.$d->order_no.' būsenos keitimas'}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/expeditions/changeState" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="orderNoState" value="{{$d->order_no}}">
                                                    <!-- <div class="form-group">
                                                        <input type="date" class="form-control" value="{//explode('!!', $d->dates)[$d->progress]}" readonly hidden>
                                                    </div> -->
                                                    @if($d->progress != count(explode('!!', $d->dates)) - 1)
                                                        <div class="form-group">
                                                            <label for="loadedState">Planuojama pasikrovimo data</label>
                                                            <input type="date" class="form-control" id="loadedState" name="loadedState" placeholder="Įveskite visą pelną"
                                                                   value="{{explode('!!', $d->dates)[$d->progress-1]}}" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="unloadedState">Pasikrovimo data</label>
                                                            <input type="date" class="form-control" id="unloadedState" name="unloadedState" placeholder="Įveskite datą"
                                                                   value="getToday()" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-success">Keisti būseną</button>
                                                    @else
                                                        <div class="form-group">
                                                            <label for="loadedState">Planuojama pristatymo data</label>
                                                            <input type="date" class="form-control" id="loadedState" name="loadedState" placeholder="Įveskite visą pelną"
                                                                   value="{{explode('!!', $d->dates)[$d->progress]}}" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="unloadedState">Pristatymo data</label>
                                                            <input type="date" class="form-control" id="unloadedState" name="unloadedState" placeholder="Įveskite datą"
                                                                   value="getToday()" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-success">Keisti būseną</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Atšaukti</button>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </td>
                <td></td>
            </tr>
        @elseif($d->state == 'received')
            <tr>
                <td></td><td></td><td></td><td></td>
                    <td>
                        <div class="card" style="width: 12rem;">
                            <div class="card-body">
                                <h5 class="card-title">Eksp. {{$d->order_no}}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{$d->route}}</h6>

                                <button class="btn btn-primary" data-toggle="modal" data-target="{{'#exp'.$d->order_no}}">Peržiūrėti</button>
                                <div class="modal fade" id="{{'exp'.$d->order_no}}" tabindex="-1" role="dialog" aria-labelledby="{{'exp'.$d->order_no}}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">{{'Ekspedicija nr. '.$d->order_no}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/expeditions/changeState" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="orderNoState" value="{{$d->order_no}}">
                                                    <a class="btn btn-light" data-toggle="collapse" href="{{'#uzsakymas'.$d->order_no}}" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                        Užsakymas
                                                    </a>
                                                    <p></p>
                                                    <div class="collapse" id="{{'uzsakymas'.$d->order_no}}">
                                                        <div class="card card-body">
                                                            <div class="form-group">
                                                                <label for="clientState">Klientas</label>
                                                                <select class="custom-select" name="clientState" id="clientState" disabled>
                                                                    <option value="{{$d->client}}">{{$d->client}}</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="supplierState">Tiekėjo pavadinimas</label>
                                                                <select class="custom-select" name="supplierState" id="supplierState" disabled>
                                                                    <option value="{{$d->supplier}}">{{$d->supplier}}</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="routeDateAddressState">Datos ir adresai</label>
                                                                <input type="text" class="form-control" id="routeDateAddressState" name="routeDateAddressState"
                                                                       value="{{explode('!!',$d->dates)[0].' '.explode('!!',$d->addresses)[0]}}" readonly>
                                                                @for($i = 1; $i < count(explode('!!',$d->dates)); $i++)
                                                                    <input type="text" class="form-control" id="{{'routeDateState'}}" name="{{'routeDateState'}}"
                                                                           value="{{explode('!!',$d->dates)[$i].' '.explode('!!',$d->addresses)[$i]}}" readonly>
                                                                @endfor
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="routeState">Maršrutas</label>
                                                                <input type="text" class="form-control" id="routeState" name="routeState"
                                                                       value="{{$d->route}}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="cargoState">Krovinys</label>
                                                                <input type="text" class="form-control" id="cargoState" name="cargoState" placeholder="Įveskite krovinį"
                                                                       value="{{$d->cargo}}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="amountState">Kiekis</label>
                                                                <input type="text" class="form-control" id="amountState" name="amountState" placeholder="Įveskite krovinio kiekį"
                                                                       value="{{$d->amount}}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="profitState">Pelnas</label>
                                                                <input type="number" class="form-control" id="profitState" name="profitState" placeholder="Įveskite pelną"
                                                                       value="{{$d->profit}}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="carrierState">Vežėjas</label>
                                                        <input type="text" class="form-control" id="carrierState" name="carrierState" placeholder="Įveskite vežėją"
                                                               value="{{$d->carrier}}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="carrierPriceState">Vežėjo kaina</label>
                                                        <input type="number" class="form-control" id="carrierPriceState" name="carrierPriceState" placeholder="Įveskite vežėjo kainą"
                                                               value="{{$d->carrier_price}}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="totalPriceState">Visas pelnas</label>
                                                        <input type="number" class="form-control" id="totalPriceState" name="totalPriceState" placeholder="Įveskite visą pelną"
                                                               value="{{$d->total_profit}}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="loadedState">Pasikrovimo data</label>
                                                        <input type="date" class="form-control" id="loadedState" name="loadedState" placeholder="Įveskite "
                                                               value="{{$d->loaded}}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="unloadedState">Išsikrovimo data</label>
                                                        <input type="date" class="form-control" id="unloadedState" name="unloadedState" placeholder="Įveskite visą pelną"
                                                               value="{{$d->unloaded}}" readonly>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Uždaryti ekspediciją</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Uždaryti</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="{{'expstate'.$d->order_no}}" tabindex="-1" role="dialog" aria-labelledby="{{'expstate'.$d->order_no}}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">{{'Ekspedicijos '.$d->order_no.' būsenos keitimas'}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/expeditions/changeState" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="orderNoState" value="{{$d->order_no}}">
                                                    <div class="form-group">
                                                        <label for="unloadedState">Išsikrovimo data</label>
                                                        <input type="date" class="form-control" id="unloadedState" name="unloadedState" placeholder="Įveskite datą"
                                                               value="getToday()" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-success">Keisti būseną</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Atšaukti</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </td>
            </tr>
        @else
            <tr>
                <td>error</td><td></td><td></td><td></td><td></td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>
@else
    <div class="alert alert-info" role="alert">
        Nėra vykstančių ekspedicijų.
    </div>
@endif


    <script>
        document.getElementById('expBtn').classList.remove('btn-outline-success');
        document.getElementById('expBtn').classList.add('btn-success');
        @if(session()->has('exp'))
            $('#exp'+{{session()->get('exp')[0]}}).modal('show');
            @php(session()->pull('exp'))
        @endif
        function getToday() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;
            return today;
        }
        function search() {
            var input, filter, table, tr, td, i;
            input = document.getElementById("search");
            filter = input.value.toUpperCase();
            table = document.getElementById("clientTable");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td") ;
                for(j=0 ; j<td.length ; j++)
                {
                    let tdata = td[j] ;
                    if (tdata) {
                        if (tdata.innerHTML.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break ;
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }
        }
        function initAdditionalFields(id, count) {
            fieldCount = count+1;
            for(let i =1; i < fieldCount; i++) {
                fieldsAddress[id][i-1] = 'routeAddressNew'+i;
                fieldsDate[id][i-1] = 'routeDateNew'+i;
                fieldsLine[id][i-1] = 'line'+i;
            }
            console.log(fieldsAddress,fieldsDate,fieldsLine);
        }
        var fieldNo = {{count(explode('!!',$d->dates))}};
        function createFields(fooID,dateString,addressString,fieldCount, orderNo) {
            document.getElementById('delEdit'+orderNo.toString()+(fieldNo-1)).hidden = false;
            var address = document.createElement("input");

            address.setAttribute("type", 'text');
            address.setAttribute("class", 'form-control');
            address.setAttribute("id", addressString + orderNo.toString() + fieldNo);
            address.setAttribute("name", addressString + fieldNo);
            address.setAttribute("placeholder", 'metai.mėn.d adresas');
            address.required = true;

            var date = document.createElement("input");

            date.setAttribute("type", 'date');
            date.setAttribute("class", 'form-control');
            date.setAttribute("id", dateString + orderNo.toString()+ fieldNo);
            date.setAttribute("name", dateString + fieldNo);
            date.required = true;

            var line = document.createElement("hr");
            line.setAttribute("id", 'lineEdit' + fieldNo);

            var delButton = document.createElement('button');
            delButton.setAttribute('type','button');
            delButton.setAttribute('class','btn btn-danger');
            delButton.setAttribute('id','delEdit'+orderNo.toString()+fieldNo);
            delButton.setAttribute('onclick', 'deleteField('+date.id+','+address.id+','+delButton.id+','+line.id+');');
            delButton.innerText = 'Trinti';

            var foo = document.getElementById(fooID.id);

            foo.appendChild(date);
            foo.appendChild(address);
            foo.appendChild(delButton);
            foo.appendChild(line);
            fieldNo++;
            document.getElementById('fieldsEditCount'+orderNo).value = fieldNo;
        }
        @if(session()->has('neworder'))
            var fieldNoNew = {{count(explode('!!',session('neworder')[0][3]))}} + 1;
            $('#naujas').modal('show');
            @php(session()->pull('neworder'))
        @else
            var fieldNoNew = 1;
        @endif
        function createFieldsNew() {
            var address = document.createElement("input");

            //Assign different attributes to the element.
            address.setAttribute("type", 'text');
            address.setAttribute("class", 'form-control');
            address.setAttribute("id", 'routeAddressNew' + fieldNoNew);
            address.setAttribute("name", 'routeAddressNew' + fieldNoNew);
            address.setAttribute("placeholder", 'metai.mėn.d adresas');
            address.required = true;

            var date = document.createElement("input");

            date.setAttribute("type", 'date');
            date.setAttribute("class", 'form-control');
            date.setAttribute("id", 'routeDateNew' + fieldNoNew);
            date.setAttribute("name", 'routeDateNew' + fieldNoNew);
            date.required = true;

            var line = document.createElement("hr");
            line.setAttribute("id", 'lineNew' + fieldNoNew);

            var delButton = document.createElement('button');
            delButton.setAttribute('type','button');
            delButton.setAttribute('id','delNew'+fieldNoNew);
            delButton.setAttribute('onclick', 'deleteFieldNew('+'routeDateNew' + fieldNoNew+','+'routeAddressNew' + fieldNoNew+','+
                'delNew'+fieldNoNew+','+'lineNew' + fieldNoNew+');');
            delButton.innerText = 'Trinti';

            var foo = document.getElementById('fooBarNew');

            //Append the element in page (in span).
            foo.appendChild(date);
            foo.appendChild(address);
            foo.appendChild(delButton);
            foo.appendChild(line);
            fieldNoNew++;
            /*fieldsAddress[id].push('routeAddressNew' + fieldCount);
            fieldsDate[id].push('routeDateNew' + fieldCount);
            fieldsLine[id].push('line' + fieldCount);
            fieldCount += 1;*/
            document.getElementById('fieldsNewCount').value = fieldNoNew - 1;
        }
        function deleteField(dateID, addressID, delID, lineID, orderNo) {
            document.getElementById(dateID.id).remove();
            document.getElementById(addressID.id).remove();
            document.getElementById(delID.id).remove();
            document.getElementById(lineID.id).remove();
            fieldNo--;
            document.getElementById('fieldsEditCount'+orderNo).value = fieldNo;
        }
        function deleteFieldNew(dateID, addressID, delID, lineID) {
            document.getElementById(dateID.id).remove();
            document.getElementById(addressID.id).remove();
            document.getElementById(delID.id).remove();
            document.getElementById(lineID.id).remove();
            fieldNoNew--;
        }
        function calcTotalProfit(orderNo) {
            var profit = document.getElementById('profitState'+orderNo);
            var carrierPrice = document.getElementById('carrierPriceState'+orderNo);
            var totalProfit = document.getElementById('totalPriceState'+orderNo);
            totalProfit.value = profit.value - carrierPrice.value;
        }
        function changeFieldNo(count) {
            fieldNo = count;
        }
        function createFieldsExporting(fooID,dateString,addressString,fieldCount, orderNo) {

            document.getElementById('delEdit'+orderNo.toString()+(fieldNo-1)).hidden = false;
            var address = document.createElement("input");

            address.setAttribute("type", 'text');
            address.setAttribute("class", 'form-control');
            address.setAttribute("id", addressString + orderNo.toString() + fieldNo);
            address.setAttribute("name", addressString + fieldNo);
            address.setAttribute("placeholder", 'metai.mėn.d adresas');
            address.required = true;

            var date = document.createElement("input");

            date.setAttribute("type", 'date');
            date.setAttribute("class", 'form-control');
            date.setAttribute("id", dateString + orderNo.toString()+ fieldNo);
            date.setAttribute("name", dateString + fieldNo);
            date.required = true;

            var line = document.createElement("hr");
            line.setAttribute("id", 'lineEdit' + fieldNo);

            var lastIndex = document.getElementById('lastIndex'+orderNo);

            if(document.getElementById('progressState'+orderNo.toString()+(fieldNo-1)).checked === false &&
                document.getElementById('progressState'+orderNo.toString()+(fieldNo-1)).disabled === false) {
                var checkBox = document.createElement('input');
                checkBox.setAttribute('type','checkbox');
                checkBox.setAttribute('id','progressState'+orderNo.toString()+fieldNo);
                checkBox.setAttribute('name','progressState'+fieldNo);
                checkBox.setAttribute('oninput','changeProgress('+orderNo+','+fieldNo+','+(parseInt(lastIndex.value)+1)+')');
                checkBox.disabled = true;
            }
            else if(document.getElementById('progressState'+orderNo.toString()+(fieldNo-1)).checked === false) {
                var checkBox = document.createElement('input');
                checkBox.setAttribute('type','checkbox');
                checkBox.setAttribute('id','progressState'+orderNo.toString()+fieldNo);
                checkBox.setAttribute('name','progressState'+fieldNo);
                checkBox.setAttribute('oninput','changeProgress('+orderNo+','+fieldNo+','+(parseInt(lastIndex.value)+1)+')');
                checkBox.disabled = true;
            } else {
                var checkBox = document.createElement('input');
                checkBox.setAttribute('type','checkbox');
                checkBox.setAttribute('id','progressState'+orderNo.toString()+fieldNo);
                checkBox.setAttribute('name','progressState'+fieldNo);
                checkBox.setAttribute('oninput','changeProgress('+orderNo+','+fieldNo+','+(parseInt(lastIndex.value)+1)+')');
            }

            var label = document.createElement('label');
            label.setAttribute('class','form-check-label');
            label.setAttribute('for','progressState'+orderNo.toString()+fieldNo);
            label.setAttribute('id','progressLabel'+fieldNo);
            label.innerText = 'Pasikrauta';

            var delButton = document.createElement('button');
            delButton.setAttribute('type','button');
            delButton.setAttribute('class','btn btn-danger');
            delButton.setAttribute('id','delEdit'+orderNo.toString()+fieldNo);
            delButton.setAttribute('onclick', 'deleteFieldsExporting('+date.id+','+address.id+','+delButton.id+','+line.id+
                ','+ orderNo + ',' + checkBox.id+','+label.id+');');
            delButton.innerText = 'Trinti';

            var foo = document.getElementById(fooID.id);

            foo.appendChild(date);
            foo.appendChild(address);
            foo.appendChild(delButton);
            foo.appendChild(delButton);
            foo.appendChild(checkBox);
            foo.appendChild(label);
            foo.appendChild(line);
            fieldNo++;
            updateLastIndex(orderNo);
            document.getElementById('fieldsEditCount'+orderNo).value = fieldNo;
            lastIndex.value = fieldNo;
        }
        function updateLastIndex(orderNo) {
            for(let i = 0; i < fieldNo; i++) {
                if(i === 0) {
                    document.getElementById('progressState'+orderNo.toString()).setAttribute('oninput',
                        'changeProgress('+orderNo+','+i+','+fieldNo+')');
                } else {
                    document.getElementById('progressState'+orderNo.toString()+i).setAttribute('oninput',
                        'changeProgress('+orderNo+','+i+','+fieldNo+')');
                }
            }
        }
        function deleteFieldsExporting(dateID, addressID, delID, lineID, orderNo,checkID,labelID) {
            document.getElementById(dateID.id).remove();
            document.getElementById(addressID.id).remove();
            document.getElementById(delID.id).remove();
            console.log(checkID.id);
            var index = checkID.id.replace(/[^\d.]/g, '').replace(orderNo,'');
            if(document.getElementById('progressState'+orderNo.toString()+(index-1)).disabled === false &&
                document.getElementById('progressState'+orderNo.toString()+(index-1)).checked === false)
                document.getElementById('progressState'+orderNo.toString()+(index-1)).disabled = true;
            else if(document.getElementById('progressState'+orderNo.toString()+(index-1)).disabled)
                document.getElementById('progressState'+orderNo.toString()+(index-1)).disabled = false;
            document.getElementById(checkID.id).remove();
            document.getElementById(labelID.id).remove();
            document.getElementById(lineID.id).remove();
            fieldNo--;
            document.getElementById('fieldsEditCount'+orderNo).value = fieldNo;
            updateLastIndex(orderNo);
        }
        function changeProgress(orderNo, index, lastIndex) {
            lastIndex -= 1;
            var progressValue = parseInt(document.getElementById('progressCount').value);
            if(index === 0)
                var progress = document.getElementById('progressState'+orderNo.toString());
            else
                var progress = document.getElementById('progressState'+orderNo.toString()+index.toString());
            if(index === 0) {
                if(progress.checked === true) {
                    document.getElementById('progressState'+orderNo.toString()+(index+1).toString()).disabled = false;
                    document.getElementById('progressCount').value = progressValue + 1;
                }
                else {
                    document.getElementById('progressState'+orderNo.toString()+(index+1).toString()).disabled = true;
                    document.getElementById('progressCount').value = progressValue - 1;
                }
            }
            else if(index === lastIndex) {
                if(progress.checked === true) {
                    document.getElementById('progressState'+orderNo.toString()+(index-1).toString()).disabled = true;
                    document.getElementById('progressCount').value = progressValue + 1;
                }
                else {
                    document.getElementById('progressState'+orderNo.toString()+(index-1).toString()).disabled = false;
                    document.getElementById('progressCount').value = progressValue - 1;
                }
            } else {
                if(progress.checked === true) {
                    if(index - 1 === 0) {
                        document.getElementById('progressState' + orderNo.toString()).disabled = true;
                        document.getElementById('progressState' + orderNo.toString() + (index + 1).toString()).disabled = false;
                    } else {
                        document.getElementById('progressState' + orderNo.toString() + (index - 1).toString()).disabled = true;
                        document.getElementById('progressState' + orderNo.toString() + (index + 1).toString()).disabled = false;
                    }
                    document.getElementById('progressCount').value = progressValue + 1;
                }
                else {
                    if(index - 1 === 0) {
                        document.getElementById('progressState' + orderNo.toString() + (index + 1).toString()).disabled = true;
                        document.getElementById('progressState' + orderNo.toString()).disabled = false;
                    } else {
                        document.getElementById('progressState' + orderNo.toString() + (index + 1).toString()).disabled = true;
                        document.getElementById('progressState' + orderNo.toString() + (index - 1).toString()).disabled = false;
                    }
                    document.getElementById('progressCount').value = progressValue - 1;
                }
            }
        }
    </script>
@endsection
