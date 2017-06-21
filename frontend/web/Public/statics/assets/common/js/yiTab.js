 /**
 * 
 * @authors Your Name (you@example.org)
 * @date    2014-11-06 10:27:00
 * @version $Id$
 */

$(function(){

	function TabClass(target,opts){
		this.opts = opts;
		this.container = target;
		this.ul = this.container.find('.tab-ul');
		this.lis = this.ul.find('li');
		this.len = this.lis.length;
		this.div = this.container.children('div.tab-list');
		this.em =this.ul.children('em.tab-line');		
		this.i = 0;
		this.init();
	};

	TabClass.prototype = {
		init : function(){
			this.initDom();
			this.bindEvent();
		},

		//初始化DOM
		initDom : function(){
			//console.info(this.lis.eq(0).width(), this.lis.eq(0).height()+1);
			//this.ul.addClass('tab-ul_'+this.len).children('ul').show();
			this.ul.addClass('tab-ul_'+this.len);
			this.liWidth = this.lis.eq(0).width();
			this.liHeight = this.lis.eq(0).height();
			this.em.css({
				'width' : this.liWidth,
				'top' : this.liHeight-3
			});
 			//this.lis.append('<em class="line-em"></em>');
		},

		bindEvent : function(){
			var _this = this,
				top = $(_this.container).offset().top;
			//console.log(top)
			this.lis.on('click',function(){
				var i = $(this).index();
				_this.i = i;
				//$(this).find('em.line-em').show();
				//$(this).siblings().find('em.line-em').hide()
				if(_this.opts.fixed){
					setTimeout(function(){
						window.scrollTo(0,top)
					},60);
				};
				if(_this.opts.callBack){
					_this.opts.callBack.call(_this,_this,this,i);
				};
				_this.animateLine();
			})

			// this.container.on('swipeRight',function(e){
			// 	_this.i++;
			// 	if(_this.i>=_this.len){
			// 		_this.i=0;
			// 	};
			// 	//console.info(_this.lis.eq(_this.i).width());
			// 	_this.animateLine();
			// });

			// this.container.on('swipeLeft',function(e){
			// 	_this.i--;
			// 	if(_this.i<0){
			// 		_this.i=_this.len-1;
			// 	};
			// 	_this.animateLine();
			// });
		},
		animateLine : function(){
			var _this = this;
			_this.em.animate({'left':_this.lis.eq(_this.i).position().left},200);
			_this.div.children('div').eq(_this.i).show().siblings().hide();
			_this.lis.eq(_this.i).addClass('cur').siblings().removeClass('cur');
		}

	};


	$.fn.tab = function(o){
		var defaults = {
			time : 5000,
			fixed : false,
			callBack : null
		};
		defaults = $.extend(defaults,o);
		//console.info(defaults);
		this.each(function(){
			new TabClass($(this),defaults);
		});
	};
});