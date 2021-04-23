@extends('layouts.app')
@section('content')
<select class="custom-select" name="formSelector" id="formSelector" oninput="selectForm()">
    <option value="0" selected>Skaičiuoti krovimo metrus (pasirinkti)...</option>
    <option value="1">Suvedant matmenis</option>
    <option value="2">Palečių kiekiu</option>
</select>
<br><br><br><br><br><br>
<form id="pallets" hidden>
    <div>
        <div class="form-group">
            <label for="length">Ilgis (m)</label>
            <input type="text" class="form-control" id="length" name="length" placeholder="Įveskite ilgį" oninput="calcDim()">
        </div>
        <div class="form-group">
            <label for="width">Plotis (m)</label>
            <input type="text" class="form-control" id="width" name="width" placeholder="Įveskite plotį" oninput="calcDim()">
        </div>
        <div class="form-group">
            <label for="amount">Kiekis</label>
            <input type="number" class="form-control" id="amount" name="amount" placeholder="Įveskite kiekį">
        </div>
    </div>
    <button type="button" class="btn btn-success" onclick="calcDim()">Skaičiuoti</button>
</form>
<form id="dimensions" hidden>
    <div>
        <div class="form-group">
            <label for="pallet">Paletės tipas</label>
            <select class="custom-select" name="pallet" id="pallet">
                <option value="0">Pasirinkite paletę</option>
                <option value="1">Euro (EPAL)</option>
                <option value="2">Finnish (FIN)</option>
                <option value="3">Industrial (IND)</option>
            </select>
        </div>
        <div class="form-group">
            <label for="amount">Kiekis</label>
            <input type="number" class="form-control" id="amount" name="amount" placeholder="Įveskite kiekį">
        </div>
    </div>
    <button type="button" class="btn btn-success" onclick="calcPal()">Skaičiuoti</button>
</form>
<p id="answer">ad</p>
<script>
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
        var answer = document.getElementById('answer');
        if(pallets.value == 1) {
            answer.innerText = 0.4;
        }
        else if(pallets.value == 2) {
            answer.innerText = 0.5;
        }
        else if(pallets.value == 3) {
            answer.innerText = 0.6;
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
        var answer = document.getElementById('answer');
        answer.innerText = length * width / 2.4;
    }
</script>
@endsection
