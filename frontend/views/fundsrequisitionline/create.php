<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 12:29 PM
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\AgendaDocument */

$this->title = 'Funds Requisition Line Request';
$this->params['breadcrumbs'][] = ['label' => 'Funds Requisition Line', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$model->isNewRecord = true;
//$model->Request_No = Yii::$app->request->get('Request_No');
?>
<div class="leave-document-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'transactionTypes' => $transactionTypes,
        'programs' => $programs,
        'departments' => $departments,
        'students' => $students
    ]) ?>

</div>
