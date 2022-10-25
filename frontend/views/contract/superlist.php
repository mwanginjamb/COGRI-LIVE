<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/22/2020
 * Time: 5:23 PM
 */



/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'COGRI - Supervisor Contract List';
$this->params['breadcrumbs'][] = ['label' => 'New Renewals List', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => 'Probation Appraisal List', 'url' => ['index']];
?>


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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">

                    <h3 class="card-title">Supervisor Contract Renewal List  &nbsp;    <?= (Yii::$app->controller->action->id == 'index')?Html::a('<i class="fa fa-plus-square"></i> Create a Renewal Request',['create'],['class' => 'add-objective btn btn-outline-info btn-sm']):'' ?></h3>


                </div>
                <div class="card-body">
                    <table class="table table-bordered dt-responsive table-hover" id="appraisal">
                    </table>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="absolute" value="<?= Yii::$app->recruitment->absoluteUrl() ?>">
<?php

$script = <<<JS

    $(function(){
        var absolute = $('input[name=absolute]').val();
         /*Data Tables*/
         
        // $.fn.dataTable.ext.errMode = 'throw';
        
    
          $('#appraisal').DataTable({
           
            //serverSide: true,  
            ajax: absolute+'contract/getsupervisorlist',
            paging: true,
            columns: [
                { title: 'Contract No.' ,data: 'No'},
                { title: 'Employee No' ,data: 'Employee_No'},
                { title: 'Employee Name' ,data: 'Employee_Name'},
               // { title: 'Level Grade' ,data: 'Level_Grade'},
                //{ title: 'Job Title' ,data: 'Job_Title'},
                //{ title: 'Appraisal Period' ,data: 'Appraisal_Period'},
                { title: 'Supervisor User Id' ,data: 'Supervisor_User_Id'},
                { title: 'Employee User Id' ,data: 'Current_User'},
                { title: 'Hr UserId' ,data: 'Hr_User_Id'},
                 { title: 'Status' ,data: 'Status'},
               
                { title: 'Action', data: 'Action' },
                
               
            ] ,                              
           language: {
                "zeroRecords": "No New Renewals to display"
            },
            
            //order : [[ 6, "desc" ]]
            
           
       });
        
       //Hidding some 
       var table = $('#appraisal').DataTable();
       //table.columns([0,6]).visible(false);
    
    /*End Data tables*/
        $('#appraisal').on('click','tr', function(){
            
        });
    });
        
JS;

$this->registerJs($script);






