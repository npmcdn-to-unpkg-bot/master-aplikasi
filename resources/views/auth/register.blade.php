@extends('sbadminv2.login')
@section('title', 'Page Title')
@section('content')

                    <div class="panel-heading">
                        <h3 class="panel-title">Register</h3>
                    </div>
                    
                    <div class="panel-body">
                    	@if ($success == 1)
    					<div class="alert alert-success">
							<ul>
								Thanks for signing up! Please check your email.          					
							</ul>
						</div>
						@endif
                        @if (count($errors) > 0)
    					<div class="alert alert-danger">
        					<ul>
            					@foreach ($errors->all() as $error)
                				<li>{{ $error }}</li>
            					@endforeach
        					</ul>
    					</div>
						@endif
                        <form role="form"  method="POST" action="/auth/register">
                            <fieldset>
                            	{!! csrf_field() !!}
                                <div class="form-group">
                                    <input class="form-control" placeholder="Name" name="name" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Confirm Password" name="password_confirmation" type="password" value="">
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-success btn-block">Register</button>
                            </fieldset>
                        </form>
                    </div>
                
@endsection