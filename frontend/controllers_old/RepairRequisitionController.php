<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:21 PM
 */

namespace frontend\controllers;
use frontend\models\Repairrequisition;
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

class RepairRequisitionController extends Controller
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
                'only' => ['list','status-monitoringlist'],
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

    public function actionMonitoring(){

        return $this->render('statusmonitoring');

    }


    public function actionCreate(){

        $model = new Repairrequisition();
        $service = Yii::$app->params['ServiceName']['RepairRequisitionDocument'];

        /*Do initial request */
        if(!isset(Yii::$app->request->post()['Repairrequisition'])){
            $model->Employee_No = Yii::$app->user->identity->{'Employee_No'};
            $request = Yii::$app->navhelper->postData($service, $model);
            if(!is_string($request) )
            {
                Yii::$app->navhelper->loadmodel($request,$model);
                $model->Service_Date = date('Y-m-d');
            }else{
                // Yii::$app->recruitment->printrr($request);
                Yii::$app->session->setFlash('error',$request);
                 return $this->render('create',[
                    'model' => $model,
                    'vehicles' => $this->getVehicles(),
                ]);
            }
        }

        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Repairrequisition'],$model) ){


            $result = Yii::$app->navhelper->updateData($service,$model);
            if(!is_string($result)){

                Yii::$app->session->setFlash('success','Request Created Successfully.' );
                return $this->redirect(['view','No' => $result->Repair_Requisition_No]);

            }else{
                Yii::$app->session->setFlash('error','Error Creating Request '.$result );
                return $this->redirect(['index']);

            }

        }


        //Yii::$app->recruitment->printrr($model);

        return $this->render('create',[
            'model' => $model,
            'vehicles' => $this->getVehicles(),
        ]);
    }




    public function actionUpdate($No){
        $model = new Repairrequisition();
        $service = Yii::$app->params['ServiceName']['RepairRequisitionDocument'];
        $model->isNewRecord = false;

        $filter = [
            'Repair_Requisition_No' => $No,
        ];
        $result = Yii::$app->navhelper->getData($service,$filter);

        if(is_array($result)){
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($result[0],$model) ;
        }else{
            Yii::$app->session->setFlash('error',$request);
            return $this->render('create',[
                'model' => $model,
                'vehicles' => $this->getVehicles(),
            ]);
        }


        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Repairrequisition'],$model) ){

            $result = Yii::$app->navhelper->updateData($service,$model);

            if(!is_string($result)){
                Yii::$app->session->setFlash('success','Document Updated Successfully.' );
                return $this->redirect(['view','No' => $result->Repair_Requisition_No]);

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
                'vehicles' => $this->getVehicles()


            ]);
        }

        return $this->render('update',[
            'model' => $model,
            'vehicles' => $this->getVehicles()

        ]);
    }

    public function actionDelete(){
        $service = Yii::$app->params['ServiceName']['RepairRequisitionDocument'];
        $result = Yii::$app->navhelper->deleteData($service,Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!is_string($result)){

            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        }else{
            return ['note' => '<div class="alert alert-danger">Error Purging Record: '.$result.'</div>' ];
        }
    }

    public function actionView($No){
        $model = new Repairrequisition();
        $service = Yii::$app->params['ServiceName']['RepairRequisitionDocument'];

        $filter = [
            'Repair_Requisition_No' => $No
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
        $service = Yii::$app->params['ServiceName']['RepairRequisitionList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->Employee_No,
        ];

        $results = \Yii::$app->navhelper->getData($service,$filter);

        $result = []; $i = 0;
        if(is_array($results))
        {
            foreach($results as $k => $item){
                ++$i;
                if(!empty($item->Repair_Requisition_No ))
                {
                    $link = $updateLink = $deleteLink =  '';
                    $Viewlink = Html::a('<i class="fas fa-eye"></i>',['view','No'=> $item->Repair_Requisition_No ],['class'=>'btn btn-outline-primary btn-xs','title' => 'View Request.' ]);
                    if($item->Requisition_Status == 'New'){
                        $link = Html::a('<i class="fas fa-paper-plane"></i>',['send-for-approval','Repair_Requisition_No'=> $item->Repair_Requisition_No ],['title'=>'Send Approval Request','class'=>'btn btn-primary btn-xs']);
                        $updateLink = Html::a('<i class="far fa-edit"></i>',['update','No'=> $item->Repair_Requisition_No ],['class'=>'btn btn-info btn-xs','title' => 'Update Request']);
                    }else if($item->Requisition_Status == 'Pending_Approval'){
                        $link = Html::a('<i class="fas fa-times"></i>',['cancel-request','No'=> $item->Repair_Requisition_No ],['title'=>'Cancel Approval Request','class'=>'btn btn-warning btn-xs']);
                    }

                    $result['data'][] = [
                        'Key' => $item->Key,
                        'No' => $item->Repair_Requisition_No,
                        'Vehicle_Registration_No' => !empty($item->Vehicle_Registration_No)?$item->Vehicle_Registration_No:'',
                        'Vehicle_Frame_No' => !empty($item->Vehicle_Frame_No)?$item->Vehicle_Frame_No:'',
                        'Vehicle_Model' => !empty($item->Vehicle_Model)?$item->Vehicle_Model:'',
                        'Requisition_Date' => !empty($item->Requisition_Date)?$item->Requisition_Date:'',
                        'Reason_Code' => !empty($item->Reason_Code)?$item->Reason_Code:'',
                        'Vehicle_Repair_Status' => !empty($item->Vehicle_Repair_Status)?$item->Vehicle_Repair_Status:'',
                        'Total_Cost' => !empty($item->Total_Cost)?$item->Total_Cost:'',
                        'Status' => $item->Requisition_Status,
                        'Action' => $link.' '. $updateLink.' '.$Viewlink ,

                    ];
                }

            }
        }


        return $result;
    }

/*Monitoring Status List*/

    public function actionStatusMonitoringlist(){
        $service = Yii::$app->params['ServiceName']['RepairsStatusMonitoring'];
        $filter = [
            // 'Employee_No' => Yii::$app->user->identity->Employee_No,
        ];

        $results = \Yii::$app->navhelper->getData($service,$filter);

        $result = []; $i = 0;
        if(is_array($results))
        {
            foreach($results as $k => $item){
                ++$i;
                if(!empty($item->Repair_Requisition_No ))
                {
                    $link = $updateLink = $deleteLink =  '';
                    $Viewlink = Html::a('<i class="fas fa-eye"></i>',['view','No'=> $item->Repair_Requisition_No ],['class'=>'btn btn-outline-primary btn-xs','title' => 'View Request.' ]);


                    $result['data'][] = [
                        'Key' => $item->Key,
                        'No' => $item->Repair_Requisition_No,
                        'Vehicle_Registration_No' => !empty($item->Vehicle_Registration_No)?$item->Vehicle_Registration_No:'',
                        'Requisition_Date' => !empty($item->Requisition_Date)?$item->Requisition_Date:'',
                        'Service_Date' => !empty($item->Service_Date)?$item->Service_Date:'',
                        'Reason_Code' => !empty($item->Reason_Code)?$item->Reason_Code:'',
                        'Requisition_Status' => !empty($item->Requisition_Status)?$item->Requisition_Status:'',
                        'Vehicle_Repair_Status' => !empty($item->Vehicle_Repair_Status)?$item->Vehicle_Repair_Status:'',
                        'Total_Cost' => !empty($item->Total_Cost)?$item->Total_Cost:'',
                        'Action' => $link.' '. $updateLink.' '.$Viewlink ,

                    ];
                }

            }
        }


        return $result;
    }


    /*Get Vehicles */
    public function getVehicles(){
        $service = Yii::$app->params['ServiceName']['AvailableVehicleLookUp'];

        $result = \Yii::$app->navhelper->getData($service, []);
        return Yii::$app->navhelper->refactorArray($result,'Vehicle_Registration_No','Make_Model');

    }

    /*Set Vehicle Registration No.*/

    public function actionSetvregno(){
        $model = new Repairrequisition();
        $service = Yii::$app->params['ServiceName']['RepairRequisitionDocument'];

        $filter = [
            'Repair_Requisition_No' => Yii::$app->request->post('Repair_Requisition_No')
        ];
        $line = Yii::$app->navhelper->getData($service, $filter);

        if(is_array($line)){
            Yii::$app->navhelper->loadmodel($line[0],$model);
            $model->Vehicle_Registration_No = Yii::$app->request->post('Vehicle_Registration_No');
        }

        $result = Yii::$app->navhelper->updateData($service,$model);
        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;
        return $result;
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