<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $user backend\models\Admin */

$resetLink  = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
$appId      = Yii::$app->id;
$isNoBackend  = (strpos($appId, 'backend') === false) ;
if ($isNoBackend) {
    $uu         = $user->username ;
} else {
    $uu         = $user->adminname ;
}


?>
<div class="password-reset">
    <p>Hello <?= Html::encode($uu) ?>,</p>

    <p>Follow the link below to reset your password:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
