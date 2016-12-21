<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$title or config('whatthetag.site_name', 'WhatTheTag')}}</title>

    {{-- Fonts --}}
    <link href='//fonts.googleapis.com/css?family=Open+Sans&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    
    {{-- WhatTheTag-specific, compiled with sey --}}
    <link rel="stylesheet" href="/css/app.min.css" type="text/css">
    
    <meta name="generator" content="WhatTheTag 0.3.0" />
    
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
                        <li><a href="{{ url('/auth/login') }}">Login</a></li>
                        <li><a href="{{ url('/auth/register') }}">Register</a></li>
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
                                <li><a href="{{ url('/auth/logout') }}">Logout</a></li>
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
                <p class="text-muted">&copy; 2015-{{date('Y')}} WhatTheTag by <a href="https://arda.kilicdagi.com" target="_blank">Arda Kılıçdağı</a>, </p>
            </div>
            <div class="col-md-6 pull-right text-right">
                <p class="text-muted"><a href="https://github.com/ardakilic/whatthetag" target="_blank">Get the source on <i class="fa fa-github fa-2x"></i></a></p>
            </div>
        </div>
    </div>
    
    </div>
</footer>
{{-- WhatTheTag-specific, compiled with sey --}}
<script src="/js/app.min.js"></script>


    <style>
        .algolia-autocomplete {
            width: 100%;
        }
        .algolia-autocomplete .aa-input, .algolia-autocomplete .aa-hint {
            width: 100%;
        }
        .algolia-autocomplete .aa-hint {
            color: #999;
        }
        .algolia-autocomplete .aa-dropdown-menu {
            width: 100%;
            background-color: #fff;
            border: 1px solid #999;
            border-top: none;
        }
        .algolia-autocomplete .aa-dropdown-menu .aa-suggestion {
            cursor: pointer;
            padding: 5px 4px;
        }
        .algolia-autocomplete .aa-dropdown-menu .aa-suggestion.aa-cursor {
            background-color: #B2D7FF;
        }
        .algolia-autocomplete .aa-dropdown-menu .aa-suggestion em {
            font-weight: bold;
            font-style: normal;
        }


        .aa-suggestion {
            font-size: 1.1em;
            padding: 4px 4px 0;
            display: block;
            width: 100%;
            height: 38px;
            clear: both;
        }
        .aa-suggestion span {
            white-space: nowrap !important;
            text-overflow: ellipsis;
            overflow: hidden;
            display: block;
            float: left;
            line-height: 2em;
            width: calc(100% - 30px);
        }
        .aa-suggestion.aa-cursor {
            background: #eee;
        }
        .aa-suggestion em {
            color: #4098CE;
        }
        .aa-suggestion img {
            float: left;
            vertical-align: middle;
            height: 30px;
            width: 20px;
            margin-right: 6px;
        }


    </style>

    <script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
    <script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.jquery.min.js"></script>
    <script>
        $(function(){

            var client = algoliasearch(
                '{{config('algolia.connections.'.config('algolia.connections.default').'.id')}}',
                '{{config('algolia.connections.'.config('algolia.connections.default').'.search_key')}}'
            );
            var index = client.initIndex('whatthetag');
            $('input[name="q"]').autocomplete({ hint: true }, [
                {
                    source: $.fn.autocomplete.sources.hits(index, { hitsPerPage: 5 }),
                    displayKey: 'title',
                    templates: {
                        header: '<h5>Search Results</h5>',
                        suggestion: function(suggestion) {
                            return '<div class="picture"><img src="'+ suggestion._highlightResult.img_src.value +'" /></div><span class="name">' + suggestion._highlightResult.title.value + '<span>';
                        },
                        footer: '<div class="branding">Powered by <img src="https://www.algolia.com/assets/algolia128x40.png" style="height: 14px;"  /></div>'
                    }
                }
            ]).on('autocomplete:selected', function(event, suggestion, dataset) {
                console.log(suggestion, dataset);
            });


        });
    </script>

@yield('footer_assets')
</body>
</html>