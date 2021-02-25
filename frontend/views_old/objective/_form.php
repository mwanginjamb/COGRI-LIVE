<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 12:13 PM
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="card-body">



                    <?php




                    $form = ActiveForm::begin(); ?>
                <div class="row">
                    <div class="col-md-12">



                            <table class="table">
                                <tbody>




                                    <?php $form->field($model, 'Line_No')->hiddenInput(['readonly' => true])->label(false) ?>

                                    <?= $form->field($model, 'Appraisal_No')->hiddenInput(['readonly' => true])->label(false) ?>

                                    <?= $form->field($model, 'Employee_No')->hiddenInput()->label(false) ?>
                                    <?= $form->field($model, 'Objective')->textarea(['rows' => '3']) ?>
                                    <?= (Yii::$app->session->get('New_Emp_App_Status') == 'Appraisee_Level')?$form->field($model, 'New_Emp_Self_Rating')->textInput(['type' => 'number']):'' ?>
                                    <?= (Yii::$app->session->get('New_Emp_App_Status') == 'Appraisee_Level')?$form->field($model, 'New_Emp_Comments')->textarea(['rows' => 3,'maxlength' => 250]):'' ?>
                                    <?= (Yii::$app->session->get('New_Emp_App_Status') == 'Supervisor_Level')?$form->field($model, 'New_Emp_Supervisor_Rating')->textInput(['type' => 'number']):'' ?>
                                    <?= (Yii::$app->session->get('New_Emp_App_Status') == 'Supervisor_Level')?$form->field($model, 'New_Emp_Supervisor_Comment')->textarea(['rows' => 3,'maxlength' => 250]):'' ?>
                                    <?= (Yii::$app->session->get('New_Emp_App_Status') == 'HR_Level')?$form->field($model, 'New_Emp_Hr_Rating')->textinput(['type' => 'number']):'' ?>
                                    <?= (Yii::$app->session->get('New_Emp_App_Status') == 'HR_Level')?$form->field($model, 'New_Emp_Hr_Comments')->textarea(['rows' => 3,'maxlength' => 250]):'' ?>


                                    <?= ($model->isNewRecord)?$form->field($model, 'Key')->hiddenInput(['readonly'=> true])->label(false):'' ?>











                                </tbody>
                            </table>



                    </div>




                </div>












                <div class="row">

                    <div class="form-group">
                        <?= Html::submitButton(($model->isNewRecord)?'Save':'Update', ['class' => 'btn btn-success']) ?>
                    </div>


                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<?php
$script = <<<JS
 //Submit Rejection form and get results in json    
        $('form').on('submit', function(e){
            e.preventDefault();
            const data = $(this).serialize();
            const url = $(this).attr('action');
            $.post(url,data).done(function(msg){
                    $('.modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
        
                },'json');
        });
JS;

$this->registerJs($script);