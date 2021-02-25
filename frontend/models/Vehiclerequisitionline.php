<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:09 PM
 */

namespace frontend\models;
use yii\base\Model;


class Vehiclerequisitionline extends Model
{

public $Key;
public $Booking_Requisition_No;
public $Vehicle_Regitration_No;
public $Vehicle_Model;
public $Booking_Date;
public $End_Date;
public $Booking_Duration_Days;
public $Booked_Status;
public $Booking_Requisition_Status;
public $isNewRecord;

    public function rules()
    {
        return [
            ['Vehicle_Regitration_No', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [

        ];
    }
}