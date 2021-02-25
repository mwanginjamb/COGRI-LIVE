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


class Imprestcard extends Model
{

public $Key;
public $No;
public $Employee_No;
public $Employee_Name;
public $Purpose;
public $CBS_Member_Id;
public $Employee_Balance;
public $Imprest_Amount;
public $Amount_LCY;
public $Status;
public $Global_Dimension_1_Code;
public $Global_Dimension_2_Code;
public $Expected_Date_of_Surrender;
public $Imprest_Type;
public $Currency_Code;
public $Exchange_Rate;
public $Exchange_Rate_Factor;
public $Posting_Date;
public $Account_Type;
public $Paying_Bank;
public $Paying_Bank_Name;
public $Pay_Mode;
public $Cheque_No;
public $EFT_No;
public $Posted_By;
public $Posted_On;
public $Request_For;
public $Imprest_Request_Line;
public $isNewRecord;

public $M_PESA_Withdrawal_Fee;

    /*public function __construct(array $config = [])
    {
        return $this->getLines($this->No);
    }*/

    public function rules()
    {
        return [
            ['Purpose', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'Global_Dimension_1_Code' => 'Program',
            'Global_Dimension_2_Code' => 'Department'
        ];
    }

    public function getLines($No){
        $service = Yii::$app->params['ServiceName']['ImprestRequestSubformPortal'];
        $filter = [
            'Request_No' => $No,
        ];

        $lines = Yii::$app->navhelper->getData($service, $filter);
       return $lines;


    }



}