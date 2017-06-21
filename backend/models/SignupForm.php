<?php
namespace backend\models;

use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $adminname;
    public $email;
    public $password;
    public $repassword;
    public $agree = true ;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['adminname', 'trim'],
            ['adminname', 'required'],
            ['adminname', 'unique', 'targetClass' => '\backend\models\Admin', 'message' => 'This adminname has already been taken.'],
            ['adminname', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\backend\models\Admin', 'message' => 'This email address has already been taken.'],

            ['agree', 'boolean'],
            ['agree', 'compare', 'compareValue' => 1, 'operator' => '==', 'message' => 'Please agree this item !'],

//            [['password', 'repassword'], 'required'],
//            [['password', 'repassword'], 'string', 'min' => 6],
            //[['repassword'], 'compare','compareAttribute'=>'password'],
        ];
    }


    /**
     * Signs user up.
     *
     * @return Admin|null the saved model or null if saving fails
     */
    public function signup()
    {
        $user = new Admin();

        if (!$this->validate()) {
            $errors     = $this->getErrors();
            $errInfo    = '';
            foreach ($errors as $key_err => $val_err) {
                $errInfo .= $key_err .' : '. $val_err[0] . '<br />' ;
            }
            \Yii::$app->getSession()->setFlash('error', $errInfo);
            return null;
        }
        $user->adminname = $this->adminname;
        $user->email = $this->email;
        $user->created_ip = \Yii::$app->getRequest()->userIP ;
        $user->created_ipport = (int)$_SERVER['REMOTE_PORT'];
        $user->password_reset_token = md5(uniqid(microtime()));
        $user->salt = $user->ins_make_password(8);
        $user->salt2 = $user->ins_make_password(20);
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }

}
