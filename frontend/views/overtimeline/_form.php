<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 12:13 PM
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\TimePicker;

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
                                    <?= $form->field($model, 'Date')->textInput(['type' => 'date'])?>
                                    <?= $form->field($model, 'Start_Time')->textInput(['type' => 'time']) ?>
                                    <?= $form->field($model, 'End_Time')->textInput(['type' => 'time']) ?>
                                    <?= $form->field($model, 'Hours_Worked')->textInput(['readonly' => true]) ?>
                                    <?= $form->field($model, 'Work_Done')->textarea(['rows' => 2,'maxlemgth' => 250]) ?>
                                    <?= $form->field($model, 'Application_No')->hiddenInput(['readonly' => true])->label(false); ?>
                                    <?= $form->field($model, 'Key')->hiddenInput(['readonly' => true])->label(false); ?>
                                    <?= $form->field($model, 'Line_No')->hiddenInput(['readonly'=> true])->label(false) ?>

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


   // $('.timepicker').timepicker();

    //Submit form and get results in json    
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

        // Commit Start Time
        
        $('#overtimeline-start_time').on('change', function(e){
            e.preventDefault();
                  
            const Line_No = $('#overtimeline-line_no').val();
            const Start_Time = $('#overtimeline-start_time').val();
            const Date = $('#overtimeline-date').val();
            
            
            const url = $('input[name="absolute"]').val()+'overtimeline/setstarttime';
            $.post(url,{'Start_Time': Start_Time,'Line_No': Line_No,'Date': Date}).done(function(msg){
                   //populate empty form fields with new data
                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-overtimeline-start_time');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        disableSubmit();
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-overtimeline-start_time');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        enableSubmit();
                    }
                    $('#overtimeline-key').val(msg.Key);
                                
                   
                    
                },'json');
        });


        /*Commit End Time */

         $('#overtimeline-end_time').on('change', function(e){
            e.preventDefault();
                  
            const Line_No = $('#overtimeline-line_no').val();
            const End_Time = $('#overtimeline-end_time').val();
            
            
            const url = $('input[name="absolute"]').val()+'overtimeline/setendtime';
            $.post(url,{'End_Time': End_Time,'Line_No': Line_No}).done(function(msg){
                   //populate empty form fields with new data
                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-overtimeline-end_time');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        disableSubmit();
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-overtimeline-end_time');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        enableSubmit();
                    }
                    $('#overtimeline-key').val(msg.Key);
                    $('#overtimeline-hours_worked').val(msg.Hours_Worked);
                    
                   
                    
                },'json');
        });
         
         $('#purchaserequisitionline-quantity').on('change', function(e){
            e.preventDefault();
                  
            const Line_No = $('#purchaserequisitionline-line_no').val();
            
            
            const url = $('input[name="absolute"]').val()+'purchase-requisitionline/setquantity';
            $.post(url,{'Line_No': Line_No,'Quantity': $(this).val()}).done(function(msg){
                   //populate empty form fields with new data
                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string'){ // A string is an error
                        const parent = document.querySelector('.field-purchaserequisitionline-quantity');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        disableSubmit();
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-purchaserequisitionline-quantity');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        enableSubmit();
                    }
                    $('#purchaserequisitionline-key').val(msg.Key);
                    $('#purchaserequisitionline-estimate_unit_price').val(msg.Estimate_Unit_Price);
                    $('#purchaserequisitionline-estimate_total_amount').val(msg.Estimate_Total_Amount);
                                        
                },'json');
        });
         
         
         
         // Set Location
         
         $('#purchaserequisitionline-location').on('change', function(e){
            e.preventDefault();
                  
            const No = $('#purchaserequisitionline-line_no').val();
            const Location = $('#purchaserequisitionline-location').val();
            
            
            const url = $('input[name="absolute"]').val()+'purchase-requisitionline/setlocation';
            $.post(url,{'Line_No': No,'Location': Location}).done(function(msg){
                   //populate empty form fields with new data
                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-purchaserequisitionline-no');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        disableSubmit();
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-purchaserequisitionline-no');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        enableSubmit();
                    }
                    $('#purchaserequisitionline-key').val(msg.Key);
                   // $('#purchaserequisitionline-available_quantity').val(msg.Available_Quantity);
                    $('#purchaserequisitionline-estimate_unit_price').val(msg.Estimate_Unit_Price);
                    $('#purchaserequisitionline-estimate_total_amount').val(msg.Estimate_Total_Amount);
                   
                    
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
