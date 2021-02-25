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


class Repairrequisition extends Model
{

public $Key;
public $Repair_Requisition_No;
public $Vehicle_Registration_No;
public $Fixed_Asset_No;
public $Vehicle_Frame_No;
public $Vehicle_Model;
public $Requisition_Date;
public $Reason_Code;
public $Requested_By;
public $Department;
public $Requisition_Status;
public $Action_Required_By;
public $Total_Cost;
public $Mileage_at_Service_KMS;
public $Service_Date;
public $Employee_No;
public $Repair_Requistion_Line;
public $isNewRecord;


    public function rules()
    {
        return [
                [['Title','No'], 'required'],
                ['Title', 'string','max' => 250],
        ];
    }

    public function attributeLabels()
    {
        return [
            ['Global_Dimension_2_Code' => 'Department'],
        ];
    }

    public function getLines(){
        $service = Yii::$app->params['ServiceName']['RepairRequisitionLine'];
        $filter = [
            'Repair_Requisition_No' => $this->Repair_Requisition_No,
        ];

        $lines = Yii::$app->navhelper->getData($service, $filter);
        return $lines;

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

    public function getReasoncode()
    {
        $ReasonArray = [
            ['Code' => 'Vehicle_Maintenance', 'Desc' => 'Vehicle_Maintenance'],
            ['Code' => 'Service', 'Desc' => 'Service'],
            ['Code' => 'Repair', 'Desc' => 'Repair'],
            ['Code' => 'Replacement', 'Desc' => 'Replacement'],
            ['Code' => 'Accident', 'Desc' => 'Accident'],

        ];

        return ArrayHelper::map($ReasonArray,'Code','Desc');
    }



}