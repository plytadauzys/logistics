@extends('layouts.app')
@section('content')
    @if($data->count() == 0)
        <p>nėra įvykusių ekspedicijų</p>
    @else
        <input class="form-control" type="text" id="search" onkeyup="search()" placeholder="Ieškoti ekspedicijų" title="Įveskite norimą tekstą">
        <table class="table table-bordered" id="clientTable">
            <thead class="thead-dark">
            <tr>
                <th>Klientas</th>
                <th>Tiekėjas</th>
                <th>Maršrutas</th>
                <th>Datos</th>
                <th>Adresai</th>
                <th>Krovinys</th>
                <th>Kiekis</th>
                <th>Pelnas</th>
                <th>Pirmas pasikrovimas</th>
                <th>Galutinis iškrovimas</th>
                <th>Vežėjas</th>
                <th>Vežėjo kaina</th>
                <th>Visas pelnas</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $d)
                <tr>
                    <td>{{$d->client}}</td>
                    <td>{{$d->supplier}}</td>
                    <td>{{$d->route}}</td>
                    <td>{{implode(', ', explode('!!',$d->dates))}}</td>
                    <td>{{implode(', ', explode('!!', $d->addresses))}}</td>
                    <td>{{$d->cargo}}</td>
                    <td>{{$d->amount}}</td>
                    <td>{{$d->profit}}</td>
                    <td>{{$d->loaded}}</td>
                    <td>{{$d->unloaded}}</td>
                    <td>{{$d->carrier}}</td>
                    <td>{{$d->carrier_price}}</td>
                    <td>{{$d->total_profit}}</td>
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
        }
        //function sort() {
        const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

        const comparer = (idx, asc) => (a, b) => ((v1, v2) =>
                v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
        )(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

        // do the work...
        document.querySelectorAll('th').forEach(th => th.addEventListener('click', (() => {
            const table = th.closest('table');
            Array.from(table.querySelectorAll('tr:nth-child(n+2)'))
                .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
                .forEach(tr => table.appendChild(tr) );
        })));
        //}
    </script>
@endsection
