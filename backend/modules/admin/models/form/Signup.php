<?php
namespace backend\modules\admin\models\form;

use Yii;
use backend\modules\admin\models\User;
use yii\base\Model;

/**
 * Signup form
 */
class Signup extends Model
{
    public $adminname;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['adminname', 'filter', 'filter' => 'trim'],
            ['adminname', 'required'],
            ['adminname', 'unique', 'targetClass' => 'backend\modules\admin\models\User', 'message' => 'This username has already been taken.'],
            ['adminname', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'backend\modules\admin\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->adminname = $this->adminname;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
