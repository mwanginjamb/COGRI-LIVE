<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/22/2020
 * Time: 5:23 PM
 */



/* @var $this yii\web\View */

$this->title = Yii::$app->params['generalTitle'];
$this->params['breadcrumbs'][] = ['label' => 'Vehicle Requisition', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Approved Vehicle Requisition List', 'url' => ['approved-requisitions']];
$url = \yii\helpers\Url::home(true);
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
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Approved Vehicle Requisition List</h3>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive table-hover" id="table">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

    <input type="hidden" value="<?= $url ?>" id="url" />
<?php

$script = <<<JS

    $(function(){
         /*Data Tables*/
         
         // $.fn.dataTable.ext.errMode = 'throw';
        const url = $('#url').val();
    
          $('#table').DataTable({
           
            //serverSide: true,  
            ajax: url+'vehiclerequisition/approved-list',
            paging: true,
            columns: [
                { title: 'Registration No.' ,data: 'No'},
                { title: 'Requisition Date' ,data: 'Requisition_Date'},
                { title: 'Vehicle Registration No.' ,data: 'Vehicle_Registration_No'},
                { title: 'Reason For Booking' ,data: 'Reason_For_Booking'},
                { title: 'Requested By' ,data: 'Requested_By'},
                { title: 'Department' ,data: 'Department'},
                { title: 'Approved By' ,data: 'Approved_By'},
                { title: 'Booking Requisition Status' ,data: 'Booking_Requisition_Status'},
               
                               
            ] ,                              
           language: {
                "zeroRecords": "No Approved Vehicle Requisitions to display"
            },
            
            order : [[ 0, "desc" ]]
            
           
       });
        
       //Hidding some 
       var table = $('#table').DataTable();
      // table.columns([0,6]).visible(false);
    
    /*End Data tables*/
        $('#table').on('click','tr', function(){
            
        });
    });
        
JS;

$this->registerJs($script);

$style = <<<CSS
    table td:nth-child(7), td:nth-child(8), td:nth-child(9) {
        text-align: center;
    }
CSS;

$this->registerCss($style);







