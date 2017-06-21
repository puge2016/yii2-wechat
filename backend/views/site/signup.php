<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url ;

use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
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
    <link href="/statics/ia/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/statics/ia/css/animate.css" rel="stylesheet">
    <link href="/statics/ia/css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">

<div class="middle-box text-center loginscreen   animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">IN+</h1>

        </div>
        <h3>Register to IN+</h3>
        <p>Create account to see it in action.</p>



        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
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
            $form->field($model, 'email')
                ->label(false)
                ->textInput(['placeholder' => 'Email'])
            ?>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <?=
            $form->field($model, 'password')
                ->label(false)
                ->passwordInput(['placeholder' => 'Password'])
            ?>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <?=
            $form->field($model, 'repassword')
                ->label(false)
                ->passwordInput(['placeholder' => 'Retype password'])
            ?>
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">

                    <?=
                    $form->field($model, 'agree')
                        ->label('Agree the terms and policy')
                        ->checkbox() // Agree the terms and policy
                    ?>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <?= Html::submitButton('Register', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'signup-button']) ?>
            </div>
            <!-- /.col -->
        </div>
        <p class="text-muted text-center"><small>Already have an account?</small></p>
        <a class="btn btn-sm btn-white btn-block" href="<?=Url::to('site/login.html', true) ?>">Login</a>
        <?php ActiveForm::end(); ?>

        <p class="m-t"> <small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small> </p>
    </div>
</div>

<!-- Mainly scripts -->
<script src="/statics/ia/js/jquery-3.1.1.min.js"></script>
<script src="/statics/ia/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="/statics/ia/js/plugins/iCheck/icheck.min.js"></script>
<script>
    $(document).ready(function(){
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    });
</script>
</body>

</html>

