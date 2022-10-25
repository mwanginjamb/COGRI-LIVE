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
use yii\helpers\ArrayHelper;


class Workticket extends Model
{

public $Key;
public $Work_Ticket_No;
public $Vehicle_Registration_No;
public $Booking_Requisition_No;

public $Fuel_Requisition_No;

public $Fixed_Asset_No;
public $Driver_Staff_No;
public $Driver_Name;
public $Created_By;
public $Date_Created;
public $Mileage_at_Start_KMS;
public $Current_Mileage_KMS;
public $Requested_By;
public $Department_Requested;
public $Destination;
public $Purpose_of_Journey;
public $Duration_of_Travel_Days;
public $Departure_Date;
public $Departure_Time;
public $Travelled_Distance_KMS;
public $Arrival_Time;
public $Return_Date;
public $Return_Departure_Time;
public $Return_Arrival_Time;
public $Submitted;
public $Department;
public $Approval_Status;
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