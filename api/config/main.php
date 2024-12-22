<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'language' => 'ru',
    'name' => 'FlaminGO',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
//        'v1' => [
//            'basePath' => '@app/modules/v1',
//            'class' => 'api\modules\v1\Module'
//        ]
    ],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'GET events/list' => 'events/list',
                'GET events/details' => 'events/details',

                'POST,OPTIONS send-sms' => 'system/send-sms',

                'GET stories/list' => 'stories/list',

                'POST,OPTIONS achievements/list-by-categories' => 'achievements/list-by-categories',

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
                'POST,OPTIONS places/rate' => 'places/rate',
                'POST,OPTIONS places/test' => 'places/test',

                'GET compilations/get-actual-compilation' => 'compilations/get-actual-compilation',
                'GET compilations/details' => 'compilations/details',

                'GET search' => 'flamingo/search',

                'POST,OPTIONS tickets/create' => 'tickets/create',

//                [
//                    'class' => 'yii\rest\UrlRule',
//                    'controller' => 'place',
//                    'extraPatterns' => [
//                        'POST,OPTIONS visit' => 'visit',
//                        'GET list' => 'list',
//                        'GET details' => 'details',
//                    ],
//                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'category',
                    'extraPatterns' => [
                        'POST,OPTIONS visit' => 'visit',
                        'GET list' => 'list',
                        'GET details' => 'details',
                        'GET get-homepage-category' => 'get-homepage-category',
                        'GET get-popular-categories' => 'get-popular-categories',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'tag'],
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

                //'POST,OPTIONS quests/start' => 'quest/start',
                //'POST,OPTIONS quests/visit' => 'quest/visit',

//                [
//                    'class' => 'yii\rest\UrlRule',
//                    'controller' => 'v1/place',
//                    'tokens' => [
//                        '{id}' => '<id:\\w+>'
//                    ]
//
//                ]
            ],
        ]
    ],
    'params' => $params,
];



