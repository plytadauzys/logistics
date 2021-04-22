<head>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<select class="custom-select" name="formSelector" id="formSelector" oninput="selectForm()">
    <option value="0" selected>Skaičiuoti krovimo metrus (pasirinkti)...</option>
    <option value="1">Suvedant matmenis</option>
    <option value="2">Palečių kiekiu</option>
</select>
<br><br><br><br><br><br>
<form id="pallets" hidden>
    <div>
        <div class="form-group">
            <label for="length">Ilgis</label>
            <input type="text" class="form-control" id="length" name="length" placeholder="Įveskite ilgį">
        </div>
        <div class="form-group">
            <label for="width">Plotis</label>
            <input type="text" class="form-control" id="width" name="width" placeholder="Įveskite plotį">
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
    //             Ilgis Plotis Aukštis LDM
    // Euro         120    80     220   0.4
    // Finnish      120    100    220   0.5
    // Industrial   120    120    220   0.6
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
    function calcDim() {

    }
</script>
