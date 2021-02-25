<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:21 PM
 */

namespace frontend\controllers;
use frontend\models\Careerdevelopmentstrength;
use frontend\models\Employeeappraisalkra;
use frontend\models\Experience;
use frontend\models\Fuel;
use frontend\models\Imprestcard;
use frontend\models\Imprestline;
use frontend\models\Imprestsurrendercard;
use frontend\models\Leaveplan;
use frontend\models\Leaveplancard;
use frontend\models\Salaryadvance;
use frontend\models\Trainingplan;
use frontend\models\Vehiclerequisition;
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

class FuelController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout','signup','index','advance-list','create','update','delete','view'],
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
                'only' => ['list'],
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


    public function actionCreate(){

        $model = new Fuel();
        $service = Yii::$app->params['ServiceName']['FuelingDocumentPortal'];

        /*Do initial request */
        if(!isset(Yii::$app->request->post()['Fuel'])){
            $model->Driver_Staff_No = Yii::$app->user->identity->{'Employee_No'};
            $request = Yii::$app->navhelper->postData($service,$model);
            if(is_object($request) )
            {
                Yii::$app->navhelper->loadmodel($request,$model);
            }else{
                Yii::$app->session->setFlash('error',$request);
                return $this->render('create',[
                    'model' => $model,
                    'vehicles' => $this->getVehicles(),
                    'employees' => $this->getEmployees()
                ]);
            }
        }

        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Fuel'],$model) ){

            $filter = [
                'Fuel_Code' => $model->Fuel_Code,
            ];
            /*Read the card again to refresh Key in case it changed*/
            $refresh = Yii::$app->navhelper->getData($service,$filter);
            Yii::$app->navhelper->loadmodel($refresh[0],$model);
            $result = Yii::$app->navhelper->updateData($service,$model);
            if(!is_string($result)){
                Yii::$app->session->setFlash('success','Request Created Successfully.' );
                return $this->redirect(['view','No' => $result->Fuel_Code]);
            }else{
                Yii::$app->session->setFlash('error','Error Creating Request '.$result );
                return $this->redirect(['index']);
            }

        }

        return $this->render('create',[
            'model' => $model,
            'vehicles' => $this->getVehicles(),
            'employees' => $this->getEmployees()
        ]);
    }




    public function actionUpdate($No){
        $model = new Fuel();
        $service = Yii::$app->params['ServiceName']['FuelingDocumentPortal'];
        $model->isNewRecord = false;

        $filter = [
            'Fuel_Code' => $No,
        ];
        $result = Yii::$app->navhelper->getData($service,$filter);
        //Yii::$app->recruitment->printrr($result);
        if(is_array($result)){
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($result[0],$model) ;//$this->loadtomodeEmployee_Plan_Nol($result[0],$Expmodel);
        }else{
            Yii::$app->recruitment->printrr($result);
        }


        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Fuel'],$model) ){
            $filter = [
                'Fuel_Code' => $model->Fuel_Code,
            ];
            /*Read the card again to refresh Key in case it changed*/
            $refresh = Yii::$app->navhelper->getData($service,$filter);
            Yii::$app->navhelper->loadmodel($refresh[0],$model);

            $result = Yii::$app->navhelper->updateData($service,$model);

            if(!is_string($result)){

                Yii::$app->session->setFlash('success','Fuel Document Updated Successfully.' );

                return $this->redirect(['view','No' => $result->Fuel_Code]);

            }else{
                Yii::$app->session->setFlash('error','Error Updating Fuel Document '.$result );
                return $this->redirect(['update','No' => $result->Fuel_Code]);

            }

        }


        // Yii::$app->recruitment->printrr($model);
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('update', [
                'model' => $model,
                'vehicles' => $this->getVehicles(),
                'employees' => $this->getEmployees()
            ]);
        }

        return $this->render('update',[
            'model' => $model,
            'vehicles' => $this->getVehicles(),
            'employees' => $this->getEmployees()
        ]);
    }

    public function actionDelete(){
        $service = Yii::$app->params['ServiceName']['FuelingDocumentPortal'];
        $result = Yii::$app->navhelper->deleteData($service,Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!is_string($result)){

            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        }else{
            return ['note' => '<div class="alert alert-danger">Error Purging Record: '.$result.'</div>' ];
        }
    }

    public function actionView($No){
        $model = new Fuel();
        $service = Yii::$app->params['ServiceName']['FuelingDocumentPortal'];

        $filter = [
            'Fuel_Code' => $No
        ];

        $result = Yii::$app->navhelper->getData($service, $filter);

        //load nav result to model
        $model = $this->loadtomodel($result[0], $model);

        //Yii::$app->recruitment->printrr($model);

        return $this->render('view',[
            'model' => $model,
        ]);
    }

   // Get imprest list

    public function actionList(){
        $service = Yii::$app->params['ServiceName']['FuelingList'];
        $filter = [
            'Driver_Staff_No' => Yii::$app->user->identity->Employee_No,
        ];

        $results = \Yii::$app->navhelper->getData($service,$filter);
        $result = [];
        foreach($results as $item){
            $link = $updateLink = $deleteLink =  '';
            $Viewlink = Html::a('<i class="fas fa-eye"></i>',['view','No'=> $item->Fuel_Code ],['class'=>'btn btn-outline-primary btn-xs','title' => 'View Request.' ]);
            if($item->Status == 'New'){
                $link = Html::a('<i class="fas fa-paper-plane"></i>',['send-for-approval','No'=> $item->Fuel_Code ],['title'=>'Send Approval Request','class'=>'btn btn-primary btn-xs']);
                $updateLink = Html::a('<i class="far fa-edit"></i>',['update','No'=> $item->Fuel_Code ],['class'=>'btn btn-info btn-xs','title' => 'Update Request']);
            }else if($item->Status == 'Pending_Approval'){
                $link = Html::a('<i class="fas fa-times"></i>',['cancel-request','No'=> $item->Fuel_Code ],['title'=>'Cancel Approval Request','class'=>'btn btn-warning btn-xs']);
            }

            $result['data'][] = [
                'Key' => $item->Key,
                'No' => $item->Fuel_Code,
                'Vehicle_Registration_No' => !empty($item->Vehicle_Registration_No)?$item->Vehicle_Registration_No:'',
                'Vehicle_Model' => !empty($item->Vehicle_Model)?$item->Vehicle_Model:'',
                'Driver_Name' => !empty($item->Driver_Name)?$item->Driver_Name:'',
                'Created_Date' => !empty($item->Created_Date)?$item->Created_Date:'',
                'Type_of_Fuel' => !empty($item->Type_of_Fuel)?$item->Type_of_Fuel:'',
                'Total_Fuel_Cost' => !empty($item->Total_Fuel_Cost)?$item->Total_Fuel_Cost:'',
                'Status' => !empty($item->Status)?$item->Status:'',

                'Action' => $link.' '. $updateLink.' '.$Viewlink ,

            ];
        }

        return $result;
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

        return ArrayHelper::map($data, 'No', 'Full_Name');
    }



    public function actionSetvehicle(){
        $model = new Fuel();
        $service = Yii::$app->params['ServiceName']['FuelingDocumentPortal'];

        $filter = [
            'Fuel_Code' => Yii::$app->request->post('Fuel_Code')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if(is_array($request)){
            Yii::$app->navhelper->loadmodel($request[0],$model);
            $model->Key = $request[0]->Key;
            $model->Vehicle_Registration_No = Yii::$app->request->post('Vehicle_Registration_No');
            $model->Driver_Staff_No = Yii::$app->user->identity->{'Employee_No'};
        }


        $result = Yii::$app->navhelper->updateData($service,$model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;

    }

    public function actionSetamount(){
        $model = new Salaryadvance();
        $service = Yii::$app->params['ServiceName']['SalaryAdvanceCard'];

        $filter = [
            'Plan_No' => Yii::$app->request->post('Plan_No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if(is_array($request)){
            Yii::$app->navhelper->loadmodel($request[0],$model);
            $model->Key = $request[0]->Key;
            $model->Amount_Requested = Yii::$app->request->post('amount');
        }


        $result = Yii::$app->navhelper->updateData($service,$model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;

    }

    /* Set Imprest Type */

    public function actionSetimpresttype(){
        $model = new Imprestcard();
        $service = Yii::$app->params['ServiceName']['ImprestRequestCardPortal'];

        $filter = [
            'Plan_No' => Yii::$app->request->post('Plan_No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if(is_array($request)){
            Yii::$app->navhelper->loadmodel($request[0],$model);
            $model->Key = $request[0]->Key;
            $model->Imprest_Type = Yii::$app->request->post('Imprest_Type');
        }


        $result = Yii::$app->navhelper->updateData($service,$model,['Amount_LCY']);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;

    }

        /*Set Imprest to Surrend*/

    public function actionSetimpresttosurrender(){
        $model = new Imprestsurrendercard();
        $service = Yii::$app->params['ServiceName']['ImprestSurrenderCardPortal'];

        $filter = [
            'Plan_No' => Yii::$app->request->post('Plan_No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if(is_array($request)){
            Yii::$app->navhelper->loadmodel($request[0],$model);
            $model->Key = $request[0]->Key;
            $model->Imprest_Plan_No = Yii::$app->request->post('Imprest_Plan_No');
        }


        $result = Yii::$app->navhelper->updateData($service,$model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;

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

    /*Get Vehicles */
    public function getVehicles(){
        $service = Yii::$app->params['ServiceName']['AvailableVehicleLookUp'];

        $result = \Yii::$app->navhelper->getData($service, []);
        $arr = [];
        $i = 0;
        foreach($result as $res){
            if(!empty($res->Vehicle_Registration_No) && !empty($res->Make_Model)){
                ++$i;
                $arr[$i] = [
                    'Code' => $res->Vehicle_Registration_No,
                    'Description' => $res->Make_Model
                ];
            }
        }

        return ArrayHelper::map($arr,'Code','Description');
    }





}