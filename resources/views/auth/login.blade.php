@extends('sbadminv2.login')
@section('title', 'Page Title')
@section('content')

                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    
                    <div class="panel-body">
                    	@if (count($errors) > 0)
    					<div class="alert alert-danger">
        					<ul>
            					@foreach ($errors->all() as $error)
                				<li>{{ $error }}</li>
            					@endforeach
        					</ul>
    					</div>
						@endif
                        <form role="form" method="POST" action="/auth/login">
                            <fieldset>
                            	{!! csrf_field() !!}
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-success btn-block">Login</button>
                                
                            </fieldset>
                        </form>
                        <br />
                        <a href="/password/email">Forgot password?</a> or <a href="/auth/register">Register</a>
                    </div>
                
@endsection