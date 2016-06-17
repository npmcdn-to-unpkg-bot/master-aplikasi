@extends('sbadminv2.backend')
@section('title', 'Path')
@section('user', $user->name )
@section('content')

<a href="https://partner.path.com/oauth2/authenticate?response_type=code&client_id=59422c0c7805f214ea5b4ad0dd7ae98f140f3348">Auth Path</a>

@endsection