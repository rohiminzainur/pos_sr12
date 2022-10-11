@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@section('content')
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-body text-center">
                    <h1>Selamat Datang</h1>
                    <h2>Anda Login Sebagai Kasir</h2>
                    <br><br>
                    <a href="{{ route('transactions.new') }}" class="btn btn-info btn-lg">Transaction New</a>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row (main row) -->
@endsection
