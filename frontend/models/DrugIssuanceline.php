<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:09 PM
 */

namespace frontend\models;
use yii\base\Model;


class DrugIssuanceline extends Model
{

    public $Key;
    public $No;
    public $Name;
    public $Unit_of_Measure;
    public $Description;
    public $Location;
    public $Quantity;
    public $Line_No;
    public $Requisition_No;
    public $Available_Quantity;
    public $FA_Transaction_Type;
    public $Lease_Period_Months_x003D_M_x002C_Years_x003D_Y;
    public $Lease_Start_Date;
    public $isNewRecord;


    public function rules()
    {
        return [

        ];
    }

    public function attributeLabels()
    {
        return [
            'Lease_Period_Months_x003D_M_x002C_Years_x003D_Y' => 'Lease Period',
            'No' => 'Item'
        ];
    }
}