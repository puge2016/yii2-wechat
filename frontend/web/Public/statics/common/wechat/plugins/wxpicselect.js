$(function(){
    function addJs(url){
        var s=document.createElement('script');
        s.src = cdnUrl+url;
        var h =document.getElementsByTagName('head');
        if(h&&h[0]){h[0].appendChild(s);}
    };
    if(window.__wxjs_is_wkwebview){ 
        //WKWebview内核
        addJs('Public/statics/common/wechat/plugins/wxpicselect-1.2.min.js');
    }else{
        //UIWebView内核
        addJs('Public/statics/common/wechat/plugins/wxpicselect-1.1.min.js');
    };
});