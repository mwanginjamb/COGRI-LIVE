<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:21 PM
 */

namespace frontend\controllers;
use frontend\models\Workticket;
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

class WorkTicketController extends Controller
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

        $model = new Workticket();
        $service = Yii::$app->params['ServiceName']['WorkTicketDocument'];

        /*Do initial request */
        if(!isset(Yii::$app->request->post()['Workticket'])){
            $model->Driver_Staff_No = Yii::$app->user->identity->{'Employee_No'};
            $request = Yii::$app->navhelper->postData($service, $model);
            if(!is_string($request) )
            {
                Yii::$app->navhelper->loadmodel($request,$model);
            }else{
                // Yii::$app->recruitment->printrr($request);
                Yii::$app->session->setFlash('error',$request);
                 return $this->render('create',[
                    'model' => $model,
                    'programs' => $this->getPrograms(),
                    'departments' => $this->getDepartments(),
                    'vehicles' => $this->getVehicles(),
                    'requisitions' => $this->getReleasedRequisitions(),
                    'fuel' => $this->getFuelReq()
                ]);
            }
        }

        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Workticket'],$model) ){


            $result = Yii::$app->navhelper->updateData($service,$model);
            if(!is_string($result)){

                Yii::$app->session->setFlash('success','Request Created Successfully.' );
                return $this->redirect(['view','No' => $result->Work_Ticket_No]);

            }else{
                Yii::$app->session->setFlash('error','Error Creating Request '.$result );
                return $this->redirect(['index']);

            }

        }


        //Yii::$app->recruitment->printrr($model);
        $model->Departure_Date = ($model->Departure_Date === '0001-01-01')?date('Y-m-d'):$model->Departure_Date;
          $model->Return_Date = ($model->Return_Date === '0001-01-01')?date('Y-m-d'):$model->Return_Date;

        return $this->render('create',[
            'model' => $model,
            'programs' => $this->getPrograms(),
            'departments' => $this->getDepartments(),
            'vehicles' => $this->getVehicles(),
            'requisitions' => $this->getReleasedRequisitions(),
            'fuel' => $this->getFuelReq()
        ]);
    }




    public function actionUpdate($No){
        $model = new Workticket();
        $service = Yii::$app->params['ServiceName']['WorkTicketDocument'];
        $model->isNewRecord = false;

        $filter = [
            'Work_Ticket_No' => $No,
        ];
        $result = Yii::$app->navhelper->getData($service,$filter);

        if(is_array($result)){
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($result[0],$model) ;
        }else{
            Yii::$app->session->setFlash('error', $result);
            return $this->renderAjax('update', [
                'model' => $model,
                'programs' => $this->getPrograms(),
                'departments' => $this->getDepartments(),
                'vehicles' => $this->getVehicles(),
                'requisitions' => $this->getReleasedRequisitions(),
                'fuel' => $this->getFuelReq()

            ]);
        }


        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Workticket'],$model) ){

            $result = Yii::$app->navhelper->updateData($service,$model);

            if(!is_string($result)){
                Yii::$app->session->setFlash('success','Document Updated Successfully.' );
                return $this->redirect(['view','No' => $result->Work_Ticket_No]);

            }else{
                Yii::$app->session->setFlash('error','Error Updating Document'.$result );
                return $this->render('update',[
                    'model' => $model,
                    'programs' => $this->getPrograms(),
                    'departments' => $this->getDepartments(),
                    'vehicles' => $this->getVehicles(),
                    'requisitions' => $this->getReleasedRequisitions(),
                    'fuel' => $this->getFuelReq()
                ]);

            }

        }

          $model->Departure_Date = ($model->Departure_Date === '0001-01-01')?date('Y-m-d'):$model->Departure_Date;
          $model->Return_Date = ($model->Return_Date === '0001-01-01')?date('Y-m-d'):$model->Return_Date;

        // Yii::$app->recruitment->printrr($model);
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('update', [
                'model' => $model,
                'programs' => $this->getPrograms(),
                'departments' => $this->getDepartments(),
                'vehicles' => $this->getVehicles(),
                'requisitions' => $this->getReleasedRequisitions(),
                'fuel' => $this->getFuelReq()


            ]);
        }

        return $this->render('update',[
            'model' => $model,
            'programs' => $this->getPrograms(),
            'departments' => $this->getDepartments(),
            'vehicles' => $this->getVehicles(),
            'requisitions' => $this->getReleasedRequisitions(),
            'fuel' => $this->getFuelReq()

        ]);
    }


    public function getVehicles(){
        $service = Yii::$app->params['ServiceName']['VehicleRegister'];

        $result = \Yii::$app->navhelper->getData($service, []);
        $arr = [];
        $i = 0;
        foreach($result as $res){
            if(!empty($res->Vehicle_Registration_No) && !empty($res->Make_Model)){
                ++$i;
                $arr[$i] = [
                    'Code' => $res->Vehicle_Registration_No,
                    'Description' => $res->Make_Model.' - '.$res->Vehicle_Registration_No
                ];
            }
        }

        return ArrayHelper::map($arr,'Code','Description');
    }

    public function getReleasedRequisitions()
    {
        $service = Yii::$app->params['ServiceName']['ReleasedBookingRequisitions'];
        $result = \Yii::$app->navhelper->getData($service, []);
         $arr = [];
        $i = 0;
        foreach($result as $res){
            if(!empty($res->Booking_Requisition_No) && !empty($res->Vehicle_Registration_No)){
                ++$i;
                $arr[$i] = [
                    'Code' => $res->Booking_Requisition_No,
                    'Description' => $res->Vehicle_Registration_No.' - '.$res->Requisition_Date.' - '. $res->Booking_Requisition_No
                ];
            }
        }
         return ArrayHelper::map($arr,'Code','Description');
    }

    public function actionDelete(){
        $service = Yii::$app->params['ServiceName']['WorkTicketDocument'];
        $result = Yii::$app->navhelper->deleteData($service,Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!is_string($result)){

            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        }else{
            return ['note' => '<div class="alert alert-danger">Error Purging Record: '.$result.'</div>' ];
        }
    }

    public function actionView($No){
        $model = new Workticket();
        $service = Yii::$app->params['ServiceName']['WorkTicketDocument'];

        $filter = [
            'Work_Ticket_No' => $No
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
        $service = Yii::$app->params['ServiceName']['WorkTicketList'];
        $filter = [
            'Driver_Staff_No' => Yii::$app->user->identity->Employee_No,
        ];



        $results = \Yii::$app->navhelper->getData($service,$filter);
        //Yii::$app->recruitment->printrr($results);
        $result = [];
        foreach($results as $item){

            if(!empty($item->Work_Ticket_No ))
            {
                $link = $updateLink = $deleteLink =  '';
                $Viewlink = Html::a('<i class="fas fa-eye"></i>',['view','No'=> $item->Work_Ticket_No ],['class'=>'btn btn-outline-primary btn-xs','title' => 'View Request.' ]);
                $updateLink = (!$item->Submitted)?
                    Html::a('<i class="far fa-edit"></i>',['update','No'=> $item->Work_Ticket_No ],['class'=>'btn btn-info btn-xs','title' => 'Update Request'])
                :'<i class="fas fa-check"></i>';

                 $SubmitLink = (!$item->Submitted)?
                    Html::a('<i class="fas fa-check"></i>',['submitwt','No'=> $item->Work_Ticket_No ],['class'=>'btn btn-success btn-xs','title' => 'submit work ticket'])
                :'<i class="fas fa-check">Submitted</i>';
                /* if($item->Status == 'New'){
                    $link = Html::a('<i class="fas fa-paper-plane"></i>',['send-for-approval','No'=> $item->No ],['title'=>'Send Approval Request','class'=>'btn btn-primary btn-xs']);
                    $updateLink = Html::a('<i class="far fa-edit"></i>',['update','No'=> $item->No ],['class'=>'btn btn-info btn-xs','title' => 'Update Request']);
                }else if($item->Status == 'Pending_Approval'){
                    $link = Html::a('<i class="fas fa-times"></i>',['cancel-request','No'=> $item->No ],['title'=>'Cancel Approval Request','class'=>'btn btn-warning btn-xs']);
                }*/

                $result['data'][] = [
                    'Key' => $item->Key,
                    'No' => $item->Work_Ticket_No,
                    'Vehicle_Registration_No' => !empty($item->Vehicle_Registration_No)?$item->Vehicle_Registration_No:'',
                    'Departure_Date' => !empty($item->Departure_Date)?$item->Departure_Date:'',
                    'Purpose_of_Journey' => !empty($item->Purpose_of_Journey)?$item->Purpose_of_Journey:'',
                    'Duration_of_Travel_Days' => !empty($item->Duration_of_Travel_Days)?$item->Duration_of_Travel_Days:'',
                    'Travelled_Distance_KMS' => !empty($item->Travelled_Distance_KMS)?$item->Travelled_Distance_KMS:'',
                    'Total_Cost' => !empty($item->Total_Cost)?$item->Total_Cost:'',
                    'Destination' => !empty($item->Destination)?$item->Destination:'',
                    'Created_By' => !empty($item->Created_By)?$item->Created_By:'',
                    'Department' => !empty($item->Department)?$item->Department:'',
                    'Submitted' => Html::checkbox('Submitted',$item->Submitted),
                    'Action' => $Viewlink.$updateLink.$SubmitLink  ,

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
        return ArrayHelper::map($result,'Code','Name');
    }

    /* Get Department*/

    public function getDepartments(){
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 2
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        return ArrayHelper::map($result,'Code','Name');
    }

    /* Get Dimension 3*/

    public function getD3(){
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 3
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        return ArrayHelper::map($result,'Code','Name');
    }

    public function getFuelReq(){
        $service = Yii::$app->params['ServiceName']['FuelingList'];

        $filter = [
            'Posted' => true
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);




        if(is_array($result)){
            $i = 0;
            foreach($result as  $emp){
                $i++;
                if(!empty($emp->Fuel_Code) && !empty($emp->Vehicle_Registration_No) && !empty($emp->Driver_Name)){
                    $data[] = [
                        'No' => $emp->Fuel_Code,
                        'Desc' => $emp->Vehicle_Registration_No.' | '.$emp->Driver_Name.' | '.$emp->Created_Date
                    ];
                }
        }

    }

    

       return ArrayHelper::map($data,'No','Desc');

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


    public function actionSubmitwt($No)
    {
        $service = Yii::$app->params['ServiceName']['FleetMgt'];
        $data = [
            'workTicket' => $No
        ];

        $result = Yii::$app->navhelper->CodeUnit($service,$data,'SubmitWorkTicketData');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Work TicketSubmitted Successfully');
           return  $this->redirect(['index']);
        }else
        {
            Yii::$app->session->setFlash('error', 'Error: '.$result);
            return $this->redirect(['index']);
        }
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
            return $this->redirect(['index']);
        }else{

            Yii::$app->session->setFlash('error', 'Error Sending  Request for Approval  : '. $result);
            return $this->redirect(['index']);

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
            return $this->redirect(['index']);
        }else{

            Yii::$app->session->setFlash('error', 'Error Cancelling Approval Request.  : '. $result);
            return $this->redirect(['index']);

        }
    }



}