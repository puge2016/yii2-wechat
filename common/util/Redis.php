<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/11
 * Time: 15:17
 */

namespace common\util;

/**
 * redis操作类
 * 说明，任何为false的串，存在redis中都是空串。
 * 只有在key不存在时，才会返回false。
 * 这点可用于防止缓存穿透
 *
 */


class Redis
{
    private $redis;

    //当前数据库ID号
    protected $dbId = 0 ;

    //当前权限认证码
    protected $auth ;

    /**
     * 实例化的对象,单例模式.
     * @var Redis
     */
    static private $_instance=array() ;

    private  $k ;

    //连接属性数组
    protected $attr=array(
        //连接超时时间，redis配置文件中默认为300秒
        'timeout'=>30,
        //选择的数据库。
        'db_id'=>0,
    );

    //什么时候重新建立连接
    protected $expireTime;

    protected $host;

    protected $port;


    private function __construct($config,$attr=array())
    {
        $this->attr        = array_merge($this->attr,$attr);
        $this->redis       = new \Redis();
        $this->port        = $config['port'] ? $config['port'] : 6379;
        $this->host        = $config['host'];
        $this->redis->connect($this->host, $this->port, $this->attr['timeout']);

        if($config['auth'])
        {
            $this->auth($config['auth']);
            $this->auth    = $config['auth'];
        }
        $this->expireTime  = time() + $this->attr['timeout'];
    }

    /**
     * @param $config
     * @param array $attr
     * @return mixed
     * 得到实例化的对象.
     * 为每个数据库建立一个连接
     * 如果连接超时，将会重新建立一个连接
     */
    public static function getInstance($config, $attr = array())
    {
        //如果是一个字符串，将其认为是数据库的ID号。以简化写法。
        if(!is_array($attr))
        {
            $dbId               = $attr;
            $attr               = array();
            $attr['db_id']      = $dbId;
        }

        $attr['db_id']          = $attr['db_id'] ? $attr['db_id'] : 0;

        $k                      = md5(implode('', $config).$attr['db_id']);
        $hasK                   = isset(static::$_instance[$k]) ;
        if( !$hasK || !(static::$_instance[$k] instanceof self))
        {

            static::$_instance[$k]          = new self($config, $attr) ;
            static::$_instance[$k]->k       = $k;
            static::$_instance[$k]->dbId    = $attr['db_id'] ;

            //如果不是0号库，选择一下数据库。
            if($attr['db_id'] != 0){
                static::$_instance[$k]->select($attr['db_id']) ;
            }
        }  elseif( time() > static::$_instance[$k]->expireTime) {
            static::$_instance[$k]->close();
            static::$_instance[$k]          = new self($config,$attr) ;
            static::$_instance[$k]->k       = $k;
            static::$_instance[$k]->dbId    = $attr['db_id'] ;

            //如果不是0号库，选择一下数据库。
            if($attr['db_id']!=0){
                static::$_instance[$k]->select($attr['db_id']) ;
            }
        }
        return static::$_instance[$k] ;
    }

    private function __clone(){}

    /**
     * 执行原生的redis操作
     * @return \Redis
     */
    public function getRedis()
    {
        return $this->redis;
    }



    /*****************hash表操作函数*******************/


    /**
     * 为hash表设定一个字段的值
     * @param string $key 缓存key, 表名
     * @param string  $field 字段
     * @param string $value 值。
     * @return bool
     */
    public function hSet($key,$field,$value)
    {
        return $this->redis->hSet($key,$field,$value);
    }

    /**
     * 为hash表设定一个字段的值,如果字段存在，返回false, 否则返回true
     * @param string $key 缓存key
     * @param string  $field 字段
     * @param string $value 值。
     * @return  bool    TRUE if the field was set, FALSE if it was already present.
     * @link    http://redis.io/commands/hsetnx
     * @example
     * $redis->delete('h')
     * $redis->hSetNx('h', 'key1', 'hello'); // TRUE, 'key1' => 'hello' in the hash at "h"
     * $redis->hSetNx('h', 'key1', 'world'); // FALSE, 'key1' => 'hello' in the hash at "h". No change since the field
     * wasn't replaced.
     */
    public function hSetNx($key, $field, $value)
    {
        return $this->redis->hSetNx($key, $field, $value);
    }

    /**
     * 得到hash表中一个字段的值
     * @param string $key 缓存key
     * @param string  $field 字段
     * @return string|false
     */
    public function hGet($key,$field)
    {
        return $this->redis->hGet($key,$field);
    }
    /**
     * 返回hash表元素个数
     * @param string $key 缓存key
     * @return int|bool
     */
    public function hLen($key)
    {
        return $this->redis->hLen($key);
    }

    /**
     * 删除hash表中指定字段 ,支持批量删除
     * @param string $key 缓存key
     * @param string  $field 字段
     * @return int
     */
    public function hDel($key,$field)
    {
        $fieldArr=explode(',',$field);
        $delNum=0;

        foreach($fieldArr as $row)
        {
            $row=trim($row);
            $delNum+=$this->redis->hDel($key,$row);
        }

        return $delNum;
    }

    /**
     * 返回所有hash表的所有字段
     * @param string $key
     * @return array|bool
     */
    public function hKeys($key)
    {
        return $this->redis->hKeys($key);
    }

    /**
     * 返回所有hash表的字段值，为一个索引数组
     * @param string $key
     * @return array|bool
     */
    public function hVals($key)
    {
        return $this->redis->hVals($key);
    }

    /**
     * 返回所有hash表的字段值，为一个关联数组
     * @param string $key
     * @return array|bool
     */
    public function hGetAll($key)
    {
        return $this->redis->hGetAll($key);
    }

    /**
     * 判断hash表中，指定field是不是存在，不存在返回FALSE
     * @param string $key 缓存key
     * @param string  $field 字段
     * @return bool
     */
    public function hExists($key,$field)
    {
        return $this->redis->hExists($key,$field);
    }

    /**
     * 为hash表多个字段设定值。
     * @param string $key
     * @param array $value
     * @return array|bool
     */
    public function hMset($key,$value)
    {
        if(!is_array($value))
            return false;
        return $this->redis->hMset($key,$value);
    }


    /**
     * @param $key
     * @param $field string 以','号分隔字段
     * @return mixed
     * 为hash表多个字段设定值。
     */
    public function hMGet($key, $field)
    {
        if(!is_array($field))
            $field=explode(',', $field);
        return $this->redis->hMGet($key,$field);
    }

    /**
     * @param string $key
     * @param int $field
     * @param string $value
     * @return bool
     * 为hash表设这累加，可以负数
     * @example
     * <pre>
     * $redis->delete('h');
     * $redis->hIncrBy('h', 'x', 2); // returns 2: h[x] = 2 now.
     * $redis->hIncrBy('h', 'x', 1); // h[x] ← 2 + 1. Returns 3
     * </pre>
     */
    public function hIncrBy($key,$field,$value)
    {
        $value=intval($value);
        return $this->redis->hIncrBy($key,$field,$value);
    }

    /**
     * Increment the float value of a hash field by the given amount
     * @param $key
     * @param $field
     * @param $value
     * @return float
     * @example
     * <pre>
     * $redis = new Redis();
     * $redis->connect('127.0.0.1');
     * $redis->hset('h', 'float', 3);
     * $redis->hset('h', 'int',   3);
     * var_dump( $redis->hIncrByFloat('h', 'float', 1.5) ); // float(4.5)
     * var_dump( $redis->hGetAll('h') );
     *
     * // Output
     *  array(2) {
     *    ["float"]=>
     *    string(3) "4.5"
     *    ["int"]=>
     *    string(1) "3"
     *  }
     * </pre>
     */
    public function hIncrByFloat($key, $field, $value)
    {
        $value=floatval($value) ;
        return $this->redis->hIncrByFloat($key, $field, $value) ;
    }


    /*********************有序集合操作*********************/

    /**
     * 给当前集合添加一个元素
     * 如果value已经存在，会更新order的值。
     * @param string $key
     * @param string $order 序号
     * @param string $value 值
     * @return bool
     */
    public function zAdd($key,$order,$value)
    {
        return $this->redis->zAdd($key,$order,$value);
    }

    /**
     * @param $key string
     * @param $value string
     * @return int
     * 删除值为value 的元素
     */
    public function zRem($key,$value)
    {
        return $this->redis->zRem($key,$value);
    }

    /**
     * 集合以order递增排列后，0表示第一个元素，-1表示最后一个元素
     * @param string $key
     * @param int $start
     * @param int $end
     * @return array|bool
     */
    public function zRange($key,$start,$end)
    {
        return $this->redis->zRange($key,$start,$end);
    }

    /**
     * 集合以order递减排列后，0表示第一个元素，-1表示最后一个元素
     * @param string $key
     * @param int $start
     * @param int $end
     * @return array|bool
     */
    public function zRevRange($key,$start,$end)
    {
        return $this->redis->zRevRange($key,$start,$end);
    }


    /**
     * @param $key
     * @param string $start
     * @param string $end
     * @param array $option
     *     withscores=>true，表示数组下标为Order值，默认返回索引数组
     *     limit=>array(0,1) 表示从0开始，取一条记录。
     * @return array
     * 集合以order递增排列后，返回指定order之间的元素。
     * min和max可以是-inf和+inf　表示最大值，最小值
     * @example
     * <pre>
     * $redis->zAdd('key', 0, 'val0');
     * $redis->zAdd('key', 2, 'val2');
     * $redis->zAdd('key', 10, 'val10');
     * $redis->zRangeByScore('key', 0, 3);                                          // array('val0', 'val2')
     * $redis->zRangeByScore('key', 0, 3, array('withscores' => TRUE);              // array('val0' => 0, 'val2' => 2)
     * $redis->zRangeByScore('key', 0, 3, array('limit' => array(1, 1));                        // array('val2' => 2)
     * $redis->zRangeByScore('key', 0, 3, array('limit' => array(1, 1));                        // array('val2')
     * $redis->zRangeByScore('key', 0, 3, array('withscores' => TRUE, 'limit' => array(1, 1));  // array('val2' => 2)
     * </pre>
     */
    public function zRangeByScore($key,$start='-inf',$end="+inf",$option=array())
    {
        return $this->redis->zRangeByScore($key,$start,$end,$option);
    }

    /**
     * @param $key
     * @param string $start
     * @param string $end
     * @param array $option
     *     withscores=>true，表示数组下标为Order值，默认返回索引数组
     *     limit=>array(0,1) 表示从0开始，取一条记录。
     * @return array
     * 集合以order递减排列后，返回指定order之间的元素。
     * min和max可以是-inf和+inf　表示最大值，最小值
     */
    public function zRevRangeByScore($key,$start='-inf',$end="+inf",$option=array())
    {
        return $this->redis->zRevRangeByScore($key,$start,$end,$option);
    }

    /**
     * @param $key
     * @param $start
     * @param $end
     * @return int
     * 返回order值在start end之间的数量
     */
    public function zCount($key,$start,$end)
    {
        return $this->redis->zCount($key,$start,$end);
    }

    /**
     * @param $key
     * @param $value
     * @return float
     * 返回值为value的order值
     */
    public function zScore($key,$value)
    {
        return $this->redis->zScore($key,$value);
    }

    /**
     * @param $key
     * @param $value
     * @return int
     * 返回集合以score递增加排序后，指定成员的排序号，从0开始。
     */
    public function zRank($key,$value)
    {
        return $this->redis->zRank($key,$value);
    }


    /**
     * @param $key
     * @param $value
     * @return int
     * 返回集合以score递增加排序后，指定成员的排序号，从0开始。
     */
    public function zRevRank($key,$value)
    {
        return $this->redis->zRevRank($key,$value);
    }

    /**
     * @param $key
     * @param $start
     * @param $end
     * @return int 删除成员的数量
     * 删除集合中，score值在start end之间的元素　包括start end
     * min和max可以是-inf和+inf　表示最大值，最小值
     */
    public function zRemRangeByScore($key,$start,$end)
    {
        return $this->redis->zRemRangeByScore($key,$start,$end);
    }

    /**
     * @param $key
     * @return int
     * 返回集合元素个数。
     */
    public function zCard($key)
    {
        return $this->redis->zCard($key);
    }





    /*********************队列操作命令************************/

    /**
     * @param $key
     * @param $value
     * @return int 返回队列长度
     * 在队列头部插入一个元素
     * @example
     * <pre>
     * $redis->lPush('l', 'v1', 'v2', 'v3', 'v4')   // int(4)
     * var_dump( $redis->lRange('l', 0, -1) );
     * //// Output:
     * // array(4) {
     * //   [0]=> string(2) "v4"
     * //   [1]=> string(2) "v3"
     * //   [2]=> string(2) "v2"
     * //   [3]=> string(2) "v1"
     * // }
     * </pre>
     */
    public function lPush($key,$value)
    {
        return $this->redis->lPush($key,$value);
    }

    /**
     * @param $key
     * @param $value
     * @return int 返回队列长度
     * 在队列尾部插入一个元素
     * @example
     * <pre>
     * $redis->rPush('l', 'v1', 'v2', 'v3', 'v4');    // int(4)
     * var_dump( $redis->lRange('l', 0, -1) );
     * //// Output:
     * // array(4) {
     * //   [0]=> string(2) "v1"
     * //   [1]=> string(2) "v2"
     * //   [2]=> string(2) "v3"
     * //   [3]=> string(2) "v4"
     * // }
     * </pre>
     *
     */
    public function rPush($key,$value)
    {
        return $this->redis->rPush($key,$value);
    }

    /**
     * @param $key
     * @param $value
     * @return int 返回队列长度
     * 在队列尾部插入一个元素 如果key不存在，什么也不做
     */
    public function rPushx($key,$value)
    {
        return $this->redis->rPushx($key,$value);
    }



    /**
     * @param $key
     * @param $value
     * @return int 返回队列长度
     * 在队列头插入一个元素 如果key不存在，什么也不做
     */
    public function lPushx($key,$value)
    {
        return $this->redis->lPushx($key,$value);
    }

    /**
     * @param $key
     * @return int 返回队列长度
     */
    public function lLen($key)
    {
        return $this->redis->lLen($key);
    }


    /**
     * @param $key
     * @param $start
     * @param $end
     * @return array
     * 返回队列指定区间的元素
     */
    public function lRange($key,$start,$end)
    {
        return $this->redis->lRange($key,$start,$end);
    }

    /**
     * @param $key
     * @param $index
     * @return String 返回队列中指定索引的元素
     */
    public function lIndex($key,$index)
    {
        return $this->redis->lIndex($key,$index);
    }

    /**
     * @param $key
     * @param $index
     * @param $value
     * @return bool
     * 设定队列中指定index的值。
     */
    public function lSet($key,$index,$value)
    {
        return $this->redis->lSet($key,$index,$value);
    }

    /**
     * @param $key
     * @param $count
     * @param $value
     * @return int
     * 删除值为vaule的count个元素
     * PHP-REDIS扩展的数据顺序与命令的顺序不太一样，不知道是不是bug
     * count>0 从尾部开始
     *  >0　从头部开始
     *  =0　删除全部
     */
    public function lRem($key,$count,$value)
    {
        return $this->redis->lRem($key,$value,$count);
    }

    /**
     * @param $key
     * @return string
     * 删除并返回队列中的头元素。
     */
    public function lPop($key)
    {
        return $this->redis->lPop($key);
    }

    /**
     * @param $key
     * @return string
     * 删除并返回队列中的尾元素
     */
    public function rPop($key)
    {
        return $this->redis->rPop($key);
    }

    /*************redis string 字符串操作命令*****************/

    /**
     * @param $key
     * @param $value
     * @return bool
     * 设置一个key ok
     */
    public function set($key,$value)
    {
        return $this->redis->set($key,$value);
    }

    /**
     * @param $arr
     * @return bool
     * 批量设置key ok
     */
    public function mset($arr)
    {
        return $this->redis->mset($arr);
    }

    /**
     * @param $key
     * @return bool|string
     * 得到一个key ok
     */
    public function get($key)
    {
        return $this->redis->get($key);
    }

    /**
     * @param $arr
     * @return array
     * 返回所有查询键的值 ok
     */
    public function mget($arr)
    {
        return $this->redis->mget($arr) ;
    }


    /**
     * @param $keys
     * ok
     */
    public function delete($keys)
    {
        $this->redis->delete($keys) ;
    }

    /**
     * @param $key
     * @param $expire
     * @param $value
     * @return bool
     * 设置一个有过期时间的key
     */
    public function setex($key,$expire,$value)
    {
        return $this->redis->setex($key,$expire,$value);
    }

    /**
     * @param $key
     * @param $value
     * @return bool
     * 设置一个key,如果key存在,不做任何操作.
     */
    public function setnx($key, $value)
    {
        return $this->redis->setnx($key, $value);
    }



    /*************redis 无序集合操作命令*****************/

    /**
     * @param $key
     * @return array
     * 返回集合中所有元素
     */
    public function sMembers($key)
    {
        return $this->redis->sMembers($key);
    }


    /**
     * @param $key1
     * @param $key2
     * @return array
     * 求2个集合的差集
     */
    public function sDiff($key1,$key2)
    {
        return $this->redis->sDiff($key1,$key2);
    }

    /**
     * 添加集合。由于版本问题，扩展不支持批量添加。这里做了封装
     * @param $key
     * @param string|array $value
     */
    public function sAdd($key,$value)
    {
        if(!is_array($value))
            $arr=array($value);
        else
            $arr=$value;
        foreach($arr as $row)
            $this->redis->sAdd($key,$row);
    }


    /**
     * @param $key
     * @return int
     * 返回无序集合的元素个数
     */
    public function sCard($key)
    {
        return $this->redis->sCard($key);
    }

    /**
     * @param $key
     * @param $value
     * @return int
     * 从集合中删除一个元素
     */
    public function sRem($key,$value)
    {
        return $this->redis->sRem($key,$value);
    }

    /*************redis管理操作命令*****************/

    /**
     * 选择数据库
     * @param int $dbId 数据库ID号
     * @return bool
     */
    public function select($dbId)
    {
        $this->dbId = $dbId;
        return $this->redis->select($dbId);
    }

    /**
     * 清空当前数据库
     * @return bool
     */
    public function flushDB()
    {
        return $this->redis->flushDB();
    }

    /**
     * 返回当前库状态
     * @return array
     */
    public function info()
    {
        return $this->redis->info();
    }

    /**
     * 同步保存数据到磁盘
     */
    public function save()
    {
        return $this->redis->save();
    }

    /**
     * @return bool
     * 异步保存数据到磁盘
     */
    public function bgsave()
    {
        return $this->redis->bgsave();
    }

    /**
     * 返回最后保存到磁盘的时间
     */
    public function lastSave()
    {
        return $this->redis->lastSave();
    }

    /**
     * 返回key,支持*多个字符，?一个字符
     * 只有*　表示全部
     * @param string $key
     * @return array
     */
    public function keys($key)
    {
        return $this->redis->keys($key);
    }


    /**
     * @param $key
     * @return int
     * 删除指定key
     */
    public function del($key)
    {
        return $this->redis->del($key);
    }

    /**
     * @param $key
     * @return bool
     * 判断一个key值是不是存在
     */
    public function exists($key)
    {
        return $this->redis->exists($key);
    }

    /**
     * @param $key
     * @param $expire
     * @return bool
     * 为一个key设定过期时间 单位为秒
     */
    public function expire($key,$expire)
    {
        return $this->redis->expire($key,$expire);
    }

    /**
     * @param $key
     * @return int
     * 返回一个key还有多久过期，单位秒
     */
    public function ttl($key)
    {
        return $this->redis->ttl($key);
    }

    /**
     * @param $key
     * @param $time
     * @return bool
     * 设定一个key什么时候过期，time为一个时间戳
     */
    public function exprieAt($key,$time)
    {
        return $this->redis->expireAt($key,$time);
    }

    /**
     * 关闭服务器链接
     */
    public function close()
    {
        $this->redis->close();
    }

    /**
     * 关闭所有连接
     */
    public static function closeAll()
    {
        foreach(static::$_instance as $o)
        {
            if($o instanceof self)
                $o->close();
        }
    }

    /** 这里不关闭连接，因为session写入会在所有对象销毁之后。
    public function __destruct()
    {
    return $this->redis->close();
    }
     **/


    /**
     * 返回当前数据库key数量
     */
    public function dbSize()
    {
        return $this->redis->dbSize();
    }

    /**
     * 返回一个随机key
     */
    public function randomKey()
    {
        return $this->redis->randomKey();
    }

    /**
     * 得到当前数据库ID
     * @return int
     */
    public function getDbId()
    {
        return $this->dbId;
    }

    /**
     * 返回当前密码
     */
    public function getAuth()
    {
        return $this->auth;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getConnInfo()
    {
        return array(
            'host'=>$this->host,
            'port'=>$this->port,
            'auth'=>$this->auth
        );
    }




    /*********************事务的相关方法************************/

    /**
     * @param $key
     * 监控key,就是一个或多个key添加一个乐观锁
     * 在此期间如果key的值如果发生的改变，刚不能为key设定值
     * 可以重新取得Key的值。
     */
    public function watch($key)
    {
        $this->redis->watch($key);
    }

    /**
     *  取消当前链接对所有key的watch
     *  EXEC 命令或 DISCARD 命令先被执行了的话，那么就不需要再执行 UNWATCH 了
     */
    public function unwatch()
    {
        $this->redis->unwatch();
    }

    /**
     * @param int $type
     * @return \Redis
     * 开启一个事务
     * 事务的调用有两种模式Redis::MULTI和Redis::PIPELINE，
     * Redis::MULTI模式，开启事务，事务块内的多条命令会按照先后顺序被放进一个队列当中，最后由 EXEC 命令在一个原子时间内执行。
     * Redis::PIPELINE 管道模式速度更快，但没有任何保证原子性有可能造成数据的丢失
     */
    public function multi($type=\Redis::MULTI)
    {
        return $this->redis->multi($type);
    }

    /**
     * 执行一个事务
     * 收到 EXEC 命令后进入事务执行，事务中任意命令执行失败，其余的命令依然被执行
     * 假如某个(或某些) key 正处于 WATCH 命令的监视之下，且事务块中有和这个(或这些) key 相关的命令，
     * 那么 EXEC 命令只在这个(或这些) key 没有被其他命令所改动的情况下执行并生效，否则该事务被打断(abort)。
     */
    public function exec()
    {
        $this->redis->exec();
    }

    /**
     * 回滚一个事务
     * 取消事务，放弃执行事务块内的所有命令。
     * 如果正在使用 WATCH 命令监视某个(或某些) key ，那么取消所有监视，等同于执行命令 UNWATCH 。
     */
    public function discard()
    {
        $this->redis->discard();
    }

    /**
     * 测试当前链接是不是已经失效
     * 没有失效返回+PONG
     * 失效返回false
     */
    public function ping()
    {
        return $this->redis->ping();
    }

    public function auth($auth)
    {
        return $this->redis->auth($auth);
    }

    /*********************服务的相关方法************************/
    //$redis->dbSize();//返回当前库中的key的个数
    //$redis->flushAll();//清空整个redis[总true]
    //$redis->flushDB();//清空当前redis库[总true]
    //$redis->save();//同步??把数据存储到磁盘-dump.rdb[true]
    //$redis->bgsave();//异步？？把数据存储到磁盘-dump.rdb[true]
    //$redis->info();//查询当前redis的状态 [verson:2.4.5....]
    //$redis->lastSave();//上次存储时间key的时间[timestamp]
    //$redis->connect('127.0.0.1',6379,1);//短链接，本地host，端口为6379，超过1秒放弃链接
    //$redis->open('127.0.0.1',6379,1);//短链接(同上)
    //$redis->pconnect('127.0.0.1',6379,1);//长链接，本地host，端口为6379，超过1秒放弃链接
    //$redis->popen('127.0.0.1',6379,1);//长链接(同上)
    //$redis->auth('password');//登录验证密码，返回【true | false】
    //$redis->select(0);//选择redis库,0~15 共16个库
    //$redis->close();//释放资源
    //$redis->ping(); //检查是否还再链接,[+pong]
    //$redis->ttl('key');//查看失效时间[-1 | timestamps]
    //$redis->persist('key');//移除失效时间[ 1 | 0]
    //$redis->sort('key',[$array]);//返回或保存给定列表、集合、有序集合key中经过排序的元素，$array为参数limit等！【配合$array很强大】 [array|false]

    /*********************自定义的方法,用于简化操作************************/

    /**
     * @param $prefix
     * @param $ids
     * @return array|bool
     * 得到一组的ID号
     */
    public function hashAll($prefix,$ids)
    {
        if($ids==false)
            return false;
        if(is_string($ids))
            $ids=explode(',', $ids);
        $arr=array();
        foreach($ids as $id)
        {
            $key=$prefix.'.'.$id;
            $res=$this->hGetAll($key);
            if($res!=false)
                $arr[]=$res;
        }

        return $arr;
    }


    /**
     * @param $lkey
     * @param $msg
     * @return string
     * 生成一条消息，放在redis数据库中。使用0号库。
     */
    public function pushMessage($lkey,$msg)
    {
        if(is_array($msg)){
            $msg    = json_encode($msg);
        }
        $key    = md5($msg);

        //如果消息已经存在，删除旧消息，已当前消息为准
        //echo $n=$this->lRem($lkey, 0, $key)."\n";
        //重新设置新消息
        $this->lPush($lkey, $key);
        $this->setex($key, 3600, $msg);
        return $key;
    }


    /**
     * @param $keys
     * @param $dbId
     * @return string
     * 得到条批量删除key的命令
     */
    public function delKeys($keys,$dbId)
    {
        $redisInfo=$this->getConnInfo();
        $cmdArr=array(
            'redis-cli',
            '-a',
            $redisInfo['auth'],
            '-h',
            $redisInfo['host'],
            '-p',
            $redisInfo['port'],
            '-n',
            $dbId,
        );
        $redisStr=implode(' ', $cmdArr);
        $cmd="{$redisStr} KEYS \"{$keys}\" | xargs {$redisStr} del";
        return $cmd;
    }
}