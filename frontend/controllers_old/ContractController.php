<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:21 PM
 */

namespace frontend\controllers;
use frontend\models\Contract;
use frontend\models\Employeeappraisalkra;
use frontend\models\Experience;
use frontend\models\Probation;
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
use kartik\mpdf\Pdf;

class ContractController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup','index'],
                'rules' => [
                    [
                        'actions' => ['signup','index'],
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
                'only' => ['getrenewals','getsupervisorlist','gethrlist','getapproved','getrejected'],
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

    public function actionSuperlist(){

    //print_r(Yii::$app->user->identity->getId()); exit;
        return $this->render('superlist');

    }

    public function actionHrlist(){


        return $this->render('hrlist');

    }

    public function actionApprovedlist(){


        return $this->render('approvedlist');

    }

    public function actionRejectedlist(){


        return $this->render('rejectedlist');

    }



    public function actionCreate(){

        $service = Yii::$app->params['ServiceName']['ContractRenewalCard'];
        $model = new Contract();

        $model->Employee_No = Yii::$app->user->identity->employee[0]->Employee_No;
        $renewal = Yii::$app->navhelper->postData($service,[]);




        if(!is_string($renewal)){
            $model = Yii::$app->navhelper->loadmodel($renewal,$model);
        }

        $status = [
            'New' => 'New',
            'Approved' => 'Approved',
            'Supervisor_Level' => 'Supervisor_Level',
            'HR_Level' => 'HR_Level',
            'Rejected' => 'Rejected'

        ];

            if(!is_string($renewal)){
                Yii::$app->session->setFlash('success','Contract Renewal Initiated successfully.',true);
                return $this->redirect(['update','Employee_No' => $model->Employee_No,'No' => $model->No]);

            }else{
                Yii::$app->session->setFlash('error','Error Creating Contract Request: '.$renewal,true);
                return $this->redirect(['index']);

            }

           //print_r($model); exit;

           return $this->render('create',[
               'model' => $model,
               'status' => $status,
           ]);

      
    }


    public function actionUpdate(){
        $model = new Contract() ;
        $model->isNewRecord = false;
        $service = Yii::$app->params['ServiceName']['ContractRenewalCard'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->employee[0]->Employee_No,
            'No' => Yii::$app->request->get('No')
        ];
        $result = Yii::$app->navhelper->getData($service,$filter);


        if(is_array($result)){
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($result[0],$model) ;
        }else{
            Yii::$app->navhelper->printrr($result);
        }

        $status = [
            'New' => 'New',
            'Approved' => 'Approved',
            'Supervisor_Level' => 'Supervisor_Level',
            'HR_Level' => 'HR_Level',
            'Rejected' => 'Rejected'

        ];


        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Contract'],$model) ){
            $result = Yii::$app->navhelper->updateData($service,$model);

            //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if(!is_string($result)){
                Yii::$app->session->setFlash('success','Contract Renewal created successfully. Kindly submit it to supervisor for processing.');
                //return ['note' => '<div class="alert alert-success">Contract Request Updated Successfully </div>' ];
                return $this->redirect(['view','Employee_No' => $model->Employee_No,'No' => $model->No]);


            }else{
                Yii::$app->session->setFlash('error','Error Creating Contract Request: '.$result,true);
                //return ['note' => '<div class="alert alert-danger">Error Updating Contract Request : '.$result.'</div>'];
                return $this->redirect(['update','Employee_No' => $model->Employee_No,'No' => $model->No]);
            }


        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('update', [
                'model' => $model,
                'status' => $status,
            ]);
        }

        return $this->render('update',[
            'model' => $model,
            'status' => $status,
        ]);
    }

    public function actionDelete(){
        $service = Yii::$app->params['ServiceName']['experience'];
        $result = Yii::$app->navhelper->deleteData($service,Yii::$app->request->get('Key'));
        if(!is_string($result)){
            Yii::$app->session->setFlash('success','Work Experience Purged Successfully .',true);
            return $this->redirect(['index']);
        }else{
            Yii::$app->session->setFlash('error','Error Purging Work Experience: '.$result,true);
            return $this->redirect(['index']);
        }
    }

    public function actionView($Employee_No, $No){
        $service = Yii::$app->params['ServiceName']['ContractRenewalCard'];
        $model = new Contract();

        $filter = [
            'No' => $No,
            'Employee_No' => $Employee_No,
        ];

        $renewal = Yii::$app->navhelper->getData($service, $filter);

        //print_r($renewal); exit;

        if(is_array($renewal)){
            $model = Yii::$app->navhelper->loadmodel($renewal[0],$model);
        }

        //Yii::$app->recruitment->printrr($model->getObjectives());

        $status = [
            'New' => 'New',
            'Approved' => 'Approved',
            'Supervisor_Level' => 'Supervisor_Level',
            'HR_Level' => 'HR_Level',
            'Rejected' => 'Rejected'

        ];



        return $this->render('view',[
            'model' => $model,
            'card' => $renewal[0],
            'status' => $status,
        ]);
    }


    public function actionApprovalRequest($app){
        $service = Yii::$app->params['ServiceName']['Portal_Workflows'];
        $data = ['applicationNo' => $app];

        $request = Yii::$app->navhelper->SendLeaveApprovalRequest($service, $data);

        if(is_array($request)){
            Yii::$app->session->setFlash('success','Leave request sent for approval Successfully',true);
            return $this->redirect(['index']);
        }else{
            Yii::$app->session->setFlash('error','Error sending leave request for approval: '.$request,true);
            return $this->redirect(['index']);
        }
    }

    public function actionCancelRequest($app){
        $service = Yii::$app->params['ServiceName']['Portal_Workflows'];
        $data = ['applicationNo' => $app];

        $request = Yii::$app->navhelper->CancelLeaveApprovalRequest($service, $data);

        if(is_array($request)){
            Yii::$app->session->setFlash('success','Leave Approval Request Cancelled Successfully',true);
            return $this->redirect(['index']);
        }else{
            Yii::$app->session->setFlash('error','Error Cancelling Leave Approval: '.$request,true);
            return $this->redirect(['index']);
        }
    }

    /*Data access functions */

    public function actionLeavebalances(){

        $balances = $this->Getleavebalance();

        return $this->render('leavebalances',['balances' => $balances]);

    }

    // Employee No

    public function actionGetrenewals(){
        $service = Yii::$app->params['ServiceName']['NewContractRenewal'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee_No'},
        ];
        $renewals = \Yii::$app->navhelper->getData($service,$filter);
        //ksort($renewals);
        $result = [];

        if(is_array($renewals)){
            ksort($renewals);
            foreach($renewals as $req){

                $Viewlink = Html::a('<i class="fa fa-eye"></i>', ['view','Employee_No' => $req->Employee_No, 'No' => !empty($req->No)?$req->No: ''], ['class' => 'btn btn-outline-primary btn-xs']);
                $Updatelink = $req->Status == 'New' ? ' | '.Html::a('<i class="fa fa-edit"></i>', ['update','Employee_No' => $req->Employee_No, 'No' => !empty($req->No)?$req->No: ''], ['class' => 'btn btn-outline-primary btn-xs']):'';

                $result['data'][] = [
                    'No' => !empty($req->No) ? $req->No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Supervisor_User_Id' =>  !empty($req->Supervisor_User_Id) ? $req->Supervisor_User_Id : '',
                    'Current_User' =>  !empty($req->Current_User) ? $req->Current_User : '',
                    'Hr_User_Id' =>  !empty($req->Hr_User_Id) ? $req->Hr_User_Id : '',
                    'Status' => !empty($req->Status) ? $req->Status : '',
                    'Action' => !empty($Viewlink) ? $Viewlink.$Updatelink : '',

                ];

            }
        }

        return $result;
    }


    // Supervisor No -- Yii::$app->user->identity->getId()

    public function actionGetsupervisorlist(){
        $service = Yii::$app->params['ServiceName']['supervisorList'];
        $filter = [
            'Supervisor_User_Id' => Yii::$app->user->identity->navuserid,
        ];
        $renewals = \Yii::$app->navhelper->getData($service,$filter);

        $result = [];

        if(is_array($renewals)){
            ksort($renewals);
            foreach($renewals as $req){

                $Viewlink = Html::a('<i class="fa fa-eye"></i>', ['view','Employee_No' => $req->Employee_No, 'No' => !empty($req->No)?$req->No: ''], ['class' => 'btn btn-outline-primary btn-xs']);


                $result['data'][] = [
                    'No' => !empty($req->No) ? $req->No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Supervisor_User_Id' =>  !empty($req->Supervisor_User_Id) ? $req->Supervisor_User_Id : '',
                    'Current_User' =>  !empty($req->Current_User) ? $req->Current_User : '',
                    'Hr_User_Id' =>  !empty($req->Hr_User_Id) ? $req->Hr_User_Id : '',
                    'Status' => !empty($req->Status) ? $req->Status : '',
                    'Action' => !empty($Viewlink) ? $Viewlink: '',

                ];

            }
        }

        return $result;
    }


    // HR No

    // Supervisor No -- Yii::$app->user->identity->getId()

    public function actionGethrlist(){
        $service = Yii::$app->params['ServiceName']['Hrcontractslist'];
        $filter = [
            'Hr_User_Id' => Yii::$app->user->identity->navuserid,
        ];
        $renewals = \Yii::$app->navhelper->getData($service,$filter);

        $result = [];

        if(is_array($renewals)){
            ksort($renewals);
            foreach($renewals as $req){

                $Viewlink = Html::a('<i class="fa fa-eye"></i>', ['view','Employee_No' => $req->Employee_No, 'No' => !empty($req->No)?$req->No: ''], ['class' => 'btn btn-outline-primary btn-xs']);


                $result['data'][] = [
                    'No' => !empty($req->No) ? $req->No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Supervisor_User_Id' =>  !empty($req->Supervisor_User_Id) ? $req->Supervisor_User_Id : '',
                    'Current_User' =>  !empty($req->Current_User) ? $req->Current_User : '',
                    'Hr_User_Id' =>  !empty($req->Hr_User_Id) ? $req->Hr_User_Id : '',
                    'Status' => !empty($req->Status) ? $req->Status : '',
                    'Action' => !empty($Viewlink) ? $Viewlink: '',

                ];

            }
        }

        return $result;
    }

    public function actionGetapproved(){
        $service = Yii::$app->params['ServiceName']['ApprovedContractsList'];
        $filter = [
            'Status' => 'Approved',
        ];
        $renewals = \Yii::$app->navhelper->getData($service,$filter);

        $result = [];

        if(is_array($renewals)){
            ksort($renewals);
            foreach($renewals as $req){

                $Viewlink = Html::a('<i class="fa fa-eye"></i>', ['view','Employee_No' => $req->Employee_No, 'No' => !empty($req->No)?$req->No: ''], ['class' => 'btn btn-outline-primary btn-xs']);


                $result['data'][] = [
                    'No' => !empty($req->No) ? $req->No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Supervisor_User_Id' =>  !empty($req->Supervisor_User_Id) ? $req->Supervisor_User_Id : '',
                    'Current_User' =>  !empty($req->Current_User) ? $req->Current_User : '',
                    'Hr_User_Id' =>  !empty($req->Hr_User_Id) ? $req->Hr_User_Id : '',
                    'Status' => !empty($req->Status) ? $req->Status : '',
                    'Action' => !empty($Viewlink) ? $Viewlink: '',

                ];

            }
        }

        return $result;
    }


    public function actionGetrejected(){
        $service = Yii::$app->params['ServiceName']['RejectedContractRenewal'];
        $filter = [
            'Status' => 'Rejected',
        ];
        $renewals = \Yii::$app->navhelper->getData($service,$filter);

        $result = [];

        if(is_array($renewals)){
            ksort($renewals);
            foreach($renewals as $req){

                $Viewlink = Html::a('<i class="fa fa-eye"></i>', ['view','Employee_No' => $req->Employee_No, 'No' => !empty($req->No)?$req->No: ''], ['class' => 'btn btn-outline-primary btn-xs']);


                $result['data'][] = [
                    'No' => !empty($req->No) ? $req->No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Supervisor_User_Id' =>  !empty($req->Supervisor_User_Id) ? $req->Supervisor_User_Id : '',
                    'Current_User' =>  !empty($req->Current_User) ? $req->Current_User : '',
                    'Hr_User_Id' =>  !empty($req->Hr_User_Id) ? $req->Hr_User_Id : '',
                    'Status' => !empty($req->Status) ? $req->Status : '',
                    'Action' => !empty($Viewlink) ? $Viewlink: '',

                ];

            }
        }

        return $result;
    }

    /*public function actionReport(){
        $service = Yii::$app->params['ServiceName']['expApplicationNo'];
        $leaves = \Yii::$app->navhelper->getData($service);
        krsort( $leaves);//sort by keys in descending order
        $content = $this->renderPartial('_historyreport',[
            'leaves' => $leaves
        ]);

        //return $content;
        $pdf = \Yii::$app->pdf;
        $pdf->content = $content;
        $pdf->orientation = Pdf::ORIENT_PORTRAIT;

        //The trick to returning binary content
        $content = $pdf->render('', 'S');
        $content = chunk_split(base64_encode($content));

        return $content;
    }*/

    public function actionReportview(){
        return $this->render('_viewreport',[
            'content'=>$this->actionReport()
        ]);
    }

    public function Getleavebalance(){
        $service = Yii::$app->params['ServiceName']['leaveBalance'];
        $filter = [
            'No' => Yii::$app->user->identity->{'Employee_No'},
        ];

        $balances = \Yii::$app->navhelper->getData($service,$filter);
        $result = [];

        //print '<pre>';
        // print_r($balances);exit;

        foreach($balances as $b){
            $result = [
                'Key' => $b->Key,
                'Annual_Leave_Bal' => $b->Annual_Leave_Bal,
                'Maternity_Leave_Bal' => $b->Maternity_Leave_Bal,
                'Paternity' => $b->Paternity,
                'Study_Leave_Bal' => $b->Study_Leave_Bal,
                'Compasionate_Leave_Bal' => $b->Compasionate_Leave_Bal,
                'Sick_Leave_Bal' => $b->Sick_Leave_Bal
            ];
        }

        return $result;

    }



    public function getAppraisalrating(){
        $service = Yii::$app->params['ServiceName']['AppraisalRating'];
        $filter = [
        ];

        $ratings = \Yii::$app->navhelper->getData($service,$filter);
        return $ratings;
    }

    public function getPerformancelevels(){
        $service = Yii::$app->params['ServiceName']['PerformanceLevel'];

        $ratings = \Yii::$app->navhelper->getData($service);
        return $ratings;
    }

    public function getCountries(){
        $service = Yii::$app->params['ServiceName']['Countries'];

        $res = [];
        $countries = \Yii::$app->navhelper->getData($service);
        foreach($countries as $c){
            if(!empty($c->Name))
                $res[] = [
                    'Code' => $c->Code,
                    'Name' => $c->Name
                ];
        }

        return $res;
    }

    public function getReligion(){
        $service = Yii::$app->params['ServiceName']['Religion'];
        $filter = [
            'Type' => 'Religion'
        ];
        $religion = \Yii::$app->navhelper->getData($service, $filter);
        return $religion;
    }

    //Submit Appraisal to supervisor

    public function actionSubmit($appraisalNo,$employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalWorkflow'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => 1
        ];

        $result = Yii::$app->navhelper->IanSendNewEmployeeForApproval($service,$data);

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Probation Appraisal Submitted Successfully.', true);
            return $this->redirect(['view','No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Submitting Probation Appraisal : '. $result);
            return $this->redirect(['view','No' => $appraisalNo,'Employee_No' => $employeeNo]);

        }

    }

    public function actionSubmittohr($appraisalNo,$employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalWorkflow'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => 1
        ];

        $result = Yii::$app->navhelper->IanSendEmployeeAppraisalToHr($service,$data);

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Probation Appraisal Submitted to HR Successfully.', true);
            return $this->redirect(['view','No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Submitting Probation Appraisal to HR : '. $result);
            return $this->redirect(['view','No' => $appraisalNo,'Employee_No' => $employeeNo]);

        }

    }


    public function actionClose($appraisalNo,$employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalWorkflow'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => 1
        ];

        $result = Yii::$app->navhelper->IanApproveNewEmployeeAppraisal($service,$data);

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Probation Appraisal Approved Successfully.', true);
            return $this->redirect(['view','No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Approving Probation Appraisal  : '. $result);
            return $this->redirect(['view','No' => $appraisalNo,'Employee_No' => $employeeNo]);

        }

    }


     public function actionBacktosuper($appraisalNo,$employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalWorkflow'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => 1,
            'rejectionComments' => 'The Appraisal is not conclusive and hence rejected.'
        ];

        $result = Yii::$app->navhelper->IanSendNewEmployeeAppraisalBackToSupervisor($service,$data);

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Probation Appraisal Sent Back to Supervisor Successfully.', true);
            return $this->redirect(['view','No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Sending Probation Back to Supervisor  : '. $result);
            return $this->redirect(['view','No' => $appraisalNo,'Employee_No' => $employeeNo]);

        }

    }

    public function actionBacktoemp($appraisalNo,$employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalWorkflow'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => 1,
            'rejectionComments' => 'The objectives are not SMART. Improve them and resubmit.'
        ];

        $result = Yii::$app->navhelper->IanSendNewEmployeeAppraisalBackToAppraisee($service,$data);

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Probation Appraisal Sent Back to Appraisee Successfully.', true);
            return $this->redirect(['view','No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Sending Probation Back to Appraisee  : '. $result);
            return $this->redirect(['view','No' => $appraisalNo,'Employee_No' => $employeeNo]);

        }

    }

    // Take Action

     public function actionTakeaction()
    {
        $service = Yii::$app->params['ServiceName']['ProbationCard'];
        $data = [
            'No' => Yii::$app->request->get('No'),
            'Employee_No' => Yii::$app->request->get('Employee_No'),
            'Action_Taken' => Yii::$app->request->get('Action_Taken'),
            'Key' => Yii::$app->request->get('Key')
            
        ];

        $result = Yii::$app->navhelper->updateData($service,$data);


        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!is_string($result)){
            return ['note' => '<div class="alert alert-success">Probation Action Set Successfully </div>' ];
        }else{

            return ['note' => '<div class="alert alert-error">Error Setting Probation Action. </div>' ];

        }

    }



    public function actionReport(){

        $service = Yii::$app->params['ServiceName']['PortalReports'];

        if(Yii::$app->request->post()){

            $data = [
                'appraisalNo' =>Yii::$app->request->post('appraisalNo'),
                'employeeNo' => Yii::$app->request->post('employeeNo')
            ];
            $path = Yii::$app->navhelper->IanGenerateNewEmployeeAppraisalReport($service,$data);
            //Yii::$app->recruitment->printrr($path);
            if(!is_file($path['return_value'])){

                return $this->render('report',[
                    'report' => false,
                    'message' => $path['return_value']
                ]);
            }
            $binary = file_get_contents($path['return_value']); //fopen($path['return_value'],'rb');
            $content = chunk_split(base64_encode($binary));
            //delete the file after getting it's contents --> This is some house keeping
            unlink($path['return_value']);

            // Yii::$app->recruitment->printrr($path);
            return $this->render('report',[
                'report' => true,
                'content' => $content,
            ]);
        }

        return $this->render('report',[
            'report' => false,
            'content' => '',
        ]);

    }


    // Send Contract Requisition to Supervisor

    public function actionSubmittosupervisor($No,$employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['ContractRenewalStatusChange'];
        $data = [
            'renewalNo' => $No,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => 1,
        ];

        $result = Yii::$app->navhelper->Contractworkflow($service,$data,'IanSendNewEmployeeForApproval');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Contract Requisition Sent to Supervisor Successfully.', true);
            return $this->redirect(['view','Employee_No' => $employeeNo,'No' => $No]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Sending Contract Renewal to Supervisor  : '. $result);
            return $this->redirect(['view','Employee_No' => $employeeNo,'No' => $No]);

        }

    }

    // Send Contract Back to Employee

    public function actionSendbacktoemployee($No,$employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['ContractRenewalStatusChange'];
        $data = [
            'renewalNo' => $No,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => 1,
            'rejectionComments' => ''
        ];

        $result = Yii::$app->navhelper->Contractworkflow($service,$data,'IanSendNewEmployeeAppraisalBackToAppraisee');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Contract Requisition Sent Back to Employee Successfully.', true);
            return $this->redirect(['view','Employee_No' => $employeeNo,'No' => $No]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Sending Contract Renewal Back to Employee  : '. $result);
            return $this->redirect(['view','Employee_No' => $employeeNo,'No' => $No]);

        }

    }

    //Send Contract to Hr

    public function actionSendtohr($No,$employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['ContractRenewalStatusChange'];
        $data = [
            'renewalNo' => $No,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => 1,
        ];

        $result = Yii::$app->navhelper->Contractworkflow($service,$data,'IanSendEmployeeAppraisalToHr');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Contract Requisition Sent to HR Successfully.', true);
            return $this->redirect(['view','Employee_No' => $employeeNo,'No' => $No]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Sending Contract Renewal to HR  : '. $result);
            return $this->redirect(['view','Employee_No' => $employeeNo,'No' => $No]);

        }

    }

    // Send Contract Back to Supervisor

    public function actionSendbacktosupervisor($No,$employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['ContractRenewalStatusChange'];
        $data = [
            'renewalNo' => $No,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => 1,
            'rejectionComments' => '',
        ];

        $result = Yii::$app->navhelper->Contractworkflow($service,$data,'IanSendNewEmployeeAppraisalBackToSupervisor');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Contract Requisition Sent Back to Supervisor Successfully.', true);
            return $this->redirect(['view','Employee_No' => $employeeNo,'No' => $No]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Sending Contract Renewal Back to Supervisor  : '. $result);
            return $this->redirect(['view','Employee_No' => $employeeNo,'No' => $No]);

        }

    }

    //Approve contract Renewal

    public function actionApprove($No,$employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['ContractRenewalStatusChange'];
        $data = [
            'renewalNo' => $No,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,


        ];

        $result = Yii::$app->navhelper->Contractworkflow($service,$data,'IanApproveNewEmployeeAppraisal');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Contract Requisition Approved Successfully.', true);
            return $this->redirect(['view','Employee_No' => $employeeNo,'No' => $No]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Approving Contract Renewal .  : '. $result);
            return $this->redirect(['view','Employee_No' => $employeeNo,'No' => $No]);

        }

    }


    // Reject Renewal

    public function actionReject($No,$employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['ContractRenewalStatusChange'];
        $data = [
            'renewalNo' => $No,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,


        ];

        $result = Yii::$app->navhelper->Contractworkflow($service,$data,'IanRejectNewEmployeeAppraisal');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Contract Requisition Rejected Successfully.', true);
            return $this->redirect(['view','Employee_No' => $employeeNo,'No' => $No]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Rejecting Contract Renewal Back to Supervisor  : '. $result);
            return $this->redirect(['view','Employee_No' => $employeeNo,'No' => $No]);

        }

    }





}