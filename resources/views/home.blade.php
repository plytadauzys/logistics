
@extends('layouts.app')
@section('content')
@if(session('error'))
    <p>{{session('error')}}</p>
@endif
<div class="container" id="myGroup">
    <p>
        <a class="btn btn-primary" data-toggle="collapse" href="#vadyb" role="button" aria-expanded="false" aria-controls="collapseExample">
            Vadybininkams
        </a>
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#admin" aria-expanded="false" aria-controls="collapseExample2">
            Administratoriams
        </button>
    </p>

    <div class="collapse" id="vadyb" data-parent="#myGroup">
        <div class="card card-body">
            <form action="/loginManager" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">El. paštas</label>
                    <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp"
                           placeholder="Įveskite el. paštą" required>
                </div>
                <div class="form-group">
                    <label for="password">Slaptažodis</label>
                    <input type="password" name="password" class="form-control" id="password"
                           placeholder="Slaptažodis" required>
                </div>
                <button type="submit" class="btn btn-info">Prisijungti</button>
            </form>
        </div>
    </div>
    <div class="collapse" id="admin" data-parent="#myGroup">
        <div class="card card-body">
            <form action="/loginAdmin" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">El. paštas</label>
                    <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp"
                           placeholder="Įveskite el. paštą" required>
                </div>
                <div class="form-group">
                    <label for="password">Slaptažodis</label>
                    <input type="password" name="password" class="form-control" id="password"
                           placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-info">Prisijungti</button>
            </form>
        </div>
    </div>
</div>
@endsection
