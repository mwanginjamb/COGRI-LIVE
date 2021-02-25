<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/21/2020
 * Time: 2:39 PM
 */

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AdminlteAsset;
use common\widgets\Alert;

AdminlteAsset::register($this);

$webroot = Yii::getAlias(@$webroot);
$absoluteUrl = \yii\helpers\Url::home(true);
$employee = (!Yii::$app->user->isGuest && is_array(Yii::$app->user->identity->employee))?Yii::$app->user->identity->employee[0]:[];
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<?php $this->beginBody() ?>

<body class="hold-transition sidebar-mini layout-fixed accent-info">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark navbar-info">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
                <?php if(!Yii::$app->user->isGuest): ?>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="<?= $absoluteUrl ?>site" class="nav-link">Home</a>
                    </li>

                <?php if(Yii::$app->controller->id == 'applicantprofile'){ ?>

                    <li class="nav-item d-none d-sm-inline-block">
                        <?= Html::a('My Applications',['recruitment/applications'],['class'=>"nav-link"])?>

                    </li>

                <?php } ?>

                <?php endif; ?>
                <!--<li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li>-->
            </ul>

            <!-- SEARCH FORM -->
            <!--<form class="form-inline ml-3">
                <div class="input-group input-group-sm">
                    <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>-->

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto ">
                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="<?= $webroot ?>/dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Brad Diesel
                                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">Call me whenever you can...</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        John Pierce
                                        <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">I got your message bro</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Nora Silvester
                                        <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">The subject goes here</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                    </div>
                </li>
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-th-large"></i>

                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <!--<span class="dropdown-item dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>-->






                        <div class="dropdown-divider"></div>

                        <?= (Yii::$app->user->isGuest)? Html::a('<i class="fas fa-sign-in-alt "></i> Signup','/site/signup/',['class'=> 'dropdown-item']): ''; ?>

                        <div class="dropdown-divider"></div>

                        <?= (Yii::$app->user->isGuest)? Html::a('<i class="fas fa-lock-open"></i> Login','/site/login/',['class'=> 'dropdown-item']): ''; ?>

                        <div class="dropdown-divider"></div>

                        <div class="dropdown-divider"></div>

                        <?= (!Yii::$app->user->isGuest)? Html::a('<i class="fas fa-sign-out-alt"></i> Logout','/site/logout/',['class'=> 'dropdown-item']):''; ?>

                        <div class="dropdown-divider"></div>

                        <?= Html::a('<i class="fas fa-user"></i> Profile',['./employee'],['class'=> 'dropdown-item']); ?>


                    </div>
                </li>
               <!-- <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="false" href="#">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>-->
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar elevation-4 sidebar-light-info">
            <!-- Brand Logo -->
            <a href="<?= $absoluteUrl ?>site" class="brand-link">
                <!--<img src="<?= $webroot ?>/images/Logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                     style="opacity: .8">-->
                <span class="brand-text font-weight-light"><?= Yii::$app->params['generalTitle']?></span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
               <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="<?/*= $webroot */?>/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="<?/*= $absoluteUrl */?>employee/" class="d-block"><?/*= (!Yii::$app->user->isGuest)? ucwords($employee->First_Name.' '.$employee->Last_Name): ''*/?></a>
                    </div>
                </div>-->

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                             with font-awesome or any other icon font library -->


<!--Approval Management -->
                        <?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->isApprover()): ?>
                        <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl('approvals')?'menu-open':'' ?>">

                            <a href="#" class="nav-link <?= Yii::$app->recruitment->currentCtrl('approvals')?'active':'' ?>">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Approval Management
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>approvals" class="nav-link <?= Yii::$app->recruitment->currentaction('approvals','index')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p>Approval Requests</p>
                                    </a>
                                </li>


                            </ul>
                        </li>
                        <?php endif; ?>
<!--end Aprroval Management-->


                        <li class="nav-item has-treeview  <?= Yii::$app->recruitment->currentCtrl(['leave','leavestatement','leaverecall','leaveplan'])?'menu-open':'' ?>">
                            <a href="#" class="nav-link <?= Yii::$app->recruitment->currentCtrl('leave')?'active':'' ?>">
                                <i class="nav-icon fas fa-paper-plane"></i>
                                <p>
                                    Leave Management
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leave/create" class="nav-link <?= Yii::$app->recruitment->currentaction('leave','create')?'active':'' ?> ">
                                        <i class="fa fa-running nav-icon"></i>
                                        <p>New Leave Application</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leave/" class="nav-link <?= Yii::$app->recruitment->currentaction('leave','index')?'active':'' ?>">
                                        <i class="fa fa-door-open nav-icon"></i>
                                        <p>Leave List</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leavestatement/index" class="nav-link <?= Yii::$app->recruitment->currentaction('leavestatement','index')?'active':'' ?>">
                                        <i class="fa fa-file-pdf nav-icon"></i>
                                        <p>Leave Statement  Report</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leaverecall/create/?create=1" class="nav-link <?= Yii::$app->recruitment->currentaction('leaverecall','create')?'active':'' ?>">
                                        <i class="fa fa-recycle nav-icon"></i>
                                        <p>Recall Leave</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leaverecall/index" class="nav-link <?= Yii::$app->recruitment->currentaction('leaverecall','index')?'active':'' ?>">
                                        <i class="fa fa-list nav-icon"></i>
                                        <p>Recall Leave List</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leaveplan/create" class="nav-link <?= Yii::$app->recruitment->currentaction('leaveplan','create')?'active':'' ?>">
                                        <i class="fa fa-directions nav-icon"></i>
                                        <p>New Leave Plan</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>leaveplan/index" class="nav-link <?= Yii::$app->recruitment->currentaction('leaveplan','index')?'active':'' ?>">
                                        <i class="fa fa-list nav-icon"></i>
                                        <p>Leave Plan List</p>
                                    </a>
                                </li>

                            </ul>
                        </li>



                        <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl(Yii::$app->params['profileControllers'])?'menu-open':'' ?>">
                            <a href="#" class="nav-link <?= Yii::$app->recruitment->currentCtrl('recruitment')?'active':'' ?>">
                                <i class="nav-icon fas fa-briefcase " ></i>
                                <p>
                                    Employee Recruitment
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>recruitment/vacancies" class="nav-link <?= Yii::$app->recruitment->currentaction('recruitment','vacancies')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p>Internal Job Vacancies </p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>recruitment/externalvacancies" class="nav-link <?= Yii::$app->recruitment->currentaction('recruitment','externalvacancies')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p>External Job Vacancies </p>
                                    </a>
                                </li>

                               <!-- <li class="nav-item">
                                    <a href="<?/*= $absoluteUrl */?>applicantprofile/create" class="nav-link <?/*= Yii::$app->recruitment->currentaction('applicantprofile',['create','index'])?'active':'' */?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p>Applicant Profile</p>
                                    </a>
                                </li>-->

                                <!--<li class="nav-item">
                                    <a href="<?/*= $absoluteUrl */?>employeerequisition" class="nav-link <?/*= Yii::$app->recruitment->currentaction('employeerequisition','index')?'active':'' */?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p>HR Requsitions List</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?/*= $absoluteUrl */?>employeerequisition/create?create=1" class="nav-link <?/*= Yii::$app->recruitment->currentaction('employeerequisition','create')?'active':'' */?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p>Create HR Requsitions</p>
                                    </a>
                                </li>
-->




                            </ul>
                        </li>

                        <!--Payroll reports -->
                         <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl(['payslip','p9'])?'menu-open':'' ?>">
                            <a href="#" class="nav-link <?= Yii::$app->recruitment->currentCtrl(['payslip','p9'])?'active':'' ?>">
                                <i class="nav-icon fa fa-file-invoice-dollar"></i>
                                <p>
                                    HR Reports
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>payslip" class="nav-link <?= Yii::$app->recruitment->currentaction('payslip','index')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p>Generate Payslip</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>p9" class="nav-link <?= Yii::$app->recruitment->currentaction('p9','index')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p>Generate P9 </p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>medical" class="nav-link <?= Yii::$app->recruitment->currentaction('p9','index')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p>Medical Claim </p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <!--payroll reports-->



                        <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl('appraisal')?'menu-open':'' ?>">
                            <a href="#" title="Performance Management" class="nav-link <?= Yii::$app->recruitment->currentCtrl('appraisal')?'active':'' ?>">
                                <i class="nav-icon fa fa-balance-scale"></i>
                                <p>
                                    Perfomance Mgt.
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">


                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>appraisal" class="nav-link <?= Yii::$app->recruitment->currentaction('appraisal','index')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> Goal Setting</p>
                                    </a>
                                </li>
                                <?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->isSupervisor()):  ?>
                                    <li class="nav-item">
                                        <a href="<?= $absoluteUrl ?>appraisal/submitted" class="nav-link <?= Yii::$app->recruitment->currentaction('appraisal','submitted')?'active':'' ?>">
                                            <i class="fa fa-check-square nav-icon"></i>
                                            <p>Submitted Goals List </p>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>appraisal/approvedappraisals" class="nav-link <?= Yii::$app->recruitment->currentaction('appraisal','approvedappraisals')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p>Approved Goals </p>
                                    </a>
                                </li>
                                <?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->isSupervisor()):  ?>
                                    <li class="nav-item">
                                        <a href="<?= $absoluteUrl ?>appraisal/superapprovedappraisals" class="nav-link <?= Yii::$app->recruitment->currentaction('appraisal','superapprovedappraisals')?'active':'' ?>">
                                            <i class="fa fa-check-square nav-icon"></i>
                                            <p>Approved (Supervisor) </p>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <!--Mid Year Appraisals-->
                                <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl('appraisal')?'menu-open':'' ?>">
                                    <a href="#" class="nav-link <?= Yii::$app->recruitment->currentCtrl('appraisal')?'active':'' ?>">
                                        <i class="nav-icon fa fa-balance-scale"></i>
                                        <p>
                                            Mid Year Appraisals
                                            <i class="fas fa-angle-left right"></i>
                                            <!--<span class="badge badge-info right">6</span>-->
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview"><!--Mid Year Appraisals Menu-->

                                        <li class="nav-item">
                                            <a href="<?= $absoluteUrl ?>appraisal/myappraiseelist" class="nav-link <?= Yii::$app->recruitment->currentaction('appraisal','myappraiseelist')?'active':'' ?>">
                                                <i class="fa fa-check-square nav-icon"></i>
                                                <p>M-Y Appraisal (Appraisee) </p>
                                            </a>
                                        </li>

                                        <?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->isSupervisor()):  ?>

                                            <li class="nav-item">
                                                <a href="<?= $absoluteUrl ?>appraisal/mysupervisorlist" class="nav-link <?= Yii::$app->recruitment->currentaction('appraisal','mysupervisorlist')?'active':'' ?>">
                                                    <i class="fa fa-check-square nav-icon"></i>
                                                    <p>M-Y Appraisal (Supervisor) </p>
                                                </a>
                                            </li>

                                        <?php endif; ?>

                                        <li class="nav-item">
                                            <a href="<?= $absoluteUrl ?>appraisal/myapprovedappraiseelist" class="nav-link <?= Yii::$app->recruitment->currentaction('appraisal','myapprovedappraiseelist')?'active':'' ?>">
                                                <i class="fa fa-check-square nav-icon"></i>
                                                <p>M-Y Approved (Appraisee) </p>
                                            </a>
                                        </li>

                                        <?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->isSupervisor()):  ?>

                                            <li class="nav-item">
                                                <a href="<?= $absoluteUrl ?>appraisal/myapprovedsupervisorlist" class="nav-link <?= Yii::$app->recruitment->currentaction('appraisal','myapprovedsupervisorlist')?'active':'' ?>">
                                                    <i class="fa fa-check-square nav-icon"></i>
                                                    <p>M-Y Approved (Supervisor) </p>
                                                </a>
                                            </li>

                                        <?php endif; ?>




                                    </ul><!--End Mid Year Appraisals menu list-->


                                </li><!--End Mid Year Child Menu list-->

                                <!--end Year Appraisals -->
                                <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl('appraisal')?'menu-open':'' ?>">
                                    <a href="#" class="nav-link <?= Yii::$app->recruitment->currentCtrl('appraisal')?'active':'' ?>">
                                        <i class="nav-icon fa fa-balance-scale"></i>
                                        <p>
                                            End Year Appraisals
                                            <i class="fas fa-angle-left right"></i>
                                            <!--<span class="badge badge-info right">6</span>-->
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview"><!--Mid Year Appraisals Menu-->


                                        <li class="nav-item">
                                            <a href="<?= $absoluteUrl ?>appraisal/eyappraiseelist" class="nav-link <?= Yii::$app->recruitment->currentaction('appraisal','eyappraiseelist')?'active':'' ?>">
                                                <i class="fa fa-check-square nav-icon"></i>
                                                <p>E-Y Appraisals (Appraisee) </p>
                                            </a>
                                        </li>

                                        <?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->isSupervisor()):  ?>
                                            <li class="nav-item">
                                                <a href="<?= $absoluteUrl ?>appraisal/eysupervisorlist" class="nav-link <?= Yii::$app->recruitment->currentaction('appraisal','eysupervisorlist')?'active':'' ?>">
                                                    <i class="fa fa-check-square nav-icon"></i>
                                                    <p>E-Y Appraisals (Supervisor) </p>
                                                </a>
                                            </li>

                                        <?php endif; ?>

                                        <li class="nav-item">
                                            <a href="<?= $absoluteUrl ?>appraisal/eypeer1list" class="nav-link <?= Yii::$app->recruitment->currentaction('appraisal','eypeer1list')?'active':'' ?>">
                                                <i class="fa fa-check-square nav-icon"></i>
                                                <p>E-Y Appraisals (Peer1) </p>
                                            </a>
                                        </li>


                                        <li class="nav-item">
                                            <a href="<?= $absoluteUrl ?>appraisal/eypeer2list" class="nav-link <?= Yii::$app->recruitment->currentaction('appraisal','eypeer2list')?'active':'' ?>">
                                                <i class="fa fa-check-square nav-icon"></i>
                                                <p>E-Y Appraisals (Peer2) </p>
                                            </a>
                                        </li>

                                        <?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->isSupervisor()):  ?>
                                            <li class="nav-item">
                                                <a href="<?= $absoluteUrl ?>appraisal/eyagreementlist" class="nav-link <?= Yii::$app->recruitment->currentaction('appraisal','eyagreementlist')?'active':'' ?>">
                                                    <i class="fa fa-check-square nav-icon"></i>
                                                    <p>E-Y Appraisals (Agreement) </p>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <li class="nav-item">
                                            <a href="<?= $absoluteUrl ?>appraisal/eyappraiseeclosedlist" class="nav-link <?= Yii::$app->recruitment->currentaction('appraisal','eyappraiseeclosedlist')?'active':'' ?>">
                                                <i class="fa fa-check-square nav-icon"></i>
                                                <p>E-Y Closed (Appraisee) </p>
                                            </a>
                                        </li>
                                        <?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->isSupervisor()):  ?>
                                            <li class="nav-item">
                                                <a href="<?= $absoluteUrl ?>appraisal/eysupervisorclosedlist" class="nav-link <?= Yii::$app->recruitment->currentaction('appraisal','eysupervisorclosedlist')?'active':'' ?>">
                                                    <i class="fa fa-check-square nav-icon"></i>
                                                    <p>E-Y Closed (Superviosr) </p>
                                                </a>
                                            </li>

                                        <?php endif; ?>




                                    </ul><!--End Mid Year Appraisals menu list-->


                                </li><!--/ End Year Child Menu list-->











                            </ul>
                        </li>



                        <!-- Start Probation Appraisal -->



                        <!---End Probationary Appraisal -->




                        <!--Contract Management --->




                        <!--end contract Management -->


                        <!--Imprest management --->

                        <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl('imprest')?'menu-open':'menu-close' ?>">
                            <a href="#" title="Performance Management" class="nav-link <?= Yii::$app->recruitment->currentCtrl('imprest')?'active':'' ?>">
                                <i class="nav-icon fa fa-coins"></i>
                                <p>
                                    Imprest Management
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">


                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>imprest/create?requestfor=Self" class="nav-link <?= Yii::$app->recruitment->currentaction('imprest','create')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> Imprest Request (Self)</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>imprest/create?requestfor=Other" class="nav-link <?= Yii::$app->recruitment->currentaction('imprest','create')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> Imprest Request (Other)</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>imprest" class="nav-link <?= Yii::$app->recruitment->currentaction('imprest','index')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> Imprest List</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>imprest/create-surrender?requestfor=Self" class="nav-link <?= Yii::$app->recruitment->currentaction('imprest','create-surrender')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> surrender (Self)</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>imprest/create-surrender?requestfor=Other" class="nav-link <?= Yii::$app->recruitment->currentaction('imprest','create-surrender')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> Surrender (Other)</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>imprest/surrenderlist" class="nav-link <?= Yii::$app->recruitment->currentaction('imprest','surrenderlist')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> Imprest Surrender List</p>
                                    </a>
                                </li>


                            </ul>

                        </li>


                        <!-- Imprest Management -->

                        <!--Fund Requisitions -->


                        <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl('contract')?'menu-open':'' ?>">
                            <a href="#" title="Performance Management" class="nav-link <?= Yii::$app->recruitment->currentCtrl('fund-requisition')?'active':'' ?>">
                                <i class="nav-icon fa fa-money-bill-wave"></i>
                                <p>
                                    Fund Requisition
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">


                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>fund-requisition/create" class="nav-link <?= Yii::$app->recruitment->currentaction('fund-requisition','create')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> New Fund Requisition</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>fund-requisition" class="nav-link <?= Yii::$app->recruitment->currentaction('fund-requisition','index')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> Requisition List</p>
                                    </a>
                                </li>


                            </ul>

                        </li>

                        <!--/Fund Requisitions -->

                        <!-- Salary Advance -->


                        <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl('salaryadvance')?'menu-open':'' ?>">
                            <a href="#" title="Performance Management" class="nav-link <?= Yii::$app->recruitment->currentCtrl('salaryadvance')?'active':'' ?>">
                                <i class="nav-icon fa fa-money-check"></i>
                                <p>
                                    Salary Advance
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">


                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>salaryadvance/create" class="nav-link <?= Yii::$app->recruitment->currentaction('salaryadvance','create')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> New Requisition</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>salaryadvance" class="nav-link <?= Yii::$app->recruitment->currentaction('salaryadvance','index')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> Salary Advance List</p>
                                    </a>
                                </li>


                            </ul>

                        </li>

                        <!--/Salary Advance -->


                        <!-- Overtime -->


                        <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl('overtime')?'menu-open':'' ?>">
                            <a href="#" title="Performance Management" class="nav-link <?= Yii::$app->recruitment->currentCtrl('overtime')?'active':'' ?>">
                                <i class="nav-icon fa fa-clock"></i>
                                <p>
                                    Overtime Management
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">


                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>overtime/create" class="nav-link <?= Yii::$app->recruitment->currentaction('overtime','create')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> New Requisition</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>overtime" class="nav-link <?= Yii::$app->recruitment->currentaction('overtime','index')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> Overtime List</p>
                                    </a>
                                </li>


                            </ul>

                        </li>

                        <!--/Overtime -->


                        <!-- Medical Cover -->


                        <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl('medicalcover')?'menu-open':'' ?>">
                            <a href="#" title="Performance Management" class="nav-link <?= Yii::$app->recruitment->currentCtrl('medicalcover')?'active':'' ?>">
                                <i class="nav-icon fa fa-clinic-medical"></i>
                                <p>
                                    Medical Cover Claim
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">


                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>medicalcover/create" class="nav-link <?= Yii::$app->recruitment->currentaction('medicalcover','create')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> New Claim</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>medicalcover/index" class="nav-link <?= Yii::$app->recruitment->currentaction('medicalcover','index')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> Claims List</p>
                                    </a>
                                </li>


                            </ul>

                        </li>

                        <!--/Medical Cover -->




                        <!-- Drug Requisitions -->


                        <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl('drug-issuance')?'menu-open':'' ?>">
                            <a href="#" title="Performance Management" class="nav-link <?= Yii::$app->recruitment->currentCtrl('drug-issuance')?'active':'' ?>">
                                <i class="nav-icon fa fa-medkit"></i>
                                <p>
                                    Drug Issuance
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">


                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>drug-issuance/create" class="nav-link <?= Yii::$app->recruitment->currentaction('drug-issuance','create')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> New Drug Requisition</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>drug-issuance/index" class="nav-link <?= Yii::$app->recruitment->currentaction('drug-issuance','index')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> Prescriptions List</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>drug-issuance/issued" class="nav-link <?= Yii::$app->recruitment->currentaction('drug-issuance','issued')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> Issued Prescriptions List</p>
                                    </a>
                                </li>


                            </ul>

                        </li>

                        <!--/Drug Requisitions -->


                        <!--Fleet Mgt-->

                        <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl(['vehiclerequisition','fuel','work-ticket','repair-requisition'])?'menu-open':'' ?>">
                            <a href="#" title="Performance Management" class="nav-link <?= Yii::$app->recruitment->currentCtrl('medicalcover')?'active':'' ?>">
                                <i class="nav-icon fa fa-truck-moving"></i>
                                <p>
                                    Fleet Management
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>vehiclerequisition/vehicle-availability" class="nav-link <?= Yii::$app->recruitment->currentaction('vehiclerequisition','vehicle-availability')?'active':'' ?>">
                                        <i class="fa fa-key nav-icon"></i>
                                        <p> Vehicle Availability List</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>vehiclerequisition/create" class="nav-link <?= Yii::$app->recruitment->currentaction('vehiclerequisition','create')?'active':'' ?>">
                                        <i class="fa fa-key nav-icon"></i>
                                        <p> New Booking Req.</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>vehiclerequisition" class="nav-link <?= Yii::$app->recruitment->currentaction('vehiclerequisition','index')?'active':'' ?>">
                                        <i class="fa fa-truck-pickup nav-icon"></i>
                                        <p> Booking Req. List</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>vehiclerequisition/approved-requisitions" class="nav-link <?= Yii::$app->recruitment->currentaction('vehiclerequisition','approved-requisitions')?'active':'' ?>">
                                        <i class="fa fa-check nav-icon"></i>
                                        <p> Approved Req. List</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>fuel/create" class="nav-link <?= Yii::$app->recruitment->currentaction('fuel','create')?'active':'' ?>">
                                        <i class="fa fa-fire nav-icon"></i>
                                        <p> New Fuel Req.</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>fuel" class="nav-link <?= Yii::$app->recruitment->currentaction('fuel','index')?'active':'' ?>">
                                        <i class=" fa fa-dumpster-fire nav-icon"></i>
                                        <p> Fuel Req. List</p>
                                    </a>
                                </li>


                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>work-ticket/create" class="nav-link <?= Yii::$app->recruitment->currentaction('work-ticket','create')?'active':'' ?>">
                                        <i class="fa fa-ticket-alt nav-icon"></i>
                                        <p> New Work Ticket</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>work-ticket" class="nav-link <?= Yii::$app->recruitment->currentaction('work-ticket','index')?'active':'' ?>">
                                        <i class=" fa fa-ticket-alt nav-icon"></i>
                                        <p> Work Ticket List</p>
                                    </a>
                                </li>


                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>repair-requisition/create" class="nav-link <?= Yii::$app->recruitment->currentaction('repair-requisition','create')?'active':'' ?>">
                                        <i class="fa fa-wrench nav-icon"></i>
                                        <p> New Repair Requisition</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>repair-requisition" class="nav-link <?= Yii::$app->recruitment->currentaction('repair-requisition','index')?'active':'' ?>">
                                        <i class=" fa fa-wrench nav-icon"></i>
                                        <p> Repair Req. List</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>repair-requisition/monitoring" class="nav-link <?= Yii::$app->recruitment->currentaction('repair-requisition','monitoring')?'active':'' ?>">
                                        <i class=" fa fa-wrench nav-icon"></i>
                                        <p> Repair Status Monitoring</p>
                                    </a>
                                </li>


                            </ul>

                        </li>


                        <!--/Fleet Mgt-->

                        <!--Procurement-->

                        <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl(['storerequisition','purchase-requisition'])?'menu-open':'' ?>">
                            <a href="#" title="Performance Management" class="nav-link <?= Yii::$app->recruitment->currentCtrl('storerequisition')?'active':'' ?>">
                                <i class="nav-icon fa fa-truck-loading"></i>
                                <p>
                                    Procurement
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">


                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>storerequisition/create" class="nav-link <?= Yii::$app->recruitment->currentaction('storerequisition','create')?'active':'' ?>">
                                        <i class="fa fa-truck-loading nav-icon"></i>
                                        <p> New Store Req.</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>storerequisition" class="nav-link <?= Yii::$app->recruitment->currentaction('storerequisition','index')?'active':'' ?>">
                                        <i class="fa fa-truck-loading nav-icon"></i>
                                        <p> Store Req. List</p>
                                    </a>
                                </li>


                            </ul>



                            <!--Purchase Requisition -->


                            <ul class="nav nav-treeview">


                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>purchase-requisition/create" class="nav-link <?= Yii::$app->recruitment->currentaction('purchase-requisition','create')?'active':'' ?>">
                                        <i class="fa fa-truck-loading nav-icon"></i>
                                        <p> New Purchase Req.</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>purchase-requisition" class="nav-link <?= Yii::$app->recruitment->currentaction('purchase-requisition','index')?'active':'' ?>">
                                        <i class="fa fa-truck-loading nav-icon"></i>
                                        <p> Purchase Req. List</p>
                                    </a>
                                </li>


                            </ul>



                        </li>


                        <!--/Procurement-->


                        <!--Contract Management --->

                        <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl('contractrenewal')?'menu-open':'' ?>">
                            <a href="#" title="Contract Management" class="nav-link <?= Yii::$app->recruitment->currentCtrl('contractrenewal')?'active':'' ?>">
                                <i class="nav-icon fa fa-paperclip"></i>
                                <p>
                                    Contract Renewal
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="<?= $absoluteUrl ?>contractrenewal/create" class="nav-link <?= Yii::$app->recruitment->currentaction('contractrenewal','create')?'active':'' ?>">
                                    <i class="fa fa-check-square nav-icon"></i>
                                    <p> Renew Conctract</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?= $absoluteUrl ?>contractrenewal" class="nav-link <?= Yii::$app->recruitment->currentaction('contractrenewal','index')?'active':'' ?>">
                                    <i class="fa fa-check-square nav-icon"></i>
                                    <p> Contracts Renewal List</p>
                                </a>
                            </li>


                        </ul>

                        </li>

                        <!--end contract Management -->



                        <!-- Start Probation Appraisal -->

                        <li class="nav-item has-treeview <?= Yii::$app->recruitment->currentCtrl('probation')?'menu-open':'' ?>">
                            <a href="#" title="Performance Management" class="nav-link <?= Yii::$app->recruitment->currentCtrl('appraisal')?'active':'' ?>">
                                <i class="nav-icon fa fa-balance-scale"></i>
                                <p>
                                    Probation Appraisal
                                    <i class="fas fa-angle-left right"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview">


                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>probation" class="nav-link <?= Yii::$app->recruitment->currentaction('probation','index')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> Objective Setting</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>probation/superlist" class="nav-link <?= Yii::$app->recruitment->currentaction('probation','superlist')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> Supervisor Appraisal List</p>
                                    </a>
                                </li>



                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>probation/hrlist" class="nav-link <?= Yii::$app->recruitment->currentaction('probation','hrlist')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> HR Appraisal List</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= $absoluteUrl ?>probation/closedlist" class="nav-link <?= Yii::$app->recruitment->currentaction('probation','closedlist')?'active':'' ?>">
                                        <i class="fa fa-check-square nav-icon"></i>
                                        <p> Closed Probation List</p>
                                    </a>
                                </li>


                            </ul>

                        </li>

                        <!---End Probationary Appraisal -->




                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"></h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <!--<li class="breadcrumb-item"><a href="site">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard v1</li>-->
                                <?=
                                Breadcrumbs::widget([
                                'itemTemplate' => "<li class=\"breadcrumb-item\"><i>{link}</i></li>\n", // template for all links
                                'homeLink' => [
                                'label' => Yii::t('yii', 'Home'),
                                'url' => Yii::$app->homeUrl,
                                ],
                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                ])
                                ?>
                            </ol>

                        </div><!-- /.col-6 bread ish -->

                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <?= $content ?>
                    <!-- /.row (main row) -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->

        </div>
        <!-- /.content-wrapper -->


        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; <?= Yii::$app->params['generalTitle'] ?> -   2014 - <?= date('Y') ?>   <a href="#"> <?= strtoupper(Yii::$app->params['demoCompany'])?></a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b><?= Yii::signature() ?></b>
            </div>
        </footer>


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-light">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->




    </div>

</body>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();

/*function currentCtrl($ctrl){
    $controller = Yii::$app->controller->id;

    if(is_array($ctrl)){
        if(in_array($controller,$ctrl)){
            return true;
        }
    }
    if($controller == $ctrl ){
        return true;
    }else{
        return false;
    }
}*/

/*function currentaction($ctrl,$actn){//modify it to accept an array of controllers as an argument--> later please
    $controller = Yii::$app->controller->id;
    $action = Yii::$app->controller->action->id;
    if($controller == $ctrl && $action == $actn){
        return true;
    }else{
        return false;
    }
}*/

?>
