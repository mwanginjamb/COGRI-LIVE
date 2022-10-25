<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->params['breadcrumbs'][] = $this->title;

?>





            <?php $form = ActiveForm::begin(['id' => 'login-form']);

                    if(Yii::$app->session->hasFlash('success'))
                    {
                        print '<div class="alert alert-success">'.Yii::$app->session->getFlash('success').'</div>';
                    }

                    if(Yii::$app->session->hasFlash('error')){
                         print '<div class="alert alert-danger">'.Yii::$app->session->getFlash('error').'</div>';
                    }

            ?>

                <?= $form->field($model, 'username',[
                    'inputTemplate' => '<div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span>{input}</div>',
                    ])
                    ->textInput([
                            'autofocus' => true,
                            'placeholder' => 'Username'
                    ])
                    ->label(false)
?>



                <?= $form->field($model, 'password',[
                    'inputTemplate' => '<div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span>{input}</div>'
                    ])->passwordInput([
                            'Placeholder' => 'Password'
])
                        ->label(false)
?>



                <?= $form->field($model, 'rememberMe')->checkbox() ?>


                <div class="form-group">

                     <?= '<p class="text-white">Click  here to '. Html::a('Reset Password', ['/site/request-password-reset'],['class' => '']). '.</p>' ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-warning', 'name' => 'login-button']) ?>

                    <?= '<p class="text-white">Click  here to '. Html::a('resend', ['/site/resend-verification-email'],['class' => '']). ' verification token .</p>' ?>

                    <?= '<p class="text-white">Don\'t have an account?  '. Html::a('signup', ['/site/signup'],['class' => '']). ' here .</p>' ?>
                </div>

    <?php ActiveForm::end(); ?>



<?php

$style = <<<CSS
    .login-page { 
          background: url("../../images/11 - nyumbani_slider_donate (Medium).jpg") no-repeat center center fixed; 
          -webkit-background-size: cover;
          -moz-background-size: cover;
          -o-background-size: cover;
          background-size: cover;
           backdrop-filter: blur(3px);

    }


    .top-logo {
        display: flex;
        margin-left: 10px;
       
    }
     .top-logo img { 
                width: 120px;
                height: auto;
                position: absolute;
                left: 15px;
                top:15px;
                
          
            }
     .login-logo a  {
        color: #ffffff!important;
        font-family: sans-serif, Verdana;
        font-size: larger;
        font-weight: 400;

     }

    input.form-control {
        border-left: 0!important;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border: 1px solid #f6c844;
    }
    
    span.input-group-text {
        border-right: 0;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border: 1px solid #f6c844;
    }
    
   .card {
    background-color: rgba(0,0,0,.6);
   }
   
   .login-card-body {
     background-color: rgba(0,0,0,.1);
   }

    
    
CSS;

$this->registerCss($style);






    






