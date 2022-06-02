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


class Overtime extends Model
{

public $Key;
public $No;
public $Employee_No;
public $Employee_Name;
public $Global_Dimension_1_Code;
public $Global_Dimension_2_Code;
public $Hours_Worked;
public $Status;
public $isNewRecord;
public $Rejection_Comments;

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
            'Global_Dimension_1_Code' => 'Department Code',
            'Global_Dimension_2_Code' => 'Project Code',
        ];
    }

    public function getLines(){
        $service = Yii::$app->params['ServiceName']['OvertimeLine'];
        $filter = [
            'Application_No' => $this->No,
        ];

        $lines = Yii::$app->navhelper->getData($service, $filter);
       return $lines;


    }



}