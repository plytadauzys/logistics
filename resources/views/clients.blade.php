<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
@if(session('message'))
    <p style="color: #008000">{{session('message')}}</p>
@endif
@if($data->count() == 0)
    <p style="color: red">Nėra jokių klientų</p>
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
                    <form action="/clients/new" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Kliento pavadinimas</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Įveskite kliento pavadinimą">
                        </div>
                        <div class="form-group">
                            <label for="address">Kliento adresas</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Įveskite kliento adresą">
                        </div>
                        <div class="form-group">
                            <label for="postal_code">Kliento pašto kodas</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Įveskite kliento pašto kodą">
                        </div>
                        <div class="form-group">
                            <label for="phone_no">Kliento telefono numeris</label>
                            <input type="text" class="form-control" id="phone_no" name="phone_no" placeholder="Įveskite kliento telefono numerį">
                        </div>
                        <div class="form-group">
                            <label for="email">Kliento el. paštas</label>
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
                    <form action="/clients/new" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Kliento pavadinimas</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Įveskite kliento pavadinimą">
                        </div>
                        <div class="form-group">
                            <label for="address">Kliento adresas</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Įveskite kliento adresą">
                        </div>
                        <div class="form-group">
                            <label for="postal_code">Kliento pašto kodas</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Įveskite kliento pašto kodą">
                        </div>
                        <div class="form-group">
                            <label for="phone_no">Kliento telefono numeris</label>
                            <input type="text" class="form-control" id="phone_no" name="phone_no" placeholder="Įveskite kliento telefono numerį">
                        </div>
                        <div class="form-group">
                            <label for="email">Kliento el. paštas</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Įveskite kliento el. paštą">
                        </div>
                        <button type="submit" class="btn btn-primary">Pridėti</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <input type="text" id="search" onkeyup="search()" placeholder="Search for names.." title="Type in a name">
    <table class="table table-bordered" id="clientTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Pavadinimas</th>
                <th scope="col">Adresas</th>
                <th scope="col">Pašto kodas</th>
                <th scope="col">Telefono nr.</th>
                <th scope="col">El. paštas</th>
                <th scope="col">Veiksmas</th>
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
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#{{$d->id}}">
                        Redaguoti
                    </button>

                    <!-- REDAGUOTI Modal -->
                    <div class="modal fade" id="{{$d->id}}" tabindex="-1" role="dialog" aria-labelledby="{{$d->id}}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Redaguoti klientą</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="/clients/edit" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$d->id}}">
                                        <div class="form-group">
                                            <label for="name">Kliento pavadinimas</label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Įveskite kliento pavadinimą"
                                                   value="{{$d->name}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Kliento adresas</label>
                                            <input type="text" class="form-control" id="address" name="address" placeholder="Įveskite kliento adresą"
                                                   value="{{$d->address}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="postal_code">Kliento pašto kodas</label>
                                            <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Įveskite kliento pašto kodą"
                                                   value="{{$d->postal_code}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="phone_no">Kliento telefono numeris</label>
                                            <input type="text" class="form-control" id="phone_no" name="phone_no" placeholder="Įveskite kliento telefono numerį"
                                                   value="{{$d->phone_no}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Kliento el. paštas</label>
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
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
<p></p>
<script>
    function search() {
        // Declare variables
        var input, filter, table, tr, td, i;
        input = document.getElementById("search");
        filter = input.value.toUpperCase();
        table = document.getElementById("clientTable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
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
