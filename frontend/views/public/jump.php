<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $code string */
/* @var $success int */
/* @var $fail string */
/* @var $waitSecond string */
/* @var $jumpUrl string 如果链接是http则赋值完全链接，如果链接是site/index则转换为本站链接 */

use yii\helpers\Html;
use yii\helpers\Url ;

$this->title    = $name ;
$jumpUrl        = (strpos($jumpUrl, 'http') === false) ? Url::to($jumpUrl, true) : $jumpUrl ;

?>
<!DOCTYPE html>
<html>
<head lang="zh-CN">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="address=no">
    <title><?= Html::encode($this->title) ?></title>
    <link rel="stylesheet" href="/Public/libs/weui/dist/style/weui.css"/>
</head>
<body>
<?php
if($success) {
    $cssClass='weui-icon-success ' ;
    $yesNo = '操作成功';
} else {
    $cssClass='weui-icon-warn ' ;
    $yesNo = '操作失败';
}
?>
<div class="page msg_warn js_show">
    <div class="weui-msg">
        <div class="weui-msg__icon-area"><i class="<?=$cssClass ?>weui-icon_msg"></i></div>
        <div class="weui-msg__text-area">
            <h2 class="weui-msg__title"><?=$yesNo ?></h2>
            <p class="weui-msg__desc"><?=$wemsg ?> </p>
            <p class="weui-msg__desc">页面自动 <a id="href" href="<?=$jumpUrl  ?>">跳转</a> 等待时间： <b id="wait"><?=$waitSecond ?></b></p>
        </div>
        <div class="weui-msg__opr-area">
            <p class="weui-btn-area">
                <a href="<?=$jumpUrl  ?>" class="weui-btn weui-btn_primary">确认</a>
            </p>
        </div>

        <div class="weui-msg__extra-area">
            <div class="weui-footer">
                <p class="weui-footer__links">
                    <a href="javascript:void(0);" class="weui-footer__link">首页</a>
                </p>
                <p class="weui-footer__text">Copyright © 2008-<?php echo date('Y', time()) ; ?> </p>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    (function(){
        var wait = document.getElementById('wait'),href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                location.href = href;
                clearInterval(interval);
            };
        }, 1000);
    })();
</script>
<script src="/Public/libs/weui/dist/example/zepto.min.js"></script>
<script src="https://res.wx.qq.com/open/libs/weuijs/1.0.0/weui.min.js"></script>
</body>
</html>