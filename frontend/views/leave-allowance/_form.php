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
    <div class="col-md-4">

        <?= ($model->Status == 'New')?Html::a('<i class="fas fa-paper-plane"></i> Send Approval Req',
            ['send-for-approval'],
            [
                'class' => 'btn btn-app submitforapproval',
                'data' => [
                    'confirm' => 'Are you sure you want to send this document for approval?',
                    'params'=>[
                        'No'=> $model->No,
                        'employeeNo' => Yii::$app->user->identity->{'Employee_No'},
                    ],
                    'method' => 'get',
                ],
                'title' => 'Submit Approval Request',
                


        ]):'' ?>


        <?= ($model->Status == 'Pending_Approval')?Html::a('<i class="fas fa-times"></i> Cancel Approval Req.',['cancel-request'],['class' => 'btn btn-app submitforapproval',
            'data' => [
            'confirm' => 'Are you sure you want to cancel leave allowance approval request?',
            'params'=>[
                'No'=> $model->No,
            ],
            'method' => 'get',
        ],
            'title' => 'Cancel Approval Request'

        ]):'' ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= Html::encode($this->title) ?></h3>

                <?php if(Yii::$app->session->hasFlash('success')): ?>
                    <div class="alert alert-success"><?= Yii::$app->session->getFlash('success')?></div>
                <?php endif; ?>

                <?php if(Yii::$app->session->hasFlash('error')): ?>
                    <div class="alert alert-danger"><?= Yii::$app->session->getFlash('error')?></div>
                <?php endif; ?>
            </div>
            <div class="card-body">



        <?php

            $form = ActiveForm::begin([
                    // 'id' => $model->formName()
            ]); ?>




            <div class="row">
                <div class="col-md-6">
                    <?= '<p><span>Employee No</span> '.Html::a($model->Employee_No,'#'); '</p>' ?>
                    <?= '<p><span>Employee Name</span> '.Html::a($model->Employee_Name,'#'); '</p>' ?>
                    <?= $form->field($model, 'Request_Type')->textInput(['readonly' => true]) ?>
                    <?= $form->field($model, 'Payroll_Period')->textInput(['readonly' => true]) ?>

                </div>
                <div class="col-md-6">
                   
                    <?= '<p><span>Program Code</span> '.Html::a($model->Global_Dimension_1_Code,'#'); '</p>' ?>
                    <?= '<p><span>Department Code </span> '.Html::a($model->Global_Dimension_2_Code,'#'); '</p>' ?>
                     <?= $form->field($model, 'Amount_Requested')->textInput(['type' => 'number','readonly' => true]) ?>
                    <?= $form->field($model, 'Status')->textInput(['readonly' => true]) ?>
                    <?= (!empty($model->Rejection_Reason))?$form->field($model, 'Rejection_Reason')->textInput(['readonly' => true]):'' ?>
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
                    <h4 class="modal-title" id="myModalLabel" style="position: absolute">Leave Management</h4>
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
   
         // Update Amount Requested

    $('#leave_allowance-amount_requested').change((e) => {
        updateField('LeaveAllowance','Amount_Requested', e);
     });
    
   function updateField(entity,fieldName, ev) {
                const model = entity.toLowerCase();
                const field = fieldName.toLowerCase();
                const formField = '.field-'+model+'-'+fieldName.toLowerCase();
                const keyField ='#'+model+'-key'; 
                const targetField = '#'+model+'-'.field;
                const tget = '#'+model+'-'+field;


                const fieldValue = ev.target.value;
                const Key = $(keyField).val();
                //alert(Key);
                if(Key.length){
                    const url = $('input[name=url]').val()+model+'/setfield?field='+fieldName;
                    $.post(url,{ fieldValue:fieldValue,'Key': Key}).done(function(msg){
                        
                            // Populate relevant Fields
                            
                            
                            // console.log(msg[fieldName]);
                            // console.log(fieldName);
                           
                            $(keyField).val(msg.Key);
                            $(targetField).val(msg[fieldName]);
                            $('#training-training_program').val(msg.Training_Program);

                           
                            if((typeof msg) === 'string') { // A string is an error
                                console.log(formField);
                                const parent = document.querySelector(formField);
                                const helpbBlock = parent.children[2];
                                helpbBlock.innerText = msg;
                                
                            }else{ // An object represents correct details

                                const parent = document.querySelector(formField);
                                const helpbBlock = parent.children[2];
                                helpbBlock.innerText = '';
                                
                            }   
                        },'json');
                }
            
     }
     
        function disableSubmit(){
             document.getElementById('submit').setAttribute("disabled", "true");
        }
        
        function enableSubmit(){
            document.getElementById('submit').removeAttribute("disabled");
        
        }
     
     /*Handle modal dismissal event  */
    $('.modal').on('hidden.bs.modal',function(){
        var reld = location.reload(true);
        setTimeout(reld,1000);
    }); 
     
     
    
     
JS;

$this->registerJs($script);

$style = <<<CSS
    
    

CSS;

$this->registerCss($style);


