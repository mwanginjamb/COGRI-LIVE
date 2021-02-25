<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:09 PM
 */

namespace frontend\models;
use common\models\User;
use Yii;
use yii\base\Model;


class Vehiclerequisition extends Model
{

public $Key;
public $Booking_Requisition_No;
public $Requisition_Date;
public $Reason_For_Booking;
public $Requested_By;
public $Department;
public $Booking_Requisition_Status;
public $Employee_No;
public $Booked_Status;
public $Action_Required_By;
public $isNewRecord;

    /*public function __construct(array $config = [])
    {
        return $this->getLines($this->No);
    }*/

    public function rules()
    {
        return [
                [['Reason_For_Booking','Booking_Requisition_No'], 'required'],
               // ['Reason_For_Booking', 'string',[5,250]],
        ];
    }

    public function attributeLabels()
    {
        return [

        ];
    }

    public function getLines(){
        $service = Yii::$app->params['ServiceName']['BookingRequisitionLine'];
        $filter = [
            'Booking_Requisition_No' => $this->Booking_Requisition_No,
        ];

        $lines = Yii::$app->navhelper->getData($service, $filter);
       return $lines;


    }



}