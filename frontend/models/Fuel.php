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


class Fuel extends Model
{

public $Key;
public $Fuel_Code;
public $Created_Date;
public $Vehicle_Registration_No;
public $Fixed_Asset_No;
public $Vehicle_Model;
public $Status;
public $Driver_Staff_No;
public $Driver_Name;
public $Type_of_Fuel;
public $Receipt_Date;
public $Receipt_Order_No;
public $Total_Fuel_Cost;
public $Mileage_KMS;
public $isNewRecord;


    public function rules()
    {
        return [
                [['Mileage_KMS'], 'required'],
               // ['Reason_For_Booking', 'string',[5,250]],
        ];
    }

    public function attributeLabels()
    {
        return [

        ];
    }

    public function getLines(){
        $service = Yii::$app->params['ServiceName']['FuelingLine'];
        $filter = [
            'Fuel_Code' => $this->Fuel_Code,
        ];
       $lines = Yii::$app->navhelper->getData($service, $filter);
       return $lines;
    }

    // Define Enumerations

    public function getFueltypes()
    {
        $data = [];
        $data = [
            ['Code' => 'Diesel','Description' => 'Diesel'],
            ['Code' => 'Petrol','Description' => 'Petrol'],
            ['Code' => 'Gasoline','Description' => 'Gasoline'],

        ];

        return ArrayHelper::map($data,'Code','Description');

    }

    public function getPaymentmodes()
    {
        $data = [
            ['Code' => '_blank_','Description' => '_blank_'],
            ['Code' => 'Cash','Description' => 'Cash'],
            ['Code' => 'Paybill','Description' => 'Paybill'],
            ['Code' => 'Fuel_Card','Description' => 'Fuel_Card'],

        ];

        return ArrayHelper::map($data,'Code','Description');

    }







}