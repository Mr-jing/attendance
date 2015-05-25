<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <title>登录</title>
    <link rel="stylesheet" type="text/css" href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?= url(); ?>/css/login.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand">考勤 Helper</a>
        </div>

        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="<?= '/record' ?>">首页</a></li>
                <li><a href="<?= '/about' ?>">关于</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if (empty($_COOKIE['job_num'])) : ?>
                <li class="active"><a href="<?= '/login' ?>">登录</a></li>
                <?php else: ?>
                <form class="navbar-form navbar-left" method="POST" action="<?= './logout' ?>">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="submit" class="btn btn-default" value="退出"/>
                </form>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    @if(Session::has('message'))
        <div class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>{{Session::get('message')}}</strong>
        </div>
    @endif
    <form class="form-signin" method="POST" action="./login">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <label for="inputAccount">姓名：</label>
        <input type="text" id="inputAccount" name="account" value="{{old('account')}}" class="form-control" required
               autofocus>
        <label for="inputNumber">工号：</label>
        <input type="text" id="inputNumber" name="job_num" value="{{old('job_num')}}" class="form-control" required>
        <button class="btn btn-lg btn-success btn-block" type="submit">登 录</button>
    </form>
</div>
<script type="text/javascript" src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript" src="http://cdn.bootcss.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</body>
</html>