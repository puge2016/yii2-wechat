(function() {
    window.isScrollInit = function(){
        var myScroll,
            pullDownEl = document.querySelector('#pullDown'),
            pullDownOffset,
            pullUpEl = document.querySelector('#pullUp'),
            pullUpOffset,
            _maxScrollY,
            $wrapper = $('#wrapper'),
            $ul = $('#scroller ul');

        $ul.css('min-height',$wrapper.height());


        if(myScroll != null){
            myScroll.destroy();
        };

        if(pullDownEl) {
            pullDownOffset = pullDownEl.offsetHeight;
        }else{
            pullDownOffset = 0;
        };

        if(pullUpEl){
            pullUpOffset = pullUpEl.offsetHeight;
        }else{
            pullUpOffset = 0;
        };

        //console.log('pullDownOffset:' + pullDownOffset);
        //console.log('pullUpOffset:' + pullUpOffset);

        //Options of IScroll
        var myOptions = {
            mouseWheel: true,
            scrollbars: false,
            fadeScrollbars: true,
            probeType: 1,
            click:true,
            startY: -pullDownOffset
        };
        window.myScrollPlug = myScroll = new IScroll('#wrapper', myOptions);
        //console.log('maxScrollY-1:' + myScroll.maxScrollY);
        _maxScrollY = myScroll.maxScrollY = myScroll.maxScrollY + pullUpOffset;
        //console.log('maxScrollY-2:' + myScroll.maxScrollY);

        var isScrolling = false;

        //Event: scrollStart
        myScroll.on("scrollStart", function() {
            if (this.y == this.startY) {
                isScrolling = true;
            }
            //console.log('start-y:' + this.y);
        });

        //Event: scroll
        myScroll.on('scroll', function() {
            if (this.y >= 5 && pullDownEl && !pullDownEl.className.match('flip')) {
                pullDownEl.className = 'flip';
                pullDownEl.querySelector('.pullDownLabel').innerHTML = '释放刷新';
                //this.minScrollY = 0;
            } else if (this.y < 5 && pullDownEl && pullDownEl.className.match('flip')) {
                pullDownEl.className = '';
                pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉刷新';
                //this.minScrollY = -pullDownOffset;
            } else if (this.y <= (_maxScrollY - pullUpOffset) && pullUpEl && !pullUpEl.className.match('flip')) {
                pullUpEl.className = 'flip';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = '释放刷新';
                //this.maxScrollY = this.maxScrollY;
                this.maxScrollY = this.maxScrollY - pullUpOffset;
            } else if (this.y > (_maxScrollY - pullUpOffset) && pullUpEl && pullUpEl.className.match('flip')) {
                pullUpEl.className = '';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = '加载更多';
                //this.maxScrollY = pullUpOffset;
                this.maxScrollY = this.maxScrollY + pullUpOffset;
            }

            //console.log('y:' + this.y);
        });

        //Event: scrollEnd
        myScroll.on("scrollEnd", function() {
            //console.log('scroll end');
            //console.log('directionY:' + this.directionY);
            //console.log('y1:' + this.y);
            //console.log('maxScrollY-3:' + this.maxScrollY);
            if (pullDownEl && !pullDownEl.className.match('flip') && this.y > this.options.startY) {
                //console.log('resume');
                this.scrollTo(0, this.options.startY, 800);
            } else if (pullDownEl && pullDownEl.className.match('flip')) {
                pullDownEl.className = 'loading';
                pullDownEl.querySelector('.pullDownLabel').innerHTML = '拼命加载中...';
                // Execute custom function (ajax call?)
                if (isScrolling) {
                    //console.log('before pull down action:' + this.y);
                    pullDownAction();
                    //console.log('after pull down action:' + this.y);
                }
            } else if (pullUpEl && pullUpEl.className.match('flip')) {
                //console.log('pull up loading');
                pullUpEl.className = 'loading';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = '拼命加载中...';
                // Execute custom function (ajax call?)
                if (isScrolling) {
                    //console.log('before pull up action:' + this.y);
                    pullUpAction();
                    //console.log('after pull up action:' + this.y);
                }
            }
            isScrolling = false;
        });

        //Event: refresh
        myScroll.on("refresh", function() {

            //console.log('maxScrollY-4:' + this.maxScrollY);
            _maxScrollY = this.maxScrollY = this.maxScrollY + pullUpOffset;
            //console.log('maxScrollY-5:' + this.maxScrollY);

            //我添加的 begin
            pullDownEl.style.opacity = '1';
            pullUpEl.style.opacity = '1';
            //我添加的 end


            if (pullDownEl && pullDownEl.className.match('loading')) {
                pullDownEl.className = '';
                pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉刷新';
                this.scrollTo(0, this.options.startY, 0);
            } else if (pullUpEl && pullUpEl.className.match('loading')) {
                pullUpEl.className = '';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = '加载更多';
                this.scrollTo(0, this.maxScrollY, 0);
            }

            //console.log('refresh finished!');
        });

        /*setTimeout(function() {
            document.getElementById('wrapper').style.left = '0';
            //alert(1212)
        }, 1500);*/



        /*document.addEventListener('touchmove', function(e) {
            e.preventDefault();
        }, false);*/
    };
})();
