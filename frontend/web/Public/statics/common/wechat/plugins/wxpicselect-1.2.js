(function($) {

    $(function(){
        //alert(34234784854)

        function showImg(){


            $('.wxshowimg').on('click', function() {

                
                /*if ($(this).parents('.plug-select').length) {
                    return;
                };*/
                var currentwxImg = $(this).attr('src'),
                    __src = currentwxImg.indexOf("http") > -1,
                    __wxsrc = currentwxImg.indexOf("data:image") > -1;


                currentwxImg = (__src || __wxsrc) ? currentwxImg : CDN_URL + currentwxImg;
                var wximgStrs = [];
                $("img.wxshowimg").each(function() {
                    var oneImgStr = $(this).attr('src'),
                        __src = oneImgStr.indexOf("http") > -1,
                        __wxsrc = oneImgStr.indexOf("data:image") > -1;

                    wximgStrs.push( (__src || __wxsrc) ? oneImgStr : CDN_URL + oneImgStr);
                });
                wx.previewImage({
                    current: currentwxImg, // 当前显示图片的http链接
                    urls: wximgStrs // 需要预览的图片http链接列表
                });
            });



            //删除照片
            $(".uploadImg .imgDel").on("click", function() {
                var index = $(this).index();
                $(this).parents(".oneImg").remove();
                var img = $("#serverids").val();
                $("#serverids").val('');
                var imgs = img.split(',');
                var imgstr = "";
                for (var j = 0; j < imgs.length; j++) {
                    if (index != j) {
                        if (imgstr != "") imgstr += ",";
                        imgstr += imgs[j];
                    }
                }
                $("#serverids").val(imgstr);
            })

        };
        showImg();



        function uploadImg(localIds){
            var i=0;
            var l=localIds.length;
            (function(){    
                var arr=arguments;
                wx.uploadImage({
                    localId: localIds[i], // 需要上传的图片的本地ID，由chooseImage接口获得
                    success: function(res) {
                        var serverId = res.serverId; // 返回图片的服务器端ID
                        if ($("#serverids").val() != "") $("#serverids").val($("#serverids").val() + "," + serverId);
                        else $("#serverids").val(serverId);
                        var demo = $(".imgDemo div").clone(true);
                        demo.find("img").addClass("wxshowimg").attr("src", localIds[i]);
                        $(".to").append(demo);
                        
                        if(i<l-1){ 
                            i++;
                            arr.callee();
                        }else{
                            showImg();
                        }
                    }
                });
            })();
        };
        

        wx.ready(function() {
            var imgs = "";
            var localIds = "";
            $("#chooseImage").on("click", function() {
                //选择图片
                wx.chooseImage({
                    count: 9, // 默认9
                    sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
                    sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
                    success: function(res) {
                        localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                        if (localIds != "") {
                            /*for (var i = 0; i < localIds.length; i++) {
                                var demo = $(".imgDemo div").clone(true);
                                demo.find("img").addClass("wxshowimg").attr("src", localIds[i]);
                                //demo.find("img").attr("src", localIds[i]);
                                $(".to").append(demo);

                                wx.uploadImage({
                                    localId: localIds[i], // 需要上传的图片的本地ID，由chooseImage接口获得
                                    success: function(res) {
                                        var serverId = res.serverId; // 返回图片的服务器端ID
                                        if ($("#serverids").val() != "") $("#serverids").val($("#serverids").val() + "," + serverId);
                                        else $("#serverids").val(serverId);

                                        wxShowImg();
                                    }
                                });
                            };*/
                            uploadImg(localIds);

                            
                           
                        }
                    }
                });
            });

        });

    });
})(Zepto);
