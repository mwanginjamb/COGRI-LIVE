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

?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <?= $this->render('_form', [
            'model' => $model,
            'items' => $items,
            'locations' => $locations,
            'students' => $students,
            'animals' => $animals,
            'shades' => $shades,
            'institutions' => $institutions,
            'glaccounts' => $glaccounts
        ]) ?>
    </div>
</div>
