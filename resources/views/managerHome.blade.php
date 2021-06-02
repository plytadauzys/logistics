@extends('layouts.app')
@section('title','Namai')
@section('content')
@if(session()->has('user'))
<div id="danger" hidden>
    <div class="alert alert-danger d-flex align-items-center" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
        <div>
            <b>SVARBUS ĮSPĖJIMAS.</b>

            @foreach($data as $d)
                @if($d->state === 'order' && \Carbon\Carbon::now()->diffInDays($d->date) >= $settings[0]->value && \Carbon\Carbon::now()->diffInDays($d->date) && $settings[0]->value != 0)
                    <p>Eksp. {{$d->order_no}}: Nekontaktuota su tiekėju
                        @if(\Carbon\Carbon::now()->diffInDays($d->date) > 3 && \Carbon\Carbon::now()->diffInDays($d->date) < 10)
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienos.
                        @elseif(\Carbon\Carbon::now()->diffInDays($d->date) > 9 && \Carbon\Carbon::now()->diffInDays($d->date) < 21)
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienų.
                        @elseif(str_ends_with(strval(\Carbon\Carbon::now()->diffInDays($d->date)),'1'))
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} diena.
                        @elseif(str_ends_with(strval(\Carbon\Carbon::now()->diffInDays($d->date)),'0'))
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienų.
                        @else
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienos.
                        @endif
                        <a href="expeditions/{{$d->order_no}}"><button type="button" class="btn btn-outline-danger">Tvarkyti</button></a></p>
                    <script>
                        var markDanger = document.getElementById('markDanger');
                        var orderNo = {{$d->order_no}};
                        if(markDanger.value == null)
                            markDanger.value = orderNo+',';
                        else
                            markDanger.value = markDanger.value +''+ orderNo+',';
                    </script>
                @elseif($d->state === 'contact' && \Carbon\Carbon::now()->diffInDays($d->date) >= $settings[1]->value && \Carbon\Carbon::now()->toDateString() > $d->date && \Carbon\Carbon::now()->diffInDays($d->date) > 0 && $settings[1]->value != 0)
                    <p>Eksp. {{$d->order_no}}: Nerastas transportas
                        @if(\Carbon\Carbon::now()->diffInDays($d->date) > 3 && \Carbon\Carbon::now()->diffInDays($d->date) < 10)
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienos.
                        @elseif(\Carbon\Carbon::now()->diffInDays($d->date) > 9 && \Carbon\Carbon::now()->diffInDays($d->date) < 21)
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienų.
                        @elseif(str_ends_with(strval(\Carbon\Carbon::now()->diffInDays($d->date)),'1'))
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} diena.
                        @elseif(str_ends_with(strval(\Carbon\Carbon::now()->diffInDays($d->date)),'0'))
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienų.
                        @else
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienos.
                        @endif
                        <a href="expeditions/{{$d->order_no}}"><button type="button" class="btn btn-outline-danger">Tvarkyti</button></a></p>
                    <script>
                        var markDanger = document.getElementById('markDanger');
                        var orderNo = {{$d->order_no}};
                        if(markDanger.value == null)
                            markDanger.value = orderNo+',';
                        else
                            markDanger.value = markDanger.value +''+ orderNo+',';
                    </script>
                @elseif($d->state === 'received' && \Carbon\Carbon::now()->diffInDays($d->date) > $settings[2]->value && \Carbon\Carbon::now()->diffInDays($d->date) && $settings[2]->value != 0)
                    <p>Eksp. {{$d->order_no}}: Ekspedicija neuždaryta
                        @if(\Carbon\Carbon::now()->diffInDays($d->date) > 3 && \Carbon\Carbon::now()->diffInDays($d->date) < 10)
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienos.
                        @elseif(\Carbon\Carbon::now()->diffInDays($d->date) > 9 && \Carbon\Carbon::now()->diffInDays($d->date) < 21)
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienų.
                        @elseif(str_ends_with(strval(\Carbon\Carbon::now()->diffInDays($d->date)),'1'))
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} diena.
                        @elseif(str_ends_with(strval(\Carbon\Carbon::now()->diffInDays($d->date)),'0'))
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienų.
                        @else
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienos.
                        @endif
                        <a href="expeditions/{{$d->order_no}}"><button type="button" class="btn btn-outline-danger">Tvarkyti</button></a></p>
                    <script>
                        var markDanger = document.getElementById('markDanger');
                        var orderNo = {{$d->order_no}};
                        if(markDanger.value == null)
                            markDanger.value = orderNo+',';
                        else
                            markDanger.value = markDanger.value +''+ orderNo+',';
                    </script>
                @elseif($d->state == 'transport' && \Carbon\Carbon::now()->diffInDays(explode('!!',$d->dates)[0]) >= $settings[3]->value && \Carbon\Carbon::now()->toDateString() > explode('!!',$d->dates)[0] && $settings[3]->value != 0)
                    <p>Eksp. {{$d->order_no}}: Nepradėtas pirmas pasikrovimas adresu {{explode('!!',$d->addresses)[0]}}, planuota
                        pasikrovimo data {{explode('!!',$d->dates)[0]}}
                        <a href="expeditions/{{$d->order_no}}"><button type="button" class="btn btn-outline-danger">Tvarkyti</button></a>
                    </p>
                    <script>
                        var markDanger = document.getElementById('markDanger');
                        var orderNo = {{$d->order_no}};
                        if(markDanger.value == null)
                            markDanger.value = orderNo+',';
                        else
                            markDanger.value = markDanger.value +''+ orderNo+',';
                    </script>
                @elseif($d->state === 'exporting' && $d->progress != count(explode('!!',$d->dates)) && \Carbon\Carbon::now()->diffInDays(explode('!!',$d->dates)[$d->progress]) >= $settings[4]->value &&
                        \Carbon\Carbon::now()->toDateString() > explode('!!',$d->dates)[$d->progress] && $settings[4]->value != 0)
                    <p>Eksp. {{$d->order_no}}: Nepradėtas pasikrovimas adresu {{explode('!!',$d->addresses)[$d->progress]}},
                        planuota pasikrovimo data {{explode('!!',$d->dates)[$d->progress]}}
                        <a href="expeditions/{{$d->order_no}}"><button type="button" class="btn btn-outline-danger">Tvarkyti</button></a>
                    </p>
                    <script>
                        var markDanger = document.getElementById('markDanger');
                        var orderNo = {{$d->order_no}};
                        if(markDanger.value == null)
                            markDanger.value = orderNo+',';
                        else
                            markDanger.value = markDanger.value +''+ orderNo+',';
                    </script>
                @endif
            @endforeach
            @foreach($client as $c)
                @if($c->name == $c->address || $c->postal_code === '0' || $c->phone_no == ($c->id).'. Reikia keisti' ||
                $c->name == $c->email)
                    <p>Pakeiskite kliento "{{$c->name}}" duomenis tikrais.  <a href="clients/{{$c->id}}"><button type="button" class="btn btn-outline-danger">Tvarkyti</button></a></p>
                    <script>
                        var markClient = document.getElementById('markClient');
                        var id = {{$c->id}};
                        if(markClient.value == null)
                            markClient.value = id+',';
                        else
                            markClient.value = markClient.value +''+ id+',';
                    </script>
                @endif
            @endforeach
            @foreach($supplier as $s)
                @if($s->name == $s->address || $s->postal_code === '0' || $s->phone_no == ($s->id).'. Reikia keisti' ||
                $s->names == $s->email)
                    <p>Pakeiskite tiekėjo "{{$s->name}}" duomenis tikrais.  <a href="suppliers/{{$s->id}}"><button type="button" class="btn btn-outline-danger">Tvarkyti</button></a></p>
                    <script>
                        var markSupplier = document.getElementById('markSupplier');
                        var id = {{$s->id}};
                        if(markSupplier.value == null)
                            markSupplier.value = id+',';
                        else
                            markSupplier.value = markSupplier.value +''+ id+',';
                    </script>
                @endif
            @endforeach
        </div>
    </div>
</div>
<div id="warning" hidden>
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
        <div>
            <b>ĮSPĖJIMAS.</b>

            @foreach($data as $d)
                @if($d->state === 'order' && \Carbon\Carbon::now()->diffInDays($d->date) >= $settings[5]->value && \Carbon\Carbon::now()->diffInDays($d->date) < $settings[0]->value  &&
                    \Carbon\Carbon::now()->toDateString() > $d->date && \Carbon\Carbon::now()->diffInDays($d->date) > 0 && $settings[5]->value != 0)
                    <p>Eksp. {{$d->order_no}}: Nekontaktuota su tiekėju
                        @if(\Carbon\Carbon::now()->diffInDays($d->date) > 3 && \Carbon\Carbon::now()->diffInDays($d->date) < 10)
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienos.
                        @elseif(\Carbon\Carbon::now()->diffInDays($d->date) > 9 && \Carbon\Carbon::now()->diffInDays($d->date) < 21)
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienų.
                        @elseif(str_ends_with(strval(\Carbon\Carbon::now()->diffInDays($d->date)),'1'))
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} diena.
                        @elseif(str_ends_with(strval(\Carbon\Carbon::now()->diffInDays($d->date)),'0'))
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienų.
                        @else
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienos.
                        @endif
                        <a href="expeditions/{{$d->order_no}}"><button type="button" class="btn btn-outline-danger">Tvarkyti</button></a></p></p>
                    <script>
                        var markWarning = document.getElementById('markWarning');
                        var orderNo = {{$d->order_no}};
                        if(markWarning.value == null)
                            markWarning.value = orderNo+',';
                        else
                            markWarning.value = markWarning.value +''+ orderNo+',';
                    </script>
                @elseif($d->state === 'contact' && \Carbon\Carbon::now()->diffInDays($d->date) >= $settings[6]->value && \Carbon\Carbon::now()->diffInDays($d->date) < $settings[1]->value &&
                        \Carbon\Carbon::now()->toDateString() > $d->date && \Carbon\Carbon::now()->diffInDays($d->date) > 0 && $settings[6]->value != 0)
                    <p>Eksp. {{$d->order_no}}: Nerastas transportas
                        @if(\Carbon\Carbon::now()->diffInDays($d->date) > 3 && \Carbon\Carbon::now()->diffInDays($d->date) < 10)
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienos.
                        @elseif(\Carbon\Carbon::now()->diffInDays($d->date) > 9 && \Carbon\Carbon::now()->diffInDays($d->date) < 21)
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienų.
                        @elseif(str_ends_with(strval(\Carbon\Carbon::now()->diffInDays($d->date)),'1'))
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} diena.
                        @elseif(str_ends_with(strval(\Carbon\Carbon::now()->diffInDays($d->date)),'0'))
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienų.
                        @else
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienos.
                        @endif
                        <a href="expeditions/{{$d->order_no}}"><button type="button" class="btn btn-outline-danger">Tvarkyti</button></a></p>
                    <script>
                        var markWarning = document.getElementById('markWarning');
                        var orderNo = {{$d->order_no}};
                        if(markWarning.value == null)
                            markWarning.value = orderNo+',';
                        else
                            markWarning.value = markWarning.value +''+ orderNo+',';
                    </script>
                @elseif($d->state === 'received' && \Carbon\Carbon::now()->diffInDays($d->date) >= $settings[7]->value && \Carbon\Carbon::now()->diffInDays($d->date) < $settings[2]->value &&
                        \Carbon\Carbon::now()->toDateString() > $d->date && \Carbon\Carbon::now()->diffInDays($d->date) > 0 && $settings[7]->value != 0)
                    <p>Eksp. {{$d->order_no}}: Ekspedicija neuždaryta
                        @if(\Carbon\Carbon::now()->diffInDays($d->date) > 3 && \Carbon\Carbon::now()->diffInDays($d->date) < 10)
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienos.
                        @elseif(\Carbon\Carbon::now()->diffInDays($d->date) > 9 && \Carbon\Carbon::now()->diffInDays($d->date) < 21)
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienų.
                        @elseif(str_ends_with(strval(\Carbon\Carbon::now()->diffInDays($d->date)),'1'))
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} diena.
                        @elseif(str_ends_with(strval(\Carbon\Carbon::now()->diffInDays($d->date)),'0'))
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienų.
                        @else
                            {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienos.
                        @endif
                        <a href="expeditions/{{$d->order_no}}"><button type="button" class="btn btn-outline-danger">Tvarkyti</button></a></p>
                    <script>
                        var markWarning = document.getElementById('markWarning');
                        var orderNo = {{$d->order_no}};
                        if(markWarning.value == null)
                            markWarning.value = orderNo+',';
                        else
                            markWarning.value = markWarning.value +''+ orderNo+',';
                    </script>
                @elseif($d->state == 'transport' && \Carbon\Carbon::now()->diffInDays(explode('!!',$d->dates)[0]) >= $settings[8]->value && \Carbon\Carbon::now()->diffInDays(explode('!!',$d->dates)[0]) < $settings[3]->value && \Carbon\Carbon::now()->toDateString() > explode('!!',$d->dates)[0] && $settings[8]->value != 0)
                    <p>Eksp. {{$d->order_no}}: Nepradėtas pirmas pasikrovimas adresu {{explode('!!',$d->addresses)[0]}}, planuota
                        pasikrovimo data {{explode('!!',$d->dates)[0]}}
                        <a href="expeditions/{{$d->order_no}}"><button type="button" class="btn btn-outline-danger">Tvarkyti</button></a>
                    </p>
                    <script>
                        var markWarning = document.getElementById('markWarning');
                        var orderNo = {{$d->order_no}};
                        if(markWarning.value == null)
                            markWarning.value = orderNo+',';
                        else
                            markWarning.value = markWarning.value +''+ orderNo+',';
                    </script>
                @elseif($d->state === 'exporting' && $d->progress != count(explode('!!',$d->dates)) && \Carbon\Carbon::now()->diffInDays(explode('!!',$d->dates)[$d->progress]) >= $settings[9]->value &&
                        \Carbon\Carbon::now()->toDateString() > explode('!!',$d->dates)[$d->progress] && \Carbon\Carbon::now()->diffInDays(explode('!!',$d->dates)[$d->progress]) < $settings[4]->value && $settings[9]->value != 0)
                    <p>Eksp. {{$d->order_no}}: Nepradėtas pasikrovimas adresu {{explode('!!',$d->addresses)[$d->progress]}},
                        planuota pasikrovimo data {{explode('!!',$d->dates)[$d->progress]}}
                        <a href="expeditions/{{$d->order_no}}"><button type="button" class="btn btn-outline-danger">Tvarkyti</button></a>
                    </p>
                    <script>
                        var markWarning = document.getElementById('markWarning');
                        var orderNo = {{$d->order_no}};
                        if(markWarning.value == null)
                            markWarning.value = orderNo+',';
                        else
                            markWarning.value = markWarning.value +''+ orderNo+',';
                    </script>
                @endif
            @endforeach
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h4 class="mt-5 font-weight-bold text-center"></h4>
        </div>
    </div>
    <div class="row mt-3 pt-3" style="background-color: #eeeeee">
        <div class="col-md-6">
            <div class="card-group">
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="card-title">Vykstančių ekspedicijų skaičius:</h6>
                        <p class="card-text blue-text"><i class="fas fa-thumbs-up fa-2x"></i><span class="ml-2" style="font-size: 30px;">
                                {{count($data)}}
                            </span></p>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="card-title">Įvykusių ekspedicijų skaičius: </h6>
                        <p class="card-text red-text"><i class="fas fa-thumbs-down fa-2x"></i></i><span class="ml-2" style="font-size: 30px;">
                                {{count($expHist)}}</span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-group">
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="card-title">Klientų skaičius: </h6>
                        <p class="card-text red-text"><i class="fas fa-angle-double-down fa-2x"></i><span class="ml-2" style="font-size: 30px;">
                                {{count($client)}}
                            </span></p>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="card-title">Tiekėjų skaičius: </h6>
                        <p class="card-text red-text"><i class="fas fa-angle-double-down fa-2x"></i><span class="ml-2" style="font-size: 30px;">
                                {{count($supplier)}}
                            </span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    //document.getElementById('warning').hidden = false;
    document.getElementById('homeBtn').classList.remove('btn-outline-success');
    document.getElementById('homeBtn').classList.add('btn-success');
    @foreach($data as $d)
        @if($d->state === 'order' && \Carbon\Carbon::now()->diffInDays($d->date) >= $settings[0]->value && \Carbon\Carbon::now()->toDateString() > $d->date && \Carbon\Carbon::now()->diffInDays($d->date) > 0 && $settings[0]->value != 0)
            document.getElementById('danger').hidden = false;
        @elseif($d->state === 'order' && \Carbon\Carbon::now()->diffInDays($d->date) >= $settings[5]->value &&
        \Carbon\Carbon::now()->diffInDays($d->date) < $settings[0]->value && \Carbon\Carbon::now()->toDateString() > $d->date && \Carbon\Carbon::now()->diffInDays($d->date) > 0 && $settings[5]->value != 0)
            document.getElementById('warning').hidden = false;
       //------------------------------------------------------------------------------------------------------------------------------------------------
        @elseif($d->state === 'contact' && \Carbon\Carbon::now()->diffInDays($d->date) >= $settings[1]->value && \Carbon\Carbon::now()->toDateString() > $d->date && \Carbon\Carbon::now()->diffInDays($d->date) > 0 && $settings[1]->value != 0)
            document.getElementById('danger').hidden = false;
        @elseif($d->state === 'contact' && \Carbon\Carbon::now()->diffInDays($d->date) >= $settings[6]->value &&
        \Carbon\Carbon::now()->diffInDays($d->date) < $settings[1]->value && \Carbon\Carbon::now()->toDateString() > $d->date && \Carbon\Carbon::now()->diffInDays($d->date) > 0 && $settings[6]->value != 0)
            document.getElementById('warning').hidden = false;
        //------------------------------------------------------------------------------------------------------------------------------------------------
        @elseif($d->state === 'transport' && \Carbon\Carbon::now()->diffInDays(explode('!!',$d->dates)[0]) >= $settings[3]->value && \Carbon\Carbon::now()->toDateString() > explode('!!',$d->dates)[0] && $settings[3]->value != 0)
            document.getElementById('danger').hidden = false;
        @elseif($d->state === 'transport' && \Carbon\Carbon::now()->diffInDays(explode('!!',$d->dates)[0]) >= $settings[8]->value && \Carbon\Carbon::now()->toDateString() > explode('!!',$d->dates)[0] && \Carbon\Carbon::now()->diffInDays(explode('!!',$d->dates)[0]) < $settings[3]->value && $settings[8]->value != 0)
            document.getElementById('warning').hidden = false;
        //------------------------------------------------------------------------------------------------------------------------------------------------
        @elseif($d->state === 'exporting' && $d->progress != count(explode('!!',$d->dates)) && \Carbon\Carbon::now()->diffInDays(explode('!!',$d->dates)[$d->progress]) >= $settings[4]->value && \Carbon\Carbon::now()->toDateString() > explode('!!',$d->dates)[$d->progress] && $settings[4]->value != 0)
            document.getElementById('danger').hidden = false;
        @elseif($d->state === 'exporting' && $d->progress != count(explode('!!',$d->dates)) && \Carbon\Carbon::now()->diffInDays(explode('!!',$d->dates)[$d->progress]) >= $settings[9]->value && \Carbon\Carbon::now()->toDateString() > explode('!!',$d->dates)[$d->progress] && \Carbon\Carbon::now()->diffInDays(explode('!!',$d->dates)[$d->progress]) < $settings[4]->value && $settings[9]->value != 0)
            document.getElementById('warning').hidden = false;
        //------------------------------------------------------------------------------------------------------------------------------------------------
    @elseif($d->state === 'received' && \Carbon\Carbon::now()->diffInDays($d->date) >= $settings[2]->value && \Carbon\Carbon::now()->toDateString() > $d->date && \Carbon\Carbon::now()->diffInDays($d->date) > 0 && $settings[2]->value != 0)
            document.getElementById('danger').hidden = false;
        @elseif($d->state === 'received' && \Carbon\Carbon::now()->diffInDays($d->date) >= $settings[7]->value &&
            \Carbon\Carbon::now()->diffInDays($d->date) < $settings[2]->value && \Carbon\Carbon::now()->toDateString() > $d->dates && \Carbon\Carbon::now()->diffInDays($d->date) > 0 && $settings[7]->value != 0)
            document.getElementById('warning').hidden = false;
        @endif
    @endforeach

    @foreach($client as $c)
        @if($c->name == $c->address || $c->postal_code === '0' || $c->phone_no == ($c->id).'. Reikia keisti' ||
        $c->name == $c->email)
            document.getElementById('danger').hidden = false;
        @endif
    @endforeach
    @foreach($supplier as $s)
        @if($s->name == $s->address || $s->postal_code == '0' || $s->phone_no == ($s->id).'. Reikia keisti' ||
        $s->names == $s->email)
            document.getElementById('danger').hidden = false;
        @endif
    @endforeach
    /*console.log(document.getElementById('markDanger').value);
    console.log(document.getElementById('markWarning').value);
    console.log(document.getElementById('markClient').value);
    console.log(document.getElementById('markSupplier').value);*/
</script>
@else
    <script>window.location = '/'</script>
@endif
@endsection
