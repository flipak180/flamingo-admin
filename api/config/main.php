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
            'identityClass' => 'common\models\Admin',
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

                'GET articles/list' => 'articles/list',
                'GET articles/details' => 'articles/details',

                'GET quests/list' => 'quests/list',
                'GET quests/details' => 'quests/details',
                'GET quests/place' => 'quests/place',
                'POST,OPTIONS quests/start' => 'quests/start',

                'GET places/list' => 'places/list',
                'GET places/visit' => 'places/visit',
                'GET places/<id:\d+>' => 'places/view',
                'GET places/details' => 'places/details',
                'POST,OPTIONS places/test' => 'places/test',

                'GET search' => 'flamingo/search',

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
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'tag'],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'user',
                    'extraPatterns' => [
                        'POST,OPTIONS login' => 'login',
                        'POST,OPTIONS visits' => 'visits',
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



