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
use frontend\models\Leaveplanline;
use frontend\models\Storerequisitionline;
use frontend\models\Vehiclerequisitionline;
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

class StorerequisitionlineController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup','index','create','update','delete','view'],
                'rules' => [
                    [
                        'actions' => ['signup','index'],
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
                'only' => ['setquantity','setitem','setlocation'],
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

    public function actionCreate($No){
       $service = Yii::$app->params['ServiceName']['StoreRequisitionLine'];
       $model = new Storerequisitionline();
       $model->Type =  'Item'; 

        if(Yii::$app->request->get('No') && !Yii::$app->request->post()){

                $model->Requisition_No = $No;
                $result = Yii::$app->navhelper->postData($service, $model);
                if(is_string($result)){
                    return '<div class="alert alert-danger">Error Creating Requisition Line: '.$result.'</div>';
                }
                Yii::$app->navhelper->loadmodel($result,$model);
        }
        

        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Storerequisitionline'],$model) ){

            
            $result = Yii::$app->navhelper->updateData($service,$model);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if(!is_string($result)){

                return ['note' => '<div class="alert alert-success">Requisition Line Created Successfully. </div>' ];
            }else{

                return ['note' => '<div class="alert alert-danger">Error Creating Requisition Line: '.$result.'</div>'];
            }

        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('create', [
                'model' => $model,
                'locations' => $this->getLocations(),
                'items' => $this->getItems(),
                'programs' => $this->getPrograms(),
                'departments' => $this->getDepartments(),
                // 'students' => $this->getStudent(),
            ]);
        }


    }


    public function actionUpdate(){
        $model = new Storerequisitionline() ;
        $model->isNewRecord = false;
        $service = Yii::$app->params['ServiceName']['StoreRequisitionLine'];
        $filter = [
            'Line_No' => Yii::$app->request->get('No'),
        ];
        $result = Yii::$app->navhelper->getData($service,$filter);

        if(is_array($result)){
            //load nav result to model
            Yii::$app->navhelper->loadmodel($result[0],$model) ;
        }else{
            Yii::$app->recruitment->printrr($result);
        }


        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Storerequisitionline'],$model) ){

            $filter = [
                'Requisition_No' => $model->Requisition_No,
            ];
            $refresh = Yii::$app->navhelper->readbykey($service, $model->Key);
            $model->Key = $refresh->Key;

            //Yii::$app->recruitment->printrr($model);

            $result = Yii::$app->navhelper->updateData($service,$model);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if(!is_string($result)){

                return ['note' => '<div class="alert alert-success"> Line Updated Successfully. </div>' ];
            }else{

                return ['note' => '<div class="alert alert-danger">Error Updating Line: '.$result.'</div>'];
            }

        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('update', [
                'model' => $model,
                'locations' => $this->getLocations(),
                'items' => $this->getItems(),
                'programs' => $this->getPrograms(),
                'departments' => $this->getDepartments(),
                // 'students' => $this->getStudent(),
            ]);
        }

        return $this->render('update',[
            'model' => $model,
            'locations' => $this->getLocations(),
            'items' => $this->getItems(),
            'programs' => $this->getPrograms(),
            'departments' => $this->getDepartments(),
            // 'students' => $this->getStudent(),
        ]);
    }

    public function actionDelete(){
        $service = Yii::$app->params['ServiceName']['StoreRequisitionLine'];
        $result = Yii::$app->navhelper->deleteData($service,Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!is_string($result)){
            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        }else{
            return ['note' => '<div class="alert alert-danger">Error Purging Record: '.$result.'</div>' ];
        }
    }

    /*Get Programs */

    public function getPrograms(){
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 1
        ];

        $result = \Yii::$app->navhelper->getData($service, $filter);
       // return ArrayHelper::map($result,'Code','Name');
        return Yii::$app->navhelper->refactorArray($result,'Code','Name');
    }

    /* Get Department*/

    public function getDepartments(){
       $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 2
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        // return ArrayHelper::map($result,'Code','Name');
        return Yii::$app->navhelper->refactorArray($result,'Code','Name');
    }

    /* Get Dimension 3*/

    public function getStudent(){
         $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 3
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        //return ArrayHelper::map($result,'Code','Name');
        //return Yii::$app->navhelper->refactorArray($result,'Code','Name');
        $arr = [];
        $i = 0;
        foreach($result as $res){
            if(!empty($res->Code) && !empty($res->Name)){
                ++$i;
                $arr[$i] = [
                    'Code' => $res->Code,
                    'Name' => $res->Name.' - '.$res->Code
                ];
            }
        }

        return ArrayHelper::map($arr,'Code','Name');
    }

    public function actionSetquantity(){
        $model = new Storerequisitionline();
        $service = Yii::$app->params['ServiceName']['StoreRequisitionLine'];

        $filter = [
            'Line_No' => Yii::$app->request->post('Line_No')
        ];
        $line = Yii::$app->navhelper->getData($service, $filter);
        // Yii::$app->recruitment->printrr($line);
        if(is_array($line)){
            Yii::$app->navhelper->loadmodel($line[0],$model);
            $model->Key = $line[0]->Key;
            $model->Quantity = Yii::$app->request->post('Quantity');

        }


        $result = Yii::$app->navhelper->updateData($service,$model);

        return $result;

    }

    // Set Location

    public function actionSetlocation(){
        $model = new Storerequisitionline();
        $service = Yii::$app->params['ServiceName']['StoreRequisitionLine'];

        $filter = [
            'Line_No' => Yii::$app->request->post('Line_No')
        ];
        $line = Yii::$app->navhelper->getData($service, $filter);
        // Yii::$app->recruitment->printrr($line);
        if(is_array($line)){
            Yii::$app->navhelper->loadmodel($line[0],$model);
            $model->Key = $line[0]->Key;
            $model->Location = Yii::$app->request->post('Location');

        }


        $result = Yii::$app->navhelper->updateData($service,$model);

        return $result;

    }

    public function actionSetitem(){
        $model = new Storerequisitionline();
        $service = Yii::$app->params['ServiceName']['StoreRequisitionLine'];

        $filter = [
            'Line_No' => Yii::$app->request->post('Line_No')
        ];
        // Yii::$app->recruitment->printrr(Yii::$app->request->post());

        $line = Yii::$app->navhelper->getData($service, $filter);
        if(is_array($line)){
            Yii::$app->navhelper->loadmodel($line[0],$model);
            $model->Key = $line[0]->Key;
            $model->No = Yii::$app->request->post('No');

        }
        $model->Type =  'Item'; 

        $result = Yii::$app->navhelper->updateData($service,$model);

        return $result;

    }


    /*Get Locations*/

    public function getLocations(){
        $service = Yii::$app->params['ServiceName']['Locations'];
        $filter = [];
        $result = \Yii::$app->navhelper->getData($service, $filter);
       // return ArrayHelper::map($result,'Code','Name');

        return Yii::$app->navhelper->refactorArray($result,'Code', 'Name');
    }



    /*Get Items*/

    public function getItems(){
        $service = Yii::$app->params['ServiceName']['Items'];
        $filter = [];
        $result = \Yii::$app->navhelper->getData($service, $filter);

        return Yii::$app->navhelper->refactorArray($result,'No','Description');
    }




    public function actionView($ApplicationNo){
        $service = Yii::$app->params['ServiceName']['leaveApplicationCard'];


        $filter = [
            'Application_No' => $ApplicationNo
        ];

        $leave = Yii::$app->navhelper->getData($service, $filter);

        //load nav result to model
        $leaveModel = new Leave();
        $model = $this->loadtomodel($leave[0],$leaveModel);


        return $this->render('view',[
            'model' => $model,
        ]);
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