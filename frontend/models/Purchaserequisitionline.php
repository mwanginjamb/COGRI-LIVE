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


class Purchaserequisitionline extends Model
{

public $Key;
public $Type;
public $No;
public $Name;
public $Unit_of_Measure;
public $Reason_For_Requisition;
public $Quantity;
public $Location;
public $Estimate_Unit_Price;
public $Estimate_Total_Amount;
public $Procurement_Method;
public $ShortcutDimCode_x005B_3_x005D_;
public $ShortcutDimCode_x005B_4_x005D_;
public $ShortcutDimCode_x005B_5_x005D_;
public $ShortcutDimCode_x005B_6_x005D_;
public $ShortcutDimCode_x005B_7_x005D_;
public $ShortcutDimCode_x005B_8_x005D_;
public $Institution_Code;
public $Institution_Name;
public $Requisition_No;
public $Line_No;
public $isNewRecord;

    /*public function __construct(array $config = [])
    {
        return $this->getLines($this->No);
    }*/

    public function rules()
    {
        return [
                [['Title','No'], 'required'],
                ['Title', 'string','max' => 250],
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

    



}