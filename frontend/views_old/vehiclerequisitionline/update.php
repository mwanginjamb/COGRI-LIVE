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

$this->title = 'Update Line.';
$model->Booking_Date = (date('Y',strtotime($model->Booking_Date)) == '0001')?$model->Booking_Date = date('Y-m-d'):$model->Booking_Date;
//$model->End_Date = (date('Y',strtotime($model->End_Date)) == '0001')?$model->End_Date = date('Y-m-d'):$model->End_Date;

?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <?= $this->render('_form', [
            'model' => $model,
            'vehicles' => $vehicles,
        ]) ?>
    </div>
</div>
