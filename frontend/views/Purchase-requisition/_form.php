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
                                    <h5><i class="icon fas fa-times"></i> Error!</h5>
                                ';
            echo Yii::$app->session->getFlash('error');
            print '</div>';
        }
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
                    // 'id' => $model->formName()
            ]);



            ?>
                <div class="row">
                    <div class="row col-md-12">



                        <div class="col-md-6">

                            <?= $form->field($model, 'No')->textInput(['readonly' => true]) ?>
                            <?= $form->field($model, 'Key')->hiddenInput()->label(false) ?>
                            <?= $form->field($model, 'Employee_No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Title')->textInput(['maxlength' => 250]) ?>

                        </div>

                        <div class="col-md-6">
                            <?= $form->field($model, 'Global_Dimension_2_Code')->dropDownList($departments,['prompt' => 'Select Department']) ?>
                            <?= $form->field($model, 'Transaction_Type')->dropDownList(['Purchase' => 'Purchase', 'Fees' => 'Fees'],['prompt' => 'Select Transaction Type ...']) ?>
                            <?= $form->field($model, 'Status')->textInput(['readonly'=> true,'disabled'=> true]) ?>
                            <?= $form->field($model, 'Requisition_Date')->textInput(['type'=> 'date']) ?>
                            <?= $form->field($model, 'Approval_Entries')->textInput(['readonly'=> true,'disabled'=> true]) ?>

                        </div>

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



    <!--My Bs Modal template  --->

    <div class="modal fade bs-example-modal-lg bs-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel" style="position: absolute">Imprest Management</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                </div>

            </div>
        </div>
    </div>
<input type="hidden" name="url" value="<?= $absoluteUrl ?>">
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

        // Set other Employee
        
     $('#salaryadvance-loan_type').change(function(e){
        const loan = e.target.value;
        const No = $('#salaryadvance-no').val();
        if(No.length){
            const url = $('input[name=url]').val()+'salaryadvance/setloantype';
            $.post(url,{'loan': loan,'No': No}).done(function(msg){
                   //populate empty form fields with new data
                   
                   $('#salaryadvance-repayment_period').val(msg.Repayment_Period);
                   $('#salaryadvance-key').val(msg.Key);
                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-imprestcard-employee_no');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-imprestcard-employee_no');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        
                    }
                    
                },'json');
        }
     });
     
     /*Set Program and Department dimension */
     
     $('#salaryadvance-amount_requested').blur(function(e){
        const amount = e.target.value;
        const No = $('#salaryadvance-no').val();
        if(No.length){
            const url = $('input[name=url]').val()+'salaryadvance/setamount';
            $.post(url,{'amount': amount,'No': No}).done(function(msg){
                   //populate empty form fields with new data
                   $('#salaryadvance-take_home').val(msg.Take_Home);
                   $('#salaryadvance-key').val(msg.Key);
                   
                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-imprestcard-global_dimension_1_code');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-imprestcard-global_dimension_1_code');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        
                    }
                    
                },'json');
        }
     });
     
     
     /* set department */
     
     $('#imprestcard-global_dimension_2_code').change(function(e){
        const dimension = e.target.value;
        const No = $('#imprestcard-no').val();
        if(No.length){
            const url = $('input[name=url]').val()+'imprest/setdimension?dimension=Global_Dimension_2_Code';
            $.post(url,{'dimension': dimension,'No': No}).done(function(msg){
                   //populate empty form fields with new data
                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-imprestcard-global_dimension_2_code');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-imprestcard-global_dimension_2_code');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        
                    }
                    
                },'json');
        }
     });
     
     
     /*Set Imprest Type*/
     
     $('#imprestcard-imprest_type').change(function(e){
        const Imprest_Type = e.target.value;
        const No = $('#imprestcard-no').val();
        if(No.length){
            const url = $('input[name=url]').val()+'imprest/setimpresttype';
            $.post(url,{'Imprest_Type': Imprest_Type,'No': No}).done(function(msg){
                   //populate empty form fields with new data
                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-imprestcard-imprest_type');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-imprestcard-imprest_type');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = '';
                        
                         $('.modal').modal('show')
                        .find('.modal-body')
                        .html('<div class="alert alert-success">Imprest Type Update Successfully.</div>');
                        
                    }
                    
                },'json');
        }
     });
     
     
     /* Add Line */
     $('.add-line').on('click', function(e){
             e.preventDefault();
            var url = $(this).attr('href');
            console.log(url);
            $('.modal').modal('show')
                            .find('.modal-body')
                            .load(url); 

        });
     
     /*Handle modal dismissal event  */
    $('.modal').on('hidden.bs.modal',function(){
        var reld = location.reload(true);
        setTimeout(reld,1000);
    }); 
     
     
     
JS;

$this->registerJs($script);
