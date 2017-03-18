<!doctype html>
<html>
<head>
    <meta charset="utf-8">
@yield('info')
    <link href="{{asset('homeStyle/css/base.css')}}" rel="stylesheet">
    <link href="{{asset('homeStyle/css/index.css')}}" rel="stylesheet">
    <link href="{{asset('homeStyle/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('homeStyle/css/new.css')}}" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="{{asset('homeStyle/js/modernizr.js')}}"></script>
    <![endif]-->
</head>
<body>
<header>
    <div id="logo"><a href="/"></a></div>
    <nav class="topnav" id="topnav">
        @foreach($navs as $k=>$v)<a href="{{url($v->nav_url)}}"><span>{{$v->nav_name}}</span><span class="en">{{$v->nav_alias}}</span></a>@endforeach
    </nav>
</header>
@section('content')
    <h3>
        <p>最新<span>文章</span></p>
    </h3>
    <ul class="rank">
        @foreach($new as $v)
            <li><a href="{{url('a/'.$v->art_id)}}" title={{$v->art_title}} target="_blank">{{$v->art_title}}</a></li>
        @endforeach
    </ul>
    @show

<footer>
    <p>{!! Config::get('web.copyright') !!} - {!! Config::get('web.web_count') !!}</p>
</footer>
<script src="{{asset('homeStyle/js/silder.js')}}"></script>
</body>
</html>