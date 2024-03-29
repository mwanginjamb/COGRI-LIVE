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
           
           

                    <?php
                    $form = ActiveForm::begin(); ?>
                


                           
                                    <?= $form->field($model, 'Document_No')->hiddenInput(['readonly' => true])->label(false) ?>
                                    <?= $form->field($model, 'Receipt_No')->textInput(['maxlength' => '35', 'required' => true]) ?>
                                    <?= $form->field($model, 'Amount')->textInput(['type' => 'number', 'required' => true]) ?>
                                    <?= $form->field($model, 'Visit_Date')->textInput(['type' => 'date']) ?>
                                    <?= $form->field($model, 'Key')->hiddenInput(['readonly'=> true])->label(false) ?>
                                    <?= $form->field($model, 'Line_No')->hiddenInput(['readonly'=> true])->label(false) ?>
                                   

                          


                

                    <div class="form-group">
                        <?= Html::submitButton(($model->isNewRecord)?'Save':'Update', ['class' => 'btn btn-success','id'=>'submit']) ?>
                    </div>

        </div>
               
                <?php ActiveForm::end(); ?>
           
      
    
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
