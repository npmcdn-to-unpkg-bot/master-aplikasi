@extends('sbadminv2.login')
@section('title', 'Page Title')
@section('content')

                    <div class="panel-heading">
                        <h3 class="panel-title">Forgot Password</h3>
                    </div>
                    
                    <div class="panel-body">
                    	<form role="form" method="POST" action="/auth/password/email">
                            <fieldset>
                            	<input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>" />
                                <div class="form-group">
                                    <input class="form-control" id="email" placeholder="E-mail" name="email" type="email" value="{{ old('email') }}" autofocus>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-success btn-block">Send Password Reset Link</button>
                            </fieldset>
                        </form>
                    </div>
                
@endsection