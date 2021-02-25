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


class Contractrenewal extends Model
{

public $Key;
public $No;
public $Employee_No;
public $Employee_Name;
public $isNewRecord;
public $Status;
public $Approval_Status;
public $Created_On;

public $Department;
public $Program;
public $Job_Description;


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

    public function getLines(){
        $service = Yii::$app->params['ServiceName']['ContractRenewalLines'];
        $filter = [
            'Request_No' => $this->No,
        ];

        $lines = Yii::$app->navhelper->getData($service, $filter);
        return $lines;

    }



}