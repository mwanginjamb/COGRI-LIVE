<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:09 PM
 */

namespace frontend\models;
use yii\base\Model;


class Fundsrequisitionline extends Model
{

public $Key;
public $PD_Transaction_Code;
public $Account_No;
public $Account_Name;
public $Description;
public $Child_Rate;
public $No_of_Children;
public $Amount;
public $Amount_LCY;
public $Net_Allowance_Amount;
public $Budgeted_Amount;
public $Commited_Amount;
public $Total_Expenditure;
public $Balance_Less_Entry;
public $Budget_Depletion;
public $Balance_Before_Entry;
public $Available_Amount;
public $Unbudgeted;
public $Request_No;
public $Line_No;
public $Global_Dimension_1_Code;
public $Global_Dimension_2_Code;
public $Sortcut_Dimension_3_Code;

public $ShortcutDimCode_x005B_3_x005D_;
public $ShortcutDimCode_x005B_4_x005D_;
public $ShortcutDimCode_x005B_5_x005D_;
public $ShortcutDimCode_x005B_6_x005D_;
public $ShortcutDimCode_x005B_7_x005D_;
public $ShortcutDimCode_x005B_8_x005D_;

public $isNewRecord;

    public function rules()
    {
        return [
            [['PD_Transaction_Code', 'Description', 'Amount'], 'required'],
            [['No_of_Children','Child_Rate'],'integer','min' => 1]
        ];
    }

    public function attributeLabels()
    {
        return [
            'Global_Dimension_1_Code' => 'Program',
            'Global_Dimension_2_Code' => 'Department',
            'Sortcut_Dimension_3_Code' => 'Student',
            'Child_Rate' => 'Rate',
            'No_of_Children' => 'Quantity',
        ];
    }
}