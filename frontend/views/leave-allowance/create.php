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

$this->title = 'Leave Allowance Application';
$this->params['breadcrumbs'][] = ['label' => 'Leaves Allowance Applications', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'New Request', 'url' => ['create']];
//$this->params['breadcrumbs'][] = $this->title;

$model->isNewRecord = true;

?>
<div class="leave-document-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
