@extends('layouts.app')
@section('content')

    @if(session('message'))
        <p style="color: #008000">{{session('message')}}</p>
    @endif
    @if(session('error'))
        <p style="color: red">{{session('error')}}</p>
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
    @if(session()->has('admin'))

        <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#naujas">
            Naujas
        </button>

        <!-- NAUJAS Modal -->
        <div class="modal fade" id="naujas" tabindex="-1" role="dialog" aria-labelledby="naujas" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Pridėti naują klientą</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/adminHome/new" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="id">Vartotojo ID</label>
                                <input type="number" class="form-control" id="id" name="id" placeholder="Įveskite vartotojo ID">
                            </div>
                            <div class="form-group">
                                <label for="first_name">Vartotojo vardas</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Įveskite vartotojo vardą">
                            </div>
                            <div class="form-group">
                                <label for="last_name">Vartotojo pavardė</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Įveskite vartotojo pavardę">
                            </div>
                            <div class="form-group">
                                <label for="email">Vartotojo el. paštas</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Įveskite vartotojo el. paštą">
                            </div>
                            <div class="form-group">
                                <label for="password">vartotojo slaptažodis</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Įveskite vartotojo slaptažodį">
                            </div>
                            <button type="submit" class="btn btn-primary">Pridėti</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <input class="form-control" type="text" id="search" onkeyup="search()" placeholder="Ieškoti klientų" title="Įveskite norimą tekstą">
    <table class="table table-bordered" id="clientTable">
        <thead class="thead-dark">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Vardas</th>
            <th scope="col">Pavardė</th>
            <th scope="col">El. paštas</th>
            <th scope="col">Slaptažodis</th>
            <th scope="col">Veiksmas</th>
        </tr>
        </thead>
        <tbody>
        @foreach($managers as $m)
            <tr>
                <th>{{$m->id}}</th>
                <td>{{$m->first_name}}</td>
                <td>{{$m->last_name}}</td>
                <td>{{$m->email}}</td>
                <td>{{$m->password}}</td>
                <td>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#{{$m->id}}">
                        Redaguoti
                    </button>

                    <!-- REDAGUOTI Modal -->
                    <div class="modal fade" id="{{$m->id}}" tabindex="-1" role="dialog" aria-labelledby="{{$m->id}}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Redaguoti klientą</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="/admin/edit" method="POST">
                                        @csrf
                                        <input type="hidden" name="idH" value="{{$m->id}}">
                                        <div class="form-group">
                                            <label for="id">ID</label>
                                            <input type="number" class="form-control" id="id" name="id" placeholder="Įveskite identifikacinį numerį (ID)"
                                                   value="{{$m->id}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="firstName">Vardas</label>
                                            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Įveskite vartotojo vardą"
                                                   value="{{$m->first_name}}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="lastName">Pavardė</label>
                                            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Įveskite vartotojo pavardę"
                                                   value="{{$m->last_name}}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">El. paštas</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Įveskite vartotojo el. paštą"
                                                   value="{{$m->email}}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Slaptažodis</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Įveskite vartotojo slaptažodį"
                                                   value="{{$m->password}}" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Redaguoti</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="{{'#confirm'.$m->id}}">Šalinti</button>
                    <div class="modal fade" id="{{'confirm'.$m->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Ar tikrai norite šalinti {{$m->first_name}} {{$m->last_name}}?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-footer d-flex justify-content-center" style="vertical-align: middle">
                                    <a href="{{'admin/remove/'.$m->id}}"><button type="button" class="btn btn-danger" value="{{$m->id}}">Taip</button></a>
                                    <button type="button" class="btn btn-success" data-dismiss="modal">Ne</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <p></p>
    @endif
    <script>
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
