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
$this->params['breadcrumbs'][] = ['label' => 'Overtime List', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'New Request', 'url' => ['create']];
$this->title = 'Update Salary Advance Application';
$model->Start_Time = date('h:i:s', strtotime($model->Start_Time));
$model->End_Time = date('h:i:s', strtotime($model->End_Time));
$model->Date = date('m-d-Y', strtotime($model->Date));
?>
<div class="agenda-document-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form',[
        'model' => $model,
        'programs' => $programs,
        'departments' => $departments
    ]) ?>

</div>
