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
$this->params['breadcrumbs'][] = ['label' => 'Vehicle Repair Requisitions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Update Request', 'url' => ['update','No' => $model->Repair_Requisition_No]];
$this->title = 'Vehicle Repair Requisition';

?>
<div class="agenda-document-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form',[
        'model' => $model,
        'vehicles' => $vehicles
    ]) ?>

</div>
