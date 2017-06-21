/**
 * 模块加载器 公共配置文件
 */
require.config({
    jsServer: siteServer+'static'
    //jsServer:'http://127.0.0.1:8020/import/static'
})({
    define: {
        'zepto': '/Public/assets/common/js/zepto-1.1.6',
        'core' : '/Public/assets/common/js/core',
        'cookie' : '/Public/assets/common/js/zepto.cookie',
        //新版考勤模块
        'record' : '/Public/modules/attend/assets/js/record',
        'yiTab' : '/Public/assets/common/js/yiTab',
        //邀请关注js
        'invite' : '/Public/modules/address/assets/js/invite',
        //考勤2.0首页（九宫格）js
        'attendIndex' : '/Public/modules/attend/assets/js/attendIndex',
        //我的模块
        'myplan' : '/Public/modules/attend/assets/js/myplan',

        //通讯录选择插件
        'selectUser' : '/Public/assets/common/js/selectUser',
        'swiper' : '/Public/assets/common/js/swiper',
        'lazyload' : '/Public/assets/common/js/lazyload',
        'dropload' : '/Public/assets/common/js/dropload',
        'selectPlug' : '/Public/assets/common/js/selectPlug'
    },
    merge: [
	    {
	        id: 'common',
	        merge: ['zepto']
	    },
	    {
			id: 'core',
	        merge: ['core']
	    },
	    {
	    	id : 'record',
	    	merge: ['core','record']
	    },
	    {
	    	id : 'invite',
	    	merge : ['core','invite']
	    },
	    {
	    	id : 'attendIndex',
	    	merge : ['attendIndex']
	    },
	    {
	    	id : 'myplan',
	    	merge : ['core','yiTab','myplan']
	    },
	    {
	    	id:'addressView',
	    	merge : ['selectUser','core','swiper','lazyload','dropload','selectPlug']
	    }
    ]
});

