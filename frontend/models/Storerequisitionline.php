<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:09 PM
 */

namespace frontend\models;
use yii\base\Model;


class Storerequisitionline extends Model
{

public $Key;
public $No;
public $Name;
public $Unit_of_Measure;
public $Description;
public $Location;
public $Quantity;
public $Available_Quantity;
public $Requisition_No;
public $Line_No;
public $FA_Transaction_Type;
public $Lease_Period_Months_x003D_M_x002C_Years_x003D_Y;
public $Lease_Start_Date;
public $isNewRecord;
public $Qty_to_Issue;

public $ShortcutDimCode_x005B_3_x005D_;
public $ShortcutDimCode_x005B_4_x005D_;
public $ShortcutDimCode_x005B_5_x005D_;
public $ShortcutDimCode_x005B_6_x005D_;
public $ShortcutDimCode_x005B_7_x005D_;
public $ShortcutDimCode_x005B_8_x005D_;
public $Global_Dimension_1_Code;
public $Global_Dimension_2_Code;

    public function rules()
    {
        return [

        ];
    }

    public function attributeLabels()
    {
        return [
            'Lease_Period_Months_x003D_M_x002C_Years_x003D_Y' => 'Lease Period',
            'No' => 'Item',
            'Global_Dimension_1_Code' => 'Program',
            'Global_Dimension_2_Code' => 'Department',
            'ShortcutDimCode_x005B_3_x005D_' => 'Student'
        ];
    }
}