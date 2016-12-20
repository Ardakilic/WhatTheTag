<?php

return [

    'site_name' => env('WTT_SITE_NAME', 'WhatTheTag'),

    //For social share plugin.
    //This fills the "shared via @whatthetag" part of the tweet
    'twitter_name' => env('WTT_TWITTER_USERNAME', 'whatthetag'),

    //For Disqus comments
    //If comments_enabled is set to true, no comments is enabled
    'comments_enabled' => env('WTT_COMMENTS_ENABLED', true),
    'disqus_identifier' => env('WTT_DISQUS_IDENTIFIER', 'xxxxxxxxxx'),

    //How many content will be displayed per page
    'pagination_count' => env('WTT_PAGINATION_COUNT', 12),

    'uploads_folder'    => env('UPLOADS_FOLDER', 'uploads'),
    's3_storage_class'    => env('S3_STORAGE_CLASS', 'STANDARD'),
    's3_storage_cdn_domain'    => env('S3_STORAGE_CDN_DOMAIN', 'https://hodor.s3.amazonaws.com/')

];