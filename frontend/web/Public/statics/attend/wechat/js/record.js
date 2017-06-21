/*var testdata = {
    "errno": 0,
    "errmsg": "获取成功",
    "data": {
        "reportlist": {
            "1467302400": {
                "day_status": 2,
                "auto_status": 0,
                "status": 20,
                "status_list": [50],
                "check_list": [],
                "status1": 50
            },
            "1467388800": {
                "day_status": 1,
                "auto_status": 1,
                "status": 20,
                "status_list": [50],
                "check_list": [],
                "status1": 50
            },
            "1467475200": {
                "day_status": 1,
                "auto_status": 1,
                "status": 8,
                "status_list": [8],
                "check_list": []
            },
            "1467561600": {
                "day_status": 2,
                "auto_status": 0,
                "status": 20,
                "status_list": [50],
                "check_list": [],
                "status1": 50
            },
            "1467648000": {
                "day_status": 2,
                "auto_status": 0,
                "status": 20,
                "status_list": [50],
                "check_list": [],
                "status1": 50
            },
            "1467734400": {
                "day_status": 2,
                "auto_status": 0,
                "status": 20,
                "status_list": [50],
                "check_list": [],
                "status1": 50
            },
            "1467820800": {
                "day_status": 2,
                "auto_status": 0,
                "status": 8,
                "status_list": [8],
                "check_list": []
            },
            "1467907200": {
                "day_status": 2,
                "auto_status": 0,
                "status": 20,
                "status_list": [50],
                "check_list": [],
                "status1": 50
            },
            "1467993600": {
                "day_status": 1,
                "auto_status": 1,
                "status": 8,
                "status_list": [8],
                "check_list": []
            },
            "1468080000": {
                "day_status": 1,
                "auto_status": 1,
                "status": 8,
                "status_list": [8],
                "check_list": []
            },
            "1468166400": {
                "day_status": 2,
                "auto_status": 0,
                "status": 8,
                "status_list": [8],
                "check_list": []
            },
            "1468252800": {
                "day_status": 2,
                "auto_status": 0,
                "status": 8,
                "status_list": [8],
                "check_list": []
            },
            "1468339200": {
                "day_status": 2,
                "auto_status": 0,
                "status": 8,
                "status_list": [8],
                "check_list": []
            },
            "1468425600": {
                "day_status": 2,
                "auto_status": 0,
                "status": 8,
                "status_list": [8],
                "check_list": []
            },
            "1468512000": {
                "day_status": 2,
                "auto_status": 0,
                "status": 8,
                "status_list": [8],
                "check_list": []
            },
            "1468598400": {
                "day_status": 1,
                "auto_status": 1,
                "status": 8,
                "status_list": [8],
                "check_list": []
            },
            "1468684800": {
                "day_status": 1,
                "auto_status": 1,
                "status": 8,
                "status_list": [8],
                "check_list": []
            },
            "1468771200": {
                "day_status": 2,
                "auto_status": 0,
                "status": 8,
                "status_list": [8],
                "check_list": []
            },
            "1468857600": {
                "day_status": 2,
                "auto_status": 0,
                "status": 8,
                "status_list": [8],
                "check_list": []
            },
            "1468944000": {
                "day_status": 2,
                "auto_status": 0,
                "status": 8,
                "status_list": [8],
                "check_list": []
            },
            "1469030400": {
                "day_status": 2,
                "auto_status": 0,
                "status": 10,
                "status_list": [20, 10],
                "check_list": ["21:45:00(原因：1111111111111111111)", "09:15:00(原因：1111111111111111)"],
                "status1": 20
            },
            "1469116800": {
                "day_status": 2,
                "auto_status": 0,
                "check_list": ["18:00:00(原因：异常申请通过，自动打卡)", "09:00:00(原因：异常申请通过，自动打卡)"],
                "status": 1
            },
            "1469203200": {
                "day_status": 1,
                "auto_status": 0,
                "check_list": [],
                "status": 1
            },
            "1469289600": {
                "day_status": 1,
                "auto_status": 0,
                "check_list": [],
                "status": 1
            },
            "1469376000": {
                "day_status": 2,
                "auto_status": 0,
                "status": 20,
                "status_list": [20],
                "check_list": ["20:15:00(原因：123)", "08:55:00(原因：123)"],
                "status1": 20
            },
            "1469462400": {
                "day_status": 2,
                "auto_status": 0,
                "status": 8,
                "status_list": [8],
                "check_list": []
            },
            "1469548800": {
                "day_status": 2,
                "auto_status": 0,
                "status": 10,
                "status_list": [10, 11],
                "check_list": ["17:29:00(原因：异常申请通过，自动打卡)", "09:18:00(原因：异常申请通过，自动打卡)"],
                "status1": 11
            },
            "1469635200": {
                "day_status": 2,
                "auto_status": 0,
                "check_list": ["21:00:00(原因：1111111111111111111)", "19:40:00(原因：1234)", "17:03:00(原因：7)"],
                "status": 1
            }
        },
        "staffinfo": {
            "we_account_id": "100100209",
            "we_avatar": "http:\/\/shp.qpic.cn\/bizmp\/HmtU0JT3A9h8KyJVmZdASJ7t8Dm47McDoffic36njW6z9IBv1fKkicGg\/",
            "we_name": "胡清松",
            "we_gender": "1",
            "we_position": "软件测试",
            "id": "525757",
            "we_department": "8",
            "department": "测试"
        },
        "checkDay": [{
            "check_range_start": "1469055600",
            "check_range_end": "1469111400",
            "staff_id": "525757",
            "times_id": "93",
            "date": "1469030400",
            "checkin_time": "1469063700",
            "checkout_time": "1469108700",
            "late": "15",
            "early": "0",
            "absent": "0"
        }, {
            "check_range_start": "1469142000",
            "check_range_end": "1469197800",
            "staff_id": "525757",
            "times_id": "93",
            "date": "1469116800",
            "checkin_time": "1469149200",
            "checkout_time": "1469181600",
            "late": "0",
            "early": "0",
            "absent": "0"
        }, {
            "check_range_start": "1469401200",
            "check_range_end": "1469457000",
            "staff_id": "525757",
            "times_id": "93",
            "date": "1469376000",
            "checkin_time": "1469408100",
            "checkout_time": "1469448900",
            "late": "0",
            "early": "0",
            "absent": "0"
        }, {
            "check_range_start": "1469574000",
            "check_range_end": "1469615400",
            "staff_id": "525757",
            "times_id": "93",
            "date": "1469548800",
            "checkin_time": "1469582280",
            "checkout_time": "1469611740",
            "late": "18",
            "early": "31",
            "absent": "0"
        }, {
            "check_range_start": "1469588400",
            "check_range_end": "1469628000",
            "staff_id": "525757",
            "times_id": "199",
            "date": "1469548800",
            "checkin_time": "0",
            "checkout_time": "0",
            "late": "0",
            "early": "0",
            "absent": "480"
        }, {
            "check_range_start": "1469660400",
            "check_range_end": "1469716200",
            "staff_id": "525757",
            "times_id": "93",
            "date": "1469635200",
            "checkin_time": "1469696580",
            "checkout_time": "1469710800",
            "late": "423",
            "early": "0",
            "absent": "480"
        }, {
            "check_range_start": "1468206000",
            "check_range_end": "1468245600",
            "staff_id": "525757",
            "times_id": "199",
            "date": "1468166400",
            "checkin_time": "0",
            "checkout_time": "0",
            "late": "0",
            "early": "0",
            "absent": "480"
        }, {
            "check_range_start": "1469502000",
            "check_range_end": "1469541600",
            "staff_id": "525757",
            "times_id": "199",
            "date": "1469462400",
            "checkin_time": "0",
            "checkout_time": "0",
            "late": "0",
            "early": "0",
            "absent": "480"
        }, {
            "check_range_start": "1469761200",
            "check_range_end": "1469800800",
            "staff_id": "525757",
            "times_id": "199",
            "date": "1469721600",
            "checkin_time": "0",
            "checkout_time": "0",
            "late": "0",
            "early": "0",
            "absent": "480"
        }]
    }
}
*/

(function($) {
    var $dateList = $('#dateList'),
        $fixedNav = $('#fixedNav'),
        $newRecordNav = $('#newRecordNav'),
        $userRecords = $('#userRecords'),
        $getMonthData = $('#getMonthData'),
        $recordDl = $('#recordDl'),
        $userStatusRender = $('#userStatusRender'),
        dateArr = {
            '周天': 0,
            '周一': 1,
            '周二': 2,
            '周三': 3,
            '周四': 4,
            '周五': 5,
            '周六': 6
        },
        status = {
            1: 'normal', //正常
            2: '', //异常
            10: 'cd', //迟到
            11: 'zt', //早退
            12: 'tx', //调休
            13: 'wc', //外出
            15: 'cc', //出差
            16: 'kg', //旷工
            20: 'jb', //加班
            50: 'qj', //请假,
            8: 'ls', //漏刷
            9: 'ls' //漏刷
        },
        _date = new Date(),
        curDate = [_date.getFullYear(), _date.getMonth() + 1],
        $recordTimes = $('#recordTimes'),
        record = {
            init: function() {
                this.ajaxInit(curDate);
                this.getMonthData();
            },
            getMonthData: function() {
                var _this = this;
                $getMonthData.children('em').on('click', function() {
                    var _T = this,
                        y = curDate[0],
                        m = curDate[1],
                        timer = null;
                    $(this).addClass('cur');
                    /*clearTimeout(timer);
                    timer = setTimeout(function(){
                        $(_T).removeClass('cur');
                    },200);*/

                    if (m > 1) {
                        curDate = [y, m - 1];
                    } else {
                        curDate = [y - 1, 12]
                    };
                    //console.info(curDate);
                    //console.info(curDate[1]);
                    _this.ajaxInit(curDate);
                });

                $getMonthData.children('span').on('click', function() {

                    var _T = this,
                        y = curDate[0],
                        m = curDate[1],
                        timer = null;

                    if (y == _date.getFullYear() && m == _date.getMonth() + 1) {
                        return false;
                    };
                    //console.info(y,m);    
                    //console.info(_date.getFullYear(),_date.getMonth()+1)
                    //if(y<=_date.getFullYear()){
                    //console.info(curDate);
                    $(this).addClass('cur');
                    /*clearTimeout(timer);
                    timer = setTimeout(function(){
                        $(_T).removeClass('cur');
                    },200);*/
                    if (m == 12) {
                        curDate = [y + 1, 1];
                    } else {
                        curDate = [y, m + 1]
                    };
                    //};
                    /*else if(y==_date.getFullYear()){
                        console.info(curDate,m,_date.getMonth()+1);
                        if(m<_date.getMonth()+1){
                            curDate = [y,m]
                        };
                    };*/


                    //console.info(curDate);
                    _this.ajaxInit(curDate);

                });
            },
            ajaxInit: function(d) {
                var _this = this;

                YI.getAjaxInfo({
                    url: '/attend/index/record',
                    method: 'post',
                    data: {
                        'date': d[0] + '-' + d[1], 'staffid': staff_id, '_csrf-frontend':_csrf
                    },
                    weui : true,
                    fn: function(data) {
                        //console.info(data.data.checkDay);
                        $getMonthData.find('span,em').removeClass('cur');
                        $getMonthData.children('i').html(d[0] + '年' + d[1] + '月').addClass('animated').addClass('flipInX');
                        setTimeout(function() {
                            $getMonthData.children('i').removeClass('animated').removeClass('flipInX');
                        }, 500);

                        //console.info(curDate);  
                        //var _d = testdata.data.reportlist,
                        // _i = testdata.data.staffinfo,
                        var _d = data.data.reportlist,
                            _i = data.data.staffinfo,
                            _a = data.data.approves,
                            dateList = [];

                        //console.info(_d);
                        //渲染用户信息+头像
                        //console.info(_i);
                        var $userGender = $userRecords.find('div.a');
                        $userRecords.find('img').attr('src', _i['we_avatar']);
                        $userGender.children('i').html(_i['we_name']);
                        $userRecords.find('div.b i').html(_i['department']);
                        if (_i['we_gender'] == 1) {
                            $userGender.addClass('man');
                        } else {
                            $userGender.addClass('woman');
                        };
                        for (var i in _d) {
                            var obj = {
                                'd': i,
                                'v': _d[i]
                            };
                            dateList.push(obj);
                        };
                        var newDateList = dateList.sort(function(a, b) {
                            return a.d - b.d
                        });
                        //console.info(newDateList);

                        $.each(dateArr, function(i, e) {
                            if (i == new Date(newDateList[0]['d'] * 1000).getWeek()) {
                                var lis = '';
                                for (var i = 0, len = e; i < len; i++) {
                                    lis += '<li><i></i></li>';
                                };
                                $dateList.html(lis);
                                return false;
                            };
                        });
                        //console.info(new Date(newDateList[0]['d']*1000).getWeek());
                        var _html = '',
                            //_zc = _cd = _zt = _kg = _qj = _tx = _jb = _cc = _wc = 0,
                            _status1=_status8=_status9=_status10=_status11=_status12 =_status13=_status15=_status16=_status20=_status50=0;                      
                            //console.info(newDateList);
                        $.each(newDateList, function(i, e) {
                            var h = (i + 1) < 10 ? ('0' + (i + 1)) : (i + 1);
                            //console.info(e)
                            //console.info(e.v['check_list'])
                            //console.info(curDate[1],(_date.getMonth()+1 ))
                            var xj = '';
                            function renderState(){
                                if(e.v['auto_status']){ //小角
                                    xj = 'xj'
                                };
                                
                                if(e.v['day_status']==1 && e.v['status']==1){ //休息日 白底
                                    _html += '<li data-second="' + e.d + '" class="cur" data-check="' + e.v['check_list'] + '" data-date="' + h + '"><i><em class="'+xj+'"><del></del><span>' + h + '</span><ins class="xx"></ins><ins class="xx"></ins><b></b></em></i></li>';
                                }else{
                                    _html += '<li data-second="' + e.d + '" class="cur" data-check="' + e.v['check_list'] + '" data-date="' + h + '"><i><em class="'+xj+'"><del></del><span>' + h + '</span><ins class="' + status[e.v['status']] + '"></ins><ins class="' + (status[e.v['status1']]?status[e.v['status1']]:status[e.v['status']]) + '"></ins><b></b></em></i></li>';
                                };
                            };

                            
                            if (curDate[1] == (_date.getMonth() + 1)) {//判断当月 today
                                if (i + 1 < newDateList.length) { //判断是不是最后一天    
                                   renderState();
                                } else {
                                    _html += '<li data-second="' + e.d + '" class="cur" data-check="' + e.v['check_list'] + '" data-date="' + h + '"><i><em class="today animated flipInY"><span>' + h + '</span><ins></ins><ins></ins><b></b></em></i></li>';
                                };
                            } else{
                                renderState();
                            };

                            if( ((new Date()).getTime()-e.d*1000) > 86400000  ){
                                if (i + 1 <= newDateList.length) {
                                    if(e.v['day_status']==1 && e.v['status']==1){
                                        //休息日 正常状态忽略
                                        //console.log(56)
                                    }else{
                                        if(e.v.status_list != undefined){
                                            $.each(e.v.status_list,function (i,v) {
                                                if(v == 8 || v == 9){
                                                    _status8++;
                                                }else{
                                                    var temp = ('_status'+v);
                                                    eval(temp+'++');
                                                }
                                            })
                                        }else{
                                            _status1++;
                                        }
                                    }
                                };
                            };
                        });
                        //重新计算每月申请次数
                        $.each(_a,function (i,e) {
                            var temp = ('_status'+i+'='+e);
                            eval(temp);
                        });
                        $userStatusRender.find('li').eq(0).children('span').html(_status1);
                        $userStatusRender.find('li').eq(1).children('span').html(_status10);
                        $userStatusRender.find('li').eq(2).children('span').html(_status11);
                        $userStatusRender.find('li').eq(3).children('span').html(_status16);
                        $userStatusRender.find('li').eq(4).children('span').html(_status50);
                        $userStatusRender.find('li').eq(5).children('span').html(_status12);
                        $userStatusRender.find('li').eq(6).children('span').html(_status20);
                        $userStatusRender.find('li').eq(7).children('span').html(_status15);
                        $userStatusRender.find('li').eq(8).children('span').html(_status13);
                        $userStatusRender.find('li').eq(9).children('span').html(_status8+_status9);

                        $dateList.append(_html);

                        var appendHtml = '',
                            monthNum = new Date(curDate[0], curDate[1], 0).getDate(),
                            minD = newDateList.length;

                        //console.info(minD,monthNum)
                        for (var j = minD; j < monthNum; j++) {
                            appendHtml += '<li data-check=""><i><em>' + (j + 1) + '<b></b></em></i></li>';
                        };
                        $dateList.append(appendHtml);
                        _this.bindEvent(data.data.checkDay);
                    }
                });
            },


            bindEvent: function(checkDay) {
                
                //休息日颜色变成黑色
                $.each($dateList.find('li'),function(i,e){
                    if($(e).find('ins.xx').length){
                        $(e).find('i > em > span').css('color','#333');  
                    };
                });
                
                //console.info(checkDay);
                $dateList.find('li').on('click', function() {
                    $(this).addClass('cur').siblings().removeClass('cur');
                    $(this).find('b').show();
                    $(this).siblings().find('b').hide();
                    $(this).find('em').addClass('animated').addClass('flipInY');
                    $(this).siblings().find('em').removeClass('animated').removeClass('flipInY');
                    var _check = $(this).data('check')
                    if (_check) {
                        var html = '<dt class="t">打卡记录</dt>',
                            _check = _check.split(',');
                        $.each(_check, function(i, e) {
                            html += '<dd>' + e + '</dd>';
                        });
                        $recordDl.html(html);
                    } else {
                        $recordDl.html('<dt class="t">打卡记录</dt><dd>暂无数据</dd>');
                    };

                    $recordTimes.html('');

                    var _second = $.trim($(this).data('second')),
                    	skip = 0;
                    //console.info($recordTimes.html());
                    //console.info(_second,checkDay);
                    if (_second) {
                        $.each(checkDay, function(i, e) {
                            //console.info(i);
                            //console.info(e.date,_second);
                            if (e.date == _second) {
                            	//console.info(e);


                                //$.each(e, function(i, e) {
                                    //console.info(i,e);
                                    /*var _a = parseInt(e.checkin_time / 60),
                                        _b = e.checkin_time % 60,
                                        _c = parseInt(e.checkout_time / 60),
                                        _d = e.checkout_time % 60,*/


                                    //console.info(e['times_id'])

                                    var _dateCheckin = new Date(e.checkin_time*1000),
                                    	_dateCheckOut = new Date(e.checkout_time*1000),
                                    	_a = _dateCheckin.getHours(),
                                    	_b = _dateCheckin.getMinutes(),
                                    	_c = _dateCheckin.getSeconds(),
                                    	_d = _dateCheckOut.getHours(),
                                    	_e = _dateCheckOut.getMinutes(),
                                    	_f = _dateCheckOut.getSeconds(),
                                        _g = _h = _oo = _oi = '';
                                    //console.info(_a,_b,_c,_d,_e,_f,_g);

                                    if(e.checkin_time==0){
                                    	_a = "—";
                                    	_b = _c = '';
                                    }else{
                                    	_a = (_a < 10) ? ('0' + _a) : _a;
                                    	_b = (_b < 10) ? ('0' + _b) : _b;
                                    	_c = (_c < 10) ? ('0' + _c) : _c;
                                    	_g = ":";
                                    };

                                    if(e.checkout_time==0){
                                    	_d = "—";
                                    	_e = _f = '';
                                    }else{
                                    	_d = (_d < 10) ? ('0' + _d) : _d;
                                    	_e = (_e < 10) ? ('0' + _e) : _e;
                                    	_f = (_f < 10) ? ('0' + _f) : _f;
                                    	_h = ":";
                                    };
                                    //console.info(_a,_b,_c,_d,_e,_f,_g);
                                    //console.info(_a,_b,_c,_d);
                                    //$recordTimes.find('span').eq(0).html(_a + _g + _b);
                                    //$recordTimes.find('span').eq(1).html(_c + _g + _d);
                                    if(CHECK_LOG_OUTSIDE == e.checkin_type){
                                        _oi = '(外勤)';
                                    }
                                    if(CHECK_LOG_OUTSIDE == e.checkout_type){
                                        _oo = '(外勤)';
                                    }
                                    if(skip==0){
                                    	$recordTimes.append('<dt>签到：<span>'+(_a+_g+_b+_g+_c)+_oi+'</span>，签退：<span>'+(_d+_h+_e+_h+_f)+_oo+'</span></dt>');
                                    }else{
                                    	$recordTimes.append('<dd>签到：<span>'+(_a+_g+_b+_g+_c)+_oi+'</span>，签退：<span>'+(_d+_h+_e+_h+_f)+_oo+'</span></dd>');
                                    };

                                    skip++;
                                //});
                                //$recordTimes.show();
                                //return false;

                            } else {
                                //$recordTimes.hide();
                            };
                        });
                    };
                    $recordTimes.show();

                });

                $.each($dateList.find('em'), function(i, e) {
                    if ($(e).hasClass('today')) {
                        $(e).parents('li').trigger('click');
                        return false;
                    };
                });

                /*$fixedNav.find('li').on('click',function(){
                    $(this).addClass('cur').siblings().removeClass('cur');
                    $(this).children('div').show();
                    $(this).siblings().children('div').hide();
                }).each(function(){
                    $(this).children('div').children('a').last().css({
                        'border-bottom':'0 none'
                    });
                });*/

            }
        };


    $(function() {
        record.init();
    });
})(Zepto);
