@extends('layouts.app')
@section('content')

<input class="form-control" type="text" id="search" onkeyup="search()" placeholder="Ieškoti ekspedicijų" title="Įveskite norimą tekstą">
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
                            <h6 class="card-subtitle mb-2 text-muted">{{$d->from}} -> {{$d->to}}</h6>
                            <p class="card-text">Some</p>

                            <a class="btn btn-primary" data-toggle="modal" data-target="{{'#exp'.$d->order_no}}">Peržiūrėti</a>
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
                                                    <label for="fromState">Vieta iš</label>
                                                    <input type="text" class="form-control" id="fromState" name="fromState" placeholder="Įveskite krovinio išvykimo vietą"
                                                           value="{{$d->from}}" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="toState">Vieta į</label>
                                                    <input type="text" class="form-control" id="toState" name="toState" placeholder="Įveskite krovinio atvykimo vietą"
                                                           value="{{$d->to}}" readonly>
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
                                                    <label for="fromState">Vieta iš</label>
                                                    <input type="text" class="form-control" id="fromState" name="fromState" placeholder="Įveskite krovinio išvykimo vietą"
                                                    value="{{$d->from}}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="toState">Vieta į</label>
                                                    <input type="text" class="form-control" id="toState" name="toState" placeholder="Įveskite krovinio atvykimo vietą"
                                                    value="{{$d->to}}" required>
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
                                                <button type="submit" class="btn btn-success">Keisti būseną</button>
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
                                <h6 class="card-subtitle mb-2 text-muted">{{$d->from}} -> {{$d->to}}</h6>
                                <p class="card-text">Some</p>

                                <a class="btn btn-primary" data-toggle="modal" data-target="{{'#exp'.$d->order_no}}">Peržiūrėti</a>
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
                                                        <label for="fromState">Vieta iš</label>
                                                        <input type="text" class="form-control" id="fromState" name="fromState" placeholder="Įveskite krovinio išvykimo vietą"
                                                               value="{{$d->from}}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="toState">Vieta į</label>
                                                        <input type="text" class="form-control" id="toState" name="toState" placeholder="Įveskite krovinio atvykimo vietą"
                                                               value="{{$d->to}}" readonly>
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
                                                        <input type="text" class="form-control" id="carrierState" name="carrierState" placeholder="Įveskite krovinio išvykimo vietą"
                                                               required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="carrierPriceState">Vežėjo kaina</label>
                                                        <input type="text" class="form-control" id="carrierPriceState" name="carrierPriceState" placeholder="Įveskite krovinio atvykimo vietą"
                                                               required>
                                                    </div>
                                                    <button type="submit" class="btn btn-success">Keisti būseną</button>
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
                                <h6 class="card-subtitle mb-2 text-muted">{{$d->from}} -> {{$d->to}}</h6>
                                <p class="card-text">Some</p>

                                <a class="btn btn-primary" data-toggle="modal" data-target="{{'#exp'.$d->order_no}}">Peržiūrėti</a>
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
                                                                <label for="fromState">Vieta iš</label>
                                                                <input type="text" class="form-control" id="fromState" name="fromState" placeholder="Įveskite krovinio išvykimo vietą"
                                                                       value="{{$d->from}}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="toState">Vieta į</label>
                                                                <input type="text" class="form-control" id="toState" name="toState" placeholder="Įveskite krovinio atvykimo vietą"
                                                                       value="{{$d->to}}" readonly>
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
                                                        <label for="loadedState">Pasikrovimo data</label>
                                                        <input type="date" class="form-control" id="loadedState" name="loadedState" placeholder="Įveskite datą"
                                                               value="getToday()" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-success">Keisti būseną</button>
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
                                <h6 class="card-subtitle mb-2 text-muted">{{$d->from}} -> {{$d->to}}</h6>
                                <p class="card-text">Some</p>

                                <a class="btn btn-primary" data-toggle="modal" data-target="{{'#exp'.$d->order_no}}">Peržiūrėti</a>
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
                                                                <label for="fromState">Vieta iš</label>
                                                                <input type="text" class="form-control" id="fromState" name="fromState" placeholder="Įveskite krovinio išvykimo vietą"
                                                                       value="{{$d->from}}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="toState">Vieta į</label>
                                                                <input type="text" class="form-control" id="toState" name="toState" placeholder="Įveskite krovinio atvykimo vietą"
                                                                       value="{{$d->to}}" readonly>
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
                                                </form>
                                                <button class="btn btn-primary" data-toggle="modal" data-target="{{'#expstate'.$d->order_no}}">Keisti būseną</button>
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
                                <h6 class="card-subtitle mb-2 text-muted">{{$d->from}} -> {{$d->to}}</h6>
                                <p class="card-text">Some</p>

                                <a class="btn btn-primary" data-toggle="modal" data-target="{{'#exp'.$d->order_no}}">Peržiūrėti</a>
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
                                                                <label for="fromState">Vieta iš</label>
                                                                <input type="text" class="form-control" id="fromState" name="fromState" placeholder="Įveskite krovinio išvykimo vietą"
                                                                       value="{{$d->from}}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="toState">Vieta į</label>
                                                                <input type="text" class="form-control" id="toState" name="toState" placeholder="Įveskite krovinio atvykimo vietą"
                                                                       value="{{$d->to}}" readonly>
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

<button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#naujas">
    Naujas
</button>

<!-- Modal -->
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
                        <label for="fromNew">Vieta iš</label>
                        <input type="text" class="form-control" id="fromNew" name="fromNew" placeholder="Įveskite krovinio išvykimo vietą">
                    </div>
                    <div class="form-group">
                        <label for="toNew">Vieta į</label>
                        <input type="text" class="form-control" id="toNew" name="toNew" placeholder="Įveskite krovinio atvykimo vietą">
                    </div>
                    <div class="form-group">
                        <label for="cargoNew">Krovinys</label>
                        <input type="text" class="form-control" id="cargoNew" name="cargoNew" placeholder="Įveskite krovinį">
                    </div>
                    <div class="form-group">
                        <label for="amountNew">Kiekis</label>
                        <input type="text" class="form-control" id="amountNew" name="amountNew" placeholder="Įveskite krovinio kiekį">
                    </div>
                    <div class="form-group">
                        <label for="profitNew">Pelnas</label>
                        <input type="number" class="form-control" id="profitNew" name="profitNew" placeholder="Įveskite pelną">
                    </div>
                    <button type="submit" class="btn btn-primary">Pridėti</button>
                </form>
            </div>
        </div>
    </div>
</div>
    <script>
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
    </script>
@endsection
