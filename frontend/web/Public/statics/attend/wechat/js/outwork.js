$(function(){
    $.showLoading("正在获取地址");
});
var map = new BMap.Map("l-map");
wx.ready(function() {
    wx.getLocation({
        success: function(res) { //微信地址
            var ggPoint = new BMap.Point(res.longitude, res.latitude);
            setTimeout(function() {
                var convertor = new BMap.Convertor();
                var pointArr = [];
                pointArr.push(ggPoint);
                convertor.translate(pointArr, 1, 5, function(data) {
                    if(data.status === 0) {
                        var latIput     = $("input[name=lat]"),
                            lngIput     = $("input[name=lng]"),
                            titleInput    = $("input[name=point_title]"),
                            contInput    = $("input[name=point_content]"),
                            gpsCont        = $("#gpsCont");
                        gpsCont.attr('href','/attend/check/panel?lng='+data.points[0].lng+'&lat='+data.points[0].lat);

                        if(lat&&lng){
                            $.hideLoading();
                            var mapTit=$(".map-tit");
                            latIput.val(lat);
                            lngIput.val(lng);
                            mapTit.find('h3').html(point_title);
                            mapTit.find('p').html(point_content);
                            ggPoint = new BMap.Point(lng,lat);
                            map.addOverlay(new BMap.Marker(ggPoint)); // 将标注添加到地图中
                        }else{
                            latIput.val(data.points[0].lat);
                            lngIput.val(data.points[0].lng);
                            ggPoint = new BMap.Point(data.points[0].lng, data.points[0].lat);
                            creatMark(ggPoint);
                            renderMap(ggPoint);
                        }

                        function creatMark(ggPoint){
                            var geoc = new BMap.Geocoder();
                            geoc.getLocation(ggPoint, function(rs) {
                                $.hideLoading();
                                var mapTit=$(".map-tit");

                                if (rs.surroundingPois.length == 0) {
                                    ads = tit = rs.address ;
                                } else {
                                    ads = rs.surroundingPois[0].address,
                                        tit = rs.surroundingPois[0].title;
                                }

                                mapTit.find('h3').html(tit);
                                mapTit.find('p').html(ads);
                                titleInput.val(tit);
                                contInput.val(ads);
                            });
                        };

                        function renderMap(ggPoint){
                            var marker = new BMap.Marker(ggPoint); // 创建标注
                            map.addOverlay(marker); // 将标注添加到地图中
                        };

                        map.centerAndZoom(ggPoint, 18);

                    }else{
                        $.alert('获取用户信息失败');
                    }
                });
            }, 1000);
            return false;
        },
        fail: function(res) {
            return false;
        },
        complete: function() {},
        cancel: function(res) {
            alert('用户拒绝授权获取地理位置');
        }
    });
})

$(function() {
    wx.ready(function() {
        var imgs = "";
        var localIds = "";
        $("#chooseImage").on("click", function() {
            //选择图片
            var _this=$(this).parent(".imgUl");
            wx.chooseImage({
                count: 9, // 默认9
                sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
                sourceType: ['camera'], // 可以指定来源是相册还是相机，默认二者都有
                success: function(res) {
                    localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                    if(localIds != "") {
                        for(var i = 0; i < localIds.length; i++) {
                            var demo = $(".imgNone").find('.imgCont').clone(true);
                            demo.find("img").attr("src",localIds[i]);
                            demo.attr("localId",localIds[i]);
                            _this.find(".upImg").after(demo);
                            wx.uploadImage({
                                localId: localIds[i], // 需要上传的图片的本地ID，由chooseImage接口获得
                                success: function(res) {
                                    var serverId = res.serverId; // 返回图片的服务器端ID
                                    _this.find(".imgCont").eq(0).attr("serverId",serverId);
                                    if($("#serverids").val() != "") $("#serverids").val($("#serverids").val() + "," + serverId);
                                    else $("#serverids").val(serverId);

                                }
                            });
                        }
                        //删除照片
                        $(".imgDel").unbind().bind("click", function() {
                            $(this).parents(".imgCont").remove();
                            $("#serverids").val('');
                            _this.find(".imgCont").each(function(k, v) {
                                var $serverId=$(this).attr("serverId");
                                if($("#serverids").val() != "") $("#serverids").val($("#serverids").val() + "," + $serverId);
                                else $("#serverids").val($serverId);
                            })
                        })

                    }
                    return false;
                }
            });
        });

    });
    var clockBtn = $("#clockBtn");
    clockBtn.on('click', function() {
        YI.getAjaxInfo({
            url: '/attend/check/outwork',
            data: $("#clockForm").serialize(),
            method: 'post',
            tip: false,
            weui: true,
            fn: function(data) {
                location.href = newUrl;
            },
            erro: function(data) {
                //console.info(data);
                $.alert(data.errmsg);
                //$.alert(66);
            }
        });
    })
})