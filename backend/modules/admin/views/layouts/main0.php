<?php

use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

list(,$url) = Yii::$app->assetManager->publish('@backend/modules/admin/assets');

$this->registerCssFile($url.'/main.css');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link href="/statics/ia/css/bootstrap.min.css" rel="stylesheet">
    <link href="/statics/ia/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="/statics/ia/css/animate.css" rel="stylesheet">
    <link href="/statics/ia/css/style.css" rel="stylesheet">
    <?php $this->head() ?>
</head>

<body>

<div id="wrapper">
    <?=$this->render('main-sidebar.php') ?>
    <div id="page-wrapper" class="gray-bg">
        <?=$this->render('navbar-header.php') ?>
        <?=$this->render('breadcrumb.php') ?>
        <div class="wrapper wrapper-content animated fadeInRight">
            <?=$content ?>
        </div>
        <?=$this->render('footer.php') ?>
    </div>
</div>


<!-- Mainly scripts -->
<script src="/statics/ia/js/jquery-3.1.1.min.js"></script>
<script src="/statics/ia/js/bootstrap.min.js"></script>
<script src="/statics/ia/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/statics/ia/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="/statics/ia/js/inspinia.js"></script>
<script src="/statics/ia/js/plugins/pace/pace.min.js"></script>

</body>
</html>

