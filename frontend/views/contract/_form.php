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
                if(Yii::$app->session->hasFlash('success')){
                    print ' <div class="alert alert-success alert-dismissable">
                             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-check"></i> Success!</h5>
 ';
                    echo Yii::$app->session->getFlash('success');
                    print '</div>';
                }else if(Yii::$app->session->hasFlash('error')){
                    print ' <div class="alert alert-danger alert-dismissable">
 
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-check"></i> Error!</h5>
                                ';
                    echo Yii::$app->session->getFlash('error');
                    print '</div>';
                }
                ?>



                <?php $form = ActiveForm::begin(); ?>


                <div class="row">
                    <div class=" row col-md-12">
                        <div class="col-md-6">
                            <?= $form->field($model, 'New_Contract_Period')->textInput(['maxLength' => 3]) ?>


                            <p class="parent"><span>+</span>

                                <?= $form->field($model, 'No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                <?= $form->field($model, 'Employee_No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                <?= $form->field($model, 'Employee_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>

                                <?= $form->field($model, 'Current_Contract_Period')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                <?= $form->field($model, 'Current_Contract_End_Date')->textInput(['readonly'=> true, 'disabled'=>true]) ?>






                            </p>


                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'Justify_Extension')->textarea(['maxLength'=>250,'rows' => 2]) ?>



                            <p class="parent"><span>+</span>

                                <?= $form->field($model, 'Supervisor_User_Id')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                <?= $form->field($model, 'Current_User')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                <?= $form->field($model, 'Supervisor_User_Id')->textInput(['readonly'=> true, 'disabled'=>true]) ?>

                                <?= $form->field($model, 'Hr_User_Id')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                <?= $form->field($model, 'Status')->dropDownList($status,[
                                    'prompt' => 'Select...',


                                ]) ?>

                                <?= $form->field($model, 'New_Contract_End_Date')->textInput(['readonly'=> true, 'disabled'=>true]) ?>



                                <input type="hidden" id="Key" value="<?= $model->Key ?>">
                                <input type="hidden" id="Employee_No" value="<?= $model->Employee_No ?>">

                            </p>



                        </div>
                    </div>
                </div>


                <div class="row">

                    <div class="form-group">
                        <?= Html::submitButton(($model->isNewRecord)?'Save':'Submit Application', ['class' => 'btn btn-success']) ?>
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
       /* $('form').on('submit', function(e){
            e.preventDefault()
            const data = $(this).serialize();
            const url = $(this).attr('action');
            $.post(url,data).done(function(msg){
                    $('.modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
        
                },'json');  
            
        });*/


 /*Divs parenting*/
            
            $('p.parent').find('span').text('+');
            $('p.parent').find('span').css({"color":"red", "font-weight":"bolder"});    
            $('p.parent').nextUntil('p.parent').slideUp(1, function(){});    
            $('p.parent').click(function(){
                    $(this).find('span').text(function(_, value){return value=='-'?'+':'-'}); //to disregard an argument -event- on a function use an underscore in the parameter               
                    $(this).nextUntil('p.parent').slideToggle(100, function(){});
             });
JS;

$this->registerJs($script);

