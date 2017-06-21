(function($) {

    if ($(".leaveType").length == 0 && type == 1) {
        //没有请假类型
        /*YI.alert({
            msg: '请联系管理员添加请假类型'
        });*/
        $.alert('请联系管理员添加请假类型');
    };

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
    });

    //提交审批
    $(".approveSubmit").unbind('click').on('click', function() {
        leaveSubmit();
    });

    var $qjDetailText = $('#qjDetailText');

    $(".selectLeaveType").on('change', function(event) {
        var typeIdIndex = $(".selectLeaveType option:selected").index();
        type_id = $(".selectLeaveType option").eq(typeIdIndex).attr("type_id");
        typeName = $(".selectLeaveType option").eq(typeIdIndex).html();
        check_pic = $(".selectLeaveType option").eq(typeIdIndex).attr("check_pic");
        check_pic_message = $(".selectLeaveType option").eq(typeIdIndex).attr("check_pic_message");
        var minType = $(".selectLeaveType option").eq(typeIdIndex).attr("unit");
        if (minType >= 480) setDate('');
        else setDate('datetime');


        var _val = $.trim($(this).val());
        //console.info(_val);
        $.each($(this).children('option'), function(i, e) {
            if (_val == $.trim($(e).attr('type_id'))) {
                var desc = $.trim($(e).data('desc'));
                if (desc) {
                    $qjDetailText.html(desc).show();
                    $qjDetailText.parent().show();
                } else {
                    $qjDetailText.html('').hide();
                    $qjDetailText.parent().hide();
                };
                return false;
            };

        });

        $(".shuo").hide();
        leaveTypeInfo = {
            "type_id": type_id
        };
        // $(".leaveBox").attr(leaveTypeInfo).html(title);
        // $(".list").eq(0).css("display","none")
        //获取请假的剩余天数
        isSend = 1;
        //check.showLoading(myLoad);
        $.ajax({
            type: 'post',
            url: '/attend/approve/approvestafflist',
            data: leaveTypeInfo,
            dataType: 'json',
            cache: false,
            success: function(data) {
                //check.hideLoading();
                allow_select = data.allow_select;
                cc_staff_ids = data.cc_staff_ids;
                var staffList = data.staff_list;
                var staff_ids = '';
                for (var i = 0; i < staffList.length; i++) {
                    if (staff_ids != '') staff_ids += ",";
                    staff_ids += staffList[i].id;
                }
                $(".approveStaff").attr("data-select", data.allow_select);
                $(".approveStaff").attr("data-ids", staff_ids);
                $(".copyStaff").attr("data-ids", data.cc_staff_ids);

                //重新初始化审批人选择
                window.selectUserInit();
                if (data.day != undefined) {
                    $(".shuo").find("span").eq(0).html(typeName);
                    $(".shuo").find("span").eq(1).html(data.day);
                    $(".shuo").find("span").eq(2).html(data.hour);
                    $(".shuo_message").css("display", 'block');
                } else {
                    $(".shuo_message").css("display", 'none');
                }
            }
        });
        $(this).parent('ul').css("display", 'none');
        //evt = event ? event : window.event;
        //event.isPropagationStopped();
    });


    $(".mui-collapse a").on('click', function() {
        $(this).parent("li").find('ul').css('display', 'block');
    })

    $(".showApprove").on('click', function() {
        if ($(this).parents("#list").find(".approviList").length > 0) {
            var _thisList = $(this).parents("#list").find(".approviList").parents(".list");
        } else var _thisList = $(this).parents("#list").find(".leaveType").parents(".list");

        if (_thisList.css("display") == "block") _thisList.css("display", "none");
        else _thisList.css("display", "block");
    })

    $(".approviList").live('click', function(event) {
        $(".approveIdList").html('<span class="on_name">@' + $(this).html() + '</span>');
        $(".approveId").attr('approveid', $(this).attr('approveid'));
        $(this).parents('.list').css("display", 'none');
        evt = event ? event : window.event;
        event.isPropagationStopped();
    });


    $(".dialogShow").on('click', function() {
        /*YI.alert({
            msg: message.content
        });*/
        $.alert(message.content);
    });


})(Zepto)


function setDate(datetime) {
    if (datetime == '') {
        $('#appDate1,#appDate2').removeClass("datetime-select").addClass("date-select");
    } else {
        $('#appDate1,#appDate2').removeClass("date-select").addClass("datetime-select");
    }
    $('#appDate1,#appDate2').val("").data("val", "");
    window.mobiScrollInit();
};


//时间插件回调
var $appDate1 = $('#appDate1'),
    $appDate2 = $('#appDate2'),
    $qjDiff = $('#qjDiff');
window.setDateCallback = function() {
    $appDate2.attr("data-min", $appDate1.attr("data-link"));
    window.mobiScrollInit();
    renderDateDiff();
}
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
                // console.info(data);
                $qjDiff.find('span').html(data.data.data);
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
                	$ul  = $recordList.children('ul');


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


}


var isSubmit = submit = 0;
var leaveTypeInfo = "";

function leaveCancel() {
    isSubmit = 0;
};


var $appDate1 = $("#appDate1"),
    $appDate2 = $('#appDate2'),
    $serverids = $("#serverids");

function leaveSubmit() {
    var approveId = $("input[name=approveid]").val();
    var reason = $(".reason").val();
    var copyIds = $("input[name=copy]").val();
    var startTime = $appDate1.attr("data-val")
    var endTime = $appDate2.attr("data-val")
    var leaveId = type_id;
    cc_staff_ids = $("input[name=copy]").val();
    var picstrs = $serverids.val();
    var data = {
        'isSubmit': isSubmit,
        'type': type,
        'approveId': approveId,
        'type_id': leaveId,
        'reason': reason,
        'startTime': startTime,
        'endTime': endTime,
        'copyIds': copyIds,
        'checkmodel': checkmodel,
        'ccIds': cc_staff_ids,
        'picstrs': picstrs,
        'level':level
    };
    if (type == 1) {
        var newdata = $.extend({}, leaveTypeInfo, data);
        //console.log(leaveTypeInfo);
    } else {
        var newdata = data;
    }
    //console.log(newdata);




    /*if(reason == "" || startTime =="" || endTime == "" || approveId == "" || ( leaveId == "")){
        YI.alert({
            msg : msgText
        });
        return false;
    };*/


    if (leaveId == "") {
        /*YI.alert({
            msg: '请选择请假类型'
        });*/
        $.alert('请选择请假类型');
        return false;
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
    if (reason == "") {
        /*YI.alert({
            msg: '请填写说明'
        });*/
        $.alert('请填写说明');
        return false;
    };
    // if (approveId == "") {
    //     /*YI.alert({
    //         msg: '请选择审批人'
    //     });*/
    //     $.alert('请选择审批人');
    //     return false;
    // };



    if ((check_pic == 1 && picstrs == "")) {
        /*YI.alert({
            msg: check_pic_message
        });*/
        $.alert(check_pic_message);
        return false;
    };




    if (submit == 1) return false;
    submit = 1;


    YI.getAjaxInfo({
        url: '/attend/approve/apply',
        data: newdata,
        method: 'post',
        tip: false,
        weui : true,
        fn: function(data) {
            //submit = 0;
            isSubmit = 0;



            function goUrl(){
                location.href = "/attend/approve/applyprocess?applyid=" + data.data.id;
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
            submit = 0;
            /*YI.alert({
                msg: data.errmsg
            });*/
            $.alert(data.errmsg);
        }
    });


};
