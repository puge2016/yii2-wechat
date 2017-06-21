<?php
namespace frontend\controllers;


use frontend\models\DepCopy;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json ;

use common\models\LoginForm;
use common\controllers\BaseController ;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\UserChat ;
use common\models\Dep ;

use common\util\Code;
use common\util\Wechat;
use common\util\Api;

/**
 * Site controller
 */
class TestController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
//        $userChat       = new \frontend\models\UserChat() ;
//        $map            = ['staff_id' => '4518002'] ;
//        $result         = $userChat::find()->asArray()->where($map)->one();
//        $eque           = $result['staff_id'] == $map['staff_id'] ;
//
//        $cache          = Yii::$app->getCache();
       // $cache->set('qiumus', 2222 ) ;
//        $result         = $cache->get('qiumus');

//        $m          = new \Memcached();
//        $m->addServer('localhost', 11211);
//        $cache      = $m->get('ss') ;



//        Yii::info($result, 'wechat') ;
//        return true ;

        $staffinfo          = Yii::$app->params['STAFF_INFO'] ;
        $staffinfo['id']    =

        var_dump($staffinfo) ;


        return true ;


    }

    public function actionTest()
    {
        $userChat       = new UserChat() ;
        $userDatas = array(
            'staff_id'      => 45180022,
            'avatar'        => 'https://shp.qpic.cn/bizmp/TRS5bvBm3nAjleo6XibahQGjPDxD7ibeVJ6qHTTC5hFANNprPvlRlxtw/',
            'department'    => '16' ,
            'gender'        => 2,
            'mobile'        => '13051037985',
            'name'          => '门赫2',
            'position'      => '',
            'status'        => 1,
            'userid'        => 'MenMen2'
        );

        $map        = ['staff_id' => $userDatas['staff_id'] ] ;
        $result     = $userChat::findOne($map);
        if ($result) {
            // 更新数据
            $userChat               = $result ;
        }
        $userChat->attributes       = $userDatas ;
        if ($userChat->save()) {
            return true ;
        } else {
            Yii::info($userChat->getErrors(), 'wechat') ;
            return false ;
        }
    }

    public function actionTest2()
    {
        $dep                    = '15,18,19' ;
        $depInfo                = Dep::find()->asArray()->where('id in ('.$dep.')')->select('dname')->all()  ;
        $department             = '';
        foreach ($depInfo as $val_info) {
            $department         .=  $val_info['dname'] .',' ;
        }
        $department                 = rtrim($department, ',') ;

        var_dump($department) ;

    }

    public function actionTest3()
    {
        $userCon        = UserChat::find()->asArray()->where(['userid' => 'qiumu'])->all() ;
        var_dump($userCon) ;
    }

    public function actionTest4()
    {
        $test               = ['e33' => '222'] ;
        return json_encode($test) ;
        // return \yii\helpers\Json::decode($test, true);
    }

    public function actionTest5()
    {
        $users          = new UserChat() ;
        //$deps           = new Dep() ;
        $userInfos      = $users->find()->asArray()->select([
            'name',
            'gender',
            'department',
            'avatar',
            'userid',
            'position',
            'staff_id',
            'userid'
        ])->all() ;

        var_dump($userInfos) ;
    }

    public function actionTest6()
    {
        $weChat         = new Wechat();
        //$result         = $weChat->getAgentList() ;
        $result         = $weChat->getAgentInfo(5) ;

//        $datas          = '{
//    "agentid": 5,
//    "report_location_flag": 0,
//    "logo_mediaid": "xxxxx",
//    "name": "NAME",
//    "description": "DESC",
//    "redirect_domain": "xxxxxx",
//    "isreportenter":0,
//    "home_url":"http://www.qq.com"
//    }';
//        $result         = $weChat->setAgent($datas) ;

        if ($result === false ) {
            Yii::info(Code::getErr(), 'wechat') ;
        } else {
            var_dump($result) ;
        }
    }

    public function actionTest7()
    {
        $value = Yii::$app->params['REDIS']['REDIS_PORT']  ;

        Yii::$app->getRequest()->validateCsrfToken() ;

        var_dump($value) ;
    }

    public function actionTest8()
    {
        $depSql         = new DepCopy() ;
        $datas          = [
            [
                'cid'           => 1,
                'did'           => 842657 ,
                'parentid'      => 2,
                'dname'         => 'xxx2',
                'dsort'         => 22,
                'updated_at'    => time(),
                'created_at'    => time()
            ],
            [
                'cid'           => 1,
                'did'           => 842658 ,
                'parentid'      => 2,
                'dname'         => 'xxx3',
                'dsort'         => 22,
                'updated_at'    => time(),
                'created_at'    => time()
            ]
        ];
        $result         = $depSql->addAll($datas) ;
        var_dump($result) ;
    }

    public function actionTest9()
    {
        $data       = '3333333333333333333333333333333333'.time() ;
        Yii::info($data, 'wechat' ) ;
       var_dump($data);
    }






    public function actionSendMail()
    {
        $mail = \Yii::$app->mailer->compose()
            ->setFrom(['839427653@qq.com' => 'Yii 中文网'])
            ->setTo('839427653@qq.com')
            ->setSubject('邮件发送配置')
            ->setHtmlBody("<br>Yii中文网教程真好！www.yii-china.com")    //发布可以带html标签的文本
            ->send();
        if($mail)
            echo 'success';
        else
            echo 'fail';
    }






    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
