<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use backend\models\CategoriesSearch;
use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Места', 'items' => CategoriesSearch::getMenuItems()],
        ['label' => 'Контент', 'items' => [
            ['label' => 'Статьи', 'url' => ['/articles/index']],
            //['label' => 'События', 'url' => ['/events/index']],
            ['label' => 'Квесты', 'url' => ['/quests/index']],
            ['label' => 'Подборки', 'url' => ['/compilations/index']],
        ]],
        ['label' => 'Справочники', 'items' => [
            ['label' => 'Категории', 'url' => ['/categories/index']],
            ['label' => 'Теги', 'url' => ['/tags/index']],
        ]],
        ['label' => 'Пользователи', 'url' => ['/users/index']],
        ['label' => 'Тикеты', 'url' => ['/tickets/index']],
        ['label' => 'Истории', 'url' => ['/stories/index']],
        ['label' => 'Пуши', 'url' => ['/push-notifications/index']],
        ['label' => 'Достижения', 'items' => [
            ['label' => 'Список', 'url' => ['/achievements/index']],
            ['label' => 'Категории', 'url' => ['/achievement-categories/index']],
        ]],
        ['label' => 'Интерактив', 'items' => [
            ['label' => 'Глазами Петра', 'url' => ['/peters-eyes/index']],
        ]],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Войти', 'url' => ['/site/login']];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $menuItems,
    ]);
    if (Yii::$app->user->isGuest) {
        echo Html::tag('div',Html::a('Войти',['/site/login'],['class' => ['btn btn-link login text-decoration-none']]),['class' => ['d-flex']]);
    } else {
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                'Выйти (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout text-decoration-none']
            )
            . Html::endForm();
    }
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-end"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
