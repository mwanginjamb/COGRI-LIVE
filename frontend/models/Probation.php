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


class Probation extends Model
{

public $Key;
public $Appraisal_No;
public $Employee_No;
public $Employee_Name;
public $Job_Title;
public $Appraisal_Period;
public $Appraisal_Start_Date;
public $Created_By;
public $Supervisor_User_Id;
public $Employee_User_Id;
public $Supervisor_No;
public $Hr_UserId;
public $New_Employee_App_Objectives;
public $New_Emp_App_Status;
public $Action_Taken;

    public function rules()
    {
        return [

        ];
    }

    public function attributeLabels()
    {
        return [



        ];
    }

    public function getObjectives(){
        $service = Yii::$app->params['ServiceName']['NewEmpObjectives'];
        $filter = [
            'Appraisal_No' => $this->Appraisal_No,
            'Employee_No' => $this->Employee_No
        ];

        $objectives = Yii::$app->navhelper->getData($service, $filter);
        return $objectives;
    }



    //get supervisor status

    public function isSupervisor($Employee_User_Id,$Supervisor_User_Id)
    {

        $user = Yii::$app->user->identity->getId();

        return ($user == $Supervisor_User_Id);

    }


}