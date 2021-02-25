<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/25/2020
 * Time: 3:55 PM
 */


namespace frontend\controllers;

use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\BadRequestHttpException;

use frontend\models\Leave;
use yii\web\Response;

class ApprovalsController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup','index'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout','index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            'contentNegotiator' =>[
                'class' => ContentNegotiator::class,
                'only' => ['getapprovals','open','rejected','approved','super-approved','super-rejected'],
                'formatParam' => '_format',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    //'application/xml' => Response::FORMAT_XML,
                ],
            ]
        ];
    }

    public function actionIndex(){

        return $this->render('index');

    }

    public function actionOpenApprovals(){

        return $this->render('open');

    }

    public function actionRejectedApprovals(){

        return $this->render('rejected');

    }


    public function actionApprovedApprovals(){

        return $this->render('approved');

    }

    public function actionSapproved() 
    {
        return $this->render('sapproved');
    }

    public function actionSrejected()
    {
        return $this->render('srejected');
    }

   

   

    public function actionGetapprovals(){
        $service = Yii::$app->params['ServiceName']['RequestsTo_ApprovePortal'];

        $filter = [
            //'Employee_No' => Yii::$app->user->identity->{'Employee_No'},
            'Approver_No' => Yii::$app->user->identity->{'Employee_No'},
        ];
        $approvals = \Yii::$app->navhelper->getData($service,$filter);

        $result = [];

        if(!is_object($approvals)){
            foreach($approvals as $app){


                    if(stripos($app->Details, 'leave') !== FALSE && stripos($app->Details, 'Recall') == FALSE && stripos($app->Details, 'Plan') == FALSE){
                        $Approvelink = ($app->Status == 'Open')? Html::a('Approve Leave',[
                            'approve-leave',
                            'app'=> $app->Document_No,
                            'empNo' => $app->Approver_No
                             ],['class'=>'btn btn-success btn-xs','data' => [
                            'confirm' => 'Are you sure you want to Approve this request?',
                            'method' => 'post',
                        ]]):'';
                    }
                    elseif(stripos($app->Details, 'Recall') !== FALSE)
                    {
                        $Approvelink = ($app->Status == 'Open')? Html::a('Approve Leave Recall',[
                            'approve-recall',
                            'app'=> $app->Document_No,
                            'empNo' => $app->Approver_No
                             ],['class'=>'btn btn-success btn-xs','data' => [
                            'confirm' => 'Are you sure you want to Approve this request?',
                            'method' => 'post',
                        ]]):'';
                    }
                    elseif(stripos($app->Details, 'Plan') !== FALSE)
                    {
                        $Approvelink = ($app->Status == 'Open')? Html::a('Approve Leave Plan',[
                            'approve-leave-plan',
                            'app'=> $app->Document_No,
                            'empNo' => $app->Approver_No
                             ],['class'=>'btn btn-success btn-xs','data' => [
                            'confirm' => 'Are you sure you want to Approve this request?',
                            'method' => 'post',
                        ]]):'';
                    }elseif($app->Document_Type == 'Requisition_Header') // Purchase Requisition
                    {
                        $Approvelink = ($app->Status == 'Open')? Html::a('Approve Request',['approve-request','app'=> $app->Document_No, 'empNo' => $app->Approver_No, 'docType' => 'Requisition_Header'  ],['class'=>'btn btn-success btn-xs','data' => [
                            'confirm' => 'Are you sure you want to Approve this request?',
                            'method' => 'post',
                        ]]):'';

                        $Rejectlink = ($app->Status == 'Open')? Html::a('Reject Request',['reject-request', 'docType' => 'Requisition_Header' ],['class'=>'btn btn-warning reject btn-xs',
                            'rel' => $app->Document_No,
                            'rev' => $app->Record_ID_to_Approve,
                            'name' => $app->Table_ID
                        ]): "";


                    }elseif ($app->Document_Type == 'Store_Requisition') {
                        $Approvelink = ($app->Status == 'Open')? Html::a('Approve Request',['approve-request','app'=> $app->Document_No, 'empNo' => $app->Approver_No, 'docType' => 'Store_Requisition'  ],['class'=>'btn btn-success btn-xs','data' => [
                            'confirm' => 'Are you sure you want to Approve this request?',
                            'method' => 'post',
                        ]]):'';

                        $Rejectlink = ($app->Status == 'Open')? Html::a('Reject Request',['reject-request', 'docType' => 'Store_Requisition' ],['class'=>'btn btn-warning reject btn-xs',
                            'rel' => $app->Document_No,
                            'rev' => $app->Record_ID_to_Approve,
                            'name' => $app->Table_ID
                        ]): "";
                    }
                    elseif($app->Document_Type == 'Fueling')
                    {
                         $Approvelink = ($app->Status == 'Open')? Html::a('Approve Request',['approve-request','app'=> $app->Document_No, 'empNo' => $app->Approver_No, 'docType' => 'Fueling'  ],['class'=>'btn btn-success btn-xs','data' => [
                            'confirm' => 'Are you sure you want to Approve this request?',
                            'method' => 'post',
                        ]]):'';

                        $Rejectlink = ($app->Status == 'Open')? Html::a('Reject Request',['reject-request', 'docType' => 'Fueling' ],['class'=>'btn btn-warning reject btn-xs',
                            'rel' => $app->Document_No,
                            'rev' => $app->Record_ID_to_Approve,
                            'name' => $app->Table_ID
                        ]): "";
                    }
                    elseif($app->Document_Type == 'V_Booking')
                    {
                         $Approvelink = ($app->Status == 'Open')? Html::a('Approve Request',['approve-request','app'=> $app->Document_No, 'empNo' => $app->Approver_No, 'docType' => 'V_Booking'  ],['class'=>'btn btn-success btn-xs','data' => [
                            'confirm' => 'Are you sure you want to Approve this request?',
                            'method' => 'post',
                        ]]):'';

                        $Rejectlink = ($app->Status == 'Open')? Html::a('Reject Request',['reject-request', 'docType' => 'V_Booking' ],['class'=>'btn btn-warning reject btn-xs',
                            'rel' => $app->Document_No,
                            'rev' => $app->Record_ID_to_Approve,
                            'name' => $app->Table_ID
                        ]): "";
                    }
                     elseif($app->Document_Type == 'V_Repair')
                    {
                         $Approvelink = ($app->Status == 'Open')? Html::a('Approve Request',['approve-request','app'=> $app->Document_No, 'empNo' => $app->Approver_No, 'docType' => 'V_Repair'  ],['class'=>'btn btn-success btn-xs','data' => [
                            'confirm' => 'Are you sure you want to Approve this request?',
                            'method' => 'post',
                        ]]):'';

                        $Rejectlink = ($app->Status == 'Open')? Html::a('Reject Request',['reject-request', 'docType' => 'V_Repair' ],['class'=>'btn btn-warning reject btn-xs',
                            'rel' => $app->Document_No,
                            'rev' => $app->Record_ID_to_Approve,
                            'name' => $app->Table_ID
                        ]): "";
                    }
                    elseif($app->Document_Type == 'Contract_Renewal')
                    {
                         $Approvelink = ($app->Status == 'Open')? Html::a('Approve Request',['approve-request','app'=> $app->Document_No, 'empNo' => $app->Approver_No, 'docType' => 'Contract_Renewal'  ],['class'=>'btn btn-success btn-xs','data' => [
                            'confirm' => 'Are you sure you want to Approve this request?',
                            'method' => 'post',
                        ]]):'';

                        $Rejectlink = ($app->Status == 'Open')? Html::a('Reject Request',['reject-request', 'docType' => 'Contract_Renewal' ],['class'=>'btn btn-warning reject btn-xs',
                            'rel' => $app->Document_No,
                            'rev' => $app->Record_ID_to_Approve,
                            'name' => $app->Table_ID
                        ]): "";
                    }
                    else
                    {
                        $Approvelink = ($app->Status == 'Open')? Html::a('Approve Request',['approve-request','app'=> $app->Document_No, 'empNo' => $app->Approver_No ],['class'=>'btn btn-success btn-xs','data' => [
                            'confirm' => 'Are you sure you want to Approve this request?',
                            'method' => 'post',
                        ]]):'';
                    }

                    $Rejectlink = ($app->Status == 'Open')? Html::a('Reject Request',['reject-request' ],['class'=>'btn btn-warning reject btn-xs',
                        'rel' => $app->Document_No,
                        'rev' => $app->Record_ID_to_Approve,
                        'name' => $app->Table_ID
                        ]): "";


                    /*Card Details */


                    if($app->Document_Type == 'Staff_Board_Allowance'){
                        $detailsLink = Html::a('View Details',['fund-requisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Imprest')
                    {
                        $detailsLink = Html::a('View Details',['imprest/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Salary_Advance')
                    {
                        $detailsLink = Html::a('View Details',['salaryadvance/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Requisition_Header')
                    {
                        $detailsLink = Html::a('View Details',['purchase-requisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Fueling')
                    {
                        $detailsLink = Html::a('View Details',['fuel/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'V_Booking')
                    {
                        $detailsLink = Html::a('View Details',['vehiclerequisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'V_Repair')
                    {
                        $detailsLink = Html::a('View Details',['repair-requisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Store_Requisition') {
                        # code...
                        $detailsLink = Html::a('View Details',['storerequisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Leave_Application') {
                        # code...
                        $detailsLink = Html::a('View Details',['leave/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Leave_Plan') {
                        # code...
                        $detailsLink = Html::a('View Details',['leaveplan/view','Plan_No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Contract_Renewal') {
                        # code...
                        $detailsLink = Html::a('View Details',['contractrenewal/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Medical') {
                        # code...
                        $detailsLink = Html::a('View Details',['medicalcover/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    else{
                        $detailsLink = '';

                    }





                $result['data'][] = [
                    'Key' => $app->Key,
                    // 'ToApprove' => $app->ToApprove,
                    'Details' => $app->Details,
                    'Comment' => $app->Comment,
                    'Sender_ID' => $app->Sender_Name,
                    'Document_Type' => $app->Document_Type,
                    'Status' => $app->Status,
                    'Document_No' => $app->Document_No,
                    'Approvelink' => $Approvelink,
                    'Rejectlink' => $Rejectlink,
                    'details' => $detailsLink

                ];
            }
        }


        return $result;
    }


    /*Open Approvals*/

    public function actionOpen(){

        $service = Yii::$app->params['ServiceName']['RequestsTo_ApprovePortal'];

        $filter = [
            'Sender_No' => Yii::$app->user->identity->{'Employee_No'},
            'Status' => 'Open'
        ];
        $approvals = \Yii::$app->navhelper->getData($service,$filter);

        $result = [];

        if(!is_object($approvals)){
            foreach($approvals as $app){

                    /*Card Details */

                    if($app->Document_Type == 'Staff_Board_Allowance'){
                        $detailsLink = Html::a('View Details',['fund-requisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Imprest')
                    {
                        $detailsLink = Html::a('View Details',['imprest/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Salary_Advance')
                    {
                        $detailsLink = Html::a('View Details',['salaryadvance/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Requisition_Header')
                    {
                        $detailsLink = Html::a('View Details',['purchase-requisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Fueling')
                    {
                        $detailsLink = Html::a('View Details',['fuel/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'V_Booking')
                    {
                        $detailsLink = Html::a('View Details',['vehiclerequisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'V_Repair')
                    {
                        $detailsLink = Html::a('View Details',['repair-requisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Store_Requisition') {
                        # code...
                        $detailsLink = Html::a('View Details',['storerequisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Leave_Application') {
                        # code...
                        $detailsLink = Html::a('View Details',['leave/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Leave_Plan') {
                        # code...
                        $detailsLink = Html::a('View Details',['leaveplan/view','Plan_No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Contract_Renewal') {
                        # code...
                        $detailsLink = Html::a('View Details',['contractrenewal/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Medical') {
                        # code...
                        $detailsLink = Html::a('View Details',['medicalcover/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    else{
                        $detailsLink = '';

                    }


                $result['data'][] = [
                    'Key' => $app->Key,
                    // 'ToApprove' => $app->ToApprove,
                    'Details' => $app->Details,
                    'Comment' => $app->Comment,
                    'Sender_ID' => $app->Sender_Name,
                    'Document_Type' => $app->Document_Type,
                    'Status' => $app->Status,
                    'Document_No' => $app->Document_No,
                    'details' => $detailsLink

                ];
            }
        }


        return $result;
    }

    public function actionRejected()
    {

        $service = Yii::$app->params['ServiceName']['RequestsTo_ApprovePortal'];

        $filter = [
            'Sender_No' => Yii::$app->user->identity->{'Employee_No'},
            'Status' => 'Rejected'
        ];
        $approvals = \Yii::$app->navhelper->getData($service,$filter);

        $result = [];

        if(!is_object($approvals)){
            foreach($approvals as $app){

                    /*Card Details */

                    if($app->Document_Type == 'Staff_Board_Allowance'){
                        $detailsLink = Html::a('View Details',['fund-requisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Imprest')
                    {
                        $detailsLink = Html::a('View Details',['imprest/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Salary_Advance')
                    {
                        $detailsLink = Html::a('View Details',['salaryadvance/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Requisition_Header')
                    {
                        $detailsLink = Html::a('View Details',['purchase-requisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Fueling')
                    {
                        $detailsLink = Html::a('View Details',['fuel/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'V_Booking')
                    {
                        $detailsLink = Html::a('View Details',['vehiclerequisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'V_Repair')
                    {
                        $detailsLink = Html::a('View Details',['repair-requisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Store_Requisition') {
                        # code...
                        $detailsLink = Html::a('View Details',['storerequisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Leave_Application') {
                        # code...
                        $detailsLink = Html::a('View Details',['leave/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Leave_Plan') {
                        # code...
                        $detailsLink = Html::a('View Details',['leaveplan/view','Plan_No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Contract_Renewal') {
                        # code...
                        $detailsLink = Html::a('View Details',['contractrenewal/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Medical') {
                        # code...
                        $detailsLink = Html::a('View Details',['medicalcover/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    else{
                        $detailsLink = '';

                    }



                $result['data'][] = [
                    'Key' => $app->Key,
                    // 'ToApprove' => $app->ToApprove,
                    'Details' => $app->Details,
                    'Comment' => $app->Comment,
                    'Sender_ID' => $app->Sender_Name,
                    'Document_Type' => $app->Document_Type,
                    'Status' => $app->Status,
                    'Document_No' => $app->Document_No,
                    'details' => $detailsLink

                ];
            }
        }


        return $result;

    }

    public function actionApproved()
    {


        $service = Yii::$app->params['ServiceName']['RequestsTo_ApprovePortal'];

        $filter = [
            'Sender_No' => Yii::$app->user->identity->{'Employee_No'},
            'Status' => 'Approved'
        ];
        $approvals = \Yii::$app->navhelper->getData($service,$filter);

        $result = [];

        if(!is_object($approvals)){
            foreach($approvals as $app){

                    /*Card Details */

                    if($app->Document_Type == 'Staff_Board_Allowance'){
                        $detailsLink = Html::a('View Details',['fund-requisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Imprest')
                    {
                        $detailsLink = Html::a('View Details',['imprest/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Salary_Advance')
                    {
                        $detailsLink = Html::a('View Details',['salaryadvance/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Requisition_Header')
                    {
                        $detailsLink = Html::a('View Details',['purchase-requisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Fueling')
                    {
                        $detailsLink = Html::a('View Details',['fuel/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'V_Booking')
                    {
                        $detailsLink = Html::a('View Details',['vehiclerequisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'V_Repair')
                    {
                        $detailsLink = Html::a('View Details',['repair-requisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Store_Requisition') {
                        # code...
                        $detailsLink = Html::a('View Details',['storerequisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Leave_Application') {
                        # code...
                        $detailsLink = Html::a('View Details',['leave/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Leave_Plan') {
                        # code...
                        $detailsLink = Html::a('View Details',['leaveplan/view','Plan_No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Contract_Renewal') {
                        # code...
                        $detailsLink = Html::a('View Details',['contractrenewal/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Medical') {
                        # code...
                        $detailsLink = Html::a('View Details',['medicalcover/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    else{
                        $detailsLink = '';

                    }



                $result['data'][] = [
                    'Key' => $app->Key,
                    // 'ToApprove' => $app->ToApprove,
                    'Details' => $app->Details,
                    'Comment' => $app->Comment,
                    'Sender_ID' => $app->Sender_Name,
                    'Document_Type' => $app->Document_Type,
                    'Status' => $app->Status,
                    'Document_No' => $app->Document_No,
                    'details' => $detailsLink

                ];
            }
        }


        return $result;

    }

    /*Get Approvals based on supervisor actions -Approved or Rejected -*/

     /*Request I have approved*/

    public function actionSuperApproved(){

        $service = Yii::$app->params['ServiceName']['RequestsTo_ApprovePortal'];
        $filter = [
            'Approver_No' => Yii::$app->user->identity->{'Employee_No'},
            'Status' => 'Approved'
        ];
        $approvals = Yii::$app->navhelper->getData($service,$filter);

        $result = [];

        if(!is_object($approvals)){
            foreach($approvals as $app){

                    /*Card Details */

                    if($app->Document_Type == 'Staff_Board_Allowance'){
                        $detailsLink = Html::a('View Details',['fund-requisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Imprest')
                    {
                        $detailsLink = Html::a('View Details',['imprest/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Salary_Advance')
                    {
                        $detailsLink = Html::a('View Details',['salaryadvance/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Requisition_Header')
                    {
                        $detailsLink = Html::a('View Details',['purchase-requisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Fueling')
                    {
                        $detailsLink = Html::a('View Details',['fuel/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'V_Booking')
                    {
                        $detailsLink = Html::a('View Details',['vehiclerequisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'V_Repair')
                    {
                        $detailsLink = Html::a('View Details',['repair-requisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Store_Requisition') {
                        # code...
                        $detailsLink = Html::a('View Details',['storerequisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Leave_Application') {
                        # code...
                        $detailsLink = Html::a('View Details',['leave/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Leave_Plan') {
                        # code...
                        $detailsLink = Html::a('View Details',['leaveplan/view','Plan_No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Contract_Renewal') {
                        # code...
                        $detailsLink = Html::a('View Details',['contractrenewal/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Medical') {
                        # code...
                        $detailsLink = Html::a('View Details',['medicalcover/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    else{
                        $detailsLink = '';

                    }



                $result['data'][] = [
                    'Key' => $app->Key,
                    // 'ToApprove' => $app->ToApprove,
                    'Details' => $app->Details,
                    'Comment' => $app->Comment,
                    'Sender_ID' => $app->Sender_Name,
                    'Document_Type' => $app->Document_Type,
                    'Status' => $app->Status,
                    'Document_No' => $app->Document_No,
                    'details' => $detailsLink

                ];
            }
        }


        return $result;

       
    }


    /* Requests I have Rejected */

    public function actionSuperRejected(){

        $service = Yii::$app->params['ServiceName']['RequestsTo_ApprovePortal'];
        $filter = [
            'Approver_No' => Yii::$app->user->identity->{'Employee_No'},
            'Status' => 'Rejected'
        ];
        $approvals = Yii::$app->navhelper->getData($service,$filter);

        $result = [];

        if(!is_object($approvals)){
            foreach($approvals as $app){

                    /*Card Details */

                    if($app->Document_Type == 'Staff_Board_Allowance'){
                        $detailsLink = Html::a('View Details',['fund-requisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Imprest')
                    {
                        $detailsLink = Html::a('View Details',['imprest/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Salary_Advance')
                    {
                        $detailsLink = Html::a('View Details',['salaryadvance/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Requisition_Header')
                    {
                        $detailsLink = Html::a('View Details',['purchase-requisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Fueling')
                    {
                        $detailsLink = Html::a('View Details',['fuel/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'V_Booking')
                    {
                        $detailsLink = Html::a('View Details',['vehiclerequisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'V_Repair')
                    {
                        $detailsLink = Html::a('View Details',['repair-requisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Store_Requisition') {
                        # code...
                        $detailsLink = Html::a('View Details',['storerequisition/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Leave_Application') {
                        # code...
                        $detailsLink = Html::a('View Details',['leave/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Leave_Plan') {
                        # code...
                        $detailsLink = Html::a('View Details',['leaveplan/view','Plan_No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Contract_Renewal') {
                        # code...
                        $detailsLink = Html::a('View Details',['contractrenewal/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    elseif ($app->Document_Type == 'Medical') {
                        # code...
                        $detailsLink = Html::a('View Details',['medicalcover/view','No'=> $app->Document_No ],['class'=>'btn btn-outline-info btn-xs','target' => '_blank']);
                    }
                    else{
                        $detailsLink = '';

                    }



                $result['data'][] = [
                    'Key' => $app->Key,
                    // 'ToApprove' => $app->ToApprove,
                    'Details' => $app->Details,
                    'Comment' => $app->Comment,
                    'Sender_ID' => $app->Sender_Name,
                    'Document_Type' => $app->Document_Type,
                    'Status' => $app->Status,
                    'Document_No' => $app->Document_No,
                    'details' => $detailsLink

                ];
            }
        }


        return $result;

        
    }





    public function actionApproveRequest($app, $empNo, $docType = "")
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];

        $data = [
            'applicationNo' => $app,
            'emplN' => $empNo
        ];

        if($docType == 'Requisition_Header' || $docType == 'Store_Requisition')
        {
            $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanApproveRequisitionHeader');
        }
        elseif($docType == 'Fueling')
        {
            $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanApproveFuelRequisition');
        }
        elseif($docType == 'V_Booking')
        {
            $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanApproveVehicleBookingRequisition');
        }
         elseif($docType == 'V_Repair')
        {
            $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanApproveVehicleRepairRequisition');
        }
        elseif($docType == 'Contract_Renewal')
        {
            $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanApproveContractRenewal');
        }
        elseif($docType == 'Medical')
        {
            $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanApproveMediical');
        }
        else{
            $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanApproveImprest');
        }


        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Approval Request Approved Successfully.', true);
            return $this->redirect(['index']);
        }else{
            Yii::$app->session->setFlash('error', 'Error Approving Approval Approval Request.  : '. $result);
            return $this->redirect(['index']);
        }
    }

    public function actionRejectRequest($docType = ""){
        $service = Yii::$app->params['ServiceName']['PortalFactory'];
        $Commentservice = Yii::$app->params['ServiceName']['ApprovalCommentsWeb'];

        if(Yii::$app->request->post()){
            $comment = Yii::$app->request->post('comment');
            $documentno = Yii::$app->request->post('documentNo');
            $Record_ID_to_Approve = Yii::$app->request->post('Record_ID_to_Approve');
            $Table_ID = Yii::$app->request->post('Table_ID');


           $commentData = [
               'Comment' => $comment,
               'Document_No' => $documentno,
               'Record_ID_to_Approve' => $Record_ID_to_Approve,
               'Table_ID' => $Table_ID
           ];


            $data = [
                'applicationNo' => $documentno,
            ];
            //save comment
            $Commentrequest = Yii::$app->navhelper->postData($Commentservice, $commentData);
           // Call rejection cu function

            if($docType == 'Requisition_Header' || $docType == 'Store_Requisition')
            {
                $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanRejectRequisitionHeader');
            }
            elseif($docType == 'Fueling')
            {
                 $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanRejectFuelRequisition');
            }
            elseif($docType == 'V_Booking')
            {
                 $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanRejectVehicleBookingRequisition');
            }
            elseif($docType == 'V_Repair')
            {
                 $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanRejectVehicleRepairRequisition');
            }
             elseif($docType == 'Contract_Renewal')
            {
                 $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanRejectContractRenewal');
            }
             elseif($docType == 'Medical')
            {
                 $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanRejectMediical');
            }
            else
            {
                $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanRejectLeave');
            }


            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if(is_object($Commentrequest) && !is_string($result)){
                return ['note' => '<div class="alert alert-success">Request Rejected Successfully. </div>' ];
            }else{
                return ['note' => '<div class="alert alert-danger">Error Rejecting Request: '.$result.'   '.$Commentrequest.'</div>'];
            }


        }


    }










    public function actionApproveLeave($app,$empNo)
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];

        $data = [
            'applicationNo' => $app,
            'emplN' => $empNo
        ];


        $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanApproveLeave');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Request Approved Successfully.', true);
            return $this->redirect(['index']);
        }else{

            Yii::$app->session->setFlash('error', 'Error Approving Request.  : '. $result);
            return $this->redirect(['index']);

        }
    }

    public function actionApproveRecall($app,$empNo)
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];

        $data = [
            'applicationNo' => $app,
            'emplN' => $empNo
        ];


        $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanApproveLeaveRecall');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Request Approved Successfully.', true);
            return $this->redirect(['index']);
        }else{

            Yii::$app->session->setFlash('error', 'Error Approving Request.  : '. $result);
            return $this->redirect(['index']);

        }
    }

    /* Approve Leave Plan */

    public function actionApproveLeavePlan($app, $empNo)
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];

        $data = [
            'applicationNo' => $app,
            'emplN' => $empNo
        ];


        $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanApproveLeavePlan');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Request Approved Successfully.', true);
            return $this->redirect(['index']);
        }else{

            Yii::$app->session->setFlash('error', 'Error Approving Request.  : '. $result);
            return $this->redirect(['index']);

        }
    }

    public function getName($userID){

        //get Employee No
        $user = \common\models\User::find()->where(['User ID' => $userID])->one();
        $No = $user->{'Employee_No'};
        //Get Employees full name
        $service = Yii::$app->params['ServiceName']['Employees'];
        $filter = [
            'No' => $No
        ];

        $results = Yii::$app->navhelper->getData($service,$filter);
        return $results[0]->FullName;
    }




    public function loadtomodel($obj,$model){

        if(!is_object($obj)){
            return false;
        }
        $modeldata = (get_object_vars($obj)) ;
        foreach($modeldata as $key => $val){
            if(is_object($val)) continue;
            $model->$key = $val;
        }

        return $model;
    }

}