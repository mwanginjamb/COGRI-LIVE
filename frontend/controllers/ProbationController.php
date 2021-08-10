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
                'only' => ['logout', 'signup','index','create','update','delete','view'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout','index','create','update','delete','view'],
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
                'only' => [
                    'getprobations',
                    'getLinemanagerobjlist',
                    'getoverviewmgrobjlist',
                    'appraiseeapprovedgoals',
                    'supervisorprobationlist',
                    'overviewprobationlist',
                    'getagreementlist',
                    'closedappraisallist',
                    'get-linemanagerobjlist'

                ],
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

    public function actionSuperglist(){

        return $this->render('superglist');

    }

    public function actionOvglist(){


        return $this->render('ovglist');

    }

    public function actionApprovedglist(){


        return $this->render('approvedglist');

    }

     public function actionSuperproblist(){


        return $this->render('superproblist');

    }

    public function actionOvproblist(){


        return $this->render('ovproblist');

    }

    public function actionAgreementlist(){


        return $this->render('agreementlist');

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
                Yii::$app->session->setFlash('success','Appraisal Initiated successfully.',true);
                return $this->redirect(['view','Employee_No' => $result->Employee_No, 'Appraisal_No' => $result->Appraisal_No]);

            }else{
                Yii::$app->session->setFlash('error','Error Creating Appraisal: '.$result,true);
               return $this->redirect(['index']);

            }

      
    }


    public function actionUpdate(){
        $model = new Employeeappraisalkra();
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
            'Extend_Probation_Period' => 'Extend_Probation_Period',
            'Terminate_Employee' => 'Terminate_Employee'
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
        $service = Yii::$app->params['ServiceName']['ObjectiveSettingList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee_No'},
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
                    'Appraisal_Start_Date' =>  !empty($req->Appraisal_Start_Date) ?$req->Appraisal_Start_Date : '',
                    'Appraisal_End_Date' =>  !empty($req->Appraisal_End_Date) ?$req->Appraisal_End_Date : '',
                    
                    'Action' => !empty($Viewlink) ? $Viewlink : '',

                ];

            }
        }

        return $result;
    }


    // Supervisor Objectives List

    public function actionGetLinemanagerobjlist(){
        $service = Yii::$app->params['ServiceName']['LnManagerObjList'];
        $filter = [
            'Supervisor_No' => Yii::$app->user->identity->{'Employee_No'},
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
                    'Appraisal_Start_Date' =>  !empty($req->Appraisal_Start_Date) ?$req->Appraisal_Start_Date : '',
                    'Appraisal_End_Date' =>  !empty($req->Appraisal_End_Date) ?$req->Appraisal_End_Date : '',
                    
                    'Action' => !empty($Viewlink) ? $Viewlink : '',

                ];

            }
        }

        return $result;
    }


    // Overview Mgr Objectives List

    public function actionGetoverviewmgrobjlist(){
        $service = Yii::$app->params['ServiceName']['ProbationOverviewObjList'];
        $filter = [
            'Overview_Manager' => Yii::$app->user->identity->{'Employee_No'},
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
                    'Appraisal_Start_Date' =>  !empty($req->Appraisal_Start_Date) ?$req->Appraisal_Start_Date : '',
                    'Appraisal_End_Date' =>  !empty($req->Appraisal_End_Date) ?$req->Appraisal_End_Date : '',
                    
                    'Action' => !empty($Viewlink) ? $Viewlink : '',

                ];

            }
        }

        return $result;
    }

    public function actionAppraiseeapprovedgoals(){
        $service = Yii::$app->params['ServiceName']['ProbationAppraiseeList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee_No'},
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
                    'Appraisal_Start_Date' =>  !empty($req->Appraisal_Start_Date) ?$req->Appraisal_Start_Date : '',
                    'Appraisal_End_Date' =>  !empty($req->Appraisal_End_Date) ?$req->Appraisal_End_Date : '',
                    
                    'Action' => !empty($Viewlink) ? $Viewlink : '',

                ];

            }
        }

        return $result;
    }

    public function actionSupervisorprobationlist(){
        $service = Yii::$app->params['ServiceName']['ProbationLnmanagerList'];
        $filter = [
            'Supervisor_No' => Yii::$app->user->identity->{'Employee_No'},
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
                    'Appraisal_Start_Date' =>  !empty($req->Appraisal_Start_Date) ?$req->Appraisal_Start_Date : '',
                    'Appraisal_End_Date' =>  !empty($req->Appraisal_End_Date) ?$req->Appraisal_End_Date : '',
                    
                    'Action' => !empty($Viewlink) ? $Viewlink : '',

                ];

            }
        }

        return $result;
    }


    public function actionOverviewprobationlist(){
        $service = Yii::$app->params['ServiceName']['OverviewSupervisorList'];
        $filter = [
            'Overview_Manager' => Yii::$app->user->identity->{'Employee_No'},
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
                    'Appraisal_Start_Date' =>  !empty($req->Appraisal_Start_Date) ?$req->Appraisal_Start_Date : '',
                    'Appraisal_End_Date' =>  !empty($req->Appraisal_End_Date) ?$req->Appraisal_End_Date : '',
                    
                    'Action' => !empty($Viewlink) ? $Viewlink : '',

                ];

            }
        }

        return $result;
    }

    public function actionGetagreementlist(){
        $service = Yii::$app->params['ServiceName']['ProbationAgreementList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee_No'},
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
                    'Appraisal_Start_Date' =>  !empty($req->Appraisal_Start_Date) ?$req->Appraisal_Start_Date : '',
                    'Appraisal_End_Date' =>  !empty($req->Appraisal_End_Date) ?$req->Appraisal_End_Date : '',
                    
                    'Action' => !empty($Viewlink) ? $Viewlink : '',

                ];

            }
        }

        return $result;
    }


    public function actionClosedappraisallist(){
        $service = Yii::$app->params['ServiceName']['ClosedProbationAppraisal'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee_No'},
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
                    'Appraisal_Start_Date' =>  !empty($req->Appraisal_Start_Date) ?$req->Appraisal_Start_Date : '',
                    'Appraisal_End_Date' =>  !empty($req->Appraisal_End_Date) ?$req->Appraisal_End_Date : '',
                    
                    'Action' => !empty($Viewlink) ? $Viewlink : '',

                ];

            }
        }

        return $result;
    }

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
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['probation/view', 'Appraisal_No' =>$appraisalNo, 'Employee_No' =>$employeeNo ])
        ];

        $result = Yii::$app->navhelper->CodeUnit($service,$data,'IanSendGoalSettingForApproval');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Appraisal Submitted Successfully.', true);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Submitting Appraisal : '. $result);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);

        }

    }




    /*Send to Agreement*/

    public function actionAgreementlevel($appraisalNo,$employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalWorkflow'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['probation/superproblist'])
        ];

        $result = Yii::$app->navhelper->CodeUnit($service,$data,'IanSendEYAppraisalToAgreementLevel');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Appraisal Sent to Agreement Successfully.', true);
            return $this->redirect(['superproblist']);
        }else{

            Yii::$app->session->setFlash('error', 'Error Sending to Agreement : '. $result);
            return $this->redirect(['superproblist']);

        }

    }


    
// Send EY Back to Line Manager  --From Agreement back to super  

/*public function actionEybacktolinemgr()
{
    $service = Yii::$app->params['ServiceName']['AppraisalWorkflow'];
    $appraisalNo = Yii::$app->request->post('Appraisal_No');
    $employeeNo = Yii::$app->request->post('Employee_No');
    $data = [
        'appraisalNo' => $appraisalNo,
        'employeeNo' => $employeeNo,
        'sendEmail' => 1,
        'approvalURL' => '',// Yii::$app->urlManager->createAbsoluteUrl(['probation/view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]),
        'rejectionComments' => '',
    ];

    

    $result = Yii::$app->navhelper->CodeUnit($service,$data,'IanSendEYAppraisaBackToLineManagerFromAgreement');
    // Yii::$app->recruitment->printrr($result);

    if(!is_string($result)){
        Yii::$app->session->setFlash('success', 'Sent Back to Line Manager Successfully.', true);
        return $this->redirect(['agreementlist']);
    }else{

        Yii::$app->session->setFlash('error', 'Error  : '. $result);
        return $this->redirect(['agreementlist']);

    }

}

*/

public function actionEybacktolinemgr()
    {
        $service = Yii::$app->params['ServiceName']['AppraisalWorkflow'];
        $appraisalNo = Yii::$app->request->get('appraisalNo');
        $employeeNo = Yii::$app->request->get('employeeNo');
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['probation/view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]),
            'rejectionComments' => ''
        ];

        $result = Yii::$app->navhelper->CodeUnit($service,$data,'IanSendEYAppraisaBackToLineManagerFromAgreement');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Appraisal Submitted Successfully.', true);
            return $this->redirect(['agreementlist']);
        }else{

            Yii::$app->session->setFlash('error', 'Error Submitting Appraisal : '. $result);
            return $this->redirect(['agreementlist',]);

        }

    }

    public function actionSubmittooverview($appraisalNo,$employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalWorkflow'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['probation/view', 'Appraisal_No' =>$appraisalNo, 'Employee_No' =>$employeeNo ])
        ];

        $result = Yii::$app->navhelper->CodeUnit($service,$data,'IanSendGoalSettingToOverview');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Appraisal Submitted to Overview Manager Successfully.', true);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Submitting Appraisal to Overview Manager : '. $result);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);

        }

    }





    public function actionApprovegoals($appraisalNo,$employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalWorkflow'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            
        ];

        $result = Yii::$app->navhelper->CodeUnit($service,$data,'IanApproveGoalSetting');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Appraisal Goals Approved Successfully.', true);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error   : '. $result);
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
            Yii::$app->session->setFlash('success', 'Appraisal Sent Back to Supervisor Successfully.', true);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Sending Back to Supervisor  : '. $result);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);

        }

    }

    public function actionBacktoemp()
    {
        $service = Yii::$app->params['ServiceName']['AppraisalWorkflow'];
        $appraisalNo = Yii::$app->request->post('Appraisal_No');
        $employeeNo = Yii::$app->request->post('Employee_No');
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['probation/view', 'Appraisal_No' =>$appraisalNo, 'Employee_No' =>$employeeNo ]),
            'rejectionComments' => Yii::$app->request->post('comment'),
        ];

        $result = Yii::$app->navhelper->CodeUnit($service,$data,'IanSendGoalSettingBackToAppraisee');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', ' Appraisal Sent Back to Appraisee Successfully.', true);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Sending Back to Appraisee  : '. $result);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);

        }

    }


    /*Submit Back to Line Mgr*/

    public function actionBacktolinemgr()
    {
        $service = Yii::$app->params['ServiceName']['AppraisalWorkflow'];
        $appraisalNo = Yii::$app->request->post('Appraisal_No');
        $employeeNo = Yii::$app->request->post('Employee_No');
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['probation/view', 'Appraisal_No' =>$appraisalNo, 'Employee_No' =>$employeeNo ]),
            'rejectionComments' => Yii::$app->request->post('comment'),
        ];

        $result = Yii::$app->navhelper->CodeUnit($service,$data,'IanSendGoalSettingBackToLineManager');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Sent Back Line Manager with comments Successfully.', true);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Line Manager : '. $result);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);

        }

    }

    // Submit Probation to Line Mgr

    public function actionSubmitprobationtolinemgr($appraisalNo,$employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalWorkflow'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['probation/view', 'Appraisal_No' =>$appraisalNo, 'Employee_No' =>$employeeNo ])
        ];

        $result = Yii::$app->navhelper->CodeUnit($service,$data,'IanSendEYAppraisalForApproval');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Appraisal Submitted Successfully.', true);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Submitting Appraisal : '. $result);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);

        }

    }

    // Reject Probation and send it back to appraisee

    public function actionProbationbacktoappraisee()
    {
        $service = Yii::$app->params['ServiceName']['AppraisalWorkflow'];
        $appraisalNo = Yii::$app->request->post('Appraisal_No');
        $employeeNo = Yii::$app->request->post('Employee_No');
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['probation/view', 'Appraisal_No' =>$appraisalNo, 'Employee_No' =>$employeeNo ]),
            'rejectionComments' => Yii::$app->request->post('comment'),
        ];

        $result = Yii::$app->navhelper->CodeUnit($service,$data,'IanSendEYAppraisaBackToAppraisee');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Sent Back to Appraisee with Comments Successfully.', true);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error  : '. $result);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);

        }

    }


    
// Overview Manager Sending Probation Appraisal Back to Line Mgr

     public function actionOverviewbacktolinemgr()
    {
        $service = Yii::$app->params['ServiceName']['AppraisalWorkflow'];
        $appraisalNo = Yii::$app->request->post('Appraisal_No');
        $employeeNo = Yii::$app->request->post('Employee_No');
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['probation/view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]),
            'rejectionComments' => Yii::$app->request->post('comment'),
        ];

        $result = Yii::$app->navhelper->CodeUnit($service,$data,'IanSendEYAppraisaBackToLineManager');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Sent Back to Line Manager with Comments Successfully.', true);
            return $this->redirect(['ovproblist']);
        }else{

            Yii::$app->session->setFlash('error', 'Error  : '. $result);
            return $this->redirect(['ovproblist']);

        }

    }



// Submit Appraisal to Overview
    public function actionSubmitprobationtooverview($appraisalNo,$employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalWorkflow'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['probation/view', 'Appraisal_No' =>$appraisalNo, 'Employee_No' =>$employeeNo ])
        ];

        $result = Yii::$app->navhelper->CodeUnit($service,$data,'IanSendEYAppraisalToOverview');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', ' Appraisal Submitted Successfully.', true);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Submitting Appraisal : '. $result);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);

        }

    }

    public function actionAgreementoverview($appraisalNo,$employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalWorkflow'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['probation/view', 'Appraisal_No' =>$appraisalNo, 'Employee_No' =>$employeeNo ])
        ];

        $result = Yii::$app->navhelper->CodeUnit($service,$data,'IanSendEYAppraisalToAgreementLevel');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', ' Appraisal Submitted Successfully.', true);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error : '. $result);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);

        }

    }


    /*Approve Probation by Overview*/

     public function actionApproveprobationoverview($appraisalNo,$employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalWorkflow'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['probation/view', 'Appraisal_No' =>$appraisalNo, 'Employee_No' =>$employeeNo ])
        ];

        $result = Yii::$app->navhelper->CodeUnit($service,$data,'IanApproveEYAppraisal');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', ' Approved Successfully.', true);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);
        }else{

            Yii::$app->session->setFlash('error', 'Error : '. $result);
            return $this->redirect(['view','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);

        }

    }





    // Take Recommended Action.

     public function actionSetaction()
    {
        $model = new Probation();
         
        $service = Yii::$app->params['ServiceName']['ProbationCard'];

        $filter = [
            'Appraisal_No' => Yii::$app->request->post('Appraisal_No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if(is_array($request)){
            Yii::$app->navhelper->loadmodel($request[0],$model);
            $model->Key = $request[0]->Key;
            $model->Probation_Recomended_Action = Yii::$app->request->post('Probation_Recomended_Action');
        }


        $result = Yii::$app->navhelper->updateData($service,$model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;

    }

    // Set Overview Comments

     public function actionSetOverviewComment()
    {
        $model = new Probation();
         
        $service = Yii::$app->params['ServiceName']['ProbationCard'];

        $filter = [
            'Appraisal_No' => Yii::$app->request->post('Appraisal_No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if(is_array($request)){
            Yii::$app->navhelper->loadmodel($request[0],$model);
            $model->Key = $request[0]->Key;
            $model->Over_View_Manager_Comments = Yii::$app->request->post('Over_View_Manager_Comments');
        }


        $result = Yii::$app->navhelper->updateData($service,$model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;

    }



    public function actionReport(){

        $service = Yii::$app->params['ServiceName']['PortalReports'];

        if(Yii::$app->request->post()){

            $data = [
                'appraisalNo' =>Yii::$app->request->post('appraisalNo'),
                'employeeNo' => Yii::$app->request->post('employeeNo')
            ];
            $path = Yii::$app->navhelper->CodeUnit($service,$data,'IanGenerateNewEmployeeAppraisalReport');
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