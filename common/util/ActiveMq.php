<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/10
 * Time: 13:00
 */

namespace common\util;


/*
 * 基于rest 无状态连接activemq rest方式(基于HTTP)
 * 基于Stomp 保持连接(Stomp基于TCP协议) 当前使用
 * 8161(web管理页面端口）
 * 61616（activemq服务监控端口）
*/

class ActiveMq
{
    /* 属性声明 */
    //消息中间件用户名
    private $username ;
    //消息中间件密码
    private $password ;
    private $host ;
    private $port ;
    private $amq_url ;
    private $_stomp ;
    //队列名称
    private $queue ;
    private $err ;

    //单例
    private static $_instance ;

    /* 方法声明 */
    private function __construct($config){
        $this->username     = $config['username'] ;
        $this->password     = $config['password'] ;
        $this->host         = $config['host'] ;
        $this->port         = $config['port'] ;
        $this->amq_url      = 'tcp://'. $this->host .':'. $this->port ;
        $this->queue        = '/queue/';
        /* connection */
        try {
            $this->_stomp = new \Stomp($this -> amq_url, $this -> username, $this -> password );
        } catch(\StompException $e) {
            // 如果连接错误不提示？？
            $this->err = 'Connection failed: ' . $e->getMessage() ;
        }
    }

    public static function getInstance($config)
    {
        if(! (self::$_instance instanceof self) ) {
            self::$_instance = new self($config);
        }
        return self::$_instance;
    }

    /**
     *
     * 禁止克隆
     */
    private function __clone(){}

    /**
     * 生产消息
     * @param string $data 待发送数据
     * @param $q
     * @param $headers
     * @return bool
     */
    public function sendMessage($data='', $q, $headers)
    {
        if(!$data){
            $this->err  = '发送的数据有误!';
            return false ;
        }
        $headers        = array_merge(array("persistentx" => "true"), $headers) ;
        $result         = $this->_stomp->send($this->queue . $q, $data, $headers ) ;
        if(!$result){
            $this->err  = '发送消息失败'.$this->_stomp->error();
            return false;
        }
        return true;
    }

    /**
     * Get the current stomp session ID
     *
     * @return string stomp session ID if it exists, or FALSE otherwise
     */
    public function getSessionId()
    {
        $session        = $this->_stomp->getSessionId() ;
        return $session ;
    }

    /**
     * @param $q
     * @return mixed
     * 订阅队列
     */
    public function subscribe($q)
    {
        $headers        = array("activemq.prefetchSize" => 1) ;
        $subscribe      = $this->_stomp->subscribe($this->queue . $q, $headers) ;
        return $subscribe ;
    }

    /**
     * @param $q
     * @return mixed
     * 释放订阅
     */
    public function unsubscribe($q)
    {
        $headers        = array("activemq.prefetchSize" => 1) ;
        $subscribe      = $this->_stomp->unsubscribe($this->queue . $q, $headers) ;
        return $subscribe ;
    }

    /**
     * @param $q
     * @return bool|object
     * 读取队列
     */
    public function readFrame($q)
    {
        $subscribe      = $this->subscribe($q) ;
        if ($subscribe) {
            // 判断是否有消息
            if ($this->_stomp->hasFrame()) {
                return $this->_stomp->readFrame() ;
                // 获取消息内容
            } else {
                $this->err  = '该队列没有消息!' ;
                return false ;
            }
        } else {
            $this->err  = '订阅错误!' ;
            return false ;
        }
    }

    /**
     * @param $frame
     * @return bool
     * 移除队列
     */
    public function ack($frame)
    {
        $result         = $this->_stomp->ack($frame) ;
        if ($result) {
            return true ;
        } else {
            $this->err  = '移除队列失败'.$this->_stomp->error();
            return false ;
        }
    }

    /**
     * 开始一个事务
     * @param $transaction_id
     * @return bool
     */
    public function begin($transaction_id )
    {
        return $this->_stomp->begin($transaction_id ) ;
    }

    /**
     * @param $transaction_id
     * @return bool
     * 订阅事务
     */
    public function commit($transaction_id )
    {
        return $this->_stomp->commit($transaction_id ) ;
    }

    /**
     * @param $t
     * @return bool
     * Rolls back a transaction in progress 回滚事务
     */
    public function abort($t)
    {
        $result = $this->_stomp->abort($t) ;
        if ($result) {
            return true ;
        } else {
            $this->err = 'rollback error!';
            return false ;
        }
    }

    /**
     * 返回当前密码
     */

    public function getUsername()
    {
        return $this->username ;
    }

    public function getPassword()
    {
        return $this->password;
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
            'host'      =>$this->host,
            'port'      =>$this->port,
            'username'  =>$this->username,
            'password'  =>$this->password
        );
    }
    public function stomp()
    {
        return $this->_stomp ;
    }

    public function getErr()
    {
        return $this->err ;
    }


    /**
     *
     *关闭连接
     */
    public function close()
    {
        unset($this->_stomp) ;
    }

}