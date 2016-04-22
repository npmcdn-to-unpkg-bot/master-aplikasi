@extends('sbadminv2.login')
@section('title', 'Page Title')
@section('content')

                    <div class="panel-heading">
                        <h3 class="panel-title">Forgot Password</h3>
                    </div>
                    
                    <div class="panel-body">
                    @if (session('status'))
      				<div class="alert alert-success">
             			{{ session('status') }}
      				</div>
    				@endif
                    @if (count($errors) > 0)
       				<div class="alert alert-danger">
       					<strong>Whoops!</strong> There were some problems with your input.<br><br>
       						<ul>
           						@foreach ($errors->all() as $error)
                    			<li>{{ $error }}</li>
           						@endforeach
       					   </ul>
        			</div>
      				@endif
                    	<form role="form" method="POST" action="/password/email">
                            <fieldset>
                            	{!! csrf_field() !!}
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="email" value="{{ old('email') }}" autofocus>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-success btn-block">Send Password Reset Link</button>
                            </fieldset>
                        </form>
                    </div>
                
@endsection