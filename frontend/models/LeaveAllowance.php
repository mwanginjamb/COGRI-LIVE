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


class LeaveAllowance extends Model
{

public $Key;
public $No;
public $Employee_No;
public $Employee_Name;
public $Request_Type;
public $Payroll_Period;
public $Amount_Requested;
public $Global_Dimension_1_Code;
public $Global_Dimension_2_Code;
public $Status;

public $isNewRecord;


    public function rules()
    {
        return [
           
        ];
    }

    public function attributeLabels()
    {
        return [
            'Global_Dimension_1_Code' => 'Program',
            'Global_Dimension_2_Code' => 'Department',
        ];
    }



}