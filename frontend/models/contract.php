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


class Contract extends Model
{

public $Key;
public $No;
public $Employee_No;
public $Employee_Name;
public $Current_Contract_Start_Date;
public $Status;
public $Current_Contract_Period;
public $Current_Contract_End_Date;
public $New_Contract_Period;
public $New_Contract_End_Date;
public $Justify_Extension;
public $Supervisor_User_Id;
public $Hr_User_Id;
public $Current_User;
public $isNewRecord;

    public function rules()
    {
        return [

        ];
    }

    public function attributeLabels()
    {
        return [
            'No' => 'Contract No.',
            'Current_User' => 'Employee Id'


        ];
    }

    /*public function getObjectives(){
        $service = Yii::$app->params['ServiceName']['NewEmpObjectives'];
        $filter = [
            'Appraisal_No' => $this->Appraisal_No,
            'Employee_No' => $this->Employee_No
        ];

        $objectives = Yii::$app->navhelper->getData($service, $filter);
        return $objectives;
    }*/



    //get supervisor status

   /* public function isSupervisor($Employee_User_Id,$Supervisor_User_Id)
    {

        $user = Yii::$app->user->identity->getId();

        return ($user == $Supervisor_User_Id);

    }*/


}