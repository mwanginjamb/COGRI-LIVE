<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:21 PM
 */

namespace frontend\controllers;
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

class ProbationController extends Controller
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
                'only' => ['getprobations','gethrprobations','getsupervisorprobations','getclosedprobations'],
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

    public function actionClosedlist(){


        return $this->render('closedlist');

    }



    public function actionCreate(){

       
            $service = Yii::$app->params['ServiceName']['ProbationCard'];

       
            $data = [
                'Employee_No' => Yii::$app->user->identity->{'Employee_No'},
            ];

            $result = Yii::$app->navhelper->postData($service,$data);

            if(!is_string($result)){
                Yii::$app->session->setFlash('success','Probation Appraisal Initiated successfully.',true);
                return $this->redirect(['index']);

            }else{
                Yii::$app->session->setFlash('error','Error Creating Probation Appraisal: '.$result,true);
                return $this->redirect(['index']);

            }

      
    }


    public function actionUpdate(){
        $model = new Employeeappraisalkra() ;
        $model->isNewRecord = false;
        $service = Yii::$app->params['ServiceName']['EmployeeAppraisalKRA'];
        $filter = [
            'Line_No' => Yii::$app->request->get('Line_No'),
            'Employee_No' => Yii::$app->request->get('Employee_No'),
            'Appraisal_No' => Yii::$app->request->get('Appraisal_No')
        ];
        $result = Yii::$app->navhelper->getData($service,$filter);
        $ratings = $this->getAppraisalrating();
        $performcelevels = $this->getPerformancelevels();
        if(is_array($result)){
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($result[0],$model) ;//$this->loadtomodeEmployee_Nol($result[0],$Expmodel);
        }else{
            Yii::$app->navhelper->printrr($result);
        }


        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Employeeappraisalkra'],$model) ){
            $result = Yii::$app->navhelper->updateData($service,$model);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if(!is_string($result)){

                return ['note' => '<div class="alert alert-success">Key Result Area Evaluated Successfully </div>' ];
            }else{

                return ['note' => '<div class="alert alert-danger">Error Evaluating Key Result Area : '.$result.'</div>'];
            }


        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('update', [
                'model' => $model,
                'ratings' => ArrayHelper::map($ratings,'Rating','Rating_Description'),
                'performancelevels' => ArrayHelper::map($performcelevels,'Line_Nos','Perfomace_Level'),
            ]);
        }

        return $this->render('update',[
            'model' => $model,
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

    public function actionView($Employee_No, $Appraisal_No){
        $service = Yii::$app->params['ServiceName']['ProbationCard'];
        $model = new Probation();

        $filter = [
            'Appraisal_No' => $Appraisal_No,
            'Employee_No' => $Employee_No,
        ];

        $appraisal = Yii::$app->navhelper->getData($service, $filter);
        if(is_array($appraisal)){
            $model = Yii::$app->navhelper->loadmodel($appraisal[0],$model);
        }

        //Yii::$app->recruitment->printrr($model->getObjectives());

        $appraisal_status = [
            '_blank_' => '_blank_',
            'Appraisee_Level' => 'Appraisee_Level',
            'Supervisor_Level' => 'Supervisor_Level',
            'HR_Level' => 'HR_Level',
            'Closed' => 'Closed'

        ];

        $action = [
            '_blank_' => '_blank_',
            'Confirmed' => 'Confirmed',
            'Probation_Extension' => 'Probation_Extension',
            'Terminated' => 'Terminated'
        ];

        return $this->render('view',[
            'model' => $model,
            'card' => $appraisal[0],
            'appraisal_status' => $appraisal_status,
            'action' => $action
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

    // Employee List

    public function actionGetprobations(){
        $service = Yii::$app->params['ServiceName']['ProbationList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee No_'},
        ];
        $appraisals = \Yii::$app->navhelper->getData($service,$filter);
        //ksort($appraisals);
        $result = [];

        if(is_array($appraisals)){
            foreach($appraisals as $req){

                $Viewlink = Html::a('View', ['view','Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No)?$req->Appraisal_No: ''], ['class' => 'btn btn-outline-primary btn-xs']);

                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ?$req->Appraisal_Period : '',
                    'Supervisor_User_Id' =>  !empty($req->Supervisor_User_Id) ? $req->Supervisor_User_Id : '',
                    'Employee_User_Id' =>  !empty($req->Employee_User_Id) ? $req->Employee_User_Id : '',
                    'Hr_UserId' =>  !empty($req->Hr_UserId) ? $req->Hr_UserId : '',
                    'Action' => !empty($Viewlink) ? $Viewlink : '',

                ];

            }
        }

        return $result;
    }


    // Supervisor List

    public function actionGetsupervisorprobations(){
        $service = Yii::$app->params['ServiceName']['ProbationSupervisorList'];
        $filter = [
            'Supervisor_User_Id' => Yii::$app->user->identity->getId(),
        ];
        $appraisals = \Yii::$app->navhelper->getData($service,$filter);
        //ksort($appraisals);
        $result = [];

        if(is_array($appraisals)){
            foreach($appraisals as $req){

                $Viewlink = Html::a('View', ['view','Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No)?$req->Appraisal_No: ''], ['class' => 'btn btn-outline-primary btn-xs']);

                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ?$req->Appraisal_Period : '',
                    'Supervisor_User_Id' =>  !empty($req->Supervisor_User_Id) ? $req->Supervisor_User_Id : '',
                    'Employee_User_Id' =>  !empty($req->Employee_User_Id) ? $req->Employee_User_Id : '',
                    'Hr_UserId' =>  !empty($req->Hr_UserId) ? $req->Hr_UserId : '',
                    'Action' => !empty($Viewlink) ? $Viewlink : '',

                ];

            }
        }

        return $result;
    }


    // HR List

    public function actionGethrprobations(){
        $service = Yii::$app->params['ServiceName']['Probationhrlist'];
        $filter = [
            'Hr_UserId' => Yii::$app->user->identity->getId(),
        ];
        $appraisals = \Yii::$app->navhelper->getData($service,$filter);
        //ksort($appraisals);
        $result = [];

        if(is_array($appraisals)){
            foreach($appraisals as $req){

                $Viewlink = Html::a('View', ['view','Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No)?$req->Appraisal_No: ''], ['class' => 'btn btn-outline-primary btn-xs']);

                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ?$req->Appraisal_Period : '',
                    'Supervisor_User_Id' =>  !empty($req->Supervisor_User_Id) ? $req->Supervisor_User_Id : '',
                    'Employee_User_Id' =>  !empty($req->Employee_User_Id) ? $req->Employee_User_Id : '',
                    'Hr_UserId' =>  !empty($req->Hr_UserId) ? $req->Hr_UserId : '',
                    'Action' => !empty($Viewlink) ? $Viewlink : '',

                ];

            }
        }

        return $result;
    }

    public function actionGetclosedprobations(){
        $service = Yii::$app->params['ServiceName']['closedprobationappraisals'];
        $filter = [
            //'Hr_UserId' => Yii::$app->user->identity->getId(),
        ];
        $appraisals = \Yii::$app->navhelper->getData($service,$filter);
        //ksort($appraisals);
        $result = [];

        if(is_array($appraisals)){
            foreach($appraisals as $req){

                $Viewlink = Html::a('View', ['view','Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No)?$req->Appraisal_No: ''], ['class' => 'btn btn-outline-primary btn-xs']);

                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ?$req->Appraisal_Period : '',
                    'Supervisor_User_Id' =>  !empty($req->Supervisor_User_Id) ? $req->Supervisor_User_Id : '',
                    'Employee_User_Id' =>  !empty($req->Employee_User_Id) ? $req->Employee_User_Id : '',
                    'Hr_UserId' =>  !empty($req->Hr_UserId) ? $req->Hr_UserId : '',
                    'Action' => !empty($Viewlink) ? $Viewlink : '',

                ];

            }
        }

        return $result;
    }

    /*public function actionReport(){
        $service = Yii::$app->params['ServiceName']['expApplicationList'];
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
            'No' => Yii::$app->user->identity->{'Employee No_'},
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
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Submitting Probation Appraisal : '. $result);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);

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
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Submitting Probation Appraisal to HR : '. $result);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);

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
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Approving Probation Appraisal  : '. $result);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);

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
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Sending Probation Back to Supervisor  : '. $result);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);

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
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Sending Probation Back to Appraisee  : '. $result);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);

        }

    }

    // Take Action

     public function actionTakeaction()
    {
        $service = Yii::$app->params['ServiceName']['ProbationCard'];
        $data = [
            'Appraisal_No' => Yii::$app->request->get('Appraisal_No'),
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

}