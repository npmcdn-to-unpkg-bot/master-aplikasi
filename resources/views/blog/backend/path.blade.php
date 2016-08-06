@extends('sbadminv2.backend')
@section('title', 'Path')
@section('user', $user->name )
@section('content')

<a href="https://partner.path.com/oauth2/authenticate?response_type=code&client_id=8157a07833d7b090dfc0b21f29df9fc8623fd13f">Auth Path</a>

@endsection