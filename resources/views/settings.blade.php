@extends('layouts.app')
@section('title','Nustatymai')
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
    <p></p>
    <div class="row m-0">
        <div class="card col-sm-6" style="background-color: #f8d7da; border-color: #f5c6cb;">
            <h5 class="card-title">Svarbūs įspėjimai</h5>
            <form action="settings/edit" method="POST">
                @csrf
                <input name="type" value="danger" hidden>
                @foreach($data->where('type','danger') as $d)
                    <div class="form-group">
                        <label for="exampleInputPassword1">{{$d->description}}</label>
                        <input type="number" class="form-control" id="{{'value'.$d->id}}" name="{{'value'.$d->id}}"
                               placeholder="Įveskite dienų skaičių" value="{{$d->value}}" required style="background-color: #fdf6f7;">
                    </div>
                @endforeach
                <button type="submit" class="btn btn-outline-dark">Keisti</button>
            </form>
        </div>
        <div class="card col-sm-6" style="background-color: #fff3cd; border-color: #ffeeba;">
            <h5 class="card-title">Mažiau svarbūs įspėjimai</h5>
            <form action="settings/edit" method="POST">
                @csrf
                <input name="type" value="warning" hidden>
                @foreach($data->where('type','warning') as $d)
                    <div class="form-group">
                        <label for="exampleInputPassword1">{{$d->description}}</label>
                        <input type="number" class="form-control" id="{{'value'.$d->id}}" name="{{'value'.$d->id}}"
                               placeholder="Įveskite dienų skaičių" value="{{$d->value}}" required style="background-color: #fffdf5;">
                    </div>
                @endforeach
                <button type="submit" class="btn btn-outline-dark">Keisti</button>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('warnSettings').classList.remove('btn-outline-success');
        document.getElementById('warnSettings').classList.add('btn-success');
    </script>
@else
    <script>window.location = '/'</script>
@endif
@endsection
