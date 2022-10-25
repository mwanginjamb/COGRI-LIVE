<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $confirmpassword;
    public $Employee_No;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            [['username', 'Employee_No'], 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
            ['email', 'emailCheck'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['confirmpassword','compare','compareAttribute'=>'password','message'=>'Passwords do not match, try again'],

            ['Employee_No', 'NoCheck']
        ];
    }



    public function emailCheck($attribute, $params)
    {
        $count = \common\models\Employee::find()->where(['E-Mail' => $this->email])->count();

        if(!$count){
            $this->addError($attribute,'E-mail address supplied is not associated with any Employee.');
        }
    }

    public function NoCheck($attribute, $params)
    {
        $count = \common\models\Employee::find()->where(['No_' => $this->Employee_No])->count();

        if(!$count){
            $this->addError($attribute,'Employee Number supplied is not associated with any Employee.');
        }
    }



    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->Employee_No = $this->Employee_No;

        return $user->save() && $this->sendEmail($user);

    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
