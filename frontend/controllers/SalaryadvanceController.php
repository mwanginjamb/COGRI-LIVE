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
use frontend\models\Imprestcard;
use frontend\models\Imprestline;
use frontend\models\Imprestsurrendercard;
use frontend\models\Leaveplancard;
use frontend\models\Salaryadvance;
use frontend\models\Trainingplan;
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

class SalaryadvanceController extends Controller
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
                        'actions' => ['logout','index','advance-list','create','update','delete','view'],
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
                'only' => ['advance-list'],
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

        $model = new Salaryadvance();
        $service = Yii::$app->params['ServiceName']['SalaryAdvanceCard'];


        /*Do initial request */
        if(!isset(Yii::$app->request->post()['Salaryadvance'])){
            $model->Employee_No = Yii::$app->user->identity->Employee_No;
            $request = Yii::$app->navhelper->postData($service, $model);
            if(is_object($request) )
            {
                Yii::$app->navhelper->loadmodel($request,$model);
            }else if(is_string($request))
            {
                Yii::$app->session->setFlash('error',$request);
                return $this->render('create',[
                    'model' => $model,
                    'employees' => $this->getEmployees(),
                    'programs' => $this->getPrograms(),
                    'departments' => $this->getDepartments(),
                    'currencies' => $this->getCurrencies(),
                    'loans' => $this->getLoans(),
                ]);
            }
        }



        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Salaryadvance'],$model) ){

            $filter = [
                'No' => $model->No,
            ];
            /*Read the card again to refresh Key in case it changed*/
            $refresh = Yii::$app->navhelper->getData($service,$filter);
            Yii::$app->navhelper->loadmodel($refresh[0],$model);
            $result = Yii::$app->navhelper->updateData($service,$model);
            if(!is_string($result)){

                Yii::$app->session->setFlash('success','Salary Advance Request Created Successfully.' );
                return $this->redirect(['view','No' => $result->No]);

            }else{
                Yii::$app->session->setFlash('error','Error Creating Salary Advance Request '.$result );
                return $this->redirect(['index']);

            }

        }


        //Yii::$app->recruitment->printrr($model);
        $model->Global_Dimension_1_Code = !empty(Yii::$app->user->identity->Employee[0]->Global_Dimension_1_Code)?Yii::$app->user->identity->Employee[0]->Global_Dimension_1_Code:'';
        $model->Global_Dimension_2_Code = !empty(Yii::$app->user->identity->Employee[0]->Global_Dimension_2_Code)?Yii::$app->user->identity->Employee[0]->Global_Dimension_2_Code:'';

        return $this->render('create',[
            'model' => $model,
            'employees' => $this->getEmployees(),
            'programs' => $this->getPrograms(),
            'departments' => $this->getDepartments(),
            'currencies' => $this->getCurrencies(),
            'loans' => $this->getLoans(),
        ]);
    }


    public function actionCreateSurrender(){
        // Yii::$app->recruitment->printrr(Yii::$app->request->get('requestfor'));
        $model = new Imprestsurrendercard();
        $service = Yii::$app->params['ServiceName']['ImprestSurrenderCardPortal'];

        /*Do initial request */
        $request = Yii::$app->navhelper->postData($service,[]);

        if(is_object($request) )
        {
            Yii::$app->navhelper->loadmodel($request,$model);

            // Update Request for
            $model->Request_For = Yii::$app->request->get('requestfor');
            $model->Key = $request->Key;
            $request = Yii::$app->navhelper->updateData($service, $model);

            if(is_string($request)){
                Yii::$app->recruitment->printrr($request);
            }


        }

        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Imprestsurrendercard'],$model) ){

            $filter = [
                'No' => $model->No,
            ];

            $refresh = Yii::$app->navhelper->getData($service,$filter);
            Yii::$app->navhelper->loadmodel($refresh[0],$model);

            $result = Yii::$app->navhelper->updateData($service,$model);


            if(!is_string($result)){
                //Yii::$app->recruitment->printrr($result);
                Yii::$app->session->setFlash('success','Imprest Request Created Successfully.' );

                return $this->redirect(['view','No' => $result->No]);

            }else{
                Yii::$app->session->setFlash('success','Error Creating Imprest Request '.$result );
                return $this->render('createsurrender',[
                    'model' => $model,
                    'employees' => $this->getEmployees(),
                    'programs' => $this->getPrograms(),
                    'departments' => $this->getDepartments(),
                    'currencies' => $this->getCurrencies(),
                    'imprests' => $this->getmyimprests(),
                    'receipts' => $this->getimprestreceipts($model->No)
                ]);

            }

        }

        return $this->render('createsurrender',[
            'model' => $model,
            'employees' => $this->getEmployees(),
            'programs' => $this->getPrograms(),
            'departments' => $this->getDepartments(),
            'currencies' => $this->getCurrencies(),
            'imprests' => $this->getmyimprests(),
            'receipts' => $this->getimprestreceipts($model->No)
        ]);
    }


    public function actionUpdate(){
        $model = new Salaryadvance();


        $service = Yii::$app->params['ServiceName']['SalaryAdvanceCard'];
        $model->isNewRecord = false;

        $filter = [
            'No' => Yii::$app->request->get('No'),
        ];
        $result = Yii::$app->navhelper->getData($service,$filter);

        if(is_array($result)){
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($result[0],$model) ;//$this->loadtomodeEmployee_Nol($result[0],$Expmodel);
        }else{
            Yii::$app->recruitment->printrr($result);
        }


        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Salaryadvance'],$model) ){
            $filter = [
                'No' => $model->No,
            ];
            /*Read the card again to refresh Key in case it changed*/
            $refresh = Yii::$app->navhelper->getData($service,$filter);
            Yii::$app->navhelper->loadmodel($refresh[0],$model);

            $result = Yii::$app->navhelper->updateData($service,$model);

            if(!is_string($result)){

                Yii::$app->session->setFlash('success','Salary Advance Request Updated Successfully.' );

                return $this->redirect(['view','No' => $result->No]);

            }else{
                Yii::$app->session->setFlash('success','Error Updating Salary Advance Request '.$result );
                return $this->render('update',[
                    'model' => $model,
                    'employees' => $this->getEmployees(),
                    'programs' => $this->getPrograms(),
                    'departments' => $this->getDepartments(),
                    'currencies' => $this->getCurrencies(),
                    'loans' => $this->getLoans(),
                ]);

            }

        }


        // Yii::$app->recruitment->printrr($model);
        $model->Global_Dimension_1_Code = !empty(Yii::$app->user->identity->Employee[0]->Global_Dimension_1_Code)?Yii::$app->user->identity->Employee[0]->Global_Dimension_1_Code:'';
        $model->Global_Dimension_2_Code = !empty(Yii::$app->user->identity->Employee[0]->Global_Dimension_2_Code)?Yii::$app->user->identity->Employee[0]->Global_Dimension_2_Code:'';
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('update', [
                'model' => $model,
                'employees' => $this->getEmployees(),
                'programs' => $this->getPrograms(),
                'departments' => $this->getDepartments(),
                'currencies' => $this->getCurrencies(),
                'loans' => $this->getLoans(),

            ]);
        }

        return $this->render('update',[
            'model' => $model,
            'employees' => $this->getEmployees(),
            'programs' => $this->getPrograms(),
            'departments' => $this->getDepartments(),
            'currencies' => $this->getCurrencies(),
            'loans' => $this->getLoans(),
        ]);
    }

    public function actionDelete(){
        $service = Yii::$app->params['ServiceName']['CareerDevStrengths'];
        $result = Yii::$app->navhelper->deleteData($service,Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!is_string($result)){

            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        }else{
            return ['note' => '<div class="alert alert-danger">Error Purging Record: '.$result.'</div>' ];
        }
    }

    public function actionView($No){
        $model = new Salaryadvance();
        $service = Yii::$app->params['ServiceName']['SalaryAdvanceCard'];

        $filter = [
            'No' => $No,
            'Employee_No' => Yii::$app->user->identity->{'Employee_No'},
        ];

        $result = Yii::$app->navhelper->getData($service, $filter);

        //load nav result to model
        $model = $this->loadtomodel($result[0], $model);

        //Yii::$app->recruitment->printrr($model);

        return $this->render('view',[
            'model' => $model,
        ]);
    }

    /*Imprest surrender card view*/

    public function actionViewSurrender($No){
        $service = Yii::$app->params['ServiceName']['ImprestSurrenderCard'];

        $filter = [
            'No' => $No
        ];

        $result = Yii::$app->navhelper->getData($service, $filter);
        //load nav result to model
        $model = $this->loadtomodel($result[0], new Imprestsurrendercard());

        return $this->render('viewsurrender',[
            'model' => $model,
            'employees' => $this->getEmployees(),
            'programs' => $this->getPrograms(),
            'departments' => $this->getDepartments(),
            'currencies' => $this->getCurrencies()
        ]);
    }

    // Get imprest list

    public function actionAdvanceList(){
        $service = Yii::$app->params['ServiceName']['SalaryAdvanceList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee_No'},
        ];

        $results = \Yii::$app->navhelper->getData($service,$filter);
        $result = [];

        if(is_array($results))
        {
            foreach($results as $item){
                $link = $updateLink = $deleteLink =  '';
                $Viewlink = Html::a('<i class="fas fa-eye"></i>',['view','No'=> $item->No ],['class'=>'btn btn-outline-primary btn-xs']);
                if($item->Status == 'New'){
                    $link = Html::a('<i class="fas fa-paper-plane"></i>',['send-for-approval','No'=> $item->No ],['title'=>'Send Approval Request','class'=>'btn btn-primary btn-xs']);
                    $updateLink = Html::a('<i class="far fa-edit"></i>',['update','No'=> $item->No ],['class'=>'btn btn-info btn-xs']);
                }else if($item->Status == 'Pending_Approval'){
                    $link = Html::a('<i class="fas fa-times"></i>',['cancel-request','No'=> $item->No ],['title'=>'Cancel Approval Request','class'=>'btn btn-warning btn-xs']);
                }

                $result['data'][] = [
                    'Key' => $item->Key,
                    'No' => $item->No,
                    'Employee_No' => !empty($item->Employee_No)?$item->Employee_No:'',
                    'Employee_Name' => !empty($item->Employee_Name)?$item->Employee_Name:'',
                    'Purpose' => !empty($item->Purpose)?$item->Purpose:'',
                    'Amount_Requested' => !empty($item->Amount_Requested)?$item->Amount_Requested:'',
                    'Status' => $item->Status,
                    'Action' => $link,
                    'Update_Action' => $updateLink,
                    'view' => $Viewlink
                ];
            }
        }


        return $result;
    }

    // Get Imprest  surrender list

    public function actionGetimprestsurrenders(){
        $service = Yii::$app->params['ServiceName']['ImprestSurrenderList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee_No'},
        ];
        //Yii::$app->recruitment->printrr( );
        $results = \Yii::$app->navhelper->getData($service,$filter);
        $result = [];
        foreach($results as $item){
            $link = $updateLink = $deleteLink =  '';
            $Viewlink = Html::a('<i class="fas fa-eye"></i>',['view-surrender','No'=> $item->No ],['class'=>'btn btn-outline-primary btn-xs']);
            if($item->Status == 'New'){
                $link = Html::a('<i class="fas fa-paper-plane"></i>',['send-for-approval','No'=> $item->No ],['title'=>'Send Approval Request','class'=>'btn btn-primary btn-xs']);

                $updateLink = Html::a('<i class="far fa-edit"></i>',['update','No'=> $item->No ],['class'=>'btn btn-info btn-xs']);
            }else if($item->Status == 'Pending_Approval'){
                $link = Html::a('<i class="fas fa-times"></i>',['cancel-request','No'=> $item->No ],['title'=>'Cancel Approval Request','class'=>'btn btn-warning btn-xs']);
            }

            $result['data'][] = [
                'Key' => $item->Key,
                'No' => $item->No,
                'Employee_No' => !empty($item->Employee_No)?$item->Employee_No:'',
                'Employee_Name' => !empty($item->Employee_Name)?$item->Employee_Name:'',
                'Purpose' => !empty($item->Purpose)?$item->Purpose:'',
                'Imprest_Amount' => !empty($item->Imprest_Amount)?$item->Imprest_Amount:'',
                'Status' => $item->Status,
                'Action' => $link,
                'Update_Action' => $updateLink,
                'view' => $Viewlink
            ];
        }

        return $result;
    }


    public function getEmployees(){
        $service = Yii::$app->params['ServiceName']['Employees'];
        $employees = \Yii::$app->navhelper->getData($service);
        return ArrayHelper::map($employees,'No','FullName');
    }

    /* My Imprests*/

    public function getmyimprests(){
        $service = Yii::$app->params['ServiceName']['PostedImprestRequest'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->Employee[0]->No,
            'Surrendered' => false,
        ];

        $results = \Yii::$app->navhelper->getData($service,$filter);

        $result = [];
        $i = 0;
        if(is_array($results)){
            foreach($results as $res){
                $result[$i] =[
                    'No' => $res->No,
                    'detail' => $res->No.' - '.$res->Imprest_Amount
                ];
                $i++;
            }
        }
        // Yii::$app->recruitment->printrr(ArrayHelper::map($result,'No','detail'));
        return ArrayHelper::map($result,'No','detail');
    }

    /*Get Staff Loans */

    public function getLoans(){
        $service = Yii::$app->params['ServiceName']['StaffLoans'];

        $results = \Yii::$app->navhelper->getData($service);
        return ArrayHelper::map($results,'Code','Loan_Name');
    }

    /* Get My Posted Imprest Receipts */

    public function getimprestreceipts($imprestNo){
        $service = Yii::$app->params['ServiceName']['PostedReceiptsList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->Employee[0]->No,
            'Imprest_No' => $imprestNo,
        ];

        $results = \Yii::$app->navhelper->getData($service,$filter);

        $result = [];
        $i = 0;
        if(is_array($results)){
            foreach($results as $res){
                $result[$i] =[
                    'No' => $res->No,
                    'detail' => $res->No.' - '.$res->Imprest_No
                ];
                $i++;
            }
        }
        // Yii::$app->recruitment->printrr(ArrayHelper::map($result,'No','detail'));
        return ArrayHelper::map($result,'No','detail');
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


    // Get Currencies

    public function getCurrencies(){
        $service = Yii::$app->params['ServiceName']['Currencies'];

        $result = \Yii::$app->navhelper->getData($service, []);
        return ArrayHelper::map($result,'Code','Description');
    }

    public function actionSetloantype(){
        $model = new Salaryadvance();
        $service = Yii::$app->params['ServiceName']['SalaryAdvanceCard'];
        $filter = [
            'No' => Yii::$app->request->post('No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);
        if(is_array($request)){
            Yii::$app->navhelper->loadmodel($request[0],$model);
            $model->Key = $request[0]->Key;
            $model->Loan_Type = Yii::$app->request->post('loan');
        }
        $result = Yii::$app->navhelper->updateData($service,$model);
        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;
        return $result;
    }

    public function actionSetamount(){
        $model = new Salaryadvance();
        $service = Yii::$app->params['ServiceName']['SalaryAdvanceCard'];
        $filter = [
            'No' => Yii::$app->request->post('No')
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
            'No' => Yii::$app->request->post('No')
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
            'No' => Yii::$app->request->post('No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if(is_array($request)){
            Yii::$app->navhelper->loadmodel($request[0],$model);
            $model->Key = $request[0]->Key;
            $model->Imprest_No = Yii::$app->request->post('Imprest_No');
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

    public function actionSendForApproval($No)
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];

        $data = [
            'applicationNo' => $No,
            'sendMail' => 1,
            'approvalUrl' => '',
        ];


        $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanSendImprestForApproval');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Imprest Request Sent to Supervisor Successfully.', true);
            return $this->redirect(['view','No' => $No]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Sending Imprest Request for Approval  : '. $result);
            return $this->redirect(['view','No' => $No]);

        }
    }

    /*Cancel Approval Request */

    public function actionCancelRequest($No)
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];

        $data = [
            'applicationNo' => $No,
        ];


        $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanCancelImprestForApproval');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Imprest Request Cancelled Successfully.', true);
            return $this->redirect(['view','No' => $No]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Cancelling Imprest Approval Request.  : '. $result);
            return $this->redirect(['view','No' => $No]);

        }
    }



}