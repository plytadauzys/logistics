<head>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
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
                                            <p>Keitimas</p>
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
                                            <p>Keitimas</p>
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
                <td></td><td></td> <td>{{$d->order_no}} {{$d->state}}</td> <td></td><td></td>
            </tr>
        @elseif($d->state == 'exporting')
            <tr>
                <td></td><td></td><td></td> <td>{{$d->order_no}} {{$d->state}}</td> <td></td>
            </tr>
        @elseif($d->state == 'received')
            <tr>
                <td></td><td></td><td></td><td></td> <td>{{$d->order_no}} {{$d->state}}</td>
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
