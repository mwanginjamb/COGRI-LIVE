<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:09 PM
 */

namespace frontend\models;
use yii\base\Model;


class MedicalCoverline extends Model
{

    public $Key;
    public $Document_No;
    public $Line_No;
    public $Receipt_No;
    public $Amount;
    public $Visit_Date;
    public $isNewRecord;


    public function rules()
    {
        return [
            [['Receipt_No','Amount','Visit_Date'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            
        ];
    }
}