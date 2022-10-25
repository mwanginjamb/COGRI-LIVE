<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:21 PM
 */

namespace frontend\controllers;
use frontend\models\DrugIssuance;
use Yii;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use kartik\mpdf\Pdf;

class DrugIssuanceController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout','signup','index','list','create','update','delete','view'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout','index','list','create','update','delete','view'],
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
                'only' => ['list','issued-list'],
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

    public function actionIssued()
    {
        return $this->render('issuedlist');
    }


    public function actionCreate(){

        $model = new DrugIssuance();
        $service = Yii::$app->params['ServiceName']['PrescriptionIssueCard'];

        /*Do initial request */
        if(!isset(Yii::$app->request->post()['DrugIssuance'])){
            $model->Employee_No = Yii::$app->user->identity->{'Employee_No'};
            $request = Yii::$app->navhelper->postData($service, $model);
            if(!is_string($request) )
            {
                Yii::$app->navhelper->loadmodel($request,$model);
            }else{
                Yii::$app->session->setFlash('error',$request);
                return $this->render('create',[
                    'model' => $model,
                    'programs' => $this->getPrograms(),
                    'departments' => $this->getDepartments(),
                    'students' => $this->getStudents(),


                ]);
            }
        }

        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['DrugIssuance'],$model) ){


            $result = Yii::$app->navhelper->updateData($service,$model);
            if(!is_string($result)){

                Yii::$app->session->setFlash('success','Request Created Successfully.' );
                return $this->redirect(['view','No' => $result->No]);

            }else{
                Yii::$app->session->setFlash('error','Error Creating Request '.$result );
                return $this->redirect(['index']);

            }

        }


        //Yii::$app->recruitment->printrr($model);

        return $this->render('create',[
            'model' => $model,
            'programs' => $this->getPrograms(),
            'departments' => $this->getDepartments(),
            'students' => $this->getStudents(),
        ]);
    }




    public function actionUpdate($No){
        $model = new DrugIssuance();
        $service = Yii::$app->params['ServiceName']['PrescriptionIssueCard'];
        $model->isNewRecord = false;

        $filter = [
            'No' => $No,
        ];
        $result = Yii::$app->navhelper->getData($service,$filter);

        if(is_array($result)){
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($result[0],$model) ;
        }else{
            Yii::$app->session->setFlash('error',$request);
            return $this->render('create',[
                'model' => $model,
                'programs' => $this->getPrograms(),
                'departments' => $this->getDepartments(),
                'students' => $this->getStudents(),
            ]);
        }


        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['DrugIssuance'],$model) ){

            $result = Yii::$app->navhelper->updateData($service,$model);

            if(!is_string($result)){
                Yii::$app->session->setFlash('success','Document Updated Successfully.' );
                return $this->redirect(['view','No' => $result->No]);

            }else{
                Yii::$app->session->setFlash('success','Error Updating Document'.$result );
                return $this->render('update',[
                    'model' => $model,
                ]);

            }

        }


        // Yii::$app->recruitment->printrr($model);
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('update', [
                'model' => $model,
                'programs' => $this->getPrograms(),
                'departments' => $this->getDepartments(),
                'students' => $this->getStudents(),


            ]);
        }

        return $this->render('update',[
            'model' => $model,
            'programs' => $this->getPrograms(),
            'departments' => $this->getDepartments(),
            'students' => $this->getStudents(),

        ]);
    }

    public function actionDelete(){
        $service = Yii::$app->params['ServiceName']['PrescriptionIssueCard'];
        $result = Yii::$app->navhelper->deleteData($service,Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!is_string($result)){

            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        }else{
            return ['note' => '<div class="alert alert-danger">Error Purging Record: '.$result.'</div>' ];
        }
    }

    public function actionView($No){
        $model = new DrugIssuance();
        $service = Yii::$app->params['ServiceName']['PrescriptionIssueCard'];

        $filter = [
            'No' => $No
        ];

        $result = Yii::$app->navhelper->getData($service, $filter);

        //load nav result to model
        $model = Yii::$app->navhelper->loadmodel($result[0],$model) ;

        //Yii::$app->recruitment->printrr($model);

        return $this->render('view',[
            'model' => $model,
        ]);
    }

   // Get list

    public function actionList(){
        $service = Yii::$app->params['ServiceName']['prescriptionsIssueList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->Employee_No,
        ];

        $results = \Yii::$app->navhelper->getData($service,$filter);
        $result = [];
        foreach($results as $item){

            if(!empty($item->No ))
            {
                $link = $updateLink = $deleteLink =  '';
                $Viewlink = Html::a('<i class="fas fa-eye"></i>',['view','No'=> $item->No ],['class'=>'btn btn-outline-primary btn-xs','title' => 'View Request.' ]);
                if($item->Status == 'New'){
                    $link = Html::a('<i class="fas fa-paper-plane"></i>',['send-for-approval','No'=> $item->No ],['title'=>'Send Approval Request','class'=>'btn btn-primary btn-xs']);
                    $updateLink = Html::a('<i class="far fa-edit"></i>',['update','No'=> $item->No ],['class'=>'btn btn-info btn-xs','title' => 'Update Request']);
                }else if($item->Status == 'Pending_Approval'){
                    $link = Html::a('<i class="fas fa-times"></i>',['cancel-request','No'=> $item->No ],['title'=>'Cancel Approval Request','class'=>'btn btn-warning btn-xs']);
                }

                $result['data'][] = [
                    'Key' => $item->Key,
                    'No' => $item->No,
                    'Employee_No' => !empty($item->Employee_No)?$item->Employee_No:'',
                    'Employee_Name' => !empty($item->Employee_Name)?$item->Employee_Name:'',
                    'Patient_Name' => !empty($item->Patient_Name)?$item->Patient_Name:'',
                    'Patient_Card_No' => !empty($item->Patient_Card_No)?$item->Patient_Card_No:'',
                    'Patient_Diagnosis' => !empty($item->Patient_Diagnosis)?$item->Patient_Diagnosis:'',

                    'Global_Dimension_3_Code' => !empty($item->Global_Dimension_3_Code)?$item->Global_Dimension_3_Code:'',
                    'Issued' => Html::checkbox('Posted',$item->Posted),
                    'Status' => $item->Status,
                    'Action' => $link.' '. $updateLink.' '.$Viewlink ,

                ];
            }

        }

        return $result;
    }


    /*List of Issues Prescriptions */

    public function actionIssuedList(){
        $service = Yii::$app->params['ServiceName']['IssuedPrescriptionsList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->Employee_No,
        ];

        $results = \Yii::$app->navhelper->getData($service,$filter);
        $result = [];
        foreach($results as $item){

            if(!empty($item->No ))
            {

                $Viewlink = Html::a('<i class="fas fa-eye"></i>',['view','No'=> $item->No ],['class'=>'btn btn-outline-primary btn-xs','title' => 'View Request.' ]);
                $result['data'][] = [
                    'Key' => $item->Key,
                    'No' => $item->No,
                    'Employee_No' => !empty($item->Employee_No)?$item->Employee_No:'',
                    'Employee_Name' => !empty($item->Employee_Name)?$item->Employee_Name:'',
                    'Patient_Name' => !empty($item->Patient_Name)?$item->Patient_Name:'',
                    'Patient_Card_No' => !empty($item->Patient_Card_No)?$item->Patient_Card_No:'',
                    'Patient_Diagnosis' => !empty($item->Patient_Diagnosis)?$item->Patient_Diagnosis:'',
                    'Global_Dimension_3_Code' => !empty($item->Global_Dimension_3_Code)?$item->Global_Dimension_3_Code:'',
                    'Issued' => Html::checkbox('Posted',$item->Posted),
                    'Action' => $Viewlink
                ];
            }

        }

        return $result;
    }


    /*Get Programs */

    public function getPrograms(){
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 1
        ];

        $result = \Yii::$app->navhelper->getData($service, $filter);
        return Yii::$app->navhelper->refactorArray($result,'Code','Name');

    }

    /* Get Department*/

    public function getDepartments(){
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 2
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        return Yii::$app->navhelper->refactorArray($result,'Code','Name');
    }

    /* Get Dimension 3*/

    public function getStudents(){
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 3
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);

        return Yii::$app->navhelper->refactorArray($result,'Code','Name');
    }





    public function getEmployees(){
        $service = Yii::$app->params['ServiceName']['Employees'];

        $employees = \Yii::$app->navhelper->getData($service);
        $data = [];
        $i = 0;
        if(is_array($employees)){

            foreach($employees as  $emp){
                $i++;
                if(!empty($emp->Full_Name) && !empty($emp->No)){
                    $data[$i] = [
                        'No' => $emp->No,
                        'Full_Name' => $emp->Full_Name
                    ];
                }

            }



        }

        return $data;
    }









    /* Call Approval Workflow Methods */

    public function actionSendForApproval()
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];

        $data = [
            'applicationNo' => Yii::$app->request->get('Booking_Requisition_No'),
            'sendMail' => true,
            'approvalUrl' => '',
        ];


        $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanSendLeavePlanForApproval');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Request Sent to Supervisor Successfully.', true);
            return $this->redirect(['view','Plan_No' => $Plan_No]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Sending  Request for Approval  : '. $result);
            return $this->redirect(['view','Plan_No' => $Plan_No]);

        }
    }

    /*Cancel Approval Request */

    public function actionCancelRequest($Plan_No)
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];

        $data = [
            'applicationPlan_No' => $Plan_No,
        ];


        $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanCancelLeavePlanApprovalRequest');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Request Cancelled Successfully.', true);
            return $this->redirect(['view','Plan_No' => $Plan_No]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Cancelling Approval Request.  : '. $result);
            return $this->redirect(['view','Plan_No' => $Plan_No]);

        }
    }



}