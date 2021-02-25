<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:09 PM
 */

namespace frontend\models;
use yii\base\Model;


class Contractrenewalline extends Model
{

public $Key;
public $Contract_Code;
public $Contract_Description;
public $Contract_Start_Date;
public $Contract_Period;
public $Contract_End_Date;
public $Notice_Period;
public $Contract_Status;
public $Request_No;
public $Employee_No;
public $Line_No;
public $isNewRecord;

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
}