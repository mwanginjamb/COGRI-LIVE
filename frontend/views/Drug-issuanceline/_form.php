<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 12:13 PM
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$absoluteUrl = \yii\helpers\Url::home(true);
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="card-body">

                    <?php
                    $form = ActiveForm::begin(); ?>
                <div class="row">


                            <div class="col-md-12">
                                    <?= $form->field($model, 'Requisition_No')->textInput(['readonly' => true]) ?>
                                    <?= $form->field($model, 'No')->dropDownList($items, ['prompt' => 'Select Item...']) ?>
                                    <?= $form->field($model, 'Location')->dropDownList($locations, ['prompt' => 'Select Location...']) ?>
                                    <?= $form->field($model, 'Quantity')->textInput(['type' => 'number']) ?>
                                    <?= $form->field($model, 'Key')->hiddenInput(['readonly'=> true])->label(false) ?>
                                    <?= $form->field($model, 'Line_No')->textInput(['readonly'=> true])->label(false) ?>
                                    <?= $form->field($model, 'Available_Quantity')->textInput(['readonly'=> true,'disabled' => true]); ?>

                            </div>



                </div>












                <div class="row">

                    <div class="form-group">
                        <?= Html::submitButton(($model->isNewRecord)?'Save':'Update', ['class' => 'btn btn-success','id'=>'submit']) ?>
                    </div>


                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="absolute" value="<?= $absoluteUrl ?>">
<?php
$script = <<<JS
 //Submit Rejection form and get results in json    
        $('form').on('submit', function(e){
            e.preventDefault()
            const data = $(this).serialize();
            const url = $(this).attr('action');
            $.post(url,data).done(function(msg){
                    $('.modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
        
                },'json');
        });

         /*Add select 2*/
         
         $('#drugissuanceline-no').select2();

         $('#drugissuanceline-no').on('change', function(e){
            e.preventDefault();
                  
            const No = e.target.value;
            const Line_No = $('#drugissuanceline-line_no').val();
            
            
            const url = $('input[name="absolute"]').val()+'drug-issuanceline/setitem';
            $.post(url,{'No': No,'Line_No': Line_No}).done(function(msg){
                   //populate empty form fields with new data
                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-drugissuanceline-no');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        disableSubmit();
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-drugissuanceline-no');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        enableSubmit();
                    }
                    $('#drugissuanceline-key').val(msg.Key);
                    $('#drugissuanceline-available_quantity').val(msg.Available_Quantity);
                   
                    
                },'json');
        });
         
         $('#drugissuanceline-quantity').on('change', function(e){
            e.preventDefault();
                  
            const Line_No = $('#drugissuanceline-line_no').val();
            
            
            const url = $('input[name="absolute"]').val()+'drug-issuanceline/setquantity';
            $.post(url,{'Line_No': Line_No,'Quantity': $(this).val()}).done(function(msg){
                   //populate empty form fields with new data
                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string'){ // A string is an error
                        const parent = document.querySelector('.field-drugissuanceline-quantity');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        disableSubmit();
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-drugissuanceline-quantity');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        enableSubmit();
                    }
                    $('#drugissuanceline-key').val(msg.Key);
                                        
                },'json');
        });
         
         
         
         // Set Location
         
         $('#drugissuanceline-location').on('change', function(e){
            e.preventDefault();
                  
            const No = $('#drugissuanceline-line_no').val();
            const Location = $('#drugissuanceline-location').val();
            
            
            const url = $('input[name="absolute"]').val()+'drug-issuanceline/setlocation';
            $.post(url,{'Line_No': No,'Location': Location}).done(function(msg){
                   //populate empty form fields with new data
                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-drugissuanceline-no');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        disableSubmit();
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-drugissuanceline-no');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        enableSubmit();
                    }
                    $('#drugissuanceline-key').val(msg.Key);
                    $('#drugissuanceline-available_quantity').val(msg.Available_Quantity);
                   
                    
                },'json');
        });
         
         
         
         
         
         
         function disableSubmit(){
             document.getElementById('submit').setAttribute("disabled", "true");
        }
        
        function enableSubmit(){
            document.getElementById('submit').removeAttribute("disabled");
        
        }
JS;

$this->registerJs($script);
