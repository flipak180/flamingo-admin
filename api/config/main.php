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
                'GET,OPTIONS events/list' => 'events/list',
                'GET,OPTIONS events/details' => 'events/details',

                'GET,OPTIONS articles/list' => 'articles/list',
                'GET,OPTIONS articles/details' => 'articles/details',

                'GET,OPTIONS quests/list' => 'quests/list',
                'GET,OPTIONS quests/details' => 'quests/details',
                'GET,OPTIONS quests/place' => 'quests/place',
                'POST,OPTIONS quests/start' => 'quests/start',

                'GET,OPTIONS places/list' => 'places/list',
                'GET,OPTIONS places/search' => 'places/search',
                'GET,OPTIONS places/visit' => 'places/visit',
                'GET,OPTIONS places/<id:\d+>' => 'places/view',
                'GET,OPTIONS places/details' => 'places/details',
                'POST,OPTIONS places/rate' => 'places/rate',
                'POST,OPTIONS places/test' => 'places/test',

                'GET,OPTIONS search' => 'flamingo/search',

                'POST,OPTIONS tickets/create' => 'tickets/create',

//                [
//                    'class' => 'yii\rest\UrlRule',
//                    'controller' => 'place',
//                    'extraPatterns' => [
//                        'POST,OPTIONS visit' => 'visit',
//                        'GET,OPTIONS list' => 'list',
//                        'GET,OPTIONS details' => 'details',
//                    ],
//                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'category',
                    'extraPatterns' => [
                        'POST,OPTIONS visit' => 'visit',
                        'GET,OPTIONS list' => 'list',
                        'GET,OPTIONS details' => 'details',
                        'GET,OPTIONS get-homepage-category' => 'get-homepage-category',
                        'GET,OPTIONS get-popular-categories' => 'get-popular-categories',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'tag'],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'user',
                    'extraPatterns' => [
                        'POST,OPTIONS auth' => 'auth',
                        'POST,OPTIONS update-name' => 'update-name',
                        'POST,OPTIONS update-avatar' => 'update-avatar',
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



