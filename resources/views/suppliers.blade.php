@extends('layouts.app')
@section('content')
@if(session('message'))
    <p style="color: #008000">{{session('message')}}</p>
@endif
@if($data->count() == 0)
    <p style="color: red">Nėra jokių klientų</p>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#naujas">
        Naujas
    </button>

    <!-- Modal -->
    <div class="modal fade" id="naujas" tabindex="-1" role="dialog" aria-labelledby="naujas" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Pridėti naują tiekėją</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/suppliers/new" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Tiekėjo pavadinimas</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Įveskite kliento pavadinimą">
                        </div>
                        <div class="form-group">
                            <label for="address">Tiekėjo adresas</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Įveskite kliento adresą">
                        </div>
                        <div class="form-group">
                            <label for="postal_code">Tiekėjo pašto kodas</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Įveskite kliento pašto kodą">
                        </div>
                        <div class="form-group">
                            <label for="phone_no">Tiekėjo telefono numeris</label>
                            <input type="text" class="form-control" id="phone_no" name="phone_no" placeholder="Įveskite kliento telefono numerį">
                        </div>
                        <div class="form-group">
                            <label for="email">Tiekėjo el. paštas</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Įveskite kliento el. paštą">
                        </div>
                        <button type="submit" class="btn btn-primary">Pridėti</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@else

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#naujas">
        Naujas
    </button>

    <!-- Modal -->
    <div class="modal fade" id="naujas" tabindex="-1" role="dialog" aria-labelledby="naujas" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Pridėti naują tiekėją</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/suppliers/new" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Tiekėjo pavadinimas</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Įveskite kliento pavadinimą">
                        </div>
                        <div class="form-group">
                            <label for="address">Tiekėjo adresas</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Įveskite kliento adresą">
                        </div>
                        <div class="form-group">
                            <label for="postal_code">Tiekėjo pašto kodas</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Įveskite kliento pašto kodą">
                        </div>
                        <div class="form-group">
                            <label for="phone_no">Tiekėjo telefono numeris</label>
                            <input type="text" class="form-control" id="phone_no" name="phone_no" placeholder="Įveskite kliento telefono numerį">
                        </div>
                        <div class="form-group">
                            <label for="email">Tiekėjo el. paštas</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Įveskite kliento el. paštą">
                        </div>
                        <button type="submit" class="btn btn-primary">Pridėti</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach($data as $d)
        <p>{{ $d }}</p>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#{{$d->id}}">
            Redaguoti
        </button>

        <!-- Modal -->
        <div class="modal fade" id="{{$d->id}}" tabindex="-1" role="dialog" aria-labelledby="{{$d->id}}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Redaguoti Tiekėją</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/suppliers/edit" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{$d->id}}">
                            <div class="form-group">
                                <label for="name">Tiekėjo pavadinimas</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Įveskite kliento pavadinimą"
                                       value="{{$d->name}}">
                            </div>
                            <div class="form-group">
                                <label for="address">Tiekėjo adresas</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="Įveskite kliento adresą"
                                       value="{{$d->address}}">
                            </div>
                            <div class="form-group">
                                <label for="postal_code">Tiekėjo pašto kodas</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Įveskite kliento pašto kodą"
                                       value="{{$d->postal_code}}">
                            </div>
                            <div class="form-group">
                                <label for="phone_no">Tiekėjo telefono numeris</label>
                                <input type="text" class="form-control" id="phone_no" name="phone_no" placeholder="Įveskite kliento telefono numerį"
                                       value="{{$d->phone_no}}">
                            </div>
                            <div class="form-group">
                                <label for="email">Tiekėjo el. paštas</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Įveskite kliento el. paštą"
                                       value="{{$d->email}}">
                            </div>
                            <button type="submit" class="btn btn-primary">Redaguoti</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-outline-danger">Šalinti</button>
    @endforeach

@endif
@endsection
