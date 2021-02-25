<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:09 PM
 */

namespace frontend\models;
use yii\base\Model;


class Fuelline extends Model
{

public $Key;
public $Fuel_Code;
public $Fueling_Date;
public $Petrol_Station;
public $Mode_Of_Payment;
public $Fuel_Card_No;
public $Type_of_Fuel;
public $Quantity_ltrs;
public $Vehicle_Registration_No;
public $Line_No;
public $isNewRecord;

    public function rules()
    {
        return [

        ];
    }

    public function attributeLabels()
    {
        return [

        ];
    }
}