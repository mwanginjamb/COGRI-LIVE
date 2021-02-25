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
                    // 'id' => $model->formName()
            ]);



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
                    <div class="row col-md-12">



                        <div class="col-md-6">

                            <?= $form->field($model, 'Repair_Requisition_No')->textInput(['readonly' => true]) ?>
                            <?= $form->field($model, 'Key')->hiddenInput()->label(false) ?>
                            <?= $form->field($model, 'Vehicle_Registration_No')->dropDownList($vehicles, ['prompt' => 'Select Vehicle']) ?>
                            <?= $form->field($model, 'Employee_No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Fixed_Asset_No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Vehicle_Frame_No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Vehicle_Model')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Requisition_Date')->textInput(['readonly'=> true, 'disabled'=>true]) ?>

                        </div>

                        <div class="col-md-6">
                            <?= $form->field($model, 'Reason_Code')->dropDownList($model->reasoncode,['prompt' => 'Select ...']) ?>
                            <?= $form->field($model, 'Requested_By')->textInput(['readonly'=> true,'disabled'=> true]) ?>
                            <?= $form->field($model, 'Department')->textInput(['readonly'=> true,'disabled'=> true]) ?>
                            <?= $form->field($model, 'Requisition_Status')->textInput(['readonly'=> true,'disabled'=> true]) ?>
                            <?= $form->field($model, 'Total_Cost')->textInput(['readonly'=> true,'disabled'=> true]) ?>
                            <?= $form->field($model, 'Mileage_at_Service_KMS')->textInput(['type'=> 'number']) ?>
                            <?= $form->field($model, 'Service_Date')->textInput(['type'=> 'date']) ?>


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

        // Set Vehicle Regno
        
     $('#repairrequisition-vehicle_registration_no').change(function(e){
        const Vehicle_Registration_No = e.target.value;
        const Repair_Requisition_No = $('#repairrequisition-repair_requisition_no').val();
        if(Repair_Requisition_No.length){
            const url = $('input[name=url]').val()+'repair-requisition/setvregno';
            $.post(url,{'Vehicle_Registration_No': Vehicle_Registration_No,'Repair_Requisition_No': Repair_Requisition_No}).done(function(msg){
                   
                //populate empty form fields with new data
                   
                   $('#repairrequisition-fixed_asset_no').val(msg.Fixed_Asset_No);
                   $('#repairrequisition-key').val(msg.Key);
                   $('#repairrequisition-vehicle_frame_no').val(msg.Vehicle_Frame_No);
                   $('#repairrequisition-vehicle_model').val(msg.Vehicle_Model);
                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-repairrequisition-vehicle_registration_no');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-repairrequisition-vehicle_registration_no');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        
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
