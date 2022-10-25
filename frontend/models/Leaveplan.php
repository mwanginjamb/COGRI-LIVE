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


class Leaveplan extends Model
{

public $Key;
public $Plan_No;
public $Employee_No;
public $Employee_Name;
public $Global_Dimension_1_Code;
public $Global_Dimension_1_Name;
public $Global_Dimension_2_Code;
public $Global_Dimension_2_Name;
public $Leave_Calender_Code;
public $Leave_Calendar_Description;
public $Leave_Calendar_Start_Date;
public $Leave_Calendar_End_Date;
public $Status;
public $isNewRecord;

    /*public function __construct(array $config = [])
    {
        return $this->getLines($this->No);
    }*/

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

    public function getLines(){
        $service = Yii::$app->params['ServiceName']['LeavePlanLine'];
        $filter = [
            'Plan_No' => $this->Plan_No,
        ];

        $lines = Yii::$app->navhelper->getData($service, $filter);
       return $lines;


    }



}