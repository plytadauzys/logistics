
@extends('layouts.app')
@section('content')

@if(session('error'))
    <div class="alert alert-danger">
        <p>{{session('error')}}</p>
    </div>
@endif
<div class="container" id="myGroup">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Prisijungimas</h5>
                    <p>
                        <a class="btn btn-outline-primary" id="managerBtn" data-toggle="collapse" href="#vadyb" role="button" aria-expanded="false" aria-controls="collapseExample"onclick="
                                document.getElementById('managerBtn').classList.remove('btn-outline-primary');
                                document.getElementById('managerBtn').classList.add('btn-primary');
                                document.getElementById('adminBtn').classList.remove('btn-primary');
                                document.getElementById('adminBtn').classList.add('btn-outline-primary');">
                            Vadybininkams
                        </a>
                        <button class="btn btn-outline-primary" id="adminBtn" type="button" data-toggle="collapse" data-target="#admin" aria-expanded="false" aria-controls="collapseExample2"onclick="
                                document.getElementById('managerBtn').classList.remove('btn-primary');
                                document.getElementById('managerBtn').classList.add('btn-outline-primary');
                                document.getElementById('adminBtn').classList.remove('btn-outline-primary');
                                document.getElementById('adminBtn').classList.add('btn-primary');">
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
            </div>
        </div>
</div>
@endsection
