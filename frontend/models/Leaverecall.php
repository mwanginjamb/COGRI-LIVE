<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/4/2020
 * Time: 12:06 PM
 */

namespace frontend\models;

use yii\base\Model;
use Yii;
use yii\helpers\ArrayHelper;

class Leaverecall extends Model
{

    public $Key;
    public $Recall_No;
    public $Employee_No;
    public $Leave_No_To_Recall;
    public $Employee_Name;
    public $Global_Dimension_1_Code;
    public $Application_Date;
    public $User_ID;
    public $Leave_Code;
    public $Start_Date;
    public $End_Date;
    public $Days_Applied;
    public $Days_To_Recall;
    public $Leave_balance;
    public $Total_No_Of_Days;
    public $Holidays;
    public $Weekend_Days;
    public $Days;
    public $Balance_After;
    public $Return_Date;
    public $Reporting_Date;
    public $Leave_Status;
    public $Comments;
    public $Supervisor_Code;
    public $Reliever;
    public $Reliever_Name;
    public $Status;
    public $Posted;
    public $isNewRecord;

    public function rules()
    {
        return [

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Global_Dimension_1_Code' => 'Program'
        ];
    }

    public function getLeaves()
    {
        $service = Yii::$app->params['ServiceName']['LeaveList'];
        $filter = [
            'Status' => 'Approved',
            'Employee_No' => $this->Employee_No,
            //'Posted' => true
        ];
        $leaves = \Yii::$app->navhelper->getData($service,$filter);

        $result = [];


        foreach($leaves as $leave){
            if(isset($leave->Days_Applied) && $leave->Days_Applied > 0) {
                $result[] = [
                    'No' => $leave->Application_No,
                    'Description' => $leave->Application_No . ' | ' . $leave->Start_Date . ' | ' . $leave->End_Date . ' | ' . $leave->Days_Applied.' | '. $leave->Leave_Code,
                ];
                krsort($result);
            }
        }

        return ArrayHelper::map($result,'No','Description');
    }

}