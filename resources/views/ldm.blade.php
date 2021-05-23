@extends('layouts.app')
@section('content')
@if(session()->has('user') || session()->has('admin'))
<br><br><br><br><br><br>
<div class="container">
    <div class="row justify-content-center">
        <div class="card">
            <div class="card-body">
                <label for="formSelector">Skaičiuoti krovimo metrus</label>
                <select class="custom-select" name="formSelector" id="formSelector" oninput="selectForm()">
                    <option value="0" selected>Skaičiuoti krovimo metrus (pasirinkti)...</option>
                    <option value="1">Suvedant matmenis</option>
                    <option value="2">Palečių kiekiu</option>
                </select>
                <form id="pallets" hidden>
                    <div>
                        <div class="form-group">
                            <label for="truckWidth">Priekabos plotis</label>
                            <input type="text" class="form-control" id="truckWidth" name="truckWidth" placeholder="Įveskite priekabos plotį" oninput="calcDim()">
                        </div>
                        <div class="form-group">
                            <label for="loadFactor">Krovimo faktorius</label>
                            <input type="text" class="form-control" id="loadFactor" name="loadFactor" placeholder="Įveskite krovimo faktorių" oninput="calcDim()">
                        </div>
                        <div class="form-group">
                            <label for="length">Ilgis (m)</label>
                            <input type="text" class="form-control" id="length" name="length" placeholder="Įveskite ilgį" oninput="calcDim()">
                        </div>
                        <div class="form-group">
                            <label for="width">Plotis (m)</label>
                            <input type="text" class="form-control" id="width" name="width" placeholder="Įveskite plotį" oninput="calcDim()">
                        </div>
                        <div class="form-group">
                            <label for="amountD">Kiekis</label>
                            <input type="number" class="form-control" id="amountD" name="amountD" placeholder="Įveskite kiekį" oninput="calcDim()">
                        </div>
                    </div>
                </form>
                <form id="dimensions" hidden>
                    <div>
                        <div class="form-group">
                            <label for="pallet">Paletės tipas</label>
                            <select class="custom-select" name="pallet" id="pallet" oninput="calcPal()">
                                <option value="0">Pasirinkite paletę</option>
                                <option value="1">Euro (EPAL)       (1 EPAL = 0.4)</option>
                                <option value="2">Finnish (FIN)     (1 FIN = 0.5)</option>
                                <option value="3">Industrial (IND)  (1 IND = 0.6)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="truckWidth">Priekabos plotis</label>
                            <input type="text" class="form-control" id="truckWidthP" name="truckWidth" placeholder="Įveskite priekabos plotį" oninput="calcDim()">
                        </div>
                        <div class="form-group">
                            <label for="loadFactor">Krovimo faktorius</label>
                            <input type="text" class="form-control" id="loadFactorP" name="loadFactor" placeholder="Įveskite krovimo faktorių" oninput="calcDim()">
                        </div>
                        <div class="form-group">
                            <label for="amountP">Kiekis</label>
                            <input type="number" class="form-control" id="amountP" name="amountP" placeholder="Įveskite kiekį" oninput="calcPal()">
                        </div>
                    </div>
                    <button type="button" class="btn btn-success" onclick="calcPal()" hidden>Skaičiuoti</button>
                </form>
                <p id="ldm"></p>
                <p id="cubMeter"></p>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('ldmBtn').classList.remove('btn-outline-success');
    document.getElementById('ldmBtn').classList.add('btn-success');
    document.getElementById('formSelector').value = 0;
    // Pallets-----------------------
    document.getElementById('pallet').value = 0;
    document.getElementById('amountP').value = 0;
    document.getElementById('truckWidthP').value = 2.4;
    document.getElementById('loadFactorP').value = 1;
    // -------------------------------
    // Dimensions---------------------
    document.getElementById('length').value = 0;
    document.getElementById('width').value = 0;
    document.getElementById('amountD').value = 0;
    document.getElementById('truckWidth').value = 2.4;
    document.getElementById('loadFactor').value = 1;
    // -------------------------------
    function selectForm() {
        var form = document.getElementById('formSelector');
        var pallets = document.getElementById('pallets');
        var dimensions = document.getElementById('dimensions');
        if(form.value == 0) {
            dimensions.hidden = true;
            pallets.hidden = true;
        }
        else if(form.value == 1) {
            dimensions.hidden = true;
            pallets.hidden = false;
        }
        else if(form.value == 2) {
            pallets.hidden = true;
            dimensions.hidden = false;
        }
    }
    //             Ilgis Plotis Aukštis LDM
    // Euro         120    80     220   0.4
    // Finnish      120    100    220   0.5
    // Industrial   120    120    220   0.6
    function calcPal() {
        var pallets = document.getElementById('pallet');
        var truckWidth = document.getElementById('truckWidthP').value;
        var loadFactor = document.getElementById('loadFactorP').value;
        var ldm = 0;
        var cubMeter = 0;
        var amount = document.getElementById('amountP').value;
        var sup = document.createElement('sup');
        sup.innerText = 3;
        if(pallets.value == 1) {
            ldm = (1.2 * 0.8 / truckWidth * loadFactor * amount);
            ldm = ldm.toFixed(1);
            cubMeter = (1.2 * 0.8 / truckWidth * loadFactor * amount) * 0.175;
            cubMeter = cubMeter.toFixed(3);
            document.getElementById('ldm').innerText = ldm + ' krovimo metrų';
            document.getElementById('cubMeter').innerText = cubMeter + ' m';
            document.getElementById('cubMeter').append(sup);
        }
        else if(pallets.value == 2) {
            ldm = (1.2 / truckWidth * loadFactor * amount);
            ldm = ldm.toFixed(1);
            cubMeter = (1.2 / truckWidth * loadFactor * amount) * 0.175;
            cubMeter = cubMeter.toFixed(3);
            document.getElementById('ldm').innerText = ldm + ' krovimo metrų';
            document.getElementById('cubMeter').innerText = cubMeter + ' m';
            document.getElementById('cubMeter').append(sup);
        }
        else if(pallets.value == 3) {
            ldm = (1.2 * 1.2 / truckWidth * loadFactor * amount);
            ldm = ldm.toFixed(1);
            cubMeter = (1.2 * 1.2 / truckWidth * loadFactor * amount) * 0.175;
            cubMeter = cubMeter.toFixed(3);
            document.getElementById('ldm').innerText = ldm + ' krovimo metrų';
            document.getElementById('cubMeter').innerText = cubMeter + ' m';
            document.getElementById('cubMeter').append(sup);
        }
    }
    //             Ilgis Plotis Aukštis LDM
    // Euro         120    80     220   0.4
    // Finnish      120    100    220   0.5
    // Industrial   120    120    220   0.6
    // Įprastas vidutinis plotis 2,4m
    // Krovinio laikiklio (priekabos) ilgis * krovinio laikiklio plotis / 2,4
    // (e.g. for a Euro pallet: 1.2 m x 0.8 m / 2.4 m = 0.4 ldm)
    // Ilgis * plotis / 2.4 / stacking factor = loading meter
    // (e.g., for a Euro pallet: 1.2 m x 0.8 m / 2.4 m / 2 = 0.2 ldm)
    // Length x width / 2.4 / stacking factor * number of load carriers = loading meter
    // (e.g. for a Euro pallet: 1.2 m x 0.8 m / 2.4 m / 2 * 16 = 3.2 ldm)
    function calcDim() {
        var length = document.getElementById('length').value;
        var width = document.getElementById('width').value;
        var amount = document.getElementById('amountD').value;
        var truckWidth = document.getElementById('truckWidth').value;
        var loadFactor = document.getElementById('loadFactor').value;
        var ldm = 0;
        var cubMeter = 0;
        ldm = length * width / truckWidth * loadFactor * amount;
        ldm = ldm.toFixed(1);
        cubMeter = (length * width / truckWidth * loadFactor * amount) * 0.175;
        cubMeter = cubMeter.toFixed(3);
        var sup = document.createElement('sup');
        sup.innerText = 3;
        document.getElementById('ldm').innerText = ldm + ' krovimo metrų';
        document.getElementById('cubMeter').innerText = cubMeter + ' m';
        document.getElementById('cubMeter').append(sup);
    }
</script>
@else
    <script>window.location = '/'</script>
@endif
@endsection
