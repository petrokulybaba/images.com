<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    //'language' => 'ua-UA',
    'bootstrap' => [
        'log',
        [
            'class' => 'frontend\components\LanguageSelector',
        ],
    ],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'user' => [
            'class' => 'frontend\modules\user\Module',
        ],
        'post' => [
            'class' => 'frontend\modules\post\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'frontend\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
                'about' => 'site/about',
                'login' => 'user/default/login',
                'signup' => 'user/default/signup',
                'profile/<nickname:\w+>' => 'user/profile/view',
                'profile/update/<id:\d+>' => 'user/profile/update',
                'post/<id:\d+>' => 'post/default/view',
                'post/create' => 'post/default/create',
                'post/<post_id:\d+>/comment/create' => 'post/comment/create',
                'post/<post_id:\d+>/comment/<comment_id:\d+>/update' => 'post/comment/update',
            ],
        ],
        'feedService' => [
            'class' => 'frontend\components\FeedService',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],
    ],
    'params' => $params,
];
