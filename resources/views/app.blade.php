<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$title or config('whatthetag.site_name', 'WhatTheTag')}}</title>

    {{-- Fonts --}}
    <link href='//fonts.googleapis.com/css?family=Open+Sans&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    
    {{-- WhatTheTag-specific, compiled with gulp --}}
    <link rel="stylesheet" href="/css/app.min.css" type="text/css">
    
    <meta name="generator" content="WhatTheTag 0.4.0" />
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
@yield('header_assets')
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ url('/') }}">{{ config('whatthetag.site_name', 'WhatTheTag') }}</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                
                <ul class="nav navbar-nav">
                    
                    <li><a href="/recents">Recent Photos</a></li>

                    @if(Auth::check())
                        <li><a href="{{ url('/photo/new') }}">New Photo</a></li>
                    @endif

                    <li><a href="/search">Search for a Photo</a></li>

                </ul>
                
                <ul class="nav navbar-nav navbar-right">
                    
                    <li>
                        <form class="navbar-form navbar-left" role="search" action="/search">
                            <div class="input-group">
                                {{-- Double curly parantheses auto escape the provided string, so it's safe to use Request::get('q') below directly --}}
                                <input type="text" class="form-control" placeholder="Search photos, tags" name="q" value="{{ Request::get('q', '') }}">
                                <div class="input-group-btn">
                                    <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </li>
                    
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        
                        @if(Auth::user()->role == 'admin')
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Administration <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ url('/admin/photos') }}">Photo Management</a></li>
                                    <li><a href="{{ url('/admin/users') }}">User Management</a></li>
                                </ul>
                            </li>
                        @endif
                    
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}">Logout</a></li>
                            </ul>
                        </li>
                    @endif
                    
                </ul>
            </div>
        </div>
    </nav>
@yield('content')
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6 pull-left text-left">
                <p class="text-muted">&copy; 2015-{{date('Y')}} WhatTheTag, </p>
            </div>
            <div class="col-md-6 pull-right text-right">
                <p class="text-muted"><a href="https://github.com/ardakilic/whatthetag" target="_blank">Contribute on <i class="fa fa-github fa-2x"></i></a></p>
            </div>
        </div>
    </div>
    
    </div>
</footer>
{{-- Better to call these from CDN instead, because this may change quite rapidly --}}
<script src="//cdn.jsdelivr.net/algoliasearch/3/algoliasearchLite.min.js"></script>
<script src="//cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>
<script>
    var client = algoliasearch(
        '{{config('scout.algolia.id')}}',
        '{{config('scout.algolia.search.search_key')}}'
    );
    var index = client.initIndex('{{config('scout.algolia.search.indice_name')}}');
</script>
{{-- WhatTheTag-specific, compiled with gulp --}}
<script src="/js/app.min.js"></script>

@yield('footer_assets')
</body>
</html>
