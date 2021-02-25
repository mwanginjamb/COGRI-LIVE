<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/26/2020
 * Time: 10:59 PM
 */



/* @var $this yii\web\View */

$this->title = 'HRMIS - Medical Claims Report';
$this->params['breadcrumbs'][] = ['label' => 'Payroll Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Medical Claims Report', 'url' => ['index']];
?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">My Medical Claims Reports</h3>

                </div>
                <div class="card-body">

                    <?php
                    if(isset($message)){
                        print '<p class="alert alert-info">'.$message.' . </p>';
                    }
                    if($report && !isset($message)){ ?>

                        <iframe src="data:application/pdf;base64,<?= $content; ?>" height="950px" width="100%"></iframe>
                   <?php } ?>



                </div>
            </div>
        </div>
    </div>

<?php
$script  = <<<JS
    $('select[name="payperiods"]').select2();
JS;

$this->registerJs($script, yii\web\View::POS_READY);










