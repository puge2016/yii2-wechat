<?php
return [

    //**************微信配置信息
    'WE_CORPID'                     => 'wx5cec6d0cd04eb0b7',
    'WE_SECRET'                     => '5NFj7AP5OgaZh2ecX7V_3G96KGRn--oAzsmumeyXTT9pgpBORbQjJ-drw-Vn1FUO',
    'TOKEN_IN'                      => 0, // 0入缓存, 1入文件
    'BAIDU_AK'                      => 'UEtLnHOgz4oFAiksAdFHshMT026zgOHy',
    'YY23'                          => [
        'AgentID'                       => '23',
        'Secret'                        => 'gI8gPjQZdBNmHhZKkeGVOAqsnTKnu6ixCUnW5_pAmyc',
        'Token'                         => 'yQahbTqLW5LmxG856H48sa',
        'EncodingAESKey'                => 'HvtjVhvxoWRmqCnJA16ZDd15Bvilu4ryTLIIIsgcEra',
        'ECHOSTR'                       => false, //false 记录， true 测试回调
    ],

    //**************MQ配置信息
    'MQ'                           => [
        'HOST'           => '127.0.0.1',
        'PORT'           => 61613 ,
        'USERNAME'       => 'admin',
        'PASSWORD'       => 'admin',
        'QUEUE'          => 'clock:check', // 队列名称
    ],

    //**************REDIS配置信息
    'REDIS'                      => [
        'HOST'        => '127.0.0.1',
        'PORT'        => 6379,
        'AUTH'        => 'redis@839427653',
    ],

    //**************默认成员信息
    'STAFF_INFO'                    => [
        'id'                            => '', //员工编号
        'we_avatar'                     => '', // 微信头像
        'we_name'                       => '', // 名字
        'we_gender'                     => '', // 性别
        'we_position'                   => '', // 职位
        'we_department'                 => '', // 部门
        'department'                    => '', // 部门信息
        'times_id'                      => 21920 , // 班次表id
        'rest_id'                       => 15271 , // 休息方案id
        'gps_ids'                       => ['42737', '42738'] , //打卡方式GPS id
        'notice'                        => 0, //消息数目
        'remind_set'                    => [
            'type0'                         => 1, // 早提醒
            'type1'                         => 1, // 晚提醒
            'type2'                         => 1, // 迟到提醒
            'type3'                         => 1 // 考勤信息
        ],
        'status_set'                    => 1 , // 0 保密，1 不保密
    ],

    //**************班次规则 times_id:21920
    'TIMES'                         => [
        'times_id'                      => 21920 ,
        'title'                         => '班次名称S',
        'type'                          => 1, //是否弹性上班
        'checkin_reminder_timeenable'   => 1, //是否签到提醒
        'checkin_reminder_time'         => 510, // 签到提醒时间 八点半
        'checkin_reminder'              => '早上好！打个卡，告诉我你的梦想是什么！', // 签到提醒内容
        'checkout_reminder_timeenable'  => 1, //是否签退提醒
        'checkout_reminder_time'        => '',
        'checkout_reminder'             => '工作一天了，下班前别忘记打卡噢！',
        'work_outside'                  => 1, //是否外勤
        'checkin'                       => 540 , //打卡时间 9点
        'checkout_next'                 => 0 , //0当日1次日
        'checkout'                      => 1110 ,//弹性上班，打卡时间 18.5 点
        'checkin_end'                   => 600 , // 弹性上班， 打卡时间 10点 checkin -- checkin_end
        'checkout_start'                => 1050 , // 弹性上班，打卡时间 17.5 点 checkout_start -- checkout
        'lateenable'                    => 1 , // 是否迟不
        'late'                          => 1 , // 迟到多少分钟不算迟到
        'earlyenable'                   => 1 , // 是否早退不
        'early'                         => 1 ,   // 早退多少分钟不算早退
        'food_start'                    => 720 , // 休息开始 12点
        'food_end'                      => 750 , // 休息结束 12点半
        'duration'                      => 480   // 有效上班时间
    ],

    //**************打卡坐标 GPS
    'GPSS'                          => [
        '42737'                         => [
            'address'                   => '北京市海淀区永丰路28',
            'lat'                       => '40.084391',
            'lng'                       => '116.248465',
            'around'                    => 500
        ],
        '42738'                     => [
            'address'                   => '北京市昌平区顺玮阁小区',
            'lat'                       => '40.120193',
            'lng'                       => '116.349278',
            'around'                    => 500
        ],
    ],

];
