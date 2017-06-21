/**
 * 模块加载器
 * @authors vaotoo (http://vaotoo.com/api/import)
 * @date    2014-07-25 10:37:37
 * @version 1.0
 */

(function (window) {

    /* 模块加载器私有默认配置 */
    var defaultConfig = {
        /**
         * JS静态服务器
         * @property {String}
         * 有值则为绝对路径 没值则为相对路径
         */
        jsServer: '',

        /**
         * JS合并设置
         * @property {Boolean}
         * True开启合并 False关闭合并
         */
        jsMerge: true,

        /**
         * JS压缩设置
         * @property {Boolean}
         * True开启压缩 False关闭压缩
         */
        jsCompress: true,

        /**
         * JS版本设置
         * @property {String}
         */
        jsVersion: null,

        /**
         * JS缓存设置
         * @property {Object}
         */
        jsCache: {},

        /**
             * JS实体文件对象
             * @property {Object}
             * 数据格式
                {
                    test1 : "js/common/dialog",
                    test2 : "js/common/paging"
                }
             */
        jsDefineObj: {},

        /**
             * JS实体映射对象
             * @property {Array}
             * 数据格式
                [
                    { id : "t1",mergeJs : ["test1","test2"]},
                    { id : "t2",mergeJs : ["test2"]}
                ]
             */
        jsMergeObj: [],

        /**
         * 管理器debug模式设置
         * @type {Boolean}
         * T开启 F关闭
         */
        jsDebug: true
    };

    /* 模块加载器私有函数  START */
    var importjs = function () {

        /* 私有工具类 */
        var util = {
            /**
             * 封装遍历Each方法
             * @param  {Object}   object   [需要遍历的对象]
             * @param  {Function} callback [自定义函数]
             * @return {Object}            [当前的Object]
             */
            each: function (object, callback) {
                var key = null,
                    i = 0,
                    length = object.length,
                    isObj = length === undefined || typeof object === 'function';

                if (isObj) {
                    for (key in object) {
                        if (callback.call(object[key], key, object[key]) === false) break;
                    }
                } else {
                    for (; i < length;) {
                        if (callback.call(object[i], i, object[i++]) === false) break;
                    }
                }
                return object;
            },

            /** 判断给定的对象是否是一个普通对象 函数、对象、表达式、字符 类型判断
             * @method isObject
             * @param { * } object 需要判断的对象
             * @return { Boolean } 给定的对象是否是普通对象
             */
            objectType: function () {
                var objType = ['String', 'Function', 'Array', 'Number', 'RegExp', 'Object'];
                this.each(objType, function (i, val) {
                    util['is' + val] = function (obj) {
                        return Object.prototype.toString.apply(obj) == '[object ' + val + ']';
                    }
                });
            },

            /** 对象继承扩展操作：
                 * @method extend
                 * @param { Object }  target 目标对象-新的属性将附加到该对象上
                 * @param { Object }  source 来源对象-该对象的属性会被附加到target对象上
                 * @param { Boolean } keep   是否保留目标对象中与源对象中属性名相同的属性
                 * @return { Object }        返回target对象
                 * @example
                    var target = {aa:"sss",bb:"",hh:[{r:"0"},{c:"4"}] },
                        source = {aa:"ddd",dd:"",hh:[{r:"1"},{c:"",a:"2",aa:"22"},{b:"3"}] };
                        util.extend( target, source, false);
                        console.log(target);
                */
            extend: function (target, source, keep) {
                if (source && typeof source === 'object') {
                    var _this = this;
                    _this.each(source, function (i, sObj) {
                        /* 目标对象中已存在当前的属性 */
                        if (target.hasOwnProperty(i)) {
                            /* 当前属性为数组 */
                            if (_this.isArray(target[i])) {
                                _this.each(sObj, function (j, sDat) {
                                    if (target[i].hasOwnProperty(j)) {
                                        /* 如果是Array则开始递归 */
                                        target[i][j] = _this.extend(target[i][j], sDat, keep);
                                    } else {
                                        target[i].push(sDat);
                                    }
                                });
                            } else {
                                /* 未设置保留状态时 则直接附加属性 */
                                if (!keep) target[i] = sObj;
                            }
                        } else {
                            /* 目标对象中不存在当前的属性 则直接附加属性 */
                            target[i] = sObj;
                        }
                    });
                }
                return target;
            },

            /**检测javascript文件是否已经加载
             * @method checkJs
             * @param  { String  } url js请求地址的绝对路径
             * @param  { String  } key js的依赖模块名称
             * @return { Boolean } true:已加载js文件 false:未加载js文件
             */
            checkJs: function (path, key) {
                if (defaultConfig.jsCache[key] === path) {
                    return true;
                } else {
                    defaultConfig.jsCache[key] = path;
                    return false;
                }
            },

            /**创建javascript引用节点
             * @method createJs
             * @param { String } url js请求地址的绝对路径
             * @param { Array  } keyList  js加载完成后回调的事件arguments
             */
            createJs: function (path, keyList) {
                if (!path) return false;
                var fn = null;
                switch (keyList.length) {
                    case 2:
                        fn = keyList[1];
                        break;
                    case 3:
                        fn = keyList[2];
                        break;
                }
                //已加载js文件
                if (this.checkJs(path, keyList[0])) {
                    if (this.isFunction(fn)) fn();
                } else {
                    var script = document.createElement("script");
                    script.type = "text/javascript";
                    script.src = defaultConfig.jsServer + "/f=" + path;
                    document.getElementsByTagName("head")[0].appendChild(script);
                    this.loadJs(script, fn);
                }
            },

            /**检查当前值是否存在当前数组中
             * @method checkValue
             * @param { String } val 数值
             * @param { Array  } arr 数组实体
             * @param { String } key 数组字段
             * @param { Boolean} closeLogs 日志输出设置
             * @return { Boolean } true:已存在依赖 false:不存在依赖
             */
            checkValue: function (val, arr, key, closeLogs) {
                arr = defaultConfig.jsMergeObj; //暂时为固定值，可移除
                key = "id"; //暂时为固定值，可移除
                var flag = false;
                for (var i in arr) {
                    if (arr[i][0][key] === val) flag = true;
                }
                if (!flag && !closeLogs) {
                    this.catchError("\u672A\u627E\u5230 \"" + val + "\" \u7684\u4F9D\u8D56\u5173\u7CFB\uFF0C\u8BF7\u68C0\u67E5\u4F60\u7684\u914D\u7F6E\u4FE1\u606F\n\n\u6E90\u4EE3\u7801 g$(\"" + val + "\")", 1);
                }
                return flag;
            },

            /**监测javascript加载过程
             * @method loadJs
             * @param { Object   } script  节点对象
             * @param { Function } fn      js加载完成后回调的事件
             */
            loadJs: function (script, fn) {
                var _this = this;
                if (script.readyState) { //IE
                    script.onreadystatechange = function () {
                        if (script.readyState === "loaded" || script.readyState === "complete") {
                            script.onreadystatechange = null;
                            if (_this.isFunction(fn)) fn();
                        }
                    };
                } else { //Others
                    script.onload = function () {
                        if (_this.isFunction(fn)) fn();
                        script.onload = null;
                    };
                    script.onerror = function () {
                        _this.catchError("\u8BF7\u6C42\u811A\u672C\u6587\u4EF6\u51FA\u9519\uFF1A " + script.src + " \u8BF7\u68C0\u67E5\u6587\u4EF6\u8DEF\u5F84", 1);
                        script.onerror = null;
                    };
                }
            },

            /**设置JSconfig配置信息
             * @method setConfig
             * @param { Object } setObj  配置对象
             */
            setConfig: function (setObj) {
                if (setObj.define && setObj.merge) {
                    var setmg = setObj.merge,
                        arrdt = [],
                        arrln = defaultConfig.jsMergeObj.length,
                        _this = this;
                    if (arrln > 0) {
                        _this.each(defaultConfig.jsMergeObj, function (i, val) {
                            _this.each(setmg, function (j, obj) {
                                if (val[0].id === obj.id) {
                                    var logs = "[{\"id\":\"" + obj.id + "\",merge:[",
                                        size = obj.merge.length;
                                    _this.each(obj.merge, function (k, meg) {
                                        logs += "\"" + meg + "\"";
                                        if (k < size - 1) logs += ",";
                                    });
                                    logs += "]}]";
                                    _this.catchError("\u6CE8\u5165 " + logs + " \u5931\u8D25\uFF0C\u5DF2\u5B58\u5728\u4F9D\u8D56\u914D\u7F6E", 1);
                                } else {
                                    arrdt.push([obj]);
                                }
                            });
                        });
                        defaultConfig.jsMergeObj = defaultConfig.jsMergeObj.concat(arrdt);
                    } else {
                        _this.each(setmg, function (i, val) {
                            defaultConfig.jsMergeObj.push([val]);
                        });
                    }
                    _this.extend(defaultConfig.jsDefineObj, setObj.define, true);
                }
            },

            /**读取JSconfig配置信息
             * @method getConfig
             * @param { Object }  g$()配置对象
             * @return { String } 完整的脚本文件路径
             */
            getConfig: function () {
                var key = arguments,
                    lth = defaultConfig.jsMergeObj.length,
                    pth = "",
                    _this = this;
                if (lth > 0) {
                    _this.each(defaultConfig.jsMergeObj, function (i, val) {
                        var mglist = val[0];
                        if (mglist.id === key[0]) {
                            var mgobj = mglist.merge,
                                msize = mgobj.length;
                            _this.each(mgobj, function (j, obj) {
                                var dfpth = defaultConfig.jsDefineObj[obj];
                                if (dfpth) {
                                    dfpth = (defaultConfig.jsCompress ? (dfpth + ".min") : dfpth) + ".js";
                                    pth += (dfpth + ((j < msize - 1 && defaultConfig.jsDefineObj[mgobj[j + 1]]) ? "," : ""));
                                } else {
                                    _this.catchError("\u8C03\u7528\u4F9D\u8D56 \u201C" + obj + "\u201D \u5931\u8D25\uFF0C\u8BE5\u4F9D\u8D56\u672A\u5728\u914D\u7F6E\u4E2D\u58F0\u660E", 1);
                                }
                            });
                        }
                    });
                }
                if (pth === "") {
                    return false;
                } else {
                    return pth + "?v=" + defaultConfig.jsVersion;
                }
            },

            /**
             * define_key调用处理
             * @param  {Array} keyList [参数集合]
             * @description 适用以下调用方式
             *  g$("key")
             *  g$({define:{},merge:[]})
             */
            define_key: function (keyList) {
                var key = keyList[0];
                if (this.isObject(key)) {
                    this.setConfig(key);
                } else {
                    this.checkValue(key) && this.createJs(this.getConfig(key), keyList);
                }
            },

            /**
             * define_key_fn调用处理
             * @param  {Array} keyList [参数集合]
             * @description 适用以下调用方式
             *  g$("key", function(){})
             *  g$({define: {}, merge: []}, function(){})
             *  g$("haha", "js/common/dialog.js")
             */
            define_key_fn: function (keyList) {
                var key = keyList[0];
                if (this.isObject(key)) {
                    this.setConfig(key);
                    this.isFunction(keyList[1]) && keyList[1]();
                } else if (this.isFunction(keyList[1])) {
                    this.checkValue(key) && this.createJs(this.getConfig(key), keyList);
                } else {
                    keyList = [keyList[0], keyList[1], null];
                    this.define_key_path_fn(keyList);
                }
            },

            /**
             * define_key_path_fn调用处理
             * @param  {Array} keyList [参数集合]
             * @description 适用以下调用方式
             *  g$("haha","js/common/dialog.js",function(){});
             */
            define_key_path_fn: function (keyList) {
                var key = keyList[0];
                if (!this.checkValue(key, null, null, true)) {
                    var jsName = key + 'js',
                        jsPath = keyList[1],
                        jsList = jsPath.split(','),
                        jsMerge = [jsName];

                    // 处理 'js/xx,js/yy' 这种拼装
                    if (jsList.length) {
                        jsMerge = [];
                        for (var i = 0, j = jsList.length; i < j; i++) {
                            jsMerge.push(jsName + i);
                            defaultConfig.jsDefineObj[jsName + i] = jsList[i];
                        }
                    } else {
                        defaultConfig.jsDefineObj[jsName] = jsPath;
                    }

                    defaultConfig.jsMergeObj.push([{
                        "id": key,
                        "merge": jsMerge
                    }]);
                    this.createJs(this.getConfig(key), keyList);
                } else {
                    this.catchError("\u6CE8\u5165 \"" + keyList[0] + "\",\"" + keyList[1] + "\" \u5931\u8D25\uFF0C\u5DF2\u5B58\u5728\u4F9D\u8D56\u914D\u7F6E", 1);
                }
            },

            /**获取版本号
             * @method getVersion
             */
            getVersion: function () {
                if (!defaultConfig.jsVersion) {
                    var paramet = {}, //参数集合
                        pattern = /(\w+)=(\w+)/ig, //定义匹配参数的正则表达式
                        scriptObj = document.getElementsByTagName("script"),
                        scriptSrc = null;
                    if (scriptObj && scriptObj.length > 0) {
                        //获取当前JS文件的全地址
                        scriptSrc = scriptObj[scriptObj.length - 1].src;
                        scriptSrc.replace(pattern, function (a, b, c) {
                            paramet[b] = c;
                        });
                        //设置版本号
                        if (paramet["v"]) {
                            defaultConfig.jsVersion = paramet["v"];
                        }
                    }
                }
            },

            /**异常处理器
             * @method catchError
             * @param { String } errorMsg  异常日志
             * @param { Number } level     异常等级
             */
            catchError: function (errorMsg, level) {
                if (window.console && window.console.log && defaultConfig.jsDebug) {
                    if (level) {
                        console.log("\n\n%c\u6A21\u5757\u4F9D\u8D56\u5F02\u5E38\n\n", "color:red;");
                        console.log("\n\nlog：\n%c" + errorMsg + "\n\n", "color:blue;");
                    } else {
                        console.log("\n\n%c\u6A21\u5757\u52A0\u8F7D\u5F02\u5E38\n\n", "color:red;");
                        console.log("\n\nlog：\n" + errorMsg + "\n\n");
                    }
                }
            }
        };
        /* 私有工具类结束 */

        /** importjs：成员变量、私有函数
         *@method
         *      keyList 实参对象(用来解决形参、实参动态变化的情况)
         *@method
         *      resolve 解析调用参数
         *@method
         *      importAPI 对外调用方法API
         */
        var keyList = arguments[0],
            resolve = function () {
                switch (keyList.length) {
                    /* 无回调函数 */
                    case 1:
                        util.define_key(keyList);
                        break;
                        /* 有回调函数 并可能为复合自定义配置项 */
                    case 2:
                        util.define_key_fn(keyList);
                        break;
                        /* 有回调函数 并为单一自定义配置项 */
                    case 3:
                        util.define_key_path_fn(keyList);
                        break;
                }
            },
            importAPI = function () {
                //工具类初始化
                this.auto = function () {
                    util.getVersion();
                };
                this.init = function () {
                    try {
                        util.objectType();
                        resolve();
                    } catch (exception) {
                        util.catchError(exception);
                    }
                };
            };
        return importAPI;
    };
    /* 模块加载器私有函数  END */

    (new(importjs())).auto();

    /* 公共调用开始 */
    window.require = function () {
        (new(importjs(arguments))).init()
    };
    window.require.config = function (_newConfig) {
            for (var k in _newConfig) {
                if (defaultConfig.hasOwnProperty(k) && k !== 'jsCache') {
                    defaultConfig[k] = _newConfig[k];
                }
            }
            return this;
        }
        /* 公共调用模块结束 */
})(window);
