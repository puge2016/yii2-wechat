<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Request password reset';
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

<div class="passwordBox animated fadeInDown">
    <div class="row">

        <div class="col-md-12">
            <div class="ibox-content">

                <h2 class="font-bold"><?= Html::encode($this->title) ?></h2>

                <p>
                    Please fill out your email. A link to reset password will be sent there.
                </p>

                <div class="row">

                    <div class="col-lg-12">

                        <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
                        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
                        <div class="form-group">
                            <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-6">
            Copyright Example Company
        </div>
        <div class="col-md-6 text-right">
            <small>© 2014-2015</small>
        </div>
    </div>
</div>
</body>
</html>

