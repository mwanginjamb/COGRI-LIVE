<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/26/2020
 * Time: 5:23 AM
 */

namespace frontend\models;


use yii\base\Model;

class Employee extends Model
{
    public $Key;
public $No;
public $First_Name;
public $Middle_Name;
public $Last_Name;
public $Full_Name;
public $Job_Title;
public $Initials;
public $Search_Name;
public $Gender;
public $Phone_No_2;
public $Company_E_Mail;
public $Last_Date_Modified;
public $Privacy_Blocked;
public $Global_Dimension_1_Code;
public $Global_Dimension_2_Code;
public $Max_Imprest_Amount;
public $Suspend_Leave_Application;
public $Maximum_Applicable_Trainings;
public $Balance;
public $Address;
public $Address_2;
public $Post_Code;
public $City;
public $Country_Region_Code;
public $ShowMap;
public $Mobile_Phone_No;
public $Pager;
public $Extension;
public $E_Mail;
public $Alt_Address_Code;
public $Alt_Address_Start_Date;
public $Alt_Address_End_Date;
public $Birth_Date;
public $Period_To_Retirement;
public $Service_Period;
public $National_ID;
public $Age;
public $Physical_Address;
public $Grade;
public $Global_Dimension_1_Name;
public $NHIF_Number;
public $NSSF_Number;
public $KRA_Number;
public $Marital_Status;
public $Payment_Methods;
public $Employment_Date;
public $Nature_Of_Employment;
public $Disabled;
public $Status;
public $End_of_Contract_Date;
public $Notice_Period;
public $Inactive_Date;
public $Cause_of_Inactivity_Code;
public $Termination_Date;
public $Grounds_for_Term_Code;
public $Emplymt_Contract_Code;
public $Statistics_Group_Code;
public $Resource_No;
public $Salespers_Purch_Code;
public $Manager_No;
public $Probation_Status;
public $Probation_Period_Extended;
public $End_of_Probation_Period;
public $Probabtion_Extended_By;
public $New_Probation_Period_End_Date;
public $Reasons_For_Extension;
public $User_ID;
public $Employee_Posting_Group;
public $Application_Method;
public $Bank_Branch_No;
public $Bank_Account_No;

public $Bank_Code;
public $Bank_Branch_Code;
public $Bank_Name;
public $Bank_Branch_Name;

public $IBAN;
public $SWIFT_Code;
public $Job_Description;
public $ProfileID;
public $Date_of_Leaving;
public $Manager;
public $Is_Long_Term;

public $Employee_Category;

    public function rules()
    {
        return [

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Global_Dimension_1_Name' =>  'Pointer'
        ];
    }

}