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


class Salaryadvance extends Model
{

public $Key;
public $No;
public $Employee_No;
public $Employee_Name;
public $Purpose;
public $Employee_Balance;
public $Status;
public $Global_Dimension_1_Code;
public $Global_Dimension_2_Code;
public $Loan_Type;
public $Repayment_Period;
public $Instalments;
public $Basic_Pay;
public $_x0031__3_of_Basic;
public $Current_Net_Pay;
public $Take_Home;
public $Amount_Requested;
public $Months_Paid;
public $Posting_Date;
public $Account_Type;
public $Paying_Bank;
public $Paying_Bank_Name;
public $Pay_Mode;
public $Cheque_No;
public $EFT_No;
public $Posted_By;
public $Posted_On;
public $isNewRecord;

    /*public function __construct(array $config = [])
    {
        return $this->getLines($this->No);
    }*/

    public function rules()
    {
        return [
            [['Amount_Requested','Loan_Type','Purpose'], 'required'],
            ['Amount_Requested','integer','min'=> 1]
        ];
    }

    public function attributeLabels()
    {
        return [
            'Global_Dimension_1_Code' => 'Program',
            'Global_Dimension_2_Code' => 'Department',
            '_x0031__3_of_Basic' => '1/3 of Basic Salary'
        ];
    }

    public function getLines($No){
        $service = Yii::$app->params['ServiceName']['ImprestRequestLine'];
        $filter = [
            'Request_No' => $No,
        ];

        $lines = Yii::$app->navhelper->getData($service, $filter);
       return $lines;


    }



}