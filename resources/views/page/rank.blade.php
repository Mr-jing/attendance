@extends('layout.default')

@section('title')记录
@stop

@section('style')
    <style>
        .rankings ul li {
            margin-top: 15px;
        }

        .list_unstyled {
            padding-left: 0;
            list-style: none;
        }

        .name {
            font-weight: 700;
            line-height: 1.2;
        }

        .time {
            float: right;
            margin-right: 20px;
        }
    </style>
@stop

@section('content')
    <div class="container" style="margin-top: 50px;">
        <div class="row">
            <div class="rankings col-md-3">
                <h2>昨日</h2>
                @include('page.rankings', array('rankings' => $dayRankings, 'names' => $names))
            </div>
            <div class="rankings col-md-3">
                <h2>上周</h2>
                @include('page.rankings', array('rankings' => $weekRankings, 'names' => $names))
            </div>
            <div class="rankings col-md-3">
                <h2>上月</h2>
                @include('page.rankings', array('rankings' => $monthRankings, 'names' => $names))
            </div>
            <div class="rankings col-md-3">
                <h2>今年</h2>
                @include('page.rankings', array('rankings' => $yearRankings, 'names' => $names))
            </div>
        </div>
    </div>
@stop

