<form action="/expeditions/file" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" id="file" name="file">
    <input type="submit">
</form>
