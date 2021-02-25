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

$this->title = 'Update Imprest Document.';
$this->params['breadcrumbs'][] = ['label' => 'imprest', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Update Imprest Request', 'url' => ['update','No' => $model->No]];

?>
<div class="agenda-document-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form',[
        'model' => $model,
        'employees' => $employees,
        'programs' => $programs,
        'departments' => $departments,
        'currencies' => $currencies
    ]) ?>

</div>
