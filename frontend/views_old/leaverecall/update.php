<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 12:31 PM
 */


use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\AgendaDocument */
$this->title = 'Update Leave Recall Application';
$this->params['breadcrumbs'][] = ['label' => 'Leaves', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Update Request', 'url' => ['update','No' => $model->Recall_No]];

$model->isNewRecord = false;
/*$now = date('m-d-Y');
$model->Start_Date = date('m-d-Y', strtotime($now.' + 2 days'));*/
?>
<div class="agenda-document-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form',[
        'model' => $model,
        'leaves' => $leaves,
    ]) ?>

</div>
