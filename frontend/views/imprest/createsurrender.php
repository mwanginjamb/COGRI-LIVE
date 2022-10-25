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

$this->title = 'Imprest Surrender Request ';
$this->params['breadcrumbs'][] = ['label' => 'surrenders', 'url' => ['surrenderlist']];
$this->params['breadcrumbs'][] = ['label' => 'imprest Surrender Update', 'url' => ['create-surrender']];

$model->isNewRecord = true;
?>
<div class="leave-document-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_surrenderform', [
        'model' => $model,
        'employees' => $employees,
        'programs' => $programs,
        'departments' => $departments,
        'currencies' => $currencies,
        'imprests' => $imprests,
        'receipts' => $receipts,
    ]) ?>

</div>
