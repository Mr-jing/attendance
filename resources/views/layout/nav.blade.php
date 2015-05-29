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
                <li class="{{ (\Request::is('record*') ? ' active ' : '') }}"><a href="{{ url('/') }}">首页</a></li>
                <li class="{{ (\Request::is('about') ? ' active ' : '') }}"><a href="{{ url('/about') }}">关于</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if(!\Request::hasCookie('job_num'))
                    <li class="{{ (\Request::is('login') ? ' active ' : '') }}"><a href="{{ url('/login') }}">登录</a>
                    </li>
                @else
                    <form class="navbar-form navbar-left" method="POST" action="{{ url('/logout') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-default" value="退出"/>
                    </form>
                @endif
            </ul>
        </div>
    </div>
</nav>