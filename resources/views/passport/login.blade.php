@extends('layout.default')


@section('title')登录
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ url('css/login.css') }}">
@stop

@section('content')
    <div class="container">
        @if(\Session::has('message'))
            <div class="alert alert-warning alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>{{ \Session::get('message') }}</strong>
            </div>
        @endif
        <form class="form-signin" method="POST" action="{{ url('login') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <label for="inputAccount">姓名：</label>
            <input type="text" id="inputAccount" name="account" value="{{ old('account') }}" class="form-control"
                   required
                   autofocus>
            <label for="inputNumber">工号：</label>
            <input type="text" id="inputNumber" name="job_num" value="{{ old('job_num') }}" class="form-control"
                   required>
            <button class="btn btn-lg btn-success btn-block" type="submit">登 录</button>
        </form>
    </div>
@stop