$(function() {
    $(".recordList li").on('click', function() {
        var date = $(this).find("a").html();
        $(".selectList").html(date);
        $(".recordList").addClass("mui-table-view-chevron");
    });

    $(".approveCancel").on('click', function() {
        /*YI.confirm({
            msg: isCancelText,
            yFn: function() {
                wx.closeWindow();
            }
        });*/
        
        $.confirm(isCancelText, function(){
            //点击确认后的回调函数
            window.close();
            wx.closeWindow();
        }, function() {
            //点击取消后的回调函数
        });
        
    })

    var isSubmit = 0;
    var successId = "";
    var successId = '';


    var $appDate1 = $('#appDate1'),
        $appDate2 = $('#appDate2');

    //提交审批
    $(".approveSubmit").unbind('click').on('click', function() {
        var approveId = $("input[name=approveid]").val();
        var reason = $(".reason").val();
        var copyIds = $("input[name=copy]").val();
        var endTime = $("#appDate2").attr("data-val")
        var startTime = $("#appDate1").attr("data-val")
        var address = $("input[name=address]").val();
        var picstrs = $("#serverids").val();
        address ? address : '';
        //var tripMode = 0;
        if (type == 3) {
            var tripMode = "";
            $(".going-input").each(function() {
                var arrive = "0";
                if ($(this).prop("checked") == true) {
                    arrive = "1";
                }
                tripMode = tripMode + arrive;
            })
        };



        if (!$appDate1.val()) {
            /*YI.alert({
                msg: '请选择开始时间'
            });*/
            $.alert('请选择开始时间');
            return false;
        };
        if (!$appDate2.val()) {
            /*YI.alert({
                msg: '请选择结束时间'
            });*/
            $.alert('请选择结束时间');
            return false;
        };

        //console.info(tripMode);
        if (type == 3 && tripMode == "0000") {
            /*YI.alert({
                msg: '请选择出行方式'
            });*/
            $.alert('请选择出行方式');
            return false;
        };

        if (type == 4 && address == "") {
            /*YI.alert({
                msg: '请填写外出地点'
            });*/
            $.alert('请填写外出地点');
            return false;
        };

        if (reason == "") {
            /*YI.alert({
                msg: '请填写说明'
            });*/
            $.alert('请填写说明');
            return false;
        };
        if (approveId == "") {
            /*YI.alert({
                msg: '请选择审批人'
            });*/
            $.alert('请选择审批人');
            return false;
        };








        /*if((approveId =="" || reason == "" || endTime == "" || startTime == "" ) || (type==3 && tripMode=="0000") || (type==4 && address=="")){
          YI.alert({
              msg : msgText
          });
          return false;
        }*/





        if ((check_pic == 1 && picstrs == "")) {
            /*YI.alert({
                msg: check_pic_message
            });*/
            $.alert(check_pic_message);
            return false;
        };






        if (isSubmit == 1) return false;
        isSubmit = 1;
        var data = {
            'address': address,
            'tripMode': tripMode,
            'type': type,
            'approveId': approveId,
            'reason': reason,
            'startTime': startTime,
            'endTime': endTime,
            'copyIds': copyIds,
            'type_id': type_id,
            'ccIds': cc_staff_ids,
            'picstrs': picstrs,
            'level':level
        };
        //check.showLoading(myLoad);



        YI.getAjaxInfo({
            url: '/attend/approve/apply',
            data: data,
            method: 'post',
            tip: false,
            weui : true,
            fn: function(data) {
                //isSubmit = 0;


                function goUrl(){
                    successId = data.data.id;
                    location.href = "/attend/approve/applyprocess?applyid=" + successId;
                };

                /*YI.alert({
                    msg : data.errmsg,
                    yFn : function(){
                        goUrl();
                    }
                });*/
               
                $.toast("申请成功");

                setTimeout(function(){
                    goUrl();
                },2000);    


            },
            erro: function(data) {
                isSubmit = 0;
                /*YI.alert({
                    msg: data.errmsg
                });*/
                $.alert(data.errmsg);
            }
        });





        /*$.ajax({    
            type:'post',        
            url:'/attend/approve/apply',    
            data:data,    
            dataType:'json', 
            cache:false,        
            success:function(data){
              isSubmit = 0;
              //check.hideLoading();
               

              YI.alert({
                  msg : data.message
              });




              if(data.errno ==0){
                
              }
              
              
            }    
          });*/
    })

    $(".arrivie").on('click', function() {
        $(".arrivie").not(this).removeAttr("checked");
        $(this).attr("checked", "checked");
    })
    $(".approviList").on('click', function() {
        $(".approveIdList").html('<span class="on_name">@' + $(this).find("a").html() + '</span>');
        $(".approveId").attr('approveid', $(this).attr('approveid'));
        $(this).parent('ul').css("display", 'none');
        return false;
    })

    $(".showApprove").on('click', function() {
        if ($(this).parents("#list").find(".approviList").length > 0) {
            var _thisList = $(this).parents("#list").find(".approviList").parents(".list");
        } else var _thisList = $(this).parents("#list").find(".leaveType").parents(".list");

        if (_thisList.css("display") == "block") _thisList.css("display", "none");
        else _thisList.css("display", "block");
    })
    $(".approviList").on('click', function(event) {
        $(".approveIdList").html('<span class="on_name">@' + $(this).html() + '</span>');
        $(".approveId").attr('approveid', $(this).attr('approveid'));
        $(this).parents('.list').css("display", 'none');
        evt = event ? event : window.event;
        event.isPropagationStopped();
    })







    function setDate() {

        var currYear = (new Date()).getFullYear();
        var opt = {};
        opt.date = {
            preset: 'date'
        };
        opt.datetime = {
            preset: 'datetime'
        };
        opt.time = {
            preset: 'time'
        };
        opt['default'] = {
            theme: 'android-ics light',
            display: 'modal',
            mode: 'scroller',
            dateFormat: 'yyyy-mm-dd',
            lang: 'zh',
            showNow: true,
            nowText: "今天",
            startYear: currYear - 24,
            endYear: currYear + 50
        };
        $("#appDate1").mobiscroll($.extend(opt['datetime'], opt['default']));
        $("#appDate2").mobiscroll($.extend(opt['datetime'], opt['default']));
    };
    // function closeConfirm(){
    //     wx.closeWindow();
    //     //history.back();
    // };
    /*时间插件回调
    // window.setDateCallback = function(){
    //     $('#appDate2').attr("data-min",$('#appDate1').attr("data-link"))
    //     window.mobiScrollInit();
    // }*/



    //时间插件回调
    var $appDate1 = $('#appDate1'),
        $appDate2 = $('#appDate2'),
        $qjDiff = $('#qjDiff');
    window.setDateCallback = function() {
        $appDate2.attr("data-min", $appDate1.attr("data-link"));
        window.mobiScrollInit();
        renderDateDiff();
    };

    window.setEndDateCallback = function() {
        $appDate1.attr("data-max", $appDate2.attr("data-link"));
        window.mobiScrollInit();
        renderDateDiff();
    };


    function renderDateDiff() {
        if (!type_id) {
            return false;
        };

        if ($appDate1.attr('data-val') && $appDate2.attr('data-val')) {
            //console.info($appDate1.attr('data-val') , $appDate2.attr('data-val'));
            $.ajax({
                type: 'post',
                url: '/attend/approve/applycomputed',
                data: {
                    'type_id': type_id,
                    'start_time': $appDate1.attr('data-val'),
                    'end_time': $appDate2.attr('data-val')
                },
                dataType: 'json',
                cache: false,
                success: function(data) {
                    //console.info(data);
                    $qjDiff.find('span').html( data.data.data );
                    $qjDiff.css('display','block').unbind('click').on('click',function(){
                	$(this).children('section').html(data.data.explain).toggle();
                });
                }
            });





            var $recordList = $('#recordList');
            $.ajax({
                type: 'post',
                url: '/attend/approve/applychecklog',
                data: {
                    'apply_id': '',
                    'start_time': $appDate1.attr('data-val'),
                    'end_time': $appDate2.attr('data-val')
                },
                dataType: 'json',
                cache: false,
                success: function(data) {
                    //console.info(1212,data);
                    //$qjDiff.find('span').html(data.data.data);
                    //$qjDiff.show();
                    var data = data.data.data,
                        $ul = $recordList.children('ul');


                    //data = ['打卡时间：2016-01-15 08:00','打卡时间：2016-01-15 08:00','打卡时间：2016-01-15 08:00','打卡时间：2016-01-15 08:00','打卡时间：2016-01-15 08:00']
                    if(data.length>0){
                        var _html = '';
                        $.each(data,function(i,e){
                           _html += '<li>'+e+'</li>';
                        });
                        $ul.html(_html);
                    }else{
                        $ul.html('<li>无</li>');
                    };
                    $recordList.show();




                    
                }
            });



            
        };


    };


});
