@extends('layouts.app')
@section('content')
<div id="danger" hidden>
    <div class="alert alert-danger d-flex align-items-center" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
        <div>
            <b>SVARBUS ĮSPĖJIMAS.</b>

            @foreach($data as $d)
                @if(\Carbon\Carbon::now()->diffInDays($d->date) > 3 && $d->state != 'transport' && $d->state != 'exporting')
                    @if($d->state === 'order')
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
                    @elseif($d->state === 'contact')
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
                    @elseif($d->state === 'received')
                        <p>Eksp. {{$d->order_no}}: Ekspedicija neuždaryta daugiau nei 4 dienos.
                            <a href="expeditions/{{$d->order_no}}"><button type="button" class="btn btn-outline-danger">Tvarkyti</button></a></p>
                    @endif
                @elseif($d->state == 'transport' && \Carbon\Carbon::now()->diffInDays(explode('!!',$d->dates)[0]) > 2)
                    <p>Eksp. {{$d->order_no}}: Nepradėtas pirmas pasikrovimas adresu {{explode('!!',$d->addresses)[0]}}, planuota
                        pasikrovimo data {{explode('!!',$d->dates)[0]}}
                        <a href="expeditions/{{$d->order_no}}"><button type="button" class="btn btn-outline-danger">Tvarkyti</button></a>
                    </p>
                @elseif($d->state === 'exporting' && \Carbon\Carbon::now()->diffInDays(explode('!!',$d->dates)[$d->progress-1]) > 1)
                    <p>Eksp. {{$d->order_no}}: Nepradėtas pasikrovimas adresu {{explode('!!',$d->addresses)[$d->progress-1]}},
                        planuota pasikrovimo data {{explode('!!',$d->dates)[$d->progress-1]}}
                        <a href="expeditions/{{$d->order_no}}"><button type="button" class="btn btn-outline-danger">Tvarkyti</button></a>
                    </p>
                @endif
            @endforeach
            @foreach($client as $c)
                @if($c->name == $c->address || $c->postal_code == 0 || $c->phone_no == ($c->id + 1).'. Reikia keisti' ||
                $c->name == $c->email)
                    <p>Pakeiskite kliento "{{$c->name}}" duomenis tikrais.  <a href="clients/{{$c->id}}"><button type="button" class="btn btn-outline-danger">Pildyti</button></a></p>
                @endif
            @endforeach
            @foreach($supplier as $s)
                @if($s->name == $s->address || $s->postal_code == '0' || $s->phone_no == ($s->id + 1).'. Reikia keisti' ||
                $s->names == $s->email)
                    <p>Pakeiskite tiekėjo "{{$s->name}}" duomenis tikrais.  <a href="suppliers/{{$s->id}}"><button type="button" class="btn btn-outline-danger">Pildyti</button></a></p>
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
                @if(\Carbon\Carbon::now()->diffInDays($d->date) > 0 && \Carbon\Carbon::now()->diffInDays($d->date) < 4
                    && $d->state != 'transport' && $d->state != 'exporting')
                    @if($d->state === 'order')
                        <p>Eksp. {{$d->order_no}}: Nekontaktuota su tiekėju
                            @if(\Carbon\Carbon::now()->diffInDays($d->date) == 1) 1 diena.
                            @elseif(\Carbon\Carbon::now()->diffInDays($d->date) > 1 && \Carbon\Carbon::now()->diffInDays($d->date) < 4)
                                {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienos.
                            @endif
                            <a href="expeditions/{{$d->order_no}}"><button type="button" class="btn btn-outline-danger">Tvarkyti</button></a></p></p>
                    @elseif($d->state === 'contact')
                        <p>Eksp. {{$d->order_no}}: Nerastas transportas
                            @if(\Carbon\Carbon::now()->diffInDays($d->date) == 1) 1 diena.
                            @elseif(\Carbon\Carbon::now()->diffInDays($d->date) > 1 && \Carbon\Carbon::now()->diffInDays($d->date) < 4)
                                {{\Carbon\Carbon::now()->diffInDays($d->date)}} dienos.
                            @endif
                            <a href="expeditions/{{$d->order_no}}"><button type="button" class="btn btn-outline-danger">Tvarkyti</button></a></p>
                    @elseif($d->state === 'received')
                        <p>Eksp. {{$d->order_no}}: Ekspedicija neuždaryta daugiau nei 1 diena.
                            <a href="expeditions/{{$d->order_no}}"><button type="button" class="btn btn-outline-danger">Tvarkyti</button></a></p>
                    @endif
                @elseif($d->state == 'transport' && \Carbon\Carbon::now()->diffInDays(explode('!!',$d->dates)[0]) == 1)
                    <p>Eksp. {{$d->order_no}}: Nepradėtas pirmas pasikrovimas adresu {{explode('!!',$d->addresses)[0]}}, planuota
                        pasikrovimo data {{explode('!!',$d->dates)[0]}}
                        <a href="expeditions/{{$d->order_no}}"><button type="button" class="btn btn-outline-danger">Tvarkyti</button></a>
                    </p>
                @elseif($d->state === 'exporting' && $d->progress != count(explode('!!',$d->dates)) && \Carbon\Carbon::now()->diffInDays(explode('!!',$d->dates)[$d->progress]) > 0)
                    <p>Eksp. {{$d->order_no}}: Nepradėtas pasikrovimas adresu {{explode('!!',$d->addresses)[$d->progress]}},
                        planuota pasikrovimo data {{explode('!!',$d->dates)[$d->progress]}}
                        <a href="expeditions/{{$d->order_no}}"><button type="button" class="btn btn-outline-danger">Tvarkyti</button></a>
                    </p>
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
                        <h6 class="card-title">Pelnas:</h6>
                        <p class="card-text green-text"><i class="fas fa-angle-double-up fa-2x"></i><span class="ml-2" style="font-size: 30px;">
                                {{$data->sum('total_profit')}}
                            </span>€</p>
                    </div>
                </div>
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
    document.getElementById('homeBtn').classList.remove('btn-outline-success');
    document.getElementById('homeBtn').classList.add('btn-success');
    @foreach($data as $d)
        @if(\Carbon\Carbon::now()->diffInDays($d->date) > 0 && \Carbon\Carbon::now()->diffInDays($d->date) < 4 &&
        $d->state != 'transport' && $d->state != 'exporting')
            document.getElementById('warning').hidden = false;
        @elseif($d->state == 'transport' && \Carbon\Carbon::now()->diffInDays(explode('!!',$d->dates)[0]) == 1)
            document.getElementById('warning').hidden = false;
        @elseif($d->state === 'exporting' && \Carbon\Carbon::now()->diffInDays(explode('!!',$d->dates)[$d->progress-1]) == 1)
            document.getElementById('warning').hidden = false;
        @endif
        @if(\Carbon\Carbon::now()->diffInDays($d->date) > 3)
            document.getElementById('danger').hidden = false;
        @endif
    @endforeach
        @foreach($client as $c)
            @if($c->name == $c->address || $c->postal_code == 0 || $c->phone_no == ($c->id + 1).'. Reikia keisti' ||
            $c->name == $c->email)
                document.getElementById('danger').hidden = false;
            @endif
        @endforeach
    @foreach($supplier as $s)
        @if($s->name == $s->address || $s->postal_code == '0' || $s->phone_no == ($s->id + 1).'. Reikia keisti' ||
        $s->names == $s->email)
            document.getElementById('danger').hidden = false;
        @endif
    @endforeach
</script>
@endsection
