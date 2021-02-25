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
use frontend\models\Fundrequisition;
use frontend\models\Fundsrequisitionline;
use frontend\models\Imprestline;
use frontend\models\Leaveplanline;
use frontend\models\Weeknessdevelopmentplan;
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

class FundsrequisitionlineController extends Controller
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
                'only' => ['uu'],
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

    public function actionCreate($Request_No){
       $service = Yii::$app->params['ServiceName']['AllowanceRequestLine'];
       $model = new Fundsrequisitionline() ;


        if($Request_No && !Yii::$app->request->post()){
                $model->Request_No = $Request_No;
                $model->Line_No = time();
                $res = Yii::$app->navhelper->postData($service, $model);
                if(!is_string($res)){
                    Yii::$app->navhelper->loadmodel($res, $model);
                    $model->Request_No = $Request_No;
                    Yii::$app->navhelper->updateData($service, $model);
                }else{
                    // Yii::$app->recruitment->printrr($res);
                    Yii::$app->session->setFlash('error', $res);
                }

            return $this->renderAjax('create', [
                'model' => $model,
                'transactionTypes' => $this->getRates(),
            ]);

        }
        // Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Fundsrequisitionline'],$model)

        if(Yii::$app->request->post()  && $model->load(Yii::$app->request->post()) ){


             $refresh = Yii::$app->navhelper->getData($service,[
                'Line_No' => Yii::$app->request->post()['Fundsrequisitionline']['Line_No']
                ]);



            $model->Key = $refresh[0]->Key;


            $result = Yii::$app->navhelper->updateData($service,$model);



            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            // return $model;
            if(!is_string($result)){

                return ['note' => '<div class="alert alert-success">Fund Requisition Line Created Successfully. </div>' ];
            }else{

                return ['note' => '<div class="alert alert-danger">Error Creating Fund Requisition Line: '.$result.'</div>'];
            }

        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('create', [
                'model' => $model,
                'transactionTypes' => $this->getRates(),
            ]);
        }


    }


    public function actionUpdate(){
        $model = new Fundsrequisitionline() ;
        $model->isNewRecord = false;
        $service = Yii::$app->params['ServiceName']['AllowanceRequestLine'];
        $filter = [
            'Line_No' => Yii::$app->request->get('Line_No'),
            'Request_No' => Yii::$app->request->get('Request_No')
        ];
        $result = Yii::$app->navhelper->getData($service,$filter);


        if(is_array($result)){
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($result[0],$model) ;
            // Yii::$app->recruitment->printrr($model);
        }else{
            Yii::$app->recruitment->printrr($result);
        }


        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Fundsrequisitionline'],$model) ){

            $refresh = Yii::$app->navhelper->getData($service,['Line_No' => Yii::$app->request->post()['Fundsrequisitionline']['Line_No']]);
            $model->Key = $refresh[0]->Key;

            $result = Yii::$app->navhelper->updateData($service,$model);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if(!is_string($result)){
                return ['note' => '<div class="alert alert-success">Fund Requisition Line Updated Successfully. </div>' ];
            }else{
                return ['note' => '<div class="alert alert-danger">Error Updating Fund Requisition Line: '.$result.'</div>'];
            }

        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('update', [
                'model' => $model,
                'transactionTypes' => $this->getRates(),
            ]);
        }

        return $this->render('update',[
            'model' => $model,
            'transactionTypes' => $this->getRates(),
        ]);
    }

    public function actionDelete(){
        $service = Yii::$app->params['ServiceName']['AllowanceRequestLine'];
        $result = Yii::$app->navhelper->deleteData($service,Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!is_string($result)){
            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        }else{
            return ['note' => '<div class="alert alert-danger">Error Purging Record: '.$result.'</div>' ];
        }
    }

    public function actionSettransactioncode(){
        $model = new Fundsrequisitionline();
        $service = Yii::$app->params['ServiceName']['AllowanceRequestLine'];

        $filter = [
            'Line_No' => Yii::$app->request->post('Line_No')
        ];
        $line = Yii::$app->navhelper->getData($service, $filter);

        if(is_array($line)){
            Yii::$app->navhelper->loadmodel($line[0],$model);
            $model->Key = $line[0]->Key;
            $model->PD_Transaction_Code = Yii::$app->request->post('PD_Transaction_Code');

        }


        $result = Yii::$app->navhelper->updateData($service,$model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;

    }

    public function actionSetenddate(){
        $model = new Imprestline();
        $service = Yii::$app->params['ServiceName']['Leave__Plan__Line'];

        $filter = [
            'Line_No' => Yii::$app->request->post('Line_No')
        ];
        $line = Yii::$app->navhelper->getData($service, $filter);

        if(is_array($line)){
            Yii::$app->navhelper->loadmodel($line[0],$model);
            $model->Key = $line[0]->Key;
            $model->End_Date = date('Y-m-d',strtotime(Yii::$app->request->post('End_Date')));
        }


        $result = Yii::$app->navhelper->updateData($service,$model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;
    }

    public function actionView($ApplicationNo){
        $service = Yii::$app->params['ServiceName']['AllowanceRequestLine'];
        $leaveTypes = $this->getLeaveTypes();
        $employees = $this->getEmployees();

        $filter = [
            'Application_No' => $ApplicationNo
        ];

        $leave = Yii::$app->navhelper->getData($service, $filter);

        //load nav result to model
        $leaveModel = new Leave();
        $model = $this->loadtomodel($leave[0],$leaveModel);


        return $this->render('view',[
            'model' => $model,
            'leaveTypes' => ArrayHelper::map($leaveTypes,'Code','Description'),
            'relievers' => ArrayHelper::map($employees,'No','Full_Name'),
        ]);
    }



    /*Get Transaction Types */

    public function getRates(){
        $service = Yii::$app->params['ServiceName']['RequisitionRates'];

        $result = \Yii::$app->navhelper->getData($service, []);
        return ArrayHelper::map($result,'Code','Description');

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