$(function($) {

    var $approveCancel = $('#approveCancel'),
        $approveSubmit = $('#approveSubmit'),
        $serverids = $("#serverids"),
        isSubmit = 0,
        $qjDiff = $('#qjDiff');

    window.getRemainCallback = function(input){
        var d = {
            'date' : $(input).data('val')
        };
        //console.info(d);
        YI.getAjaxInfo({
            url: '/attend/approve/applytimes',
            data: d,
            method: 'post',
            weui : true,
            fn : function(data){
                //console.info(data);
                var data = data.data;
                if(data){
                    $qjDiff.children('div').html('申请期间漏打卡申请额度剩余：'+data[1]+'次').end().css('display','block');
                }else{
                    $qjDiff.children('div').html('').end().css('display','none');
                };
            }
        });
    };

    //取消
    $approveCancel.on('click', function() {
        
        $.confirm(isCancel, function(){
            //点击确认后的回调函数
            window.close();
            wx.closeWindow();
        }, function() {
            //点击取消后的回调函数
        });

        
        /*YI.confirm({
            msg : isCancel,
            yFn : function(){
                window.close();
                wx.closeWindow();
            }
        });*/
    });

    
    //提交审批
    $approveSubmit.on('click', function() {

        var approveId = $("input[name=approveid]").val(),
            reason = $(".reason").val(),
            copyIds = $("input[name=copy]").val(),
            picstrs = $serverids.val(),
            _date = $(".approveDate").val(),
            arrive = "";


        $(".arrivie").each(function(k, v) {
            if ($(this).prop("checked") == true) {
                arrive += "1";
            } else {
                arrive += "0";
            }
        });

        //console.info(_date)
        if(_date == ""){ 
            //YI.alert({msg : '请选择时间'});
            $.alert('请选择时间');
            return false;
        };
        if(arrive == "0000"){ 
            //YI.alert({msg : '请选择异常类型'});
            $.alert('请选择异常类型');
            return false; 
        };
        if(reason == ""){ 
            //YI.alert({msg : '请填写异常说明'});
            $.alert('请填写异常说明');
            return false; 
        };
        // if(approveId == ""){
        //     //YI.alert({msg : '请选择审批人'});
        //     $.alert('请选择审批人');
        //     return false;
        // };

        if (check_pic == 1 && picstrs == "") {
            /*YI.alert({
                msg : check_pic_message
            });*/
            $.alert(check_pic_message);
            return false;
        };

        /*if (approveId == "" || reason == "" || arrive == "00" || _date == "") {
            YI.alert({msg : infoMsg});
            return false;
        };*/ 
        //阻止重复提交


        var data = {
            type_id: type_id,
            approveId: approveId,
            reason: reason,
            arrive: arrive,
            date: $(".approveDate").data('val'),
            copyIds: copyIds,
            type: 0,
            ccIds: cc_staff_ids,
            picstrs : picstrs,
            level:level
        };



        //check.showLoading(myLoad);

        //alert(isSubmit)

        if (isSubmit == 1){
            return false;
        };
        isSubmit = 1;
        
        YI.getAjaxInfo({
            url: '/wei/approve/apply',
            data: data,
            method: 'post',
            tip: false,
            weui : true,
            fn : function(data){
                //inSubmit = 0;
                /*check.hideLoading();
                var message = {
                    'content': data.message,
                    'yes': yesText
                };
                $.fn.dialog(message);
                */
                function goUrl(){
                    location.href = "/wei/approve/applyprocess?applyid=" + data.data.id;
                };

                /*YI.alert({
                    msg : data.errmsg,
                    yFn : function(){
                        goUrl();
                    }
                });*/
                $.toast("申请成功");
                /*$.alert(data.errmsg, function() {
                    //点击确认后的回调函数
                    goUrl();
                });*/
                

                setTimeout(function(){
                    goUrl();
                },2000);

            },
            erro : function(data){
                isSubmit = 0;
                /*YI.alert({
                    msg : data.errmsg
                });*/
               
                $.alert(data.errmsg);
            }
        });


        /*$.ajax({
            type: 'post',
            url: '/attend/approve/apply',
            data: data,
            dataType: 'json',
            cache: false,
            success: function(data) {
                inSubmit = 0;
                check.hideLoading();
                var message = {
                    'content': data.message,
                    'yes': yesText
                };
                $.fn.dialog(message);
                if (data.error == 1) {
                    location.href = "/attend/approve/applyprocess?applyid=" + data.id;
                }
            }
        });*/




    });


    wx.ready(function() {
        $(".mui-action-back").on("click", function() {
            wx.closeWindow();
        })
    })
});
