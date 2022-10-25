<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:09 PM
 */

namespace frontend\models;
use yii\base\Model;


class Imprestline extends Model
{

public $Key;
public $Line_No;
public $Request_No;
public $Transaction_Type;
public $Account_No;
public $Account_Name;
public $Description;
public $Amount;
public $Amount_LCY;
public $Global_Dimension_1_Code;
public $Global_Dimension_2_Code;
public $Sortcut_Dimension_3_Code;
public $Budgeted_Amount;
public $Commited_Amount;
public $Total_Expenditure;
public $Available_Amount;
public $Unbudgeted;
public $Employee_No;
public $Balance_Less_Entry;
public $Balance_Before_Entry;
public $isNewRecord;

    public function rules()
    {
        return [
            [['Transaction_Type', 'Description', 'Amount'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'Global_Dimension_1_Code' => 'Program',
            'Global_Dimension_2_Code' => 'Department',
            'Sortcut_Dimension_3_Code' => 'Student (Optional)'
        ];
    }
}