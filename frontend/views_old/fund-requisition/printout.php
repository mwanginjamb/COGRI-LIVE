<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/26/2020
 * Time: 10:59 PM
 */



/* @var $this yii\web\View */

$this->title = 'HRMIS - Document Printout';
$this->params['breadcrumbs'][] = ['label' => 'Fund Requisitions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'printout', 'url' => ['print-requisition']];
?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Document Printout <?= \yii\helpers\Html::a('<i class="fa fa-backward"></i> Back to Document',['view','No' => $_GET['No']],['class' => 'btn btn-outline-warning mx-4']) ?></h3>

                </div>
                <div class="card-body">

                    <!--<iframe src="data:application/pdf;base64,<?/*= $content; */?>" height="950px" width="100%"></iframe>-->
                    <?php
                    if(Yii::$app->session->hasFlash('error')){
                        print '<p class="alert alert-info">'.Yii::$app->session->getFlash('error').' . </p>';
                    }
                    if($report){ ?>

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










