<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url ;
use yii\bootstrap\ActiveForm;
use common\widgets\Alert;
$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= Html::encode($this->title) ?></title>

    <link href="/statics/ia/css/bootstrap.min.css" rel="stylesheet">
    <link href="/statics/ia/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="/statics/ia/css/animate.css" rel="stylesheet">
    <link href="/statics/ia/css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">
<?= Alert::widget() ?>

<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">IN+</h1>

        </div>
        <h3>Welcome to IN+</h3>
        <p>Perfectly designed and precisely prepared admin theme with over 50 pages with extra new web app views.
            <!--Continually expanded and constantly improved Inspinia Admin Them (IN+)-->
        </p>
        <p>Login in. To see it in action.</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'class' => 'm-t']); ?>

        <div class="form-group has-feedback">
            <?=
            $form->field($model, 'adminname')
                ->label(false)
                ->textInput(['autofocus' => true, 'placeholder' => 'Adminname'])
            ?>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>



        <div class="form-group has-feedback">
            <?=
            $form->field($model, 'password')
                ->label(false)
                ->passwordInput(['placeholder' => 'Password'])
            ?>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck"><?= $form->field($model, 'rememberMe')->checkbox() ?></div>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <?= Html::submitButton('Sign In', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col -->
        </div>

        <a href="<?=Url::to('site/request-password-reset.html', true) ?>"><small>Forgot password?</small></a>
        <p class="text-muted text-center"><small>Do not have an account?</small></p>
        <a class="btn btn-sm btn-white btn-block" href="<?=Url::to('site/signup.html', true) ?>">Create an account</a>

        <?php ActiveForm::end(); ?>


        <p class="m-t"> <small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small> </p>
    </div>
</div>

<!-- Mainly scripts -->
<script src="/statics/ia/js/jquery-3.1.1.min.js"></script>
<script src="/statics/ia/js/bootstrap.min.js"></script>

</body>
</html>











