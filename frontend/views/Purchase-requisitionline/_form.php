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


                            <div class="col-md-6">
                                    <?= $form->field($model, 'Type')->dropDownList([
                                            'G_L_Account' => 'G_L_Account',
                                            'Fixed_Asset' => 'Fixed_Asset',
                                            'Item' => 'Item'
                                        ],
                                        [
                                                'prompt' => 'Select Type ...',
                                                'onchange' => '$.post("../purchase-requisitionline/no-dd?type="+$(this).val(), (data) => {
                                                $("select#purchaserequisitionline-no").html( data );
                                            })'
                                         ]); ?>
                                    <?= $form->field($model, 'No')->dropDownList($items, ['prompt' => 'Select Item...']) ?>
                                    <?= $form->field($model, 'Location')->dropDownList($locations, ['prompt' => 'Select Location...']) ?>
                                    <?= $form->field($model, 'Estimate_Unit_Price')->textInput() ?>
                                    <?= $form->field($model, 'Quantity')->textInput(['type' => 'number']) ?>
                                    <?= $form->field($model, 'Reason_For_Requisition')->textInput(['maxlength'=> 250]); ?>

                            </div>

                            <div class="col-md-6">
                                <?= $form->field($model, 'Requisition_No')->textInput(['readonly' => true]) ?>

                                <?= $form->field($model, 'Estimate_Total_Amount')->textInput(['readonly' => true, 'disabled' =>  true]) ?>
                                <?= $form->field($model, 'Sortcut_Dimension_3_Code')->dropDownList($students, ['prompt' => 'Select Student...']) ?>
                                <?= $form->field($model, 'Institution_Code')->dropDownList($institutions, ['prompt' => 'Select Institution ...']) ?>
                                <?= $form->field($model, 'ShortcutDimCode_x005B_4_x005D_')->dropDownList($shades, ['Prompt' => 'Select Shade ...']) ?>
                                <?= $form->field($model, 'ShortcutDimCode_x005B_5_x005D_')->dropDownList($animals, ['Prompt' => 'Select Animal...']) ?>


                            </div>

                                <?= $form->field($model, 'Key')->hiddenInput(['readonly'=> true])->label(false) ?>
                                <?= $form->field($model, 'Line_No')->hiddenInput(['readonly'=> true,'disabled' => true])->label(false) ?>
                                <?= $form->field($model, 'Requisition_No')->hiddenInput(['readonly'=> true])->label(false) ?>



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

$('#purchaserequisitionline-sortcut_dimension_3_code').select2();
$('#purchaserequisitionline-institution_code').select2();


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

        /*Commit Type */
        
        $('#purchaserequisitionline-type').on('change', function(e){
            e.preventDefault();
                  
            const Type = e.target.value;
            const Line_No = $('#purchaserequisitionline-line_no').val();
            
            
            const url = $('input[name="absolute"]').val()+'purchase-requisitionline/set-type';
            $.post(url,{'Type': Type,'Line_No': Line_No}).done(function(msg){
                   //populate empty form fields with new data
                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-purchaserequisitionline-type');
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
                    $('#purchaserequisitionline-estimate_unit_price').val(msg.Estimate_Unit_Price);
                    $('#purchaserequisitionline-estimate_total_amount').val(msg.Estimate_Total_Amount);
                   
                    
                },'json');
        });



        /*Commit Item */

         $('#purchaserequisitionline-no').on('change', function(e){
            e.preventDefault();
                  
            const No = e.target.value;
            const Line_No = $('#purchaserequisitionline-line_no').val();
            
            
            const url = $('input[name="absolute"]').val()+'purchase-requisitionline/setitem';
            $.post(url,{'No': No,'Line_No': Line_No}).done(function(msg){
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
                    $('#purchaserequisitionline-estimate_unit_price').val(msg.Estimate_Unit_Price);
                    $('#purchaserequisitionline-estimate_total_amount').val(msg.Estimate_Total_Amount);
                   
                    
                },'json');
        });
         
         
         /*Commit Unit Price */
         
         
           $('#purchaserequisitionline-estimate_unit_price').on('blur', function(e){
            e.preventDefault();
                  
            const Line_No = $('#purchaserequisitionline-line_no').val();
            
            
            const url = $('input[name="absolute"]').val()+'purchase-requisitionline/setprice';
            $.post(url,{'Line_No': Line_No,'Estimate_Unit_Price': $(this).val()}).done(function(msg){
                   //populate empty form fields with new data
                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string'){ // A string is an error
                        const parent = document.querySelector('.field-purchaserequisitionline-estimate_unit_price');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        disableSubmit();
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-purchaserequisitionline-estimate_unit_price');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        enableSubmit();
                    }
                    $('#purchaserequisitionline-key').val(msg.Key);
                    $('#purchaserequisitionline-estimate_unit_price').val(msg.Estimate_Unit_Price);
                    $('#purchaserequisitionline-estimate_total_amount').val(msg.Estimate_Total_Amount);
                                        
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
                    // $('#purchaserequisitionline-estimate_unit_price').val(msg.Estimate_Unit_Price);
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
