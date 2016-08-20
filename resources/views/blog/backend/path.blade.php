@extends('sbadminv2.backend')
@section('title', 'Path')
@section('user', $user->name )
@section('content')

<a href="https://partner.path.com/oauth2/authenticate?response_type=code&client_id=2cf8069650a4053293bb474e685a371feebe060b">Auth Path</a>

@endsection