<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 6:09 PM
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Renewal - '.$model->No;
$this->params['breadcrumbs'][] = ['label' => 'Performance Management', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Renewal View', 'url' => ['view','Employee_No'=> $model->Employee_No,'No' => $model->No]];
/** Status Sessions */

Yii::$app->session->set('Status',$model->Status);
/* Yii::$app->session->set('MY_Appraisal_Status',$model->MY_Appraisal_Status);
Yii::$app->session->set('EY_Appraisal_Status',$model->EY_Appraisal_Status);
Yii::$app->session->set('isSupervisor',false);*/
?>

<div class="row">
    <div class="col-md-12">
        <div class="card-info">
            <div class="card-header">
                <h3>Contract Renewal  Card </h3>
            </div>
            
            <div class="card-body info-box">

                <div class="row">
                            <?php if($model->Status == 'New'): ?>

                                <div class="col-md-4">

                                    <?= Html::a('<i class="fas fa-forward"></i> submit',['submittosupervisor','No'=> $_GET['No'],'employeeNo' => $_GET['Employee_No']],['class' => 'btn btn-app submitforapproval','data' => [
                                            'confirm' => 'Are you sure you want to submit this contract requisition to supervisor ?',
                                            'method' => 'post',
                                        ],
                                        'title' => 'Submit Contract Renewal to Supervisor'

                                    ]) ?>
                                </div>

                            <?php endif; ?>


                    <?php if($model->Status == 'Supervisor_Level' && $model->Supervisor_User_Id == Yii::$app->user->identity->getId()): ?>

                        <div class="col-md-4">

                            <?= Html::a('<i class="fas fa-forward"></i> submit to HR',['sendtohr','No'=> $_GET['No'],'employeeNo' => $_GET['Employee_No']],['class' => 'btn btn-app submitforapproval','data' => [
                                'confirm' => 'Are you sure you want to submit this appraisal to HR?',
                                'method' => 'post',
                            ],
                                'title' => 'Submit Contract Renewal to HR for Approval'

                            ]) ?>
                        </div>
                        <div class="col-md-4">&nbsp;</div>
                        <div class="col-md-4">

                            <?= Html::a('<i class="fas fa-backward"></i>Send Back',['sendbacktoemployee','No'=> $_GET['No'],'employeeNo' => $_GET['Employee_No']],['class' => 'btn btn-app bg-danger submitforapproval','data' => [
                                'confirm' => 'Are you sure you want to send this contract renewal back to employee ?',
                                'method' => 'post',
                            ],
                                'title' => 'Send Contract Renewal Request Back to Employee.'

                            ]) ?>
                        </div>

                        


                    <?php endif; ?>


                    <?php if($model->Status == 'Hr_Level' && $model->Hr_User_Id == Yii::$app->user->identity->getId() ): ?>

                        <div class="col-md-4">

                            <?= Html::a('<i class="fas fa-forward"></i> Approve',['approve','No'=> $_GET['No'],'employeeNo' => $_GET['Employee_No']],['class' => 'btn bg-success btn-app submitforapproval','data' => [
                                'confirm' => 'Are you sure you want to approve this contract renewal?',
                                'method' => 'post',
                            ],
                                'title' => 'Approve contract renewal.'

                            ]) ?>
                        </div>

                        <div class="col-md-2">&nbsp;</div>

                        <div class="col-md-4">

                            <?= Html::a('<i class="fas fa-backward"></i> Send Back',['sendbacktosupervisor','No'=> $_GET['No'],'employeeNo' => $_GET['Employee_No']],['class' => 'btn btn-app bg-danger submitforapproval','data' => [
                                'confirm' => 'Are you sure you want to send back this contract renewal to supervisor ?',
                                'method' => 'post',
                            ],
                                'title' => 'Send Probation Appraisal Back to Supervisor.'

                            ]) ?>
                        </div>

                        <div class="col-md-2">&nbsp;</div>

                        <div class="col-md-4">

                            <?= Html::a('<i class="fas fa-times"></i> Reject',['reject','No'=> $_GET['No'],'employeeNo' => $_GET['Employee_No']],['class' => 'btn bg-success btn-app submitforapproval','data' => [
                                'confirm' => 'Are you sure you want to Reject this contract renewal?',
                                'method' => 'post',
                            ],
                                'title' => 'Approve contract renewal.'

                            ]) ?>
                        </div>

                    <?php endif; ?>



                    



                </div>

            </div>
           
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">




                <h3 class="card-title">Contract Renewal No. : <?= $model->No?></h3>



                <?php
                    if(Yii::$app->session->hasFlash('success')){
                        print ' <div class="alert alert-success alert-dismissable">
                                 ';
                                    echo Yii::$app->session->getFlash('success');
                        print '</div>';
                    }else if(Yii::$app->session->hasFlash('error')){
                        print ' <div class="alert alert-danger alert-dismissable">
                                 ';
                        echo Yii::$app->session->getFlash('error');
                        print '</div>';
                    }
                ?>
            </div>
            <div class="card-body">


               <?php $form = ActiveForm::begin(); ?>


               <div class="row">
                   <div class=" row col-md-12">
                       <div class="col-md-6">

                           <?= $form->field($model, 'No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                           <?= $form->field($model, 'Employee_No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                           <?= $form->field($model, 'Employee_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>

                           <p class="parent"><span>+</span>

                               <?= $form->field($model, 'Current_Contract_Period')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                               <?= $form->field($model, 'Current_Contract_End_Date')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                               <?= $form->field($model, 'New_Contract_Period')->textInput(['readonly'=> true, 'disabled'=>true]) ?>

                               <?= $form->field($model, 'Justify_Extension')->textarea(['readonly'=> true, 'disabled'=>true]) ?>



                           </p>


                       </div>
                       <div class="col-md-6">

                           <?= $form->field($model, 'Supervisor_User_Id')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                           <?= $form->field($model, 'Current_User')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                           <?= $form->field($model, 'Supervisor_User_Id')->textInput(['readonly'=> true, 'disabled'=>true]) ?>

                           <p class="parent"><span>+</span>

                               <?= $form->field($model, 'Hr_User_Id')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                               <?= $form->field($model, 'Status')->dropDownList($status,[
                                'prompt' => 'Select...','readonly'=>true,'disabled' => true
                                

                            ]) ?>

                               <?= $form->field($model, 'New_Contract_End_Date')->textInput(['readonly'=> true, 'disabled'=>true]) ?>



                                <input type="hidden" id="Key" value="<?= $model->Key ?>">
                                 <input type="hidden" id="Employee_No" value="<?= $model->Employee_No ?>">
                                  <input type="hidden" id="No" value="<?= $model->No ?>">
                           </p>



                       </div>
                   </div>
               </div>




               <?php ActiveForm::end(); ?>



            </div>
        </div><!--end details card-->











    </>
</div>

<!--My Bs Modal template  --->

<div class="modal fade bs-example-modal-lg bs-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel" style="position: absolute">Probation Appraisal</h4>
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


<?php

$script = <<<JS

    $(function(){
      
        
     /*Deleting Records*/
     
     $('.delete, .delete-objective').on('click',function(e){
         e.preventDefault();
           var secondThought = confirm("Are you sure you want to delete this record ?");
           if(!secondThought){//if user says no, kill code execution
                return;
           }
           
         var url = $(this).attr('href');
         $.get(url).done(function(msg){
             $('.modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
         },'json');
     });
      
    
    /*Evaluate KRA*/
        $('.evalkra').on('click', function(e){
             e.preventDefault();
            var url = $(this).attr('href');
            console.log('clicking...');
            $('.modal').modal('show')
                            .find('.modal-body')
                            .load(url); 

        });
        
        
      //Add a training plan
    
     $('.add-objective, .update-objective').on('click',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log('clicking...');
        $('.modal').modal('show')
                        .find('.modal-body')
                        .load(url); 

     });
     
     
     //Update a training plan
    
     $('.update-trainingplan').on('click',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log('clicking...');
        $('.modal').modal('show')
                        .find('.modal-body')
                        .load(url); 

     });
     
     
     //Update/ Evalute Employeeappraisal behaviour -- evalbehaviour
     
      $('.evalbehaviour').on('click',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log('clicking...');
        $('.modal').modal('show')
                        .find('.modal-body')
                        .load(url); 

     });
      
      /*Add learning assessment competence-----> add-learning-assessment */
      
      
      $('.add-learning-assessment').on('click',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log('clicking...');
        $('.modal').modal('show')
                        .find('.modal-body')
                        .load(url); 

     });
      
      /*Update Learning Assessment and Add/update employee objectives/kpis */
      
      $('.update-learning, .add-objective').on('click',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log('clicking...');
        $('.modal').modal('show')
                        .find('.modal-body')
                        .load(url); 

     });
      
      
      
    
    /*Handle modal dismissal event  */
    $('.modal').on('hidden.bs.modal',function(){
        var reld = location.reload(true);
        setTimeout(reld,1000);
    }); 
        
   
    
    /*Divs parenting*/
    
     $('p.parent').find('span').text('+');
    $('p.parent').find('span').css({"color":"red", "font-weight":"bolder"});    
    $('p.parent').nextUntil('p.parent').slideUp(1, function(){});    
    $('p.parent').click(function(){
            $(this).find('span').text(function(_, value){return value=='-'?'+':'-'}); //to disregard an argument -event- on a function use an underscore in the parameter               
            $(this).nextUntil('p.parent').slideToggle(100, function(){});
     });
    
        //Add Career Development Plan
        
        $('.add-cdp').on('click',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
           
            
            console.log('clicking...');
            $('.modal').modal('show')
                            .find('.modal-body')
                            .load(url); 
            
         });//End Adding career development plan
         
         /*Add Career development Strength*/
         
         
        $('.add-cds').on('click',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            
            $('.modal').modal('show')
                            .find('.modal-body')
                            .load(url); 
            
         });
         
         /*End Add Career development Strength*/
         
         
         /* Add further development Areas */
         
            $('.add-fda').on('click',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
                       
            console.log('clicking...');
            $('.modal').modal('show')
                            .find('.modal-body')
                            .load(url); 
            
         });
         
         /* End Add further development Areas */
         
         /*Add Weakness Development Plan*/
             $('.add-wdp').on('click',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
                       
            console.log('clicking...');
            $('.modal').modal('show')
                            .find('.modal-body')
                            .load(url); 
            
         });
         /*End Add Weakness Development Plan*/

         //Change Action taken

         $('select#probation-action_taken').on('change',(e) => {

            const key = $('input[id=Key]').val();
            const Employee_No = $('input[id=Employee_No]').val();
            const No = $('input[id=No]').val();
            const Action_Taken = $('#probation-action_taken option:selected').val();
           
              

            /* var data = {
                "Action_Taken": Action_Taken,
                "No": No,
                "Employee_No": Employee_No,
                "Key": key

             } 
            */
            $.get('./takeaction', {"Key":key,"No":No, "Action_Taken": Action_Taken,"Employee_No": Employee_No}).done(function(msg){
                 $('.modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
                });


            })
    
        
    });//end jquery

    

        
JS;

$this->registerJs($script);

