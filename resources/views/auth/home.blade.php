@extends('sbadminv2.backend')
@section('title', 'Dashboard')
@section('user', $user->name )
@section('content')

<form method="post" action="takephoto.php" enctype="multipart/form-data">
<input type="file" accept="image/*;capture=camera" name="file">
<input type="submit">
</form>

@endsection