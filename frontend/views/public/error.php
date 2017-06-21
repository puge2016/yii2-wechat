<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $code string */
/* @var $message string */
/* @var $userErrDef array */
/* @var $exception Exception */

use yii\helpers\Html ;
use yii\helpers\Url ;
use common\widgets\Alert ;
$this->title        = $name;
$redirectUrl        = $userErrDef['redirectUrl'] ;
$redirectName       = $userErrDef['redirectName'] ;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Html::encode($this->title) ?></title>
    <link href="/Public/statics/ia/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/statics/ia/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="/Public/statics/ia/css/animate.css" rel="stylesheet">
    <link href="/Public/statics/ia/css/style.css" rel="stylesheet">
</head>
<body class="gray-bg">
<?= Alert::widget() ?>
<div class="middle-box text-center animated fadeInDown">
    <h1> <?=$code ?> </h1>
    <h3 class="font-bold"><?= nl2br(Html::encode($message)) ?></h3>
    <div class="error-desc">
        Sorry, but the page you are looking for has note been found. Try checking the URL for error, then hit the refresh button on your browser or try found something else in our app.
        return <a href="<?=Url::to($redirectUrl, true) ?>"><?=$redirectName ?></a>
        <form class="form-inline m-t" role="form">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Search for page">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
</div>
<!-- Mainly scripts -->
<script src="/Public/statics/ia/js/jquery-3.1.1.min.js"></script>
<script src="/Public/statics/ia/js/bootstrap.min.js"></script>
</body>
</html>

