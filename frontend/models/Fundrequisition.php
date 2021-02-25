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


class Fundrequisition extends Model
{

public $Key;
public $No;
public $Employee_No;
public $Employee_Name;
public $Purpose;
public $Gross_Allowance; // renamed to Gross_Amount
public $Gross_Amount;
public $Net_Allowance_LCY;
public $Status;
public $Global_Dimension_1_Code;
public $Global_Dimension_2_Code;
public $Expected_Date_of_Surrender;
public $Currency_Code;
public $Exchange_Rate;
public $Exchange_Rate_Factor;
public $Posting_Date;
public $Account_Type;
public $Paying_Bank;
public $Paying_Bank_Name;
public $Pay_Mode;
public $Payroll_Period;
public $M_PESA_Withdrawal_Fee;
public $Cheque_No;
public $EFT_No;
public $Allowance_Request_Line;
public $Charge_as_a_Single_Transaction;
public $isNewRecord;

    /*public function __construct(array $config = [])
    {
        return $this->getLines($this->No);
    }*/

    public function rules()
    {
        return [
            [['Purpose','Global_Dimension_1_Code','Global_Dimension_2_Code'], 'required'],
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
        $service = Yii::$app->params['ServiceName']['AllowanceRequestLine'];
        $filter = [
            'Request_No' => $No,
        ];

        $lines = Yii::$app->navhelper->getData($service, $filter);
        $this->Allowance_Request_Line = $lines;
       return $lines;


    }



}