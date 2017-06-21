$(function() {
	if (typeof YI === "undefined") {
		YI = {};
	};
	YI.dialog = function(opts) {
		var defaults = {
			id: 'dialogWrap',
			appendTo: 'body',
			mask: true, //遮罩层
			opacity: '0.3', //遮罩层背景透明度
			overlayBg: "#000",
			overlayZindex: 9999,
			closeDom: '',
			html: '', //中心区HTML内容
			initFn: null,
			initDomFn:null,
			animate:"",
			//animate:"fadeInDown",
			callBack: null
		};
		var opts = $.extend(defaults, opts);
		this.id = opts.id;
		this.appendTo = opts.appendTo;
		this.mask = opts.mask;
		this.opacity = opts.opacity;
		this.overlayBg = opts.overlayBg;
		this.overlayZindex = opts.overlayZindex;
		this.closeDom = opts.closeDom;
		this.html = opts.html;
		this.callBack = opts.callBack;
		this.initFn = opts.initFn;
		this.initDomFn=opts.initDomFn;
		this.animate=opts.animate;
		//this.callBack = opts.callBack;  this.callBack为新生成的对象添加一个callBack函数，此时this.callBack的指针指向opts对象里面的opts.callBack函数,以下的检测代码
		//console.info(this.callBack === opts.callBack); //true 
		this.init();
	};
	YI.dialog.prototype = {
		init: function() {
			if(this.initDomFn)
				this.initDomFn();
			this.bulidDom();
		},
		//初始化DOM
		bulidDom: function() {
			var _this = this;
			//$('.dialog-mask,.dialog-wrap').remove();
			$("div.yi-dialog-mask,div.dialog-wrap").remove();
			//创建遮罩层
			if (this.mask) {
				var $dialogMask = $('<div class="yi-dialog-mask" id="dialogMask"></div>');
				$dialogMask.css({
					'height': '100%',
					'width': '100%',
					'background': this.overlayBg,
					'opacity': this.opacity,
					'z-index': this.overlayZindex
				});
				// .on('click',function(e){
				// 	e.stopPropagation();
				// 	_this.close();
				// });
				//console.info($dialogMask);
				$(this.appendTo).append($dialogMask);
				this.mask = $dialogMask;
				this.mask.on('click touchstart touchmove', function() {
					_this.close();
				})
			};
			var $dialogWrap = $('<div class="dialog-wrap animated fast '+this.animate+'" id="dialogWrap">' +
					'<div class="dialog-content"></div>' +
					'</div>'),
				$dialogContent = $dialogWrap.children('.dialog-content');
			$dialogContent.html(this.html);
			$(this.appendTo).append($dialogWrap);
			$dialogWrap.css({
				'height': $dialogContent.height(),
				'z-index': this.overlayZindex + 1000,
				'margin-top': -$dialogContent.height() / 2 - 50
			});
			this.content = $dialogWrap;
			//console.info($dialogContent.height());
		},
		show: function() {
			if (this.initFn)
				this.initFn.apply(this);
			if (this.mask)
				this.mask.css('display','block');
			this.content.css({
				'visibility': 'visible',
				'display': 'block'
			});
			if (this.callBack) {
				this.callBack.apply(this);
			};
		},
		close: function() {
			var _this = this;
			this.content.remove();
			_this.mask.css({
				'display':'none',
				'opacity': '0',
				'filter': 'Alpha(Opacity=0)'
			});
			//console.info(_this.mask,this.content)
			//setTimeout(function() {
				//alert(123)
				if (_this.mask) {
					_this.mask.remove();
				};
			//}, 100);

		}
	};
	YI.evalData = function(a) {
		if (typeof(a) == "object")
			return a;
		else {
			var list = null;
			try {
				list = $.parseJSON(a);
			} catch (e) {
				eval("list=" + a);
			}
			return list;
		}
	};

    YI.deepCopy=function(json) {
        if (typeof json == 'number' || typeof json == 'string' || typeof json == 'boolean') {
            return json;
        } else if (typeof json == 'object') {
            if (json instanceof Array) {
                var newArr = [],
                    i, len = json.length;
                for (i = 0; i < len; i++) {
                    newArr[i] = arguments.callee(json[i]);
                }
                return newArr;
            } else {
                var newObj = {};
                for (var name in json) {
                    newObj[name] = arguments.callee(json[name]);
                }
                return newObj; 
            }
        }
    };
    
	YI.createSelect = function(opts) {
		var html = [];
		if (opts.initFn)
			html = opts.initFn(html);
		for (var i in opts.data) {
			var row = opts.data[i];
			if (typeof opts.format == "function")
				html.push('<option value="' + row[opts.valField] + '">' + opts.format(row) + '</option>');
			else {
				html.push('<option value="' + row[opts.valField] + '">' + row[opts.format] + '</option>');
			}
		}
		opts.$obj.html(html.join("\n"));
		if (opts.doneFn) opts.doneFn(opts.$obj);
	};
	YI.getAjaxInfo = function(opts) {
		var defaults = {
			async: true,
			method: 'get',
			processData: true,
			erro: null,
			isLoading: true,
			verify: false,
			verifyOne: null,
			formObj: null,
			beforeSend: null,    
			tip: true,
			weui : false,
			showLoadingText : '拼命加载中...',
			contentType: 'application/x-www-form-urlencoded; charset=utf-8',
			data: ''
		};
		var isSubmit = true;
		var reSerialize = false;
		var opts = $.extend(defaults, opts);
		if (opts.verify) {
			var formObj;
			if (opts.verifyOne)
				formObj = opts.verifyOne;
			else {
				reSerialize = true;
				formObj = opts.formObj.find("input[data-minlength],input[data-pattern],input[data-required],select[data-required]");
			}
			formObj.each(function(i, e) {
				var tmp = $(e).val();
				var minlength = $(e).data("minlength");
				var title = $(e).data("title");
				var requiredMsg = $(e).data("requiredmsg") || $(e).attr("placeholder");
				var strReg = $(e).data("pattern");
				var required = $(e).data("required");
				if (required) {
					if ($(e).val() == "") {
						isSubmit = false;
						YI.tip(requiredMsg);
						return false;
					}
				}
				if (minlength)
					if (tmp.length < minlength) {
						isSubmit = false;
						YI.tip(title + "至少填写" + minlength + "位");
						return false;
					}

				if (strReg&&required) {
					var reg = new RegExp(strReg);
					if (!reg.test(tmp)) {
						isSubmit = false;
						YI.tip(title);
						return false;
					}
				}

			});
		}
		
		if (isSubmit) {
			if (opts.beforeSend) {
				opts.beforeSend();
				/*if (opts.verify && reSerialize)
					opts.data = opts.formObj.serialize();*/
			}
			//console.info(111,opts.data)
			$.ajax({
				accepts: "text/html",
				processData: opts.processData,
				contentType: opts.contentType,
				async: opts.async,
				cache: false,
				type: opts.method,
				beforeSend: function() {
				    if(opts.weui){
				        $.showLoading(opts.showLoadingText);
				    };
					if (opts.isLoading && !opts.weui){
						YI.loading();
					};
				},
				url: opts.url,
				data: opts.data,
				dataType: 'text',
				timeout: 10000,
				success: function(data, textStatus, R) {
				    if(opts.weui){
                        $.hideLoading();
                    };
					if (opts.isLoading && !opts.weui){
						$("div.yi-dialog-mask,div.dialog-wrap").remove();
					};
					data = YI.evalData(data);
					if (data.errno == "0"){
						opts.fn(data);
					}else {
						if (opts.tip){
							//YI.tip(data.errmsg);
							if(opts.weui){
                                $.alert(data.errmsg);
                            }else{
                                YI.alert({msg:data.errmsg});
                            };
						}
						if (opts.erro){
							opts.erro(data);
						}
					}
				},
				complete: function(textStatus) {
					/*if($.hideLoading){
						$.hideLoading(); 
					}*/
					if (textStatus.statusText == "timeout") {
						//YI.tip("链接超时");
						if(opts.weui){
                            $.alert('链接超时');
                        }else{
                            YI.alert({msg:"链接超时"});
                        };
					}
					if(textStatus.status==400||textStatus.status==404||textStatus.status==403||textStatus.status==500){
						//YI.tip("请求失败");
						if(opts.weui){
                            $.alert('请求失败');
                        }else{
                            YI.alert({msg:"请求失败"});
                        };
					};
				},
				error : function( data ){
				    if(opts.weui){
                        $.alert('请求失败，请检查您的网络设置');
                    }else{
                        YI.alert({msg:"请求失败，请检查您的网络设置"});
                    };
					
				}
			});
		}
	};
	YI.getLocation = function(fn, erroFn) {
		if (this.testGeolocation()) {
			navigator.geolocation.getCurrentPosition(function(h) {
				var j = h.coords.latitude;
				var i = h.coords.longitude;
				YI.getAjaxInfo({
					url: "/m/main/seekCity.json?location=" + j + "," + i,
					fn: function(res) {
						$.cookie("cityId", res.data.cityId, {
							path: "/"
						});
						$.cookie("cityName", res.data.cityName, {
							path: "/"
						});
						if (fn)
							fn(res);
					},
					tip: false,
					isLoading: false,
					erro: function(msg) {
						//alert("ajaxErro");
						if (erroFn) {
							var error = {};
							error.code = 0;
							error.PERMISSION_DENIED = 1;
							erroFn(error, msg);
						}
					}
				});
			}, function(error) {
				var str = "";
				switch (error.code) {
					case error.TIMEOUT:
						str = "连接超时";
						break;
					case error.PERMISSION_DENIED:
						str = "您拒绝了使用位置共享服务，查询已取消";
						break;
					case error.POSITION_UNAVAILABLE:
						str = "获取位置信息失败";
						break;
					default:
						str = "Gps定位失败";
				}
				if (erroFn)
					erroFn(error, str);
				//error.PERMISSION_DENIED

			}, {
				timeout: 5000
			});
		} else {
			YI.confirm({
				msg: "定位失败，请手动选择",
				yFn: function() {
					window.location.href = "/web/m/wap/area/area.jsp";
				},
				nFn: function() {
				}
			});
			//alert("对不起，您的手机不支持定位功能！");
		}
	};
	YI.getLocation.prototype = {
		testGeolocation: function() {
			if (!!navigator.geolocation) {
				return true;
			}
			return false;
		}
	};
	YI.alert = function(opts) {
		var defaults = {
			title: "",
			yText: "确认",
			msg: "",
			yFn: null,
			initFn:null,
			nFn: null
		};
		var opts = $.extend(defaults, opts);
		var title = "";
		if (opts.title)
			title = '<h3>' + opts.title + '</h3>';

		var html = '<div class="confirm">' +
			title +
			'<span style="padding:25px 10px;">' + opts.msg + '</span>' +
			'<div class="btns">' +
			'<a href="javascript:void(0)" style="border-right:none;width:100%" data-type="yes">' + opts.yText + '</a>' +
			'</div>' +
			'</div>';
		new YI.dialog({
			html: html,
			initFn:opts.initFn,
			callBack: function() {
				//里面的this指向新生成的$dialog对象
				var _this = this;
				this.content.find('.confirm .btns > a').on('click', function(e) {
					var isClose = true;
					
					if ($(this).data('type') == "yes") {
						if (opts.yFn)
							opts.yFn(_this);
					} else {
						if (opts.nFn)
							opts.nFn(_this);
					};

					_this.close();
					/*if (isClose || typeof isClose == "undefined") {
						_this.close();
						e.preventDefault();
					}*/
				});
			}
		}).show();
	};
	YI.confirm = function(opts) {
		var defaults = {
			title: "",
			yText: "确认",
			nText: "取消",
			msg: "",
			yFn: null,
			nFn: null
		};
		var opts = $.extend(defaults, opts);
		var title = "";
		if (opts.title)
			title = '<h3>' + opts.title + '</h3>';

		var html = '<div class="confirm">' +
			title +
			'<span style="padding:25px 10px;">' + opts.msg + '</span>' +
			'<div class="btns">' +
			'<a href="javascript:void(0)" data-type="no">' + opts.nText + '</a>' +
			'<a href="javascript:void(0)" data-type="yes">' + opts.yText + '</a>' +
			'</div>' +
			'</div>';
		new YI.dialog({
			html: html,
			callBack: function() {
				//里面的this指向新生成的$dialog对象
				var _this = this;
				this.content.find('.confirm .btns > a').on('click', function(e) {
					var isClose = true;
					
					if ($(this).data('type') == "yes") {
						if (opts.yFn)
							opts.yFn(_this);
					} else {
						if (opts.nFn)
							opts.nFn(_this);
					};

					_this.close();
					/*if (isClose || typeof isClose == "undefined") {
						_this.close();
						e.preventDefault();
					}*/
				});
			}
		}).show();
	};
	YI.loading = function() {
		var html = '<div class="dialog-loading animated fast fadeIn"><img src="/statics/common/wechat/img/wxloading70.gif" />拼命加载中...</div>';
		new YI.dialog({
			    html: html,
			    mask: true,
			    animate:null,
			    initFn: function() {
				    $("#dialogWrap").css("background","transparent");
			    }
		}).show();
	};
	YI.tip = function(msg, time, fn) {
		time = time || 3000;
		var html = '<div class="dialog-tip animated fast fadeIn">' + msg + '</div>';
		new YI.dialog({
			html: html,
			mask: true,
			animate:null,
			initFn: function() {
				$("#dialogWrap").css("background","transparent");
			},
			//			initFn: function() {
			//				$(this.content[0]).css({
			//					"top": "50%",
			//					"bottom": "120px"
			//				});
			//			},
			callBack: function() {
				//里面的this指向新生成的$dialog对象
				var _this = this;
				if (time > 0) {
					var val = time;
					(function() {
						val -= 1000;
						if (val > 0) {
							setTimeout(arguments.callee, 1000);
						} else if (val <= 0) {
							//元素透明度为0后隐藏元素
							
							if (fn) fn();
							_this.close();
						}
					})();
				} else {
					if (fn) fn(_this);
				}
			}
		}).show();
	};

    YI.getUrlParam = function(url) {
        url = url || window.location.href;
        var objRequest = new Object();
        if (url.indexOf("?") != -1) {
            url = url.split("?")[1];
            var strArr = url.split("&");
            for (var i = 0; i < strArr.length; i ++) {
                objRequest[strArr[i].split("=")[0]] = decodeURI((strArr[i].split("=")[1]));
            }
        }
        return objRequest;
    };



    YI.dateModify = function(date, days, operator) {
        var nd = new Date(date);
        nd = nd.valueOf();
        if (operator == "+") {
            nd = nd + days * 24 * 60 * 60 * 1000;
        } else if (operator == "-") {
            nd = nd - days * 24 * 60 * 60 * 1000;
        } else {
            return false;
        }
        nd = new Date(nd);
        return nd;
    };


	//footer
	$('#goTop,a.gotop').on('click', function() {
		setTimeout(function() {
			window.scrollTo(0, 0);
		}, 60);
	});
	// var $fas = $('#footer > a');
	// $fas.on('click', function (){
	//     $(this).addClass('on');
	// });


});
Date.prototype.format = function(format) {
    var args = {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3), //quarter
        "S": this.getMilliseconds()
    };
    if (/(y+)/.test(format))
        format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var i in args) {
        var n = args[i];
        if (new RegExp("(" + i + ")").test(format))
            format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? n : ("00" + n).substr(("" + n).length));
    }
    return format;
};
Date.prototype.getWeek=function(){
    var dayNames = new Array("周天","周一","周二","周三","周四","周五","周六");  
    return dayNames[this.getDay()]
};
Date.prototype.DayNumOfMonth=function(){
    var d = new Date(this.getFullYear(),this.getMonth()+1,0);
    return d.getDate();
};
Date.prototype.diff = function(date) {
    var time = ((this.getTime() - date.getTime()) / (60 * 60 * 1000)).toFixed(1);
    time = time.toString();
    var hour = time.substr(0, time.indexOf("."));
    var minute = time.substr(time.indexOf("."), time.length) * 60;
    return [hour, minute];
};