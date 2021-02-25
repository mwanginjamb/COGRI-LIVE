<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 6:09 PM
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/*print '<pre>';
print_r($model); exit;*/


?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <?php

                if(!Yii::$app->user->isGuest && !empty( Yii::$app->user->identity->Employee[0]->ProfileID)){ //Profile ID for internal user
                    $profileID = Yii::$app->user->identity->Employee[0]->ProfileID;

                    Yii::$app->session->set('ProfileID',$profileID);

                }else if(Yii::$app->session->has('HRUSER')){ //Profile ID for external user
                    $hruser = \common\models\Hruser::findByUsername(Yii::$app->session->get('HRUSER')->username);
                    $profileID =  $hruser->profileID;
                     Yii::$app->session->set('ProfileID',$profileID);
                }

                echo (Yii::$app->session->has('ProfileID') || Yii::$app->recruitment->hasProfile(Yii::$app->session->get('ProfileID')))?

                    \yii\helpers\Html::a('<i class="fa fa-address-book"></i>  Update Profile and Submit Application',['applicantprofile/update','No'=> $profileID],['class' => 'btn btn-outline-warning push-right'])
                    :
                    \yii\helpers\Html::a('<i class="fa fa-address-book"></i>  Create a Profile',['applicantprofile/create','Job_ID'=> Yii::$app->request->get('Job_ID')],['class' => 'btn btn-outline-info push-right'])
                ;






           ?>
            </div>
        </div>
    </div>
</div>


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
                                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                                 ';
    echo Yii::$app->session->getFlash('success');
    print '</div>';
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-blue">
            <div class="card-header">

                <h3 class="card-title">Job Vacancy : <?= $model->Job_Description?></h3>


            </div>
            <div class="card-body">

                <table class="table table-bordered">
                    <tr>
                        <td colspan="2"><b>Job Title: </b><?= !empty($model->Job_Description)?$model->Job_Description: 'Not Set' ?> </td>
                    </tr>

                    <tr>
                        <td><b>Opening Date:</b> <?= !empty($model->Start_Date)?$model->Start_Date: 'Not Set'?></td>
                        <td><b>:Contract Period </b> <?= !empty($model->Contract_Period)?$model->Contract_Period: 'Not Set' ?></td>
                    </tr>

                    <tr>
                        <td><b>Closing Date: </b> <?= !empty($model->End_Date)?$model->End_Date: ' Not Set' ?></td>
                        <td><b>Probation Period : </b> <?= !empty($model->Probation_Period)?$model->Probation_Period: 'Not Set' ?></td>
                    </tr>


                    <tr>

                        <td colspan="2">
                            <b>Reporting Lines</b><br>

                            <?= !empty($model->Position_Reporting_to)?$model->Position_Reporting_to: 'Not Set' ?>
                        </td>
                    </tr>
                </table>





            </div><!--end form card-body-->
        </div>


        <div class="card card-blue">
            <!---Add responsibilities------->
            <div class="card-header">
                <h3 class="card-title">Job Responsibilities</h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                    <table class="table table-bordered" >
                        <?php
                            if(!empty($model->Hr_Job_Responsibilities->Hr_Job_Responsibilities) && sizeof($model->Hr_Job_Responsibilities->Hr_Job_Responsibilities)){
                                foreach($model->Hr_Job_Responsibilities->Hr_Job_Responsibilities as $resp){

                                    if(!empty($resp->Responsibility_Description)){
                                        print '<tr>
                                        <td class="parent"><span>+</span>'.$resp->Responsibility_Description.'</td>';

                                        echo (Yii::$app->recruitment->Responsibilityspecs($resp->Line_No));

                                        print '</tr>';
                                    }

                                }
                            }else{
                                print '<tr>
                                        <td>No responsibilities set yet.</td>
                                    </tr>';
                            }
                        ?>
                    </table>
                    </div>
                </div>
            </div>
        </div>
            <!---end responsibilities------->

        <div class="card card-blue">
            <!-------Add requirements------------>
            <div class="card-header">
                <h3 class="card-title">Job Requirements</h3>
            </div>


            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" >
                            <?php

                            if(!empty($model->Hr_job_requirements->Hr_job_requirements) && sizeof($model->Hr_job_requirements->Hr_job_requirements)){
                                foreach($model->Hr_job_requirements->Hr_job_requirements as $req){
                                    if(!empty($req->Requirement)){
                                        print '<tr>
                                            <td class="parent"><span>+</span>'.$req->Requirement.'</td>';
                                            echo Yii::$app->recruitment->Requirementspecs($req->Line_No);

                                        print'</tr>';
                                    }

                                }
                            }else{
                                print '<tr>
                                            <td>No requirements set yet.</td>
                                        </tr>';
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>

            <!---end requirements------->


        </div>
    </div>
</div>

<?php

$script = <<<JS
    /*Parent-Children accordion*/ 
    
    $('td.parent').find('span').text('+');
    $('td.parent').find('span').css({"color":"red", "font-weight":"bolder", "margin-right": "10px"});    
    $('td.parent').nextUntil('td.parent').slideUp(1, function(){});    
    $('td.parent').click(function(){
            $(this).find('span').text(function(_, value){return value=='-'?'+':'-'}); //to disregard an argument -event- on a function use an underscore in the parameter               
            $(this).nextUntil('td.parent').slideToggle(100, function(){});
     });
JS;

$this->registerJs($script);

