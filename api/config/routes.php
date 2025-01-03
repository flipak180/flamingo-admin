<?php
return [
    'GET events/list' => 'events/list',
    'GET events/details' => 'events/details',

    'POST,OPTIONS send-sms' => 'system/send-sms',

    'GET stories/list' => 'stories/list',

    'POST,OPTIONS achievements/list' => 'achievements/list',
    'POST,OPTIONS achievements/list-by-categories' => 'achievements/list-by-categories',
    'POST,OPTIONS achievements/categories' => 'achievements/categories',
    'POST,OPTIONS achievements/add-progress' => 'achievements/add-progress',

    'POST,OPTIONS push-tokens/register' => 'push-tokens/register',
    'POST,OPTIONS push-tokens/detach' => 'push-tokens/detach',

    'GET articles/list' => 'articles/list',
    'GET articles/details' => 'articles/details',

    'GET quests/list' => 'quests/list',
    'GET quests/details' => 'quests/details',
    'GET quests/place' => 'quests/place',
    'POST,OPTIONS quests/start' => 'quests/start',

    'POST,OPTIONS places/list' => 'places/list',
    'POST,OPTIONS places/search' => 'places/search',
    'POST,OPTIONS places/visit' => 'places/visit',
    'POST,OPTIONS places/details' => 'places/details',
    'GET places/details' => 'places/details',
    'POST,OPTIONS places/rate' => 'places/rate',
    'POST,OPTIONS places/test' => 'places/test',

    'GET compilations/get-actual-compilation' => 'compilations/get-actual-compilation',
    'GET compilations/details' => 'compilations/details',

    'GET search' => 'flamingo/search',
    'GET docs' => 'flamingo/docs',

    'POST,OPTIONS tickets/create' => 'tickets/create',

    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'category',
        'extraPatterns' => [
            'GET list' => 'list',
            'GET details' => 'details',
            'GET get-homepage-category' => 'get-homepage-category',
            'GET get-popular-categories' => 'get-popular-categories',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'user',
        'extraPatterns' => [
            'POST,OPTIONS auth' => 'auth',
            'POST,OPTIONS get-profile' => 'get-profile',
            'POST,OPTIONS update-profile' => 'update-profile',
            'POST,OPTIONS get-rated-places' => 'get-rated-places',
            'POST,OPTIONS get-places' => 'get-places',
            'POST,OPTIONS place' => 'place',
            'POST,OPTIONS delete-account' => 'delete-account',
        ],
    ],
];