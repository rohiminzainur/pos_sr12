@extends('layouts.auth')
@section('content')
    <div class="container login-box-body cob"
        style="margin-top: 5%; border-top-right-radius: 50px; border-bottom-left-radius: 50px; line-height: 50px;">
        <div class="row align-items-center row-login" style="margin-top: 2%;">
            <div class="col-lg-6 text-center">
                <div class="login-logo">
                    <a href="{{ url('/') }}"><img src="{{ url($setting->path_logo) }}" alt="Logo" width="200"></a>
                </div>

            </div>
            <div class="col-lg-5">
                <h2>
                    <b>Login POS SR12</b>
                    <hr>
                </h2>
                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <div class="form-group has-feedback @error('email') has-error @enderror">
                        <input type="email" name="email" class="form-control" placeholder="Email"
                            value="{{ old('email') }}" required autofocus>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        @error('email')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group has-feedback @error('password') has-error @enderror">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        @error('password')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="checkbox icheck">
                                <label>
                                    <input type="checkbox"> Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- <div class="login-box">
        <!-- /.login-logo -->
        <div class="login-box-body">
            <div class="login-logo">
                <a href="{{ url('/') }}"><img src="{{ asset('images/login-placeholder.png') }}" alt="Logo"
                        style="width: 100px;"></a>
            </div>

            <form action="{{ route('login') }}" method="post">
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" placeholder="Email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox"> Remember Me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

        </div>
        <!-- /.login-box-body -->
    </div> --}}
@endsection
