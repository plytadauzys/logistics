@extends('layouts.app')
@section('content')

    @if(session('message'))
        <p style="color: #008000">{{session('message')}}</p>
    @endif

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
                <th scope="row">{{$m->id}}</th>
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
                                        <input type="hidden" name="id" value="{{$m->id}}">
                                        <div class="form-group">
                                            <label for="id">ID</label>
                                            <input type="text" class="form-control" id="id" name="id" placeholder="Įveskite identifikacinį numerį (ID)"
                                                   value="{{$m->id}}" required>
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
                                            <input type="text" class="form-control" id="email" name="email" placeholder="Įveskite vartotojo el. paštą"
                                                   value="{{$m->phone_no}}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Slaptažodis</label>
                                            <input type="email" class="form-control" id="password" name="password" placeholder="Įveskite vartotojo slaptažodį"
                                                   value="{{$m->email}}" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Redaguoti</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-danger">Šalinti</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <p></p>
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
