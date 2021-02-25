<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:09 PM
 */

namespace frontend\models;
use yii\base\Model;


class Objective extends Model
{
public $Line_No;
public $Appraisal_No;
public $Employee_No;
public $Objective;
public $New_Emp_Self_Rating;
public $New_Emp_Comments;
public $New_Emp_Supervisor_Rating;
public $New_Emp_Supervisor_Comment;
public $New_Emp_Hr_Rating;
public $New_Emp_Hr_Comments;
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
            'New_Emp_Hr_Comments' => 'HR Comments'
        ];
    }
}