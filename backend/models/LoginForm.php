<?php
namespace backend\models;

use Yii;
use yii\base\Model ;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $adminname;
    public $password;
    public $rememberMe = true;

    private $_admin;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // adminname and password are both required
            [['adminname', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $admin = $this->getAdmin();
            if (!$admin || !$admin->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect adminname or password.');
            }
        }
    }

    /**
     * Logs in a admin using the provided adminname and password.
     *
     * @return bool whether the admin is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getAdmin(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds Admin by [[adminname]]
     *
     * @return Admin|null
     */
    protected function getAdmin()
    {
        if ($this->_admin === null) {
            $this->_admin = Admin::findByadminname($this->adminname);
        }

        return $this->_admin;
    }
}
