(function($){var $window=$(window);function _updateScrollRect(){var b=window.scrollY,a=b+window.innerHeight;return[b,a];}function _throttle(a,b){var c;function d(){var g=this,f=[].slice.call(arguments);
function e(){b.apply(g,f);}c&&clearTimeout(c);c=setTimeout(e,a);}d._zid=b._zid=b._zid||$.proxy(b)._zid;return d;}$.fn.lazyload=function(c){var f={threshold:0,dataAttribute:"src",supportAsync:false};
c=$.extend({},f,c);$window.on("scrollStop orientationchange",b);var e=this,a=this.selector,h,d;function b(){var i=_updateScrollRect();h=i[0];d=i[1];if(c.supportAsync){e=$(a);
}e=$($.map(e,function(j){return(j.lazyload||!$(j).data(c.dataAttribute))?null:j;}));e.each(g);}function g(){var k=this,o=$(this);if(k.lazyload){return;
}var n=o.data(c.dataAttribute),i=c.threshold,m=o.offset(),j=m.top-i,l=m.top+m.height+i;if((h<=j&&j<=d)||(h<=l&&l<=d)||(j<=h&&l>=d)){o.attr("src",n).css("visibility","hidden");
k.lazyload="loading";k.onload=function(){k.lazyload="loaded";o.css("visibility","visible");};}else{if(j>d){return false;}}}$(document).ready(function(){b();
});if((/iphone|ipad/gi).test(navigator.appVersion)){$window.bind("pageshow",function(i){if(i.originalEvent&&i.originalEvent.persisted){b();}});}};$(window).on("scroll",_throttle(80,function(){$(window).trigger("scrollStop");
}));})(Zepto);