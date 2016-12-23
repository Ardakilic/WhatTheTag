@extends('app')

@section('header_assets')
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/instantsearch.js/1/instantsearch.min.css">
@stop

@section('footer_assets')
    <script src="//cdn.jsdelivr.net/instantsearch.js/1/instantsearch.min.js"></script>
    <script>
        var search = instantsearch({
            appId: '{{config('algolia.connections.'.config('algolia.default').'.id')}}',
            apiKey: '{{config('algolia.connections.'.config('algolia.default').'.search_key')}}',
            indexName: '{{config('algolia.connections.'.config('algolia.default').'.indice_name')}}',
            urlSync: true
        });

        search.addWidget(
            instantsearch.widgets.searchBox({
                container: '#whatthetag-search-box',
                placeholder: 'Search for photos, tags, uploader...'
            })
        );

        search.addWidget(
            instantsearch.widgets.hits({
                container: '#hits-container',
                templates: {
                    item: '<div class="col-md-3">'
                            + '<div class="thumbnail list-thumbnail">'
                                + '<div class="caption list-caption">'
                                + '<h4><a rel="tooltip" data-placement="bottom" title="go to photo" href="/photo/detail/<?php echo '{{'; ?>slug<?php echo '}}'; ?>"><?php echo '{{'; ?>title<?php echo '}}'; ?></a></h4>'
                                + '<p><a href="#" data-link-type="list-modal" data-toggle="modal" data-target="#myModal" data-img-url="<?php echo '{{'; ?>img_src<?php echo '}}'; ?>" data-img-title="<?php echo '{{'; ?>title<?php echo '}}'; ?>" class="label label-danger" rel="tooltip index-modal" title="Zoom">Zoom</a></p>'
                                + '<p>'
                                    + '<h5>Tags:</h5>'
                                    + '<p class="tagsWrapper">'
                                        + '<a class="label label-info" rel="tooltip" href="/photo/tagged/slug" title="Photos tagged with: slug">tagadi</a>'
                                    + '</p>'
                                + '</p>'
                                + '</div>'
                                + '<img src="<?php echo '{{';?>thumb_src<?php echo '}}'; ?>" alt="<?php echo '{{'; ?>title<?php echo '}}'; ?>">'
                            + '</div>'
                        + '</div>'
                }
            })
        );

        search.addWidget(
            instantsearch.widgets.pagination({
                container: '#pagination-container'
            })
        );

        search.start();

        search.on('render', triggerTooltips);
    </script>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Search for a Photo</div>

                    <div class="panel-body">

                        @include('partials.messages')

                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div id="custom-search-input">
                                    <div class="input-group col-md-12">
                                        <input type="text" class="form-control input-lg" placeholder="Search for Photos, Tags, Uploader..." id="whatthetag-search-box" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-info btn-lg" type="button">
                                                <i class="glyphicon glyphicon-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row upper-margin">
                            <div class="col-md-12" id="hits-container"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-12" id="pagination-container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal --}}
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalTitle">Modal title</h4>
                </div>
                <div class="modal-body">
                    <img src="" class="img-responsive">
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-8">
                            <span id="modalTagWrapper" class="pull-left"></span>
                        </div>

                        <div class="col-md-4">
                            <a id="modalDownloadBtn" role="button" href="" class="btn btn-default" target="_blank" download>Download</a>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- /Modal --}}

@endsection