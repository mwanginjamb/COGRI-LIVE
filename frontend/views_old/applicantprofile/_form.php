<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/26/2020
 * Time: 5:41 AM
 */




use yii\helpers\Html;
use yii\widgets\ActiveForm;
//$this->title = 'AAS - Employee Profile'
?>



<!--THE STEPS THING--->
<div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Applicant : <?= $model->No.' - '. $model->First_Name. ' '. $model->Last_Name?></h2>
            </div>
        </div>
               <?= $this->render('_steps') ?>
    </div>
</div>

<!--END THE STEPS THING--->


<div class="row">
    <div class="col-md-12">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Personal info for Profile: <?= Yii::$app->session->has('ProfileID')? Yii::$app->session->get('ProfileID'): '' ?></h3>
            </div>
            <div class="card-body">

                 

                <div class="row">
                    <div class=" row col-md-12">
                        <div class="col-md-6">
                            <?= $form->field($model, 'No')->textInput(['readonly'=> true,'disabled' => true]) ?>
                            <?= $form->field($model, 'First_Name')->textInput() ?>
                            <?= $form->field($model, 'Middle_Name')->textInput() ?>
                            <?= $form->field($model, 'Last_Name')->textInput() ?>


                        </div>
                        <div class="col-md-6">

                            <?= $form->field($model, 'Initials')->textInput() ?>

                            <?= $form->field($model, 'Full_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Gender')->dropDownList([
                                '_blank_' => '_blank_',
                                'Female' => 'Female',
                                'Male' => 'Male',
                            ],['prompt' => 'Select Gender']) ?>

                        </div>
                    </div>
                </div>







            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Communication</h3>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class=" row col-md-12">
                        <div class="col-md-6">
                            <?= $form->field($model, 'Address')->textInput(['placeholder' => 'Postal Address']) ?>
                            <?= $form->field($model, 'Country_Region_Code')->dropDownList($countries, ['prompt' => 'Select Country of Origin..']) ?>
                            <?= $form->field($model, 'City')->dropDownList($countries, ['prompt' => 'Select City..']) ?>
                            <?= $form->field($model, 'Post_Code')->dropDownList($countries, ['prompt' => 'Select Post Code..']) ?>

                        </div>
                        <div class="col-md-6">

                            <?= $form->field($model, 'County')->textInput(['placeholder'=> 'County']) ?>
                            <?= $form->field($model, 'Phone_No')->textInput(['placeholder'=> 'Phone Number']) ?>
                            <?= $form->field($model, 'Mobile_Phone_No')->textInput(['placeholder '=> 'Cell Number']) ?>

                            <?= $form->field($model, 'E_Mail')->textInput(['placeholder'=> 'E-mail Address', 'type' => 'email']) ?>


                        </div>
                    </div>
                </div>







            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Other Details</h3>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class=" row col-md-12">
                        <div class="col-md-6">

                            <?= $form->field($model, 'imageFile')->fileInput(['accept' => 'image/*']) ?>
                            <div class="image">
                                <?php if(!empty($model->ImageUrl)){
                                    print '<img src="'.Yii::$app->recruitment->absoluteUrl().$model->ImageUrl.'">';
                                } ?>
                            </div>


                            <?= $form->field($model, 'Birth_Date')->textInput(['type' => 'date']) ?>




                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model,'Key')->hiddenInput()->label(false) ?>

                            <?= $form->field($model, 'Disabled')->checkbox() ?>
                            <?= $form->field($model, 'Describe_Disability')->textInput() ?>
                            <?= $form->field($model, 'NHIF_Number')->textInput() ?>


                            <?= $form->field($model, 'NSSF_Number')->textInput() ?>
                            <?= $form->field($model, 'KRA_Number')->textInput() ?>
                            <?= $form->field($model, 'Age')->textInput(['readonly' => true,'disabled'=> true]) ?>
                            <?= $form->field($model, 'National_ID')->textInput() ?>

                            <?= $form->field($model, 'Marital_Status')->dropDownList([
                                'Single' => 'Single',
                                'Married' => 'Married',
                                'Separated' => 'Separated',
                                'Divorced' => 'Divorced',
                                'Widow_er' => 'Widow_er',
                                'Other' => 'Other'
                            ],['prompt' => 'Select Status']) ?>


                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="form-group">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    </div>


                </div>







            </div>
        </div>











        <?php ActiveForm::end(); ?>
    </div>
</div>
