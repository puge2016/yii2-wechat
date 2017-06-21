<?php

use yii\db\Migration;

class m170521_055949_wechat_admin extends Migration
{
//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//        echo "m170521_055949_wechat_admin cannot be reverted.\n";
//
//        return false;
//    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction

    */

    const TABLE_NAME = '{{%admin}}';
    public function safeUp()
    {
        $tableOptions = null ;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(self::TABLE_NAME,[
            'adminid'       => $this->primaryKey()->comment('用户ID'),
            'adminname'     => $this->string()->notNull()->unique()->comment('用户名称'),
            'groupid'       => $this->integer()->notNull()->comment('用户组ID'),
            'truename'      => $this->string()->notNull()->comment('真实姓名'),
            'classid'       => $this->integer()->notNull()->comment('部门ID'),
            'loginnum'      => $this->integer()->notNull()->comment('登录次数'),
            'email'         => $this->string()->notNull()->unique()->comment('绑定邮箱'),
            'status'        => $this->integer()->notNull()->comment('状态'),
            'lastip'        => $this->string()->notNull()->comment('lastip'),
            'lastipport'    => $this->string()->notNull()->comment('lastipport'),
            'lasttime'      => $this->integer()->notNull()->comment('lasttime'),
            'preip'         => $this->string()->notNull()->comment('preip'),
            'preipport'     => $this->string()->notNull()->comment('preipport'),
            'pretime'       => $this->integer()->notNull()->comment('pretime'),
            'created_ip'    => $this->string()->notNull()->comment('创建人IP'),
            'created_ipport'=> $this->string()->notNull()->comment('创建人IP端口'),
            'created_at'    => $this->integer()->notNull()->comment('创建时间'),
            'created_by'    => $this->integer()->notNull()->comment('由谁创建'),
            'updated_ip'            => $this->string()->notNull()->comment('updated_ip'),
            'updated_ipport'        => $this->string()->notNull()->comment('端口'),
            'updated_at'            => $this->integer()->notNull()->comment('更新时间'),
            'updated_by'            => $this->integer()->notNull()->comment('更新者'),
            'salt'                  => $this->string()->notNull()->comment('salt'),
            'salt2'                 => $this->string()->notNull()->comment('salt2'),
            'auth_key'              => $this->string(32)->notNull()->comment('auth_key'),
            'password_hash'         => $this->string()->notNull()->comment('password_hash'),
            'password_reset_token'  => $this->string()->notNull()->unique()->comment('token'),
        ],$tableOptions);

        $this->createIndex('created_at', self::TABLE_NAME, ['created_at'] , false);
        $this->createIndex('updated_at', self::TABLE_NAME, ['updated_at'] , false);
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }

}
