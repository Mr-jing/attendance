@extends('layout.default')


@section('title')关于
@stop


@section('content')
    <div class="container">
        <p style="margin-top: 75px" class="panel panel-default panel-body">
            Q：为什么要做这个东西？<br/>
            A：因为OA上填写加班申请单时有严格的格式，我之前填错过，重新再填写并发起流程是一个麻烦的过程。我希望不要再填错了，于是就想写个工具来生成。<br/>
        </p>

        <p class="panel panel-default panel-body">
            Q：计算的规则是什么？<br/>
            A：按照公司弹性工作制，08:30:00~09:30:00是上班时间，对应的下班时间就是：17:30:00~18:30:00。周末的加班时间也是按照这个时间来计算的。<br/>
        </p>

        <p class="panel panel-default panel-body">
            Q：为什么不能记录请假、调休、出差的状态？<br/>
            A：计划下一版加上，下一版可能还会加上“考勤排行榜”、“邮件通知”等功能。当然，我只是有计划而已。<br/>
        </p>

        <p class="panel panel-default panel-body">
            Q：我也想和你一起来维护这个项目，比如我觉得你的界面真是丑爆了，我如何参与其中？<br/>
            A：如果真的有人要参与的话，我就把项目托管的Githuh上面。<br/>
        </p>

        <p class="panel panel-default panel-body">
            如果有其他疑问，请联系我：<br/>
            <strong>熊晶</strong><br/>
            <a href="mailto:king.xiong@transn.com">king.xiong@transn.com</a>
        </p>
    </div>
@stop