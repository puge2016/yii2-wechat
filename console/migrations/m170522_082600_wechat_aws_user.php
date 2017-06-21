<?php

use yii\db\Migration;

class m170522_082600_wechat_aws_user extends Migration
{
/*    public function up()
    {

    }

    public function down()
    {
        echo "m170522_082600_wechat_aws_user cannot be reverted.\n";

        return false;
    }*/

//    CREATE TABLE `chat_aws_user` (
//    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
//    `staff_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '员工编码',
//    `userid` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '员工账号',
//    `status` tinyint(1) NOT NULL DEFAULT '0',
//    PRIMARY KEY (`id`),
//    KEY `staff_id` (`staff_id`)
//    ) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    // Use safeUp/safeDown to run migration code within a transaction
    const TABLE_NAME = '{{%aws_user}}';
    public function safeUp()
    {
        $tableOptions = null ;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(self::TABLE_NAME,[
            'id'        => $this->primaryKey()->comment('自增ID'),
            'staff_id'  => $this->bigInteger()->notNull()->unique()->comment('员工编码'),
            'userid'    => $this->char(80)->notNull()->unique()->comment('微信成员ID'),
            'status'    => $this->integer(1)->notNull()->comment('状态'),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
