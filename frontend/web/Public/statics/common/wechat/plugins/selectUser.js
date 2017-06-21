(function($){
	
    $(function() {
    	window.__appstoreid = 0; 
        //console.info(__appstoreid); 
        var selectUser = function(){
    		var $oneMore = $('.oneMore'),
    			$getOne = $('.getOne'),
    			$getMore = $('.getMore'),
    //			$ul = $oneMore.find('ul'),
    //			ids = $.trim($ul.data('ids')),
    //			_select = $.trim($ul.data('select')),
    			//aid = $.trim($ul.data('aid')),
    			_WH = 0,
    			Yi = {
    			init : function() {
    				_WH = this.windowHeight();
    				this.bindEvent();
    				var _this = this;
    				//回调，重新渲染列表
    				window.renderContactList = function(o){
    					//console.info(o);
    					var html = '';
    					$.each($('.plug-select'),function(i,e){
    						if($(e).hasClass('selected')){
    							
    							var $ul = $(e).children('section').find('ul');
    							//console.info(e)
    							if( $(e).hasClass('oneMore') || $(e).hasClass('getOne') ){
    								$.each(o,function(m,n){
    									var status = false;
    									html += '<li data-uid="'+n.uid+'"><img src="'+n.src+'" /><ins>'+n.name+'</ins><em></em></li>';	
    								});
    								var add = $ul.find('li.add').clone(true);
    								$ul.html(html).append(add);
    								$(e).find('input[type="hidden"]').val($(e).find('li').eq(0).data('uid'));
    							}else if($(e).hasClass('getMore')){
    								
                                    
    								$.each(o,function(m,n){
    									var status = false;
    									$.each($ul.children('li'),function(a,b){
    										if($(b).data('uid')==n.uid){
    											status = true;
    										};
    									});
    									if(!status){
    										html += '<li data-uid="'+n.uid+'"><img src="'+n.src+'" /><ins>'+n.name+'</ins><em></em><b></b><h6></h6></li>';
    									};
    									
    								});
    								
    								
    								var add    = $ul.find('li.add').clone(true);
    								var reduce = $ul.find('li.reduce').clone(true);
    								$ul.find('li.add,li.reduce').remove();
    								$ul.append(html).append(add).append(reduce);
                                    
    								_this.renderSendData();
                                    _this.renderNumber($ul[0]);
                                    _this.bxSelectPlug();
    							};						
    							return false;
    						};
    					});
    					//_this.openFrame();
    					//_this.sendInit();
    				}
    				
    			},
    			
    			
    			//多人时候，删除的重新渲染值
    			renderSendData : function(){
                    $.each($getMore,function(m,n){
                        var $lis = $(n).find('li').not('.add,.reduce'),
                        arr = [];
                        $.each($lis,function(i,e){
                            arr.push($(e).data('uid'));
                        });

                        $(n).find('input[type="hidden"]').val(arr.join(','));
                    });
    				
    			},

                bxSelectPlug : function(){
                    var $bxPlugSelect = $('.bx-plug-select');
                    //alert($bxPlugSelect.length);
                    $.each($bxPlugSelect,function(i,e){
                        var index = $(e).find('.add').index();
                        $(e).find('li').children('h6').show();
                        $(e).find('li').not('.add,.reduce').css('margin-right','20px');
                        $(e).find('li').eq(index-1).css('margin-right','5px');
                        $(e).find('li').eq(index-1).children('h6').hide();
                    });
                },
    			
    			sendInit:function(){
    				var _this = this,
    					emStatus = false;

                    //报销模块样式
                    this.bxSelectPlug(); 

    				//抄送人初始化赋值
    				this.renderSendData();
    				
    				//抄送人(减人)
    				$getMore.delegate('li.reduce','click',function(){

    					var _em = $(this).siblings().children('em');
    					
    					if($.trim($(this).data('reduce'))=="true"){   
    						var $lis = $(this).parents('ul').find('li').not('.add,.reduce');
    						$.each($lis,function(i,e){
    							if(!$(e).children('em').length){
    								$(e).append('<em></em>');
    							};
    						});
    					};

    					
    				//$getMore.find('li.reduce').unbind('click').on('click',function(){
    					if(!emStatus){
    						emStatus = true;
    						_em.css({'display':'block!important'});
    					}else{
    						emStatus = false;
    						_em.css({'display':'none!important'});
    					};
    				});
    				$getMore.delegate('li > em','click',function(){
    				//$getMore.find('li > em').unbind('click').on('click',function(){
                        var ul = $(this).parents('ul')[0];
    					$(this).parents('li').remove();
    					_this.renderSendData();
                        _this.renderNumber(ul);
                        _this.bxSelectPlug();
    				});	
    			},


                renderNumber : function(ul){
                    var $lis = $(ul).find('li').not('.add,.reduce');
                    $.each($lis,function(i,e){
                        $(e).children('b').html(i+1);
                    });
                },
    			
    			windowHeight : function(){
    				return $('window').height();
    			},
    			
    			openSelectLayer : function(){
    				
    				$('.plug-select').delegate('li.add','click',function(){		


                        window.__appstoreid = $(this).parents('ul').data('appstoreid');
                        //console.log( window.__appstoreid)

    				//$('.plug-select li.add').unbind('click').on('click',function(){
    					var $selectContainer = $('#selectContainer');
    					$(this).parents('.plug-select').addClass('selected').siblings().removeClass('selected');
    					
    					//$('body').addClass('hideStyle')
    					//alert($('body').height())
    					$selectContainer.css({
    						'min-height':$(document).height()+'px'
    					}).show();
    					window._scrollTopVal = $(window).scrollTop();
    					$(window).scrollTop(0);
    					
    					window.YiSelect.init($.trim($(this).children('a').data('type')));	
    				});
    			},
    			
    			//是否可选
    			/*isSelect : function(){
    				//是否可选
    				if(_select){
    					$ul.append('<li class="add"><span></span><a href="/address/staff/select?type=0&aid='+aid+'"></a></li>');
    					//this.openFrame();
    				};
    				
    			},*/
    			
    			bindEvent : function(){
    				var _this = this;
    				$.each($oneMore.find('ul'),function(i,e){
    					var	ids = $.trim($(e).data('ids'));
    					if(!ids){
    						$(e).attr('data-select',1);
    					};
    				});
    				$.each($oneMore.find('ul'),function(i,e){
    					var	ids = $.trim($(e).data('ids')),
    						_select = $.trim($(e).data('select'));
    						//console.info(ids,_select);
    
    					if(ids){
    						$.ajax({
    							url : '/address/staff/getstaffbyid',
    							type : 'get',
    							data : {
    							    aid : _aid,
    							    id : ids
    							},
    							dataType : 'json',
    							success : function(data){
    								//console.info(data)
    								if(data.staff.length){
    									var html = '';
    									$.each(data.staff,function(i,e){
    										html += '<li data-uid="'+e.id+'"><img src="'+(e.we_avatar?e.we_avatar:'')+'" /><ins>'+e.we_name+'</ins><i></i></li>'
    									});
    									$(e).html(html);


                                        //审批人赋值(多个选一个)
                                        var $li = $oneMore.children('section').find('li');
                                        $li.on('click',function(){
                                            if($(this).hasClass('select')){
                                                $(this).removeClass('select'); 
                                                $(this).parents('article.plug-select').find('input').val('');
                                            }else{
                                                var uid = $(this).data('uid');
                                                $(this).addClass('select').siblings().removeClass('select');
                                                $(this).parents('article.plug-select').find('input').val(uid);
                                            };
                                        });
                                        $.each($li,function(i,e){
                                            if($(e).hasClass('select')){
                                                $(e).parents('article.plug-select').find('input').val($(e).data('uid'));
                                            };
                                        });
                                        if(_select==1){
                                            $(e).append('<li class="add"><span></span><a href="javascript:void(0)"></a></li>');
                                        };      


    								}else{
                                        if(!$(e).find('li.add').length){
                                            $(e).append('<li class="add"><span></span><a href="javascript:void(0)"></a></li>');
                                        };
                                    };
    								
    										
    							}
    						});	
    					}else{
    						if(_select==1){
    						    if(!$(e).find('li.add').length){
    						        $(e).append('<li class="add"><span></span><a href="javascript:void(0)"></a></li>');
    						    };
    							
    						};
    						
    					};
    						
    						
    						
    				});
    				
    
    				
    				
    				//抄送人  ajax请求数据不可删除
    				this.sendNoDelete();
    				
    				
    				//审批人赋值(只有一个)
    				//$getOne.find('input[type="hidden"]').val($getOne.find('li').eq(0).data('uid'));
    				
    				//抄送人 默认没有人
    				this.sendInit();
    				
    				
    				//点击添加人选择跳转
    				this.openSelectLayer();
    				
    			},
    			sendNoDelete : function(){
                    var _this = this,
                        _ul = $('.getMore').find('ul'),
                        len = _ul.length;
                    $.each(_ul,function(i,e){
                        var _ids = $.trim($(e).data('ids')),
                            aid = $.trim($(e).data('aid'));
                        if($(e).data('delete')!="1"){ //不可删除默认值
                            if(_ids){
                                $.ajax({
                                    url : '/address/staff/getstaffbyid',
                                    type : 'get',
                                    data : {
                                        aid : aid || _aid,
                                        id : _ids
                                    },
                                    dataType : 'json',
                                    success : function(data){
                                        //console.info(data)
                                        if(data.staff.length){
                                            var html = '';
                                            $.each(data.staff,function(i,e){
                                                //html += '<li data-uid="'+e.id+'"><img src="'+(e.we_avatar?e.we_avatar:'')+'" /><ins>'+e.we_name+'</ins><i></i></li>'
                                                html += '<li class="no-delete" data-uid="'+e.id+'"><img src="'+(e.we_avatar?e.we_avatar:'')+'"><ins>'+e.we_name+'</ins></li>';
                                            });
                                            var add    = $(e).find('li.add').clone(true);
                                            var reduce = $(e).find('li.reduce').clone(true);
                                            $(e).find('li.add,li.reduce').remove();
                                            $(e).html(html).append(add).append(reduce);
                                        };
                                        
                                        if((i+1)==len){//全部渲染完成后再绑定事件
                                            //console.info(i,i+1,len)
                                            _this.sendInit();
                                        };
                                                    
                                    }
                                }); 
                            };
                        }else{//可删除默认值
                            if(_ids){
                                $.ajax({
                                    url : '/address/staff/getstaffbyid',
                                    type : 'get',
                                    data : {
                                        aid : aid || _aid,
                                        id : _ids
                                    },
                                    dataType : 'json',
                                    success : function(data){
                                        //console.info(data)
                                        if(data.staff.length){
                                            var html = '';
                                            $.each(data.staff,function(i,e){
                                                //html += '<li data-uid="'+e.id+'"><img src="'+(e.we_avatar?e.we_avatar:'')+'" /><ins>'+e.we_name+'</ins><i></i></li>'
                                                html += '<li data-uid="'+e.id+'"><img src="'+(e.we_avatar?e.we_avatar:'')+'"><ins>'+e.we_name+'</ins><em></em><b>'+(i+1)+'</b><h6></h6></li>';
                                            });
                                            var add    = $(e).find('li.add').clone(true);
                                            var reduce = $(e).find('li.reduce').clone(true);
                                            $(e).find('li.add,li.reduce').remove();
                                            $(e).html(html).append(add).append(reduce);
                                        };
                                        
                                        if((i+1)==len){//全部渲染完成后再绑定事件
                                            //console.info(i,i+1,len)
                                            _this.sendInit();
                                        };
                                                    
                                    }
                                }); 
                            };
                        }

                    });	

    			}
    		};
    	
    		Yi.init();
    	};	
    	
    	selectUser();
    	window.selectUserInit = selectUser;
    		
    });
})(Zepto);
	