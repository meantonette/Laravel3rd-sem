<form action="{{ url('handle-form') }}"
 method="POST"
 enctype="multipart/form-data">

 {{ csrf_field() }}

 <input type="file" name="book" />
<input type="submit">
</form>
