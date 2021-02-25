<?php

/* @var $this yii\web\View */

$this->title = Yii::$app->params['generalTitle'];
$this->params['breadcrumbs'][] = ['label' => 'Dashboard', 'url' => ['index']];
$this->params['breadcrumbs'][] = '';

/*print '<pre>';
print_r(Yii::$app->user->identity->employee);
exit;*/

?>

<section class="content">
    <div class="container-fluid">






        <div class="row">



            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="card card-info card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                 src="https://via.placeholder.com/150/cccccc/FFFFFF/?text=<?= !empty(Yii::$app->user->identity->Employee[0]->Last_Name)?Yii::$app->user->identity->Employee[0]->Last_Name: 'User'?>"
                                 alt="User profile picture">
                        </div>

                        <h3 class="profile-username text-center"><?= (!empty( $employee->First_Name) && !empty( $employee->Last_Name))? $employee->First_Name.' '.$employee->Last_Name:'';  ?></h3>

                        <p class="text-muted text-center"><?= !empty($employee->Title)?$employee->Title:'' ?></p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b><i class="fa fa-phone-alt"></i></b> <a class="float-right"><?= !empty($employee->Mobile_Phone_No)?$employee->Mobile_Phone_No:'' ?></a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fa fa-mail-bulk"></i></b><a class="float-right"><?= !empty($employee->E_Mail)?$employee->E_Mail:'' ?></a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fa fa-hourglass-start"></i></b> <a title="Length of Service" class="float-right"><?= !empty($employee->Service_Period)?$employee->Service_Period:'' ?></a>
                            </li>
                        </ul>

                        <a href="mailto:<?= !empty($supervisor)?$supervisor->Company_E_Mail: 'Not Set' ?>" class="btn btn-info btn-block"><b>Email Supervisor</b></a>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- About Me Box -->
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">About Me</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <strong><i class="fas fa-book mr-1"></i> Job Title</strong>

                        <p class="text-muted">
                            <?= !empty($employee->Job_Description)?$employee->Job_Description:'' ?>
                        </p>

                        <hr>

                        <strong><i class="fas fa-person-booth mr-1"></i> Gender</strong>

                        <p class="text-muted"><?= !empty($employee->Gender)?$employee->Gender:'' ?> </p>

                        <hr>

                        <strong><i class="fas fa-birthday-cake mr-1"></i> Age</strong>

                        <p class="text-muted">
                            <?= !empty($employee->Age)?$employee->Age:''?>
                        </p>

                        <hr>

                        <strong><i class="far fa-file-alt mr-1"></i> Date of Join:</strong>

                        <p class="text-muted"><?= !empty($employee->Employment_Date)?$employee->Employment_Date:'' ?></p>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->


            <!--start col ---9-->



            <div class="col-md-9">



                <!-- Info boxes -->
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <a href="<?= Yii::$app->recruitment->absoluteUrl().'recruitment/vacancies' ?>" target="_blank">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-briefcase"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">HR Vacancies</span>
                                    <span class="info-box-number"><?= Yii::$app->dashboard->getVacancies() ?>
                                      <small></small>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </a>
                    </div>

                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>


                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Open Approvals</span>
                                <span class="info-box-number"><?= Yii::$app->dashboard->getOpenApprovals() ?>
                                  <small></small>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>

                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>
                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-thumbs-up"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Approved Reqs.</span>
                                <span class="info-box-number"><?= Yii::$app->dashboard->getApprovedApprovals() ?></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-times"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Rejected Reqs.</span>
                                <span class="info-box-number"><?= Yii::$app->dashboard->getRejectedApprovals() ?></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>


                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>

                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Staff Count</span>
                                <span class="info-box-number"><?= number_format(Yii::$app->dashboard->getStaffCount())?></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>

                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <a href="<?= Yii::$app->recruitment->absoluteUrl().'leave/activeleaves' ?>" target="_blank">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-paper-plane"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Staff on Leave</span>
                                    <span class="info-box-number"><?= number_format(Yii::$app->dashboard->getOnLeave())?></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </a>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>

                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <a href="<?= Yii::$app->recruitment->absoluteUrl().'recruitment/internalapplications' ?>" target="_blank">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-paper-plane"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">My Applications</span>
                                    <span class="info-box-number"><?= number_format(Yii::$app->dashboard->getInternalapplications())?></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </a>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->


                </div>
                <!-- /.row -->

                <div class="row">

                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Super Approved</span>
                                <span class="info-box-number"><?= number_format(Yii::$app->dashboard->getSuperApproved())?></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Super Rejected</span>
                                <span class="info-box-number"><?= number_format(Yii::$app->dashboard->getSuperRejected())?></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->


                </div>




                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">My Leave Balances</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive table-hover" id="leavebances">
                            <thead>
                                <tr>
                                    <th>Leave Type</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php



                            foreach($balances as $key => $val){
                                if($key == 'Key') {
                                    continue;
                                }
                                elseif ($key == 'Annual_Leave_Bal' || $key == 'Compasionate_Leave_Bal'){
                                    print '
                                    <tr>
                                        <td>'.$key.'</td><td>'.$val.'</td>
                                     </tr>
                                     ';
                                }


                            } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

</section>



<?php


$script = <<<JS

    $(function(){
         /*Data Tables*/
        
         
         //$.fn.dataTable.ext.errMode = 'throw';
        
    
          $('#onleave').DataTable({
           
           paging: true,                                        
           language: {
                "zeroRecords": "No records to display"
            },
            
            order : [[ 0, "desc" ]]            
           
       });
          
          $('#leavebances').DataTable({});
        
       //Hidding some 
       var table = $('#onleave').DataTable();
       //table.columns([3,4,5,6,]).visible(false);
    
    /*End Data tables*/
        $('#onleave').on('click','tr', function(){
            
        });
    });
        
JS;

$this->registerJs($script);
