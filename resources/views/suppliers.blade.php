@extends('layouts.app')
@section('title','Tiekėjai')
@section('content')
@if(session()->has('user') || session()->has('admin'))
@if(session('message'))
    <div class="alert alert-success" role="alert" style="width: 100%">
        <p>{{session('message')}}</p>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger" role="alert" style="width: 100%">
        <p>{{session('error')}}</p>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li style="color:red">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if($data->count() == 0)
    <div class="alert alert-info" role="alert">
        Nėra jokių tiekėjų.
    </div>
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

    <div class="row m-0">
        <input class="form-control col-sm-8" type="text" id="search" onkeyup="search()" placeholder="Ieškoti tiekėjų" title="Įveskite norimą tekstą">
        <div class="col-sm-1">
            <button type="button" class="btn btn-danger" onclick="document.getElementById('search').value = ''; search();">Valyti paieškos laukelį</button>
        </div>
    </div>
    <p id="allNull" hidden>Nėra tokių įrašų</p>
    <table class="table table-bordered sortable" id="clientTable">
        <thead class="thead-dark">
        <tr>
            <th>Pavadinimas</th>
            <th>Adresas</th>
            <th>Pašto kodas</th>
            <th>Telefono nr.</th>
            <th>El. paštas</th>
            <th>Veiksmas</th>
            <th>Vykstančios ekspedicijos</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $d)
            <tr>
                <th scope="row">{{$d->name}}</th>
                <td>{{$d->address}}</td>
                <td>{{$d->postal_code}}</td>
                <td>{{$d->phone_no}}</td>
                <td>{{$d->email}}</td>
                <td>
                    <!-- Button trigger REDAGUOTI modal -->
                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#{{$d->id}}">
                        Redaguoti
                    </button>

                    <!-- REDAGUOTI Modal -->
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
                    <!-- Button trigger ŠALINTI modal -->
                    <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="{{'#confirm'.$d->id}}">Šalinti</button>
                    <!-- ŠALINTI Modal -->
                    <div class="modal fade" id="{{'confirm'.$d->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Ar tikrai norite šalinti {{$d->name}}?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-footer d-flex justify-content-center" style="vertical-align: middle">
                                    <a href="{{'suppliers/remove/'.$d->id}}"><button type="button" class="btn btn-danger" value="{{$d->id}}">Taip</button></a>
                                    <button type="button" class="btn btn-success" data-dismiss="modal">Ne</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                @if($d->ekspedicija->count() > 0)
                    <td>
                        @foreach($d->ekspedicija as $de)
                            @if($loop->last)
                                Eksp. {{$de->order_no}}
                            @else
                                Eksp. {{$de->order_no}},
                            @endif
                        @endforeach
                    </td>
                @else
                    <td>Nėra</td>
                @endif
            </tr>
    @endforeach
        </tbody>
    </table>
@endif
<script>
    document.getElementById('supplierBtn').classList.remove('btn-outline-success');
    document.getElementById('supplierBtn').classList.add('btn-success');
    @if(session()->has('supplier'))
        $('#'+{{session()->get('supplier')[0]}}).modal('show');
        @php(session()->pull('supplier'))
    @endif
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
        for(i = 1; i < tr.length; i++) {
            if(tr[i].style.display == 'none') {
                allNull = true;
            }
            else {
                allNull = false;
                break
            }
        }
        if(allNull)
            document.getElementById('allNull').hidden = false;
        else
            document.getElementById('allNull').hidden = true;
    }
</script>
@else
    <script>window.location = '/'</script>
@endif
@endsection
