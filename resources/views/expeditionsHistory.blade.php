@extends('layouts.app')
@section('title','Ekspedicijų istorija')
@section('content')
@if(session()->has('user') || session()->has('admin'))
    @if($data->count() == 0)
        <div class="alert alert-info" role="alert">
            Nėra įvykusių ekspedicijų.
        </div>
    @else
        <div class="row m-0">
            <input class="form-control col-sm-8" type="text" id="search" onkeyup="search()" placeholder="Ieškoti ekspedicijų" title="Įveskite norimą tekstą">
            <div class="col-sm-4">
                <button type="button" class="btn btn-danger" onclick="document.getElementById('search').value = ''; search();">Valyti paieškos laukelį</button>
                <input type="checkbox" id="check" name="progressState">
                <label class="form-check-label" for="check">Leisti teksto pridėjimą paspaudus</label>
            </div>
        </div>
        <p id="allNull" hidden>Nėra tokių įrašų</p>
        <table class="table table-bordered sortable" id="clientTable">
            <thead class="thead-dark">
            <tr>
                <th onclick="addTextToSearch('Klientas')">Klientas</th>
                <th onclick="addTextToSearch('Tiekėjas')">Tiekėjas</th>
                <th onclick="addTextToSearch('Maršrutas')">Maršrutas</th>
                <th onclick="addTextToSearch('Datos')">Datos</th>
                <th onclick="addTextToSearch('Adresai')">Adresai</th>
                <th onclick="addTextToSearch('Krovinys')">Krovinys</th>
                <th onclick="addTextToSearch('Kiekis')">Kiekis</th>
                <th onclick="addTextToSearch('Pelnas')">Pelnas</th>
                <th onclick="addTextToSearch('Pirmas pasikrovimas')">Pirmas pasikrovimas</th>
                <th onclick="addTextToSearch('Pristatyta')">Pristatyta</th>
                <th onclick="addTextToSearch('Vežėjas')">Vežėjas</th>
                <th onclick="addTextToSearch('Vežėjo kaina')">Vežėjo kaina</th>
                <th onclick="addTextToSearch('Visas pelnas')">Visas pelnas</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $d)
                <tr>
                    <td onclick="addTextToSearch('{{$d->client}}')">
                        {{$d->client}}
                        <p hidden>Klientas {{$d->client}}</p>
                    </td>
                    <td onclick="addTextToSearch('{{$d->supplier}}')">
                        {{$d->supplier}}
                        <p hidden>Tiekėjas {{$d->supplier}}</p>
                    </td>
                    <td onclick="addTextToSearch('{{$d->route}}')">
                        {{$d->route}}
                        <p hidden>Maršrutas {{$d->route}}</p>
                    </td>
                    <td id="{{'alldates'.$d->order_no}}" onclick="addTextToSearchDates('alldates',{{$d->order_no}})">
                        {{implode(', ', explode('!!',$d->dates))}}
                        <p hidden>Datos {{implode(', ', explode('!!',$d->dates))}}</p>
                    </td>
                    <td id="{{'alladdresses'.$d->order_no}}" onclick="addTextToSearchDates('alladdresses',{{$d->order_no}})">
                        {{implode(', ', explode('!!', $d->addresses))}}
                        <p hidden>Adresai {{implode(', ', explode('!!', $d->addresses))}}</p>
                    </td>
                    <td onclick="addTextToSearch('{{$d->cargo}}')">
                        {{$d->cargo}} <p hidden>Krovinys {{$d->cargo}}</p>
                    </td>
                    <td onclick="addTextToSearch('{{$d->amount}}')">
                        {{$d->amount}} <p hidden>Kiekis {{$d->amount}}</p>
                    </td>
                    <td onclick="addTextToSearch('{{$d->profit}}')">
                        {{$d->profit}} <p hidden>Pelnas {{$d->profit}}</p>
                    </td>
                    <td id="{{'date'.$d->order_no}}" onclick="addTextToSearchDates('date',{{$d->order_no}})">
                        {{explode('!!',$d->dates)[0]}} <p hidden>Pirmas pasikrovimas {{explode('!!',$d->dates)[0]}}</p>
                    </td>
                    <td id="{{'dateLast'.$d->order_no}}" onclick="addTextToSearchDates('dateLast',{{$d->order_no}})">
                        {{explode('!!',$d->dates)[array_key_last(explode('!!',$d->dates))]}}
                        <p hidden> Pristatyta {{explode('!!',$d->dates)[array_key_last(explode('!!',$d->dates))]}}</p>
                    </td>
                    <td onclick="addTextToSearch('{{$d->carrier}}')">
                        {{$d->carrier}} <p hidden>Vežėjas {{$d->carrier}}</p>
                    </td>
                    <td onclick="addTextToSearch('{{$d->carrier_price}}')">
                        {{$d->carrier_price}} <p hidden>Vežėjo kaina {{$d->carrier_price}}</p>
                    </td>
                    <td onclick="addTextToSearch('{{$d->total_profit}}')">
                        {{$d->total_profit}} <p hidden>Visas pelnas {{$d->total_profit}}</p>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
    <script>
        document.getElementById('expHistBtn').classList.remove('btn-outline-success');
        document.getElementById('expHistBtn').classList.add('btn-success');
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
        function addTextToSearchDates(field,orderNo) {
            var textString = document.getElementById(field+orderNo.toString()).innerText;
            //textString = textString.toString();
            if(document.getElementById('check').checked) {
                console.log(textString);
                if(document.getElementById('search').value == '')
                    document.getElementById('search').value = document.getElementById('search').value + textString.toString();
                else
                    document.getElementById('search').value = document.getElementById('search').value + ' ' + textString.toString();
                search();
            }
        }
        function addTextToSearch(textString) {
            textString = textString.toString();
            if(document.getElementById('check').checked) {
                if(document.getElementById('search').value == '')
                    document.getElementById('search').value = document.getElementById('search').value + textString.toString();
                else
                    document.getElementById('search').value = document.getElementById('search').value + ' ' + textString.toString();
                search();
            }
        }
    </script>
@else
    <script>window.location = '/'</script>
@endif
@endsection
