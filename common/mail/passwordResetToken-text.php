<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);

$appId      = Yii::$app->id;
$isNoBackend  = (strpos($appId, 'backend') === false) ;
if ($isNoBackend) {
    $uu         = $user->username ;
} else {
    $uu         = $user->adminname ;
}


?>
Hello <?= $uu ?>,

Follow the link below to reset your password:

<?= $resetLink ?>
