/*var testdata = {"data":{"staffList":{"11039":{"id":"11039","we_name":"\u4f55\u91d1\u7389","we_avatar":"http:\/\/shp.qpic.cn\/bizmp\/HmtU0JT3A9hXjCJV2b9o4gKECtGnb8seQ6w6iaVSOFBSvXGVNDd6pXw\/"}},"leaveList":{"1086":{"id":"1086","title":"\u5f02\u5e38s"},"1087":{"id":"1087","title":"\u51fa\u5dee"},"1088":{"id":"1088","title":"\u5916\u51fa"},"1089":{"id":"1089","title":"\u52a0\u73ed"},"1090":{"id":"1090","title":"\u8c03\u4f11"},"1091":{"id":"1091","title":"\u4e8b\u5047"},"1092":{"id":"1092","title":"\u75c5\u5047"},"1093":{"id":"1093","title":"\u5e74\u5047"},"1094":{"id":"1094","title":"\u5a5a\u5047"},"1095":{"id":"1095","title":"\u4e27\u5047"},"1096":{"id":"1096","title":"\u4ea7\u5047"},"1130":{"id":"1130","title":"\u5de5\u4f5c\u65e5"},"1131":{"id":"1131","title":"asdf"},"1132":{"id":"1132","title":"1212"},"1133":{"id":"1133","title":"1212"},"1134":{"id":"1134","title":"1212"},"1135":{"id":"1135","title":"1212"},"1136":{"id":"1136","title":"13"},"1137":{"id":"1137","title":"\u6d4b\u8bd5\u8003\u52e4\u7c7b\u522b"},"1138":{"id":"1138","title":"\u6d4b\u8bd5"},"1139":{"id":"1139","title":"\u6d4b\u8bd5\u8003\u52e4\u7c7b\u522b"},"1140":{"id":"1140","title":"123445"},"1141":{"id":"1141","title":"\u5047\u671f\u7684\u6d4b\u8bd5"},"1153":{"id":"1153","title":"233"},"1159":{"id":"1159","title":"\u75c5\u5047"},"1160":{"id":"1160","title":"\u4e8b\u50472"},"1172":{"id":"1172","title":"bingjia"},"1173":{"id":"1173","title":"\u6d4b\u8bd5\u81ea\u5b9a\u4e49\u5047\u671f"}},"approveList":[{"id":"172","we_account_id":"100100209","staff_id":"11039","type_id":"53","approve_type_id":"1093","start_time":"1451990880","end_time":"1451991000","duration":"2","verify_duration":"0","reason":"ghdggf","config":"","status":"0","create_time":"2016-01-05 18:49","flow_id":"0","pic_strs":"","repeat":0},{"id":"160","we_account_id":"100100209","staff_id":"11039","type_id":"2","approve_type_id":"1086","start_time":"0","end_time":"0","duration":"0","verify_duration":"0","reason":"\u5373\u4f7f\u662f\u7ee7\u7eed\u7ee7\u7eed\u7ee7\u7eed\u7ea0\u7ed3","config":"{\"date\":\"2016\\u5e7401\\u670805\\u65e5\\u5468\\u4e8c 15:54 \\u4e0b\\u5348\",\"arrive\":\"0100\"}","status":"0","create_time":"2016-01-05 15:55","flow_id":"0","pic_strs":"","repeat":0},{"id":"159","we_account_id":"100100209","staff_id":"11039","type_id":"2","approve_type_id":"1086","start_time":"0","end_time":"0","duration":"0","verify_duration":"0","reason":"asfdasfdasfdsafds","config":"{\"date\":\"2016\\u5e7401\\u670805\\u65e5\\u5468\\u4e8c 15:49 \\u4e0b\\u5348\",\"arrive\":\"0100\"}","status":"0","create_time":"2016-01-05 15:49","flow_id":"0","pic_strs":"","repeat":0},{"id":"158","we_account_id":"100100209","staff_id":"11039","type_id":"2","approve_type_id":"1086","start_time":"0","end_time":"0","duration":"0","verify_duration":"0","reason":"\u597d\u8bfb\u540e\u611f","config":"{\"date\":\"2016\\u5e7401\\u670805\\u65e5\\u5468\\u4e8c 15:03 \\u4e0b\\u5348\",\"arrive\":\"0001\"}","status":"0","create_time":"2016-01-05 15:03","flow_id":"0","pic_strs":"","repeat":0},{"id":"157","we_account_id":"100100209","staff_id":"11039","type_id":"2","approve_type_id":"1086","start_time":"0","end_time":"0","duration":"0","verify_duration":"0","reason":"\u5bf9\u9ad8\u8017\u80fd\u7684\u80a1\u4efd","config":"{\"date\":\"2016\\u5e7401\\u670805\\u65e5\\u5468\\u4e8c 15:02 \\u4e0b\\u5348\",\"arrive\":\"0100\"}","status":"0","create_time":"2016-01-05 15:02","flow_id":"0","pic_strs":"","repeat":0},{"id":"149","we_account_id":"100100209","staff_id":"11039","type_id":"1139","approve_type_id":"1139","start_time":"1451809560","end_time":"1451895960","duration":"331","verify_duration":"0","reason":"asdfasdf","config":"","status":"0","create_time":"2016-01-03 16:27","flow_id":"0","pic_strs":"","repeat":0},{"id":"144","we_account_id":"100100209","staff_id":"11039","type_id":"2","approve_type_id":"1086","start_time":"0","end_time":"0","duration":"0","verify_duration":"0","reason":"ngfdgfhdhgfhf","config":"{\"date\":\"2016-1-3 5:34\",\"arrive\":\"1111\"}","status":"0","create_time":"2016-01-03 09:34","flow_id":"0","pic_strs":"","repeat":0}]},"errno":0,"errmsg":""};*/




$(function() {

    
    var $approveIscroll = $('#approveIscroll'),
        $ul = $approveIscroll.find('.tab-ul ul'),
        $input = $('#approveText').children('input'),
        $scrollUL = $('#scrollContent').find('ul'),
        $searchBtn = $('#approveText').children('em'),
        $input = $('#approveText').children('input'),
        islocalStorage = true,
        parObj = {
            status : '',
            page : 1,
            isme : YI.getUrlParam()['isme'],
            keyword : ''
        },
        approveObj = {
            init: function() {
            	this.bindEvent();
            },
            firstPage : 1,
			ajaxInfo : function(insertType){
				//console.info(this.page);
				var _this = this;
			    YI.getAjaxInfo({
			    	url: "/attend/approve/list",
			    	data: parObj,
			    	isLoading: false,
			    	fn : function(data){
			    		var data = data.data,
			    			list = data.approveList,
                            staffList = data.staffList,
                            leaveList = data.leaveList,
                            html = '';
            
			    		//console.info(data,list,staffList,leaveList);
			    		
			    		$.each(list,function(i,e){
			    		    //console.info(e,leaveList)
			    		    var title = leaveList[e.approve_type_id]?leaveList[e.approve_type_id]['title']:'未知申请',
			    		        _id = e.id,
			    		        _herf = (parObj.isme==1 ||parObj.status==-1)?("/attend/approve/view?applyid="+_id):("/attend/approve/approve?applyid="+_id);

			    		    if(parObj.isme==0){
                                var process = '';
                                // if(e.process == 1){
                                //     process = '<span style="color: #00aa00;float: none;padding-right: 0;display: inline-block;">（同意）</span>';
                                // }else if(e.process == 2) {
                                //     process = '<span style="color: #EF404A;float: none;padding-right: 0;display: inline-block;">（驳回）</span>';
                                // }
								if(e.process == ignoreStatus){
                                    process = '<span style="color: #00aa00;float: none;padding-right: 0;display: inline-block;">（转批）</span>';
								}
			    		        var _img = staffList[e['staff_id']].we_avatar?staffList[e['staff_id']].we_avatar:(__ASSETS__+"images/picm.jpg"),
			    		        _str = e.repeat?"block":'';			    		        
                                html += '<li class="has-img">'+
                                    '<a href="'+_herf+'">'+
                                        '<dl>'+
                                            '<dt><img src="'+_img+'" /></dt>'+
                                            '<dd>'+staffList[e['staff_id']].we_name+'提交的'+title+process+'</dd>'+
                                            '<dd>'+(e['status']==0?status1:status2)+'<span class="'+_str+'">抄送给我</span></dd>'+
                                            '<dd>'+e['create_time']+'</dd>'+
                                        '</dl>'+
                                   '</a>'+
                                   '<ins></ins>'+
                                '</li>';
                            }else{
                                //待开发 ==1
                                html += '<li class="no-img">'+
                                    '<a href="'+_herf+'">'+
                                        '<dl>'+
                                            '<dt>'+title+'</dt>'+
                                            '<dd>'+e['create_time']+'</dd>'+
                                        '</dl>'+
                                   '</a>'+
                                   '<ins></ins>'+
                                '</li>';      
                                
                            };
			    		});

						var $li = null;
			    		if(!html && !$.trim($scrollUL.html())){
			    		    $scrollUL.html('<li class="no-data">暂无数据</li>');
			    		    $li = $scrollUL.find('li:last');
			    		}else if(!html && $.trim($scrollUL.html()) && $.trim($scrollUL.text())=="暂无数据"){
			    		    $scrollUL.html('<li class="no-data">暂无数据</li>');
			    		    $li = $scrollUL.find('li:last');
			    		}else{
			    		    if(insertType=='append'){
				    			$li = $scrollUL.find('li:last');
				    			$scrollUL[insertType](html); 
				    		}else{
				    			$scrollUL[insertType](html); 
				    		};
			    		};
			    		

						if (myScrollPlug) {
		    		    	if(parObj.page>1){	    		    		
		    		    		//console.info(1212);
		    		    		/*$li.on('customClick',function(){
		    		    			myScrollPlug.scrollToElement(this, 0,null, null);
		    		    		});*/
								var $liHeight = $li.position().top,
									$wrapperHeight = $('#wrapper').height(),
									$pullDownHeight = $('#pullDown').height();
		    		    		setTimeout(function(){
		    		    			//$li.trigger('customClick');
		    		    			//myScrollPlug.scrollToElement($li[0],0,null,null,true);
		    		    			if($liHeight-($wrapperHeight+$pullDownHeight)>0){
		    		    				myScrollPlug.scrollTo(0,-($liHeight-$wrapperHeight+$li.height()+$pullDownHeight), null, true)
		    		    			};
		    		    			//console.info($li.position().top,$('#wrapper').height());
		    		    		},50);	
		    		    	};
                       		myScrollPlug.refresh();
                   		};

			    		
			    	}
			    });
			},

			bindEvent : function(){
	    		/*$scrollUL.delegate('li','click',function(){
	    			var id = $(this).data("itemid"),
                        isme = parObj.isme;
                    if(isme == 1){
                        $(this).children('a').attr('href',"/attend/approve/view?applyid="+id);
                    }else{ 
                        $(this).children('a').attr('href',"/attend/approve/approve?applyid="+id);
                    };
	    		});*/

	    		$searchBtn.on('click',function(){
                    parObj.keyword = $.trim($input.val());
                    //console.info(parObj);
                    $scrollUL.html('');
                    _this.ajaxInfo('html');
                });
	    		
	    		
	    		
	    		isme = YI.getUrlParam()['isme'];
                var _this = this,
                    waitli = [
                    '<li class="cur" data-status="0"><span>待处理</span></li>',
                    '<li data-status="1,2,3,4"><span>已处理</span></li>'
                ],
                    myli = [
                    '<li class="cur" data-status="0"><span>审批中</span></li>',  //isme=1
                    '<li data-status="1"><span>同意</span></li>',
                    '<li data-status="2"><span>驳回</span></li>'
                ];
                
                
				/*if(isme==0){
				    $ul.html(waitli.join(''));
				}else{  
                    $ul.html(myli.join(''));
				};*/
				
				$("[data-type='tab']").tab({
                    callBack : function(o,li,i){
                        parObj.status = $.trim($(li).data('status'));
                        parObj.page = 1;
                        $scrollUL.html('');
                        //console.info(parObj,i);
                        if(islocalStorage){
	                        if(parObj.isme==0){
	                        	localStorage.setItem('approvet1',i);
	                        }else{
								localStorage.setItem('approvet2',i);
	                        };
                    	};
                        _this.ajaxInfo('html');
                    }
                });

				var $lis = $('#approveUl li');
                if(parObj.isme==0 && YI.getUrlParam()['type']==1){
                    //localStorage.setItem('approvet1',1);
                    islocalStorage = false;
                    $lis.eq(1).trigger('click');
                    islocalStorage = true;
                }else{
                	if(localStorage.getItem('approvet1') || localStorage.getItem('approvet2')){
	                	if(parObj.isme==0){
		                    $lis.eq(localStorage.getItem('approvet1')).trigger('click');
		                }else{
		                	$lis.eq(localStorage.getItem('approvet2')).trigger('click');
		                };
	                }else{
						parObj.status = $.trim($approveIscroll.find('ul li').eq(0).data('status'));
						_this.ajaxInfo('append');
	                };
                };

                

                

                
				//$approveIscroll.find('ul li').eq(0).trigger('click');
				/*window.iScrollLoadMethod('.approveiscroll-content', function() {
                    //console.info('向下滑动回调函数');
                    //$scrollUL.html('<li class="loading-status">数据加载中，请稍后...</li>');
                    parObj.page = 1;
                    _this.ajaxInfo('html');
                }, function() {
                    //console.info('向上滑动回调函数');
                    parObj.page++;
                    _this.ajaxInfo('append');
                });*/
                
                window.pullDownAction = function() { //向下滑动回调函数  加载历史数据
					 parObj.page = 1;
                    _this.ajaxInfo('html');
	            };

	            window.pullUpAction = function() { //向上滑动回调函数  加载第一页数据
	                //$scrollerContent.html('<li class="loading-status">数据加载中，请稍后...</li>');
			        parObj.page++;
                    _this.ajaxInfo('append');
	            };
                
                
                
				
			}
        };



    	window.isScrollInit();
    	approveObj.init();

    	

    // document.body.onload = function(){
    // 	window.isScrollInit();
    // 	approveObj.init();
    // };

});