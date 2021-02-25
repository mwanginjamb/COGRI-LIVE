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
                </div>
                <div class="col-md-6">
                    <?= '<p><span>Program Code</span> '.Html::a($model->_x003C_Global_Dimension_1_Code_x003E_,'#'); '</p>' ?>
                    <?= '<p><span>Department Code </span> '.Html::a($model->Global_Dimension_2_Code,'#'); '</p>' ?>
                </div>
            </div>


                <div class="row">
                    <div class="row col-md-12">



                        <div class="col-md-6">




                            <?= $form->field($model, 'Leave_Code')->dropDownList($leavetypes,['prompt' => 'Select ..']) ?>

                            <?= $form->field($model, 'Start_Date')->textInput(['type' => 'date','required' => true]) ?>
                            <?= $form->field($model, 'Days_To_Go_on_Leave')->textInput(['type' => 'number','required' =>  true,'min'=> 1]) ?>
                            <?= $form->field($model, 'Reliever')->dropDownList($employees,['prompt' => 'Select ..','required'=> true]) ?>
                            <?= $form->field($model, 'Comments')->textarea(['rows'=> 2,'maxlength' => 250]) ?>



                        </div>

                        <div class="col-md-6">



                            <div class="row">
                                <div class="col-md-6 col-sm-12">

                                    <?= $form->field($model, 'End_Date')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                    <?= $form->field($model, 'Total_No_Of_Days')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                    <?= $form->field($model, 'Leave_balance')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                    <?= $form->field($model, 'Reliever_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                    <?= $form->field($model, 'Status')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                </div>
                                <div class="col-md-6 col-sm-12">


                                    <?= $form->field($model, 'Holidays')->textInput(['readonly'=> true,'disabled'=>true]) ?>
                                    <?= $form->field($model, 'Weekend_Days')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                    <?= $form->field($model, 'Balance_After')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                    <?= $form->field($model, 'Reporting_Date')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                    <?= $form->field($model, 'Application_Date')->textInput(['required' => true, 'disabled'=>true]) ?>
                                    <?= $form->field($model, 'Key')->hiddenInput(['required' => true, 'disabled'=>true])->label(false) ?>
                                </div>
                            </div>




                            <p class="parent"><span>+</span>

                            <?= '<p><span>Approval_Entries</span> '.Html::a($model->Approval_Entries,'#'); '</p>' ?>
                            <?= $form->field($model, 'Application_No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>

                            <?= $form->field($model, 'User_ID')->textInput(['required' => true, 'disabled'=>true]) ?>

                            </p>

                        </div>

                    </div>

                </div>












                <div class="row">

                    <div class="form-group">
                        <?= Html::submitButton(($model->isNewRecord)?'Save':'Update', ['class' => 'btn btn-success','id' => 'submit']) ?>
                    </div>


                </div>
                <?php ActiveForm::end(); ?>



                <!---Upload Leave Attachment File-->

                <?php $atform = \yii\widgets\ActiveForm::begin(['id'=>'attachmentform'],['options' => ['enctype' => 'multipart/form-data']]); ?>
                    <?= $atform->errorSummary($Attachmentmodel)?>
                    <button class="btn btn-primary btn-file"><?= $Attachmentmodel->getPath($model->Application_No)?'<i class="fa fa-upload"></i>&nbsp;&nbsp;Update Leave Attachment':'<i class="fa fa-upload"></i>&nbsp;&nbsp;Upload Leave Attachment' ?>
                        <?= $atform->field($Attachmentmodel,'attachmentfile')->fileInput(['id' => 'attachmentfile', 'name' => 'attachmentfile' ])->label(false);?>
                    </button>
                    <?= $atform->field($Attachmentmodel,'Document_No')->hiddenInput(['value' => $model->Application_No])->label(false);?>
                <?php \yii\widgets\ActiveForm::end(); ?>

                <?php if($Attachmentmodel->getPath($model->Application_No)){   ?>

                <iframe src="data:application/pdf;base64,<?= $Attachmentmodel->readAttachment($model->Application_No); ?>" height="950px" width="100%"></iframe>
                <?php }  ?>
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
    $('#attachmentform').hide();
        // Set Leave Type
        
     $('#leave-leave_code').change(function(e){
        const Leave_Code = e.target.value;
        const No = $('#leave-application_no').val();
        if(No.length){
            
            const url = $('input[name=url]').val()+'leave/setleavetype';
            $.post(url,{'Leave_Code': Leave_Code,'No': No}).done(function(msg){
                   //populate empty form fields with new data
                   
                   $('#leave-leave_balance').val(msg.Leave_balance);  
                   $('#leave-key').val(msg.Key);
                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-leave-leave_code');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        disableSubmit();
                        
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-leave-leave_code');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        enableSubmit();
                        
                    }
                    
                },'json');
            
        }     
     });
     
     /*Check if Leave Type requires an attachment */
     
     $('#leave-leave_code').change(function(e){
         e.preventDefault();
          const Leave_Code = e.target.value;
          // Check if leave required an attachment or not
            const Vurl = $('input[name=url]').val()+'leave/requiresattachment?Code='+Leave_Code;
            $.post(Vurl).done(function(msg){
                console.log(msg);
                if(msg.Requires_Attachment){
                    $('#attachmentform').show();
                }else{
                    $('#attachmentform').hide();
                }
            });
         
     });
     /*Set Start Date*/
     
      $('#leave-start_date').blur(function(e){
        const Start_Date = e.target.value;
        const No = $('#leave-application_no').val();
        if(No.length){
            const url = $('input[name=url]').val()+'leave/setstartdate';
            $.post(url,{'Start_Date': Start_Date,'No': No}).done(function(msg){
                   //populate empty form fields with new data
                    $('#leave-leave_balance').val(msg.Leave_balance);
                    $('#leave-key').val(msg.Key);
                   
                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-leave-start_date');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        disableSubmit();
                        
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-leave-start_date');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        enableSubmit();
                        
                    }
                    
                },'json');
        }
     });

     
     /*Set Days to go on leave */
     
     $('#leave-days_to_go_on_leave').blur(function(e){
        const Days_To_Go_on_Leave = e.target.value;
        const No = $('#leave-application_no').val();
        if(No.length){
            const url = $('input[name=url]').val()+'leave/setdays';
            $.post(url,{'Days_To_Go_on_Leave': Days_To_Go_on_Leave,'No': No}).done(function(msg){
                   //populate empty form fields with new data
                   
                    $('#leave-leave_balance').val(msg.Leave_balance);
                    $('#leave-end_date').val(msg.End_Date);
                    $('#leave-total_no_of_days').val(msg.Total_No_Of_Days);
                    $('#leave-reporting_date').val(msg.Reporting_Date);
                    $('#leave-holidays').val(msg.Holidays);
                    $('#leave-weekend_days').val(msg.Weekend_Days);
                    $('#leave-balance_after').val(msg.Balance_After);                    
                    $('#leave-key').val(msg.Key);
                   
                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-leave-days_to_go_on_leave');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                         disableSubmit();
                        
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-leave-days_to_go_on_leave');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = '';
                        enableSubmit();
                        
                    }
                    
                },'json');
        }
     });



     // Commit Releaver

     $('#leave-reliever').change(function(e){
        const Reliever = e.target.value;
        const No = $('#leave-application_no').val();
        if(No.length){
            
            const url = $('input[name=url]').val()+'leave/setreliever';
            $.post(url,{'Reliever': Reliever,'No': No}).done(function(msg){
                   //populate empty form fields with new data
                   
                   $('#leave-reliever').val(msg.Reliever);  
                   $('#leave-key').val(msg.Key);
                   $('#leave-reliever_name').val(msg.Reliever_Name);

                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-leave-reliever');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        disableSubmit();
                        
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-leave-reliever');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        enableSubmit();
                        
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

        const DaysApplied = $('#leave-days_to_go_on_leave').val();

     
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
     
     
    
    $('#attachmentfile').change((e) => {
        $(e.target).closest('form').trigger('submit');
    });
    
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

$style = <<<CSS
    
    
    .btn-file {
        display: flex;
        position: relative;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    
    }

    .btn-file input {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
    }
    
   

CSS;

$this->registerCss($style);


