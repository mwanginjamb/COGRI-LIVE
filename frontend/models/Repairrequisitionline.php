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


class Repairrequisitionline extends Model
{

public $Key;
public $Repair_Requisition_No;
public $Vehicle_Registration_No;
public $Type;
public $No;
public $Description;
public $Location_Code;
public $Quantity;
public $Repair_Date;
public $Duration_Of_Use;
public $Due_Replacement_Date;
public $Vendor_Garage;
public $Garage_Name;
public $Cost_of_Repair;
public $Budgeted_Amount;
public $Line_No;

public $isNewRecord;


    public function rules()
    {
        return [
                [['Title','No'], 'required'],
                ['Description', 'string','max' => 250],
        ];
    }

// General Ledger Setup -- Lookup dimension defs there

    public function attributeLabels()
    {
        return [
            'Global_Dimension_2_Code' => 'Department',
            'ShortcutDimCode_x005B_3_x005D_' => 'Student',
            'ShortcutDimCode_x005B_4_x005D_' => 'Shade',
            'ShortcutDimCode_x005B_5_x005D_' => 'Animal',
            'ShortcutDimCode_x005B_6_x005D_' => 'unknown Dim 3',
            'ShortcutDimCode_x005B_7_x005D_' => 'Unknown Dim',
            'ShortcutDimCode_x005B_8_x005D_' => 'Unknown Dim2',
           
        ];
    }

    public function getTypes()
    {
        $TypeArray = [
            ['Code' => 'Service', 'Desc' => 'Service'],
            ['Code' => 'Repair', 'Desc' => 'Repair'],
            ['Code' => 'Replacement', 'Desc' => 'Replacement'],
            ['Code' => 'Accident', 'Desc' => 'Accident']

        ];

        return ArrayHelper::map($TypeArray,'Code','Desc');
    }

    



}