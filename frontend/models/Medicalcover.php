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


class Medicalcover extends Model
{

public $Key;
public $Employee_No;
public $Employee_Name;
public $_x003C_Global_Dimension_1_Code_x003E_;
public $Global_Dimension_2_Code;
public $Application_No;
public $Application_Date;
public $User_ID;
public $Cover_Type;
public $Status;
public $Approval_Entries;
public $Limit_Amount;
public $Used_Amount;
public $Balance_Before;
public $Receipt_Amount;
public $Balance_After;
public $Receipt_No;
public $Phone_No;
public $E_Mail_Address;
public $Comments;
public $Exceed_Balance;
public $isNewRecord;

    /*public function __construct(array $config = [])
    {
        return $this->getLines($this->No);
    }*/

    public function rules()
    {
        return [
            [['Amount_Requested','Loan_Type','Purpose'], 'required'],
            ['Amount_Requested','integer','min'=> 1]
        ];
    }

    public function attributeLabels()
    {
        return [
            '_x003C_Global_Dimension_1_Code_x003E_' => 'Program',
            'Global_Dimension_2_Code' => 'Department',
        ];
    }



}