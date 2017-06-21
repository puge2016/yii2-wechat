<?php

use yii\db\Migration;

class m170522_072946_wechat_user_chat extends Migration
{
/*    public function up()
    {

    }

    public function down()
    {
        echo "m170522_072946_wechat_user_chat cannot be reverted.\n";

        return false;
    }*/

//    CREATE TABLE `chat_user_chat` (
//    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
//    `password` char(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
//    `staff_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '员工编号',
//    `avatar` text COLLATE utf8_unicode_ci NOT NULL COMMENT '头像链接',
//    `department` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '部门ID',
//    `gender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
//    `mobile` char(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '手机',
//    `name` char(80) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '成员名字',
//    `position` char(80) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '职位',
//    `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
//    `userid` char(80) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '成员ID',
//    `weixinid` char(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '微信号',
//    `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
//    `updatetime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
//    PRIMARY KEY (`id`),
//    UNIQUE KEY `staff_id` (`staff_id`),
//    KEY `createtime` (`createtime`),
//    KEY `updatetime` (`updatetime`)
//    ) ENGINE=InnoDB AUTO_INCREMENT=1040 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    // Use safeUp/safeDown to run migration code within a transaction
    const TABLE_NAME = '{{%user_chat}}';
    public function safeUp()
    {
        $tableOptions = null ;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME,[
            'id'            => $this->primaryKey()->comment('自增ID'),
            'staff_id'      => $this->integer()->notNull()->unique()->comment('员工编号'),
            'avatar'        => $this->string()->notNull()->comment('微信头像'),
            'department'    => $this->string(255)->notNull()->comment('部门'),
            'gender'        => $this->integer(1)->notNull()->comment('性别'),
            'mobile'        => $this->char(30)->notNull()->comment('手机号'),
            'name'          => $this->char(80)->notNull()->comment('成员名字'),
            'position'      => $this->char(80)->notNull()->comment('职位'),
            'status'        => $this->integer(1)->notNull()->comment('状态'),
            'userid'        => $this->char(80)->notNull()->unique()->comment('成员ID'),
            'weixinid'      => $this->char(30)->notNull()->comment('微信ID'),
            'created_at'    => $this->integer()->notNull()->comment('创建时间'),
            'updated_at'    => $this->integer()->notNull()->comment('更新时间'),
        ], $tableOptions) ;
        $this->createIndex('created_at', self::TABLE_NAME, ['created_at'] , false) ;
        $this->createIndex('updated_at', self::TABLE_NAME, ['updated_at'] , false) ;
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }

}
