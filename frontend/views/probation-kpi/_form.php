<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 12:13 PM
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$absoluteUrl = \yii\helpers\Url::home(true);
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="card-body">



                <?php


                $disabled = (Yii::$app->session->get('Probation_Recomended_Action') == 'Extend_Probation' && !Yii::$app->session->get('Is_Short_Term')  )? true: false;


                    $form = ActiveForm::begin(); ?>
                <div class="row">
                    <div class="col-md-12">



                            <table class="table">
                                <tbody>




                                    <?= $form->field($model, 'Appraisal_No')->hiddenInput(['readonly' => true])->label(false) ?>

                                    <?= $form->field($model, 'Employee_No')->hiddenInput(['readonly' => true])->label(false) ?>

                                    <?= $form->field($model, 'KRA_Line_No')->hiddenInput(['readonly' => true])->label(false) ?>

                                    <?= (Yii::$app->session->get('Goal_Setting_Status') == 'New')?
                                    $form->field($model, 'Objective')->textArea(['max-length' => 250, 'row' => 4,'placeholder' => 'Your KPI']):
                                     $form->field($model, 'Objective')->textArea(['max-length' => 250, 'row' => 4,'placeholder' => 'Your KPI','readonly' => true,'disabled'=> true])
                                     ?>

                                     <?= (Yii::$app->session->get('Goal_Setting_Status') == 'New')?$form->field($model, 'Weight')->textInput(['type' => 'number']):'' ?>


                                     <?=
                                     (!$disabled && Yii::$app->session->get('Goal_Setting_Status') == 'Closed' && Yii::$app->session->get('Appraisal_Status') == 'Appraisee_Level')?
                                     $form->field($model, 'Appraisee_Self_Rating')->dropDownList($ratings,['prompt' => 'Select Rating...',$disabled])
                                     :
                                     $form->field($model, 'Appraisee_Self_Rating')->dropDownList($ratings,['prompt' => 'Select Rating...','disabled' => true,'readonly' => true]) ?>

                                    


                                     <?= (!$disabled && Yii::$app->session->get('Goal_Setting_Status') == 'Closed' && Yii::$app->session->get('Appraisal_Status') == 'Appraisee_Level')? 

                                     $form->field($model, 'Employee_Comments')->textInput(['type' => 'text'])
                                     :
                                     $form->field($model, 'Employee_Comments')->textInput(['type' => 'text','disabled' => true,'readonly' => true]) 
                                      ?>




                                      <?= (Yii::$app->session->get('Goal_Setting_Status') == 'Closed' && Yii::$app->session->get('Appraisal_Status') == 'Supervisor_Level')?$form->field($model, 'Appraiser_Rating')->dropDownList($ratings,['prompt' => 'Select Rating...']):'' ?>



                                     <?= (Yii::$app->session->get('Goal_Setting_Status') == 'Closed' && Yii::$app->session->get('Appraisal_Status') == 'Supervisor_Level')? $form->field($model, 'Supervisor_Comments')->textInput(['type' => 'text']): '' ?>

                                     <?= (Yii::$app->session->get('Goal_Setting_Status') == 'Closed' && Yii::$app->session->get('Appraisal_Status') == 'Agreement_Level')?$form->field($model, 'Agree')->dropDownList([
                                        true => 'I agree', false => 'I disagree'
                                     ]): '' ?>

                                      <?= (Yii::$app->session->get('Goal_Setting_Status' == 'Closed') && Yii::$app->session->get('Appraisal_Status') == 'Agreement_Level') ? $form->field($model, 'Disagreement_Comments')->textArea(['max-length' => 250, 'row' => 4,'placeholder' => 'Your Comment']):'' ?>

                                      <?= (Yii::$app->session->get('Goal_Setting_Status') == 'Closed' && Yii::$app->session->get('Appraisal_Status') == 'Overview_Manager' )? $form->field($model, 'Overview_Manager_Comments')->textArea(['max-length' => 250, 'row' => 4,'placeholder' => 'Over View Manager Comment']):'' ?>


<!-- If Probation_Recomended_Action is Extend_Probation_Period -->

<?php if( Yii::$app->session->has('Probation_Recomended_Action') && Yii::$app->session->get('Probation_Recomended_Action') == 'Extend_Probation'): ?>
                                       <?= (Yii::$app->session->get('Appraisal_Status') == 'Appraisee_Level')? $form->field($model, 'Appraisee_Self_Rating_Ex')->dropDownList($ratings,['prompt' => 'Select ...']):'' ?>

                                       <?= (Yii::$app->session->get('Appraisal_Status') == 'Supervisor_Level')? $form->field($model, 'Appraiser_Rating_Ex')->dropDownList($ratings,['prompt' => 'Select ...']):'' ?>

                                       

                                     

                                       <?= (Yii::$app->session->get('Appraisal_Status') == 'Appraisee_Level')?$form->field($model, 'Employee_Comments_Ex')->textArea(['type' => 'number']):'' ?>

                                        <?= (Yii::$app->session->get('Appraisal_Status') == 'Supervisor_Level')?$form->field($model, 'End_Year_Supervisor_Comments_E')->textArea(['max-length' => 250, 'row' => 2]):'' ?>


<?php endif; ?>




                                    <?= $form->field($model, 'Key')->hiddenInput(['readonly'=> true])->label(false) ?>
                                     <?= $form->field($model, 'Line_No')->hiddenInput(['readonly'=> true])->label(false) ?>











                                </tbody>
                            </table>



                    </div>




                </div>












                <div class="row">

                    <div class="form-group">
                        <?= Html::submitButton(($model->isNewRecord)?'Save':'Update', ['class' => 'btn btn-success','id'=>'submit']) ?>
                    </div>


                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="url" value="<?= $absoluteUrl ?>">

<?php
$script = <<<JS
 //Submit Rejection form and get results in json    
        $('form').on('submit', function(e){
            e.preventDefault()
            const data = $(this).serialize();
            const url = $(this).attr('action');
            $.post(url,data).done(function(msg){
                    $('.modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
        
                },'json');
        });


/*Set KPI weight*/
        disableSubmit();
        $('#probationkpi-weight').change(function(e){

        const Weight = e.target.value;
        const Appraisal_No = $('#probationkpi-appraisal_no').val();
        const Line_No = $('#probationkpi-line_no').val();
        const Objective = $('#probationkpi-objective').val();
        const Employee_No = $('#probationkpi-employee_no').val();
        const KRA_Line_No = $('#probationkpi-kra_line_no').val();
        const Key = $('#probationkpi-key').val();


        if(Appraisal_No.length){
            
            const url = $('input[name=url]').val()+'probation-kpi/setweight';
            $.post(url,{'Weight': Weight,'Appraisal_No': Appraisal_No,'Line_No': Line_No, 'Objective': Objective,'Employee_No': Employee_No,'KRA_Line_No': KRA_Line_No,'Key': Key  }).done(function(msg){
                   //populate empty form fields with new data
                   
                  
                   $('#probationkpi-key').val(msg.Key);
                  

                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-probationkpi-weight');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        disableSubmit();
                      
                        
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-probationkpi-weight');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        enableSubmit();
                        
                    }
                    
                },'json');
            
        }     
     });

     /* Set Objective */

        $('#probationkpi-objective').change(function(e){

        const Objective = e.target.value;
        const Appraisal_No = $('#probationkpi-appraisal_no').val();
        const Employee_No = $('#probationkpi-employee_no').val();
        const KRA_Line_No = $('#probationkpi-kra_line_no').val();
        const Key = $('#probationkpi-key').val();
      

        if(Objective.length){
            
            const url = $('input[name=url]').val()+'probation-kpi/setkpi';
            $.post(url,{'Objective': Objective, 'Appraisal_No': Appraisal_No, 'Employee_No': Employee_No, 'KRA_Line_No': KRA_Line_No, 'Key': Key}).done(function(msg){
                   //populate empty form fields with new data
                   
                  
                   $('#probationkpi-key').val(msg.Key);
                   $('#probationkpi-line_no').val(msg.Line_No);
                                    

                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-probationkpi-objective');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        disableSubmit();
                      
                        
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-probationkpi-objective');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        enableSubmit();
                        
                    }
                    
                },'json');
            
        }     
     }); 


      function disableSubmit(){
             document.getElementById('submit').setAttribute("disabled", "true");
        }
        
        function enableSubmit(){
            document.getElementById('submit').removeAttribute("disabled");
        
        }



JS;

$this->registerJs($script);
