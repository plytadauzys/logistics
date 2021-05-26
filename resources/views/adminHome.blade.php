@extends('layouts.app')
@section('title','Namai')
@section('content')
    @if(session()->has('admin'))
    @if(session('message'))
        <div class="alert alert-success" role="alert" style="width: 100%">
            <p style="color: #008000">{{session('message')}}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" role="alert" style="width: 100%">
            <p style="color: red">{{session('error')}}</p>
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
                                <label for="role">Vartotojo rolė</label>
                                <select class="custom-select" name="role" id="role">
                                    <option value="manager">Vadybininkas</option>
                                    <option value="admin">Administratorius</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id">Vartotojo ID (nebūtina)</label>
                                <input type="number" class="form-control" id="id" name="id" placeholder="Įveskite vartotojo ID">
                            </div>
                            <div class="form-group">
                                <label for="first_name">Vartotojo vardas</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Įveskite vartotojo vardą" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Vartotojo pavardė</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Įveskite vartotojo pavardę" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Vartotojo el. paštas</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Įveskite vartotojo el. paštą" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Vartotojo slaptažodis</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Įveskite vartotojo slaptažodį" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Pridėti</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <input class="form-control" type="text" id="search" onkeyup="search()" placeholder="Ieškoti vartotojų" title="Įveskite norimą tekstą">
    <p id="allNull" hidden>Nėra tokių įrašų</p>
    <table class="table table-bordered" id="clientTable">
        <thead class="thead-dark">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Vardas</th>
            <th scope="col">Pavardė</th>
            <th scope="col">El. paštas</th>
            <th scope="col">Slaptažodis</th>
            <th scope="col">Veiksmas</th>
            <th scope="col">Rolė</th>
        </tr>
        </thead>
        <tbody>@foreach($data as $a)
            <tr>
                <th>{{$a->id}}</th>
                <td>{{$a->first_name}}</td>
                <td>{{$a->last_name}}</td>
                <td>{{$a->email}}</td>
                <td>{{$a->password}}</td>
                <td>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#a{{$a->id}}">
                        Redaguoti
                    </button>

                    <!-- REDAGUOTI Modal -->
                    <div class="modal fade" id="a{{$a->id}}" tabindex="-1" role="dialog" aria-labelledby="a{{$a->id}}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Redaguoti klientą</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="/admin/editAdmin" method="POST">
                                        @csrf
                                        <input type="hidden" name="idH" value="{{$a->id}}">
                                        <div class="form-group">
                                            <label for="id">ID (nebūtina)</label>
                                            <input type="number" class="form-control" id="id" name="id" placeholder="Įveskite identifikacinį numerį (ID)"
                                                   value="{{$a->id}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="firstName">Vardas</label>
                                            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Įveskite vartotojo vardą"
                                                   value="{{$a->first_name}}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="lastName">Pavardė</label>
                                            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Įveskite vartotojo pavardę"
                                                   value="{{$a->last_name}}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">El. paštas</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Įveskite vartotojo el. paštą"
                                                   value="{{$a->email}}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Slaptažodis</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Įveskite vartotojo slaptažodį"
                                                   value="{{$a->password}}" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Redaguoti</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="{{'#confirma'.$a->id}}">Šalinti</button>
                    <div class="modal fade" id="{{'confirma'.$a->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Ar tikrai norite šalinti {{$a->first_name}} {{$a->last_name}}?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-footer d-flex justify-content-center" style="vertical-align: middle">
                                    <a href="{{'admin/removeAdmin/'.$a->id}}"><button type="button" class="btn btn-danger" value="{{$a->id}}">Taip</button></a>
                                    <button type="button" class="btn btn-success" data-dismiss="modal">Ne</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td>Administratorius</td>
            </tr>
        @endforeach
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
                                    <form action="/admin/editUser" method="POST">
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
                <td>Vadybininkas</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <p></p>

    <script>
        document.getElementById('homeBtn').classList.remove('btn-outline-success');
        document.getElementById('homeBtn').classList.add('btn-success');
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
