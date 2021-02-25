<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 6:09 PM
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Probation Appraisal - '.$model->Appraisal_No;
$this->params['breadcrumbs'][] = ['label' => 'Performance Management', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Appraisal View', 'url' => ['view','Employee_No'=> $model->Employee_No,'Appraisal_No' => $model->Appraisal_No]];
/** Status Sessions */

Yii::$app->session->set('New_Emp_App_Status',$model->New_Emp_App_Status);
/* Yii::$app->session->set('MY_Appraisal_Status',$model->MY_Appraisal_Status);
Yii::$app->session->set('EY_Appraisal_Status',$model->EY_Appraisal_Status);
Yii::$app->session->set('isSupervisor',false);*/
?>

<div class="row">
    <div class="col-md-12">
        <div class="card-info">
            <div class="card-header">
                <h3>Probation Appraisal Card </h3>
            </div>
            
            <div class="card-body info-box">

                <div class="row">
                            <?php if($model->New_Emp_App_Status == 'Appraisee_Level'): ?>

                                <div class="col-md-4">

                                    <?= Html::a('<i class="fas fa-forward"></i> submit',['submit','appraisalNo'=> $_GET['Appraisal_No'],'employeeNo' => $_GET['Employee_No']],['class' => 'btn btn-app submitforapproval','data' => [
                                            'confirm' => 'Are you sure you want to submit this probation appraisal to supervisor ?',
                                            'method' => 'post',
                                        ],
                                        'title' => 'Submit Goals for Approval'

                                    ]) ?>
                                </div>

                            <?php endif; ?>


                    <?php if($model->New_Emp_App_Status == 'Supervisor_Level' && $model->Supervisor_User_Id == Yii::$app->user->identity->getId()): ?>

                        <div class="col-md-4">

                            <?= Html::a('<i class="fas fa-forward"></i> submit to HR',['submittohr','appraisalNo'=> $_GET['Appraisal_No'],'employeeNo' => $_GET['Employee_No']],['class' => 'btn btn-app submitforapproval','data' => [
                                'confirm' => 'Are you sure you want to submit this appraisal to HR?',
                                'method' => 'post',
                            ],
                                'title' => 'Submit Goals for Approval'

                            ]) ?>
                        </div>
                        <div class="col-md-4">&nbsp;</div>
                        <div class="col-md-4">

                            <?= Html::a('<i class="fas fa-backward"></i>Send Back',['backtoemp','appraisalNo'=> $_GET['Appraisal_No'],'employeeNo' => $_GET['Employee_No']],['class' => 'btn btn-app bg-danger submitforapproval','data' => [
                                'confirm' => 'Are you sure you want to send this probation appraisal back to appraisee ?',
                                'method' => 'post',
                            ],
                                'title' => 'Submit Goals for Approval'

                            ]) ?>
                        </div>

                        


                    <?php endif; ?>


                    <?php if($model->New_Emp_App_Status == 'HR_Level' && $model->Hr_UserId == Yii::$app->user->identity->getId() ): ?>

                        <div class="col-md-4">

                            <?= Html::a('<i class="fas fa-forward"></i> Approve',['close','appraisalNo'=> $_GET['Appraisal_No'],'employeeNo' => $_GET['Employee_No']],['class' => 'btn bg-success btn-app submitforapproval','data' => [
                                'confirm' => 'Are you sure you want to approve this probation appraisal?',
                                'method' => 'post',
                            ],
                                'title' => 'Approve and Close Probation Appraisal.'

                            ]) ?>
                        </div>

                        <div class="col-md-4">&nbsp;</div>

                        <div class="col-md-4">

                            <?= Html::a('<i class="fas fa-backward"></i> Send Back',['backtosuper','appraisalNo'=> $_GET['Appraisal_No'],'employeeNo' => $_GET['Employee_No']],['class' => 'btn btn-app bg-danger submitforapproval','data' => [
                                'confirm' => 'Are you sure you want to send back this probation appraisal to supervisor ?',
                                'method' => 'post',
                            ],
                                'title' => 'Send Probation Appraisal Back to Supervisor.'

                            ]) ?>
                        </div>



                    <?php endif; ?>


                     <?=  ($model->New_Emp_App_Status == 'Closed')?Html::a('<i class="fas fa-book-open"></i> P.A Report',['report','appraisalNo'=> $_GET['Appraisal_No'],'employeeNo' => $_GET['Employee_No']],[
                        'class' => 'btn btn-app bg-success  pull-right',
                        'title' => 'Generate Performance Appraisal Report',
                        'target'=> '_blank',
                        'data' => [
                            // 'confirm' => 'Are you sure you want to send appraisal to peer 2?',
                            'params'=>[
                                'appraisalNo'=> $_GET['Appraisal_No'],
                                'employeeNo' => $_GET['Employee_No'],
                            ],
                            'method' => 'post',]
                    ]):'';
                    ?>
                    



                </div>

            </div>
           
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">




                <h3 class="card-title">Appraisal : <?= $model->Appraisal_No?></h3>



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

                           <?= $form->field($model, 'Appraisal_No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                           <?= $form->field($model, 'Employee_No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                           <?= $form->field($model, 'Employee_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>

                           <p class="parent"><span>+</span>
                               <?= $form->field($model, 'Job_Title')->textInput(['readonly'=> true, 'disabled'=>true]) ?>

                               <?= $form->field($model, 'Appraisal_Period')->textInput(['readonly'=> true, 'disabled'=>true]) ?>


                               <?= $form->field($model, 'Appraisal_Start_Date')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                               <?= $form->field($model, 'Created_By')->textInput(['readonly'=> true, 'disabled'=>true]) ?>


                           </p>


                       </div>
                       <div class="col-md-6">

                           <?= $form->field($model, 'Supervisor_User_Id')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                           <?= $form->field($model, 'Employee_User_Id')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                           <?= $form->field($model, 'Supervisor_No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>

                           <p class="parent"><span>+</span>

                               <?= $form->field($model, 'Hr_UserId')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                               <?= $form->field($model, 'New_Emp_App_Status')->dropDownList($appraisal_status,[
                                'prompt' => 'Select...',
                                

                            ]) ?>
                               <?= $form->field($model, 'Action_Taken')->dropDownList($action,[
                                'prompt' => 'Select...',
                                'rel'=> $model->Employee_No,
                                'rev' => $model->Appraisal_No,
                            ]) ?>


                                <input type="hidden" id="Key" value="<?= $model->Key ?>">
                                 <input type="hidden" id="Employee_No" value="<?= $model->Employee_No ?>">
                                  <input type="hidden" id="Appraisal_No" value="<?= $model->Appraisal_No ?>">
                           </p>



                       </div>
                   </div>
               </div>




               <?php ActiveForm::end(); ?>



            </div>
        </div><!--end details card-->


        <!--Objectives card -->

        <div class="card">
            <div class="card-header">
                <div class="card-title">Employee Appraisal Objectives   <?= ($model->New_Emp_App_Status == 'Appraisee_Level')?Html::a('<i class="fa fa-plus-square"></i> Add Objective',['objective/create','Employee_No'=>$_GET['Employee_No'],'Appraisal_No' => $_GET['Appraisal_No']],['class' => 'add-objective btn btn-outline-info']):'' ?></div>
            </div>

            <div class="card-body">





                <?php if(is_array($model->getObjectives())){ //show Objectives ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td><b>Objective</b></td>
                            <td><b>Self Rating</b></td>
                            <td><b> Appraisee Comments</b></td>
                            <td><b>Supervisor Rating</b></td>
                            <td><b>Supervisor Comment</b></td>
                            <td><b>New Emp Hr Rating</b></td>
                            <td><b>New Emp Hr Comments</b></td>
                            <td><b>Action</b></td>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                           // print '<pre>'; print_r($model->getObjectives()); exit;

                         foreach($model->getObjectives() as $obj):
                            $evaluateLink = Html::a('Evaluate',['objective/update','Line_No'=> $obj->Line_No,'Employee_No'=>$_GET['Employee_No'],'Appraisal_No' => $_GET['Appraisal_No']],['class' => 'update-objective btn btn-xs btn-outline-info']);
                             $deleteLink = Html::a('<i class="fa fa-trash"></i>',['objective/delete','Key'=> $obj->Key ],['class'=>'delete btn btn-outline-danger btn-xs']);
                         ?>
                                <tr>

                                    <td><?= !empty($obj->Objective)?$obj->Objective:'Not Set' ?></td>
                                    <td><?= !empty($obj->New_Emp_Self_Rating)?$obj->New_Emp_Self_Rating:'Not Set' ?></td>
                                    <td><?= !empty($obj->New_Emp_Comments)?$obj->New_Emp_Comments:'Not Set' ?></td>
                                    <td><?= !empty($obj->New_Emp_Supervisor_Rating)?$obj->New_Emp_Supervisor_Rating:'Not Set' ?></td>
                                    <td><?= !empty($obj->New_Emp_Supervisor_Comment)?$obj->New_Emp_Supervisor_Comment:'Not Set' ?></td>
                                    <td><?= !empty($obj->New_Emp_Hr_Rating)?$obj->New_Emp_Hr_Rating:'Not Set' ?></td>
                                    <td><?= !empty($obj->New_Emp_Hr_Comments)?$obj->New_Emp_Hr_Comments:'Not Set' ?></td>
                                    <td><?= $evaluateLink.' | '.$deleteLink ?></td>
                                </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php } ?>
            </div>
        </div>

        <!--objectives card -->








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
        
    /*Parent-Children accordion*/ 
    
    $('tr.parent').find('span').text('+');
    $('tr.parent').find('span').css({"color":"red", "font-weight":"bolder"});    
    $('tr.parent').nextUntil('tr.parent').slideUp(1, function(){});    
    $('tr.parent').click(function(){
            $(this).find('span').text(function(_, value){return value=='-'?'+':'-'}); //to disregard an argument -event- on a function use an underscore in the parameter               
            $(this).nextUntil('tr.parent').slideToggle(100, function(){});
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
            const Appraisal_No = $('input[id=Appraisal_No]').val();
            const Action_Taken = $('#probation-action_taken option:selected').val();
           
              

            /* var data = {
                "Action_Taken": Action_Taken,
                "Appraisal_No": Appraisal_No,
                "Employee_No": Employee_No,
                "Key": key

             } 
            */
            $.get('./takeaction', {"Key":key,"Appraisal_No":Appraisal_No, "Action_Taken": Action_Taken,"Employee_No": Employee_No}).done(function(msg){
                 $('.modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
                });


            })
    
        
    });//end jquery

    

        
JS;

$this->registerJs($script);

