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

                    $form = ActiveForm::begin([
                        'id' => $model->formName(),
                        //'enableAjaxValidation' => true,
                    ]);
                    //echo $form->errorSummary($model);
                    ?>
                <div class="row">







                        <div class=" row col-md-12">



                            <div class="col-md-6">
                                <?= $form->field($model, 'Line_No')->hiddenInput(['readonly' => true])->label(false) ?>
                                <?= $form->field($model, 'Account_No')->hiddenInput(['readonly' => true,'disabled' => true])->label(false) ?>
                                <?= $form->field($model, 'Account_Name')->hiddenInput(['readonly' => true,'disabled' => true])->label(false) ?>

                                <?= $form->field($model, 'Request_No')->hiddenInput(['readonly' => true])->label(false) ?>
                                <?= $form->field($model, 'PD_Transaction_Code')->
                                dropDownList($transactionTypes,['prompt' => 'Select Transaction Type ..','required' => true]) ?>

                                <?= $form->field($model, 'Description')->textarea(['rows' => 3,'required' => true]) ?>
                                <?= $form->field($model, 'Child_Rate',['enableAjaxValidation' => true])->textInput(['type' => 'number','required' => true,'min' => 1]) ?>
                                <?= $form->field($model, 'No_of_Children',['enableAjaxValidation' => true])->textInput(['type' => 'number','required' => true, 'min' => 1]) ?>
                            </div>

                            <div class="col-md-6">
                                 <?= $form->field($model, 'Global_Dimension_1_Code')->
                                dropDownList($programs,['prompt' => 'Select  ..']) ?>

                                <?= $form->field($model, 'Global_Dimension_2_Code')->
                                dropDownList($departments,['prompt' => 'Select ..']) ?>

                                <?= $form->field($model, 'Sortcut_Dimension_3_Code')->
                                dropDownList($students,['prompt' => 'Select  ..']) ?>
                            </div>

                            <?= $form->field($model, 'Key')->hiddenInput(['readonly'=> true])->label(false) ?>

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
<input type="hidden" name="absolute" value="<?= $absoluteUrl ?>">
<?php
$script = <<<JS


$('#fundsrequisitionline-sortcut_dimension_3_code').select2();


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

         $('#fundsrequisitionline-pd_transaction_code').on('change', function(e){
            e.preventDefault();
                  
            const Line_No = $('#fundsrequisitionline-line_no').val();
            
            
            const url = $('input[name="absolute"]').val()+'fundsrequisitionline/settransactioncode';
            $.post(url,{'Line_No': Line_No,'PD_Transaction_Code': $(this).val()}).done(function(msg){
                   //populate empty form fields with new data
                    console.log(typeof msg);
                    console.table(msg);
                     $('#fundsrequisitionline-account_no').val(msg.Account_No);
                    $('#fundsrequisitionline-account_name').val(msg.Account_Name);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-leaveplanline-start_date');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        disableSubmit();
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-leaveplanline-start_date');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        enableSubmit();
                    }
                   
                    
                    
                },'json');
        });
         
         $('#leaveplanline-end_date').on('change', function(e){
            e.preventDefault();
                  
            const Line_No = $('#leaveplanline-line_no').val();
            
            
            const url = $('input[name="absolute"]').val()+'leaveplanline/setenddate';
            $.post(url,{'Line_No': Line_No,'End_Date': $(this).val()}).done(function(msg){
                   //populate empty form fields with new data
                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string'){ // A string is an error
                        const parent = document.querySelector('.field-leaveplanline-end_date');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        disableSubmit();
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-leaveplanline-end_date');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        enableSubmit();
                    }
                    $('#leaveplanline-days_planned').val(msg.Days_Planned);
                    // $('#leaveplanline-start_date').val(msg.Start_Date);
                    $('#leaveplanline-holidays').val(msg.Holidays);
                    $('#leaveplanline-weekend_days').val(msg.Weekend_Days);
                    $('#leaveplanline-total_no_of_days').val(msg.Total_No_Of_Days);
                    
                },'json');
        });
         
         function disableSubmit(){
             document.getElementById('submit').setAttribute("disabled", "true");
        }
        
        function enableSubmit(){
            document.getElementById('submit').removeAttribute("disabled");
        
        }
JS;

$this->registerJs($script);
