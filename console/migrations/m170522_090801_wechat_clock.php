<?php

use yii\db\Migration;

class m170522_090801_wechat_clock extends Migration
{
//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//        echo "m170522_090801_wechat_clock cannot be reverted.\n";
//
//        return false;
//    }

//CREATE TABLE `chat_clock` (
//`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '????ID',
//`userid` char(80) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '??ԱID',
//`latitude` decimal(17,14) DEFAULT NULL COMMENT 'γ??',
//`longitude` decimal(17,14) DEFAULT NULL COMMENT '????',
//`point_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
//`point_content` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
//`wetype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '????????',
//`wedate` date DEFAULT NULL COMMENT '????',
//`createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '?ϰ?ʱ??',
//PRIMARY KEY (`id`),
//UNIQUE KEY `userid_createtime` (`userid`,`createtime`),
//KEY `createtime` (`createtime`)
//) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    // Use safeUp/safeDown to run migration code within a transaction
    const TABLE_NAME = '{{%clock}}';
    public function safeUp()
    {
        $tableOptions = null ;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(self::TABLE_NAME,[
            'id'            => $this->primaryKey()->comment('自增ID'),
            'userid'        => $this->char(80)->notNull()->comment('成员微信ID'),
            'latitude'      => $this->decimal(17, 14)->comment('纬度'),
            'longitude'     => $this->decimal(17, 14)->comment('经度'),
            'point_title'   => $this->string()->notNull()->comment('地址'),
            'point_content' => $this->string()->notNull()->comment('详细地址'),
            'wetype'        => $this->integer(1)->notNull()->comment('考勤类别'),
            'wedate'        => $this->date()->comment('考勤日期'),
            'created_at'    => $this->integer()->notNull()->comment('创建时间'),
        ], $tableOptions);
        $this->createIndex('userid_create', self::TABLE_NAME, ['userid', 'created_at'] , true);
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }

}
