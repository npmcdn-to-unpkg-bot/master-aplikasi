@extends('sbadminv2.backend')
@section('title', 'Path')
@section('user', $user->name )
@section('content')

<a href="https://partner.path.com/oauth2/authenticate?response_type=code&client_id=a10a8805d3c1a6da3801a8b7f149960eb9597c66">Auth Path</a>

@endsection