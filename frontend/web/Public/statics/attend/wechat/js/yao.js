;(function($){
    var $showBtn = $('#showBtn'),
        $checkStatus = $('.checkStatus'),

        $checkyao = $('#checkyao'),
        $currentAddress = $("#currentAddress"),
        $reflush = $('#reflush'),
        $checkyao = $('#checkyao'),
        $timer = $('#timer'),
        deviceList = $.parseJSON(devicestr),
        nearNotAllow,
        Yao = {
            init : function(){
                this.setTime();
                this.bindEvent();
            },

            setTime : function(){
                serverTime = serverTime*1000;
                //console.info(serverTime);
                function getTimer(){
                    var d = new Date(serverTime),
                        h = d.getHours(),
                        m = d.getMinutes(),
                        s = d.getSeconds();
                    if(h<10){ h="0"+h; };
                    if(m<10){ m="0"+m; };
                    if(s<10){ s="0"+s; };
                    $timer.html(h+":"+m+":"+s);
                    serverTime += 1000;
                };
                getTimer();
                setInterval(getTimer,1000);

            },



            refresh : function(){

            },


            bindEvent : function(){

                setYao();

                function wxPosition(){
                    wx.ready(function(){
                        wx.getLocation({
                            success: function (res) {
                                var point = new BMap.Point(res.longitude,res.latitude);
                                setTimeout(function(){
                                    var convertor = new BMap.Convertor();
                                    var pointArr = [];
                                    pointArr.push(point);
                                    convertor.translate(pointArr, 1, 5, function(data){
                                        if(data.status === 0) {
                                            lat = data.points[0].lat;
                                            lng = data.points[0].lng;
                                            latlngList();

                                        }
                                    });
                                }, 200);
                                return false;
                            },
                            fail:function (res){
                                refresh();
                                //return false;
                            },
                            complete:function(){
                            },
                            cancel:function(){
                                return false;
                            }
                        });
                    });
                };
                wxPosition();


                function getRad(d){
                    return d*PI/180.0;
                };
                /**
                 *两点之间的距离
                 */
                function getFlatternDistance(lat1,lng1,lat2,lng2){
                    var radLat1 = getRad(lat1);
                    var radLat2 = getRad(lat2);

                    var a = radLat1 - radLat2;
                    var b = getRad(lng1) - getRad(lng2);

                    var s = 2*Math.asin(Math.sqrt(Math.pow(Math.sin(a/2),2) + Math.cos(radLat1)*Math.cos(radLat2)*Math.pow(Math.sin(b/2),2)));
                    s = s*EARTH_RADIUS;
                    s = Math.round(s*10000)/10000.0;
                    return s;
                };


                $reflush.on("click",function(){
                    //refresh();
                    wxPosition();
                });




                var getLocation = 0;
                var successHtml  = '<span style="color:#00af89;font-weight:bold;">'+allowMsg+'</span>';
                function setYao(){
                    /**/
                    (function(){
                        var oBdov={
                            idok:document.getElementById('ok'),
                            iderror:document.getElementById('error')
                        }
                        oBdov.idok.style.display='block';
                        oBdov.iderror.style.display='none';
                    })();
                };

                function refresh(){
                    //setYao();//移出去
                    if(getLocation!=0) return false;
                    /*var currentMsg = $("#currentAddress span").html();
                     $("#currentAddress").html(inMsg);
                     var curTime = new Date().getTime();
                     if(allowMsg == currentMsg && (curTime - last_update) < 1000){
                     $("#currentAddress").html(successHtml);
                     return false;
                     }*/

                    getLocationgps(); //gps
                    getCurrentPosition(); //baidu
                    return false;



                    /*getLocation=1;
                     wx.getLocation({
                     success: function (res) {
                     getLocation=0;
                     logTime = new Date().getTime()
                     lat = res.latitude;
                     lng = res.longitude;
                     latlngList();
                     return false;
                     },
                     fail:function (res){
                     getLocation=0;
                     var cancel = {
                     'content' : notPoint,
                     'yes'   : yes,
                     };
                     $.fn.dialog(cancel);
                     return false;
                     },
                     complete:function(){
                     getLocation=0;
                     },
                     cancel:function(){
                     getLocation=0;
                     var cancel = {
                     'content' : userCancel,
                     'yes'   : yes,
                     };
                     $.fn.dialog(cancel);
                     return false;
                     }
                     });*/
                };
                //refresh();  //入口



                $checkyao.on('click',function(){
                    if(allowMsg != $("#currentAddress span").text()){
                        /*var cancel = {
                         'content' : notAllow,
                         'yes'   : yes,
                         };
                         $.fn.dialog(cancel);*/
                        /*YI.alert({
                         msg : notAllow
                         });*/
                        if(nearNotAllow){
                            $.alert(nearNotAllow);
                        }else{
                            $.alert(notAllow);
                        }
                        return false;
                    }else{
                        isClick = 1;
                        isLocation = 0;
                        checkSet(lat,lng);
                    }

                });





                function isShow(){
                    if($("#popDiv").is(":visible")==false){
                        $("#popDiv").show();
                    }else{
                        $("#popDiv").hide();
                    }
                };





                function checkSet(lat,lng){
                    //console.info(isSend,lat)
                    if(isSend == 1 || lat == ""){
                        return false;
                    };


                    var sumbitTime = Date.parse(new Date());
                    if((sumbitTime - computedTime) > (60*1000)){
                        // var cancel = {
                        //            'content' : pointCancel,
                        //            'yes'   : yes,
                        //            'yesCb':refresh,
                        //        };
                        //    $.fn.dialog(cancel);
                        /*YI.confirm({
                         msg : content,
                         yFn : function(){
                         refresh();
                         }
                         });*/


                        $.confirm(content, function(){
                            //点击确认后的回调函数
                            refresh();
                        }, function() {
                            //点击取消后的回调函数
                        });


                        return false;
                    }
                    var ggPoint = new BMap.Point(lng, lat) ;
                    var geoc    = new BMap.Geocoder() ; //从百度获取地址
                    geoc.getLocation(ggPoint, function(rs) {
                        ads = rs.surroundingPois[0].address ;
                        tit = rs.surroundingPois[0].title ;
                        isSend = 1;
                        var data = {"lat":lat,"lng":lng,'type':type,"_csrf-frontend":_csrf,'kind':1,'point_title':tit,'point_content':ads};
                        //check.showLoading(load);
                        //YI.loading();
                        $.showLoading('拼命加载中...');
                        $.ajax({
                            type:'post',
                            url:'/attend/check/yao',
                            data:data,
                            dataType:'json',
                            success:function(data){
                                $.hideLoading();
                                //isSend = 0;
                                //YI.loading();
                                //check.hideLoading();
                                if(data.errno==0){
                                    window.location.href="/attend/check/wesuccess?aid="+aid+"&userid="+staffId+"&day="+type+"&date="+currentDate;
                                }else{
                                    // var cancel = {
                                    //        'content' : error,
                                    //        'yes'   : yes,
                                    //    };
                                    //    $.fn.dialog(cancel);

                                    /*YI.alert({
                                     msg : error
                                     });*/
                                    $.alert(error);
                                }
                            },
                            error:function(){
                                //isSend = 0;
                                //check.hideLoading();
                                // var cancel = {
                                //     'content' : error,
                                //     'yes'   : yes,
                                // };
                                // $.fn.dialog(cancel);


                                /*YI.alert({
                                 msg : error
                                 });*/
                                $.hideLoading();
                                $.alert(error);

                            },
                            complete : function(){
                                setTimeout(function(){
                                    isSend = 0;
                                },1000);
                            }
                        });
                    });
                };

                //摇一摇的动作
                function yao(eventData){

                    var acceleration = eventData.accelerationIncludingGravity;
                    var curTime = new Date().getTime();
                    if ((curTime - last_update) > 200) {
                        var diffTime = curTime - last_update;
                        last_update = curTime;
                        x = acceleration.x;
                        y = acceleration.y;
                        z = acceleration.z;
                        var speed = Math.abs(x + y + z - last_x - last_y - last_z) / diffTime * 10000;
                        if (speed > SHAKE_THRESHOLD) {
                            if(curTime-logTime < (60*1000)){
                                $checkyao.click();
                                return false;
                            }else{
                                refresh();
                                $checkyao.click();
                            };
                        }
                        last_x = x;
                        last_y = y;
                        last_z = z;
                    }
                };
                if (window.DeviceMotionEvent) {
                    window.addEventListener("devicemotion", yao, false);
                };




                //gps 2 定位
                function getLocationgps(){
                    if(navigator.geolocation){
                        navigator.geolocation.getCurrentPosition(showPosition,showError);
                    }else{
                        x.innerHTML="Geolocation is not supported by this browser.";
                    };
                };


                function showPosition(position){
                    var gpslat = position.coords.latitude ;
                    var gpslng =  position.coords.longitude;
                    gesTobd(gpslng,gpslat);
                };




                function gesTobd(gpslat,gpslng){
                    var xx = gpslat;
                    var yy = gpslng;
                    var gpsPoint = new BMap.Point(xx,yy);
                    //坐标转换完之后的回调函数
                    translateCallback = function (point){
                        lat = point.lat ;
                        lng =  point.lng;
                        latlngList();
                    }
                    setTimeout(function(){
                        BMap.Convertor.translate(gpsPoint,0,translateCallback);     //真实经纬度转成百度坐标
                    }, 2000);
                };




                function showError(error){
                    var errorCode = '';
                    switch(error.code)
                    {
                        case error.PERMISSION_DENIED:
                            errorCode = userCancel;
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorCode = notPoint;
                            break;
                        case error.TIMEOUT:
                            errorCode = timeOut
                            break;
                        case error.UNKNOWN_ERROR:
                            errorCode = notPoint;
                            break;
                    }
                    // var cancel = {
                    //           'content' : errorCode,
                    //           'yes'   : yes,
                    //       };
                    //       $.fn.dialog(cancel);


                    /*YI.alert({
                     msg : errorCode
                     });*/
                    //$.alert(errorCode);

                };

                var currentMsg = '';
                //baidu定位 1
                function getCurrentPosition(){
                    var geolocation = new BMap.Geolocation();
                    geolocation.getCurrentPosition(function(r){
                        if(this.getStatus() == BMAP_STATUS_SUCCESS){
                            lat = r.point.lat;
                            lng = r.point.lng;
                            latlngList()
                        }
                        else {
                            /**/
                            (function(){
                                var oBdov={
                                    idok:document.getElementById('ok'),
                                    iderror:document.getElementById('error')
                                };
                                oBdov.idok.style.display='none';
                                oBdov.iderror.style.display='block';

                            })();
                            /**/
                            // var cancel = {
                            //           'content' : notPoint,
                            //           'yes'   : yes,
                            //       };
                            //       $.fn.dialog(cancel);
                            $currentAddress.html(notPoint);
                            /*YI.alert({
                             msg : notPoint
                             });*/
                            $.alert(notPoint);

                        }
                    },{enableHighAccuracy: true,timeout:5000,maximumAge:60*1000})
                }


                var computedTime = 0 ;
                //验证是否在范围内 3
                function latlngList() {
                    var min_distance;
                    if (deviceList.length > 0) {
                        setYao();
                        currentMsg = allowMsg;
                        if (deviceList.length == 0) {
                            $("#currentAddress").html(deviceError);
                        }
                        //位置计算时间
                        computedTime = Date.parse(new Date());
                        for (var i = 0; i < deviceList.length; i++) {
                            var distance = getFlatternDistance(lat, lng, deviceList[i].lat, deviceList[i].lng);

                            //console.info(distance);
                            //console.info(lat, lng, deviceList[i].lat, deviceList[i].lng);

                            if (deviceList[i].around >= distance) {
                                $currentAddress.html(successHtml);
                                break;
                            } else {
                                if(min_distance){
                                    if(min_distance > distance){
                                        min_distance = distance;
                                        nearNotAllow = "<span style='font-weight: bold'>您当前不在考勤范围内<br>有效考勤范围："+deviceList[i].address+" "+deviceList[i].around+"米内";
                                    }
                                }else{
                                    min_distance = distance;
                                    nearNotAllow = "<span style='font-weight: bold'>您当前不在考勤范围内</span><br>有效考勤范围："+deviceList[i].address+" <span style='color: #0bb20c;'>"+deviceList[i].around+"</span>米内";
                                }
                                $currentAddress.html(notAllow);
                                currentMsg = notAllow;
                            }

                        }
                    } else {
                        $currentAddress.html(notInAllow);
                        currentMsg = notInAllow;
                    }
                }


            }
        };


    $(function(){
        Yao.init();
    });
})(Zepto);
