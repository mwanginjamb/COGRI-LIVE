<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:09 PM
 */

namespace frontend\models;
use yii\base\Model;


class Probationkpi extends Model
{
public $Appraisal_No;
public $Employee_No;
public $Objective;
public $Weight;
public $Appraisee_Self_Rating;
public $Employee_Comments;
public $Appraiser_Rating;
public $Supervisor_Comments;
public $Agree;
public $Disagreement_Comments;
public $Appraisee_Self_Rating_Ex;
public $Appraiser_Rating_Ex;
public $Agree_Ex;
public $Disagreement_Comments_Ex;
public $Employee_Comments_Ex;
public $End_Year_Supervisor_Comments_E;
public $KRA_Line_No;
public $Overview_Manager_Comments;
public $Line_No;
public $Key;
public $isNewRecord;

    public function rules()
    {
        return [

        ];
    }

    public function attributeLabels()
    {
        return [
            'New_Emp_Self_Rating' => 'Self Rating',
            'New_Emp_Comments' => 'Appraisee Comment',
            'New_Emp_Supervisor_Rating' => 'Supervisor Rating',
            'New_Emp_Supervisor_Comment' => 'Supervisor Comment',
            'New_Emp_Hr_Rating' => 'HR Rating',
            'New_Emp_Hr_Comments' => 'HR Comments',
            'End_Year_Supervisor_Comments_E' => 'Supervisor Extension Comments'
        ];
    }


}