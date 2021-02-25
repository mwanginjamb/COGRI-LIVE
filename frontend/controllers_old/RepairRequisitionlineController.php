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
use frontend\models\Repairrequisitionline;
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

class RepairRequisitionlineController extends Controller
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
       $service = Yii::$app->params['ServiceName']['RepairRequisitionLine'];
       $model = new Repairrequisitionline();

        if(Yii::$app->request->get('No') && !isset(Yii::$app->request->post()['PurchaseRequisitionlineController'])){

                $model->Repair_Requisition_No = $No;
                $result = Yii::$app->navhelper->postData($service, $model);
                if(is_string($result))
                {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['note' => '<div class="alert alert-danger">Error Creating Requisition Line: '.$result.'</div>'];
                }
                Yii::$app->navhelper->loadmodel($result,$model);
        }
        

        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Repairrequisitionline'],$model) ){

            $filter = [
                'No' => $model->No,
            ];

            $result = Yii::$app->navhelper->updateData($service,$model);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if(!is_string($result)){

                return ['note' => '<div class="alert alert-success">Requisition Line Created Successfully. </div>' ];
            }else{

                return ['note' => '<div class="alert alert-danger">Error Creating Requisition Line: '.$result.'</div>'];
            }

        }
         // Yii::$app->recruitment->printrr($this->getInstitutions());
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('create', [
                'model' => $model,
                'locations' => $this->getLocations(),
                'items' => $this->getItems(),
                'students' => $this->getStudents(),
                'animals' => $this->getAnimals(),
                'shades' => $this->getShades(),
                'institutions' => $this->getInstitutions()
            ]);
        }


    }


    public function actionUpdate(){
        $model = new Repairrequisitionline() ;
        $model->isNewRecord = false;
        $service = Yii::$app->params['ServiceName']['RepairRequisitionLine'];
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


        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Repairrequisitionline'],$model) ){

            $filter = [
                'Requisition_No' => $model->Requisition_No,
            ];
            $refresh = Yii::$app->navhelper->getData($service, $filter);
            $model->Key = $refresh[0]->Key;

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
                'students' => $this->getStudents(),
                'animals' => $this->getAnimals(),
                'shades' => $this->getShades(),
                'institutions' => $this->getInstitutions()
            ]);
        }

        return $this->render('update',[
            'model' => $model,
            'locations' => $this->getLocations(),
            'items' => $this->getItems(),
        ]);
    }

    public function actionDelete(){
        $service = Yii::$app->params['ServiceName']['RepairRequisitionLine'];
        $result = Yii::$app->navhelper->deleteData($service,Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!is_string($result)){
            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        }else{
            return ['note' => '<div class="alert alert-danger">Error Purging Record: '.$result.'</div>' ];
        }
    }

    public function actionSetquantity(){
        $model = new Repairrequisitionline();
        $service = Yii::$app->params['ServiceName']['RepairRequisitionLine'];

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
        $model = new Repairrequisitionline();
        $service = Yii::$app->params['ServiceName']['RepairRequisitionLine'];

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
        $model = new Repairrequisitionline();
        $service = Yii::$app->params['ServiceName']['RepairRequisitionLine'];

        $filter = [
            'Line_No' => Yii::$app->request->post('Line_No')
        ];
        $line = Yii::$app->navhelper->getData($service, $filter);
        // Yii::$app->recruitment->printrr($line);
        if(is_array($line)){
            Yii::$app->navhelper->loadmodel($line[0],$model);
            $model->Key = $line[0]->Key;
            $model->No = Yii::$app->request->post('No');

        }

        $result = Yii::$app->navhelper->updateData($service,$model);

        return $result;

    }


    /*Get Locations*/

    public function getLocations(){
        $service = Yii::$app->params['ServiceName']['Locations'];
        $filter = [];
        $result = \Yii::$app->navhelper->getData($service, $filter);

        $arr = [];
        if(is_array($result))
        {
            foreach($result as $res)
            {

                if(!empty($res->Name)){
                    $arr[] = [
                        'Code' => $res->Code,
                        'Name' => $res->Name
                    ];
                }

            }
        }
        return ArrayHelper::map($arr,'Code','Name');
    }

    /*Get Items*/

    public function getItems(){
        $service = Yii::$app->params['ServiceName']['Items'];
        $filter = [];
        $result = \Yii::$app->navhelper->getData($service, $filter);



        $arr = [];
        if(is_array($result))
        {
            foreach($result as $res)
            {

                if(!empty($res->Description)){
                    $arr[]= [
                        'No' => $res->No,
                        'Description' => $res->Description
                    ];
                }

            }
        }


        return ArrayHelper::map($arr,'No','Description');
    }

    /*  Students Dim 3*/
    public function getStudents(){
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 3
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);

        $arr = [];
        if(is_array($result))
        {
            foreach($result as $res)
            {

                if(!empty($res->Name)){
                    $arr[] = [
                        'Code' => $res->Code,
                        'Name' => $res->Name
                    ];
                }

            }
        }


        return ArrayHelper::map($arr,'Code','Name');
    }


    /* Get Shades 4 */

    public function getShades(){
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 4
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);



        $arr = [];
        if(is_array($result))
        {
            foreach($result as $res)
            {

                if(!empty($res->Name)){
                    $arr[] = [
                        'Code' => $res->Code,
                        'Name' => $res->Name
                    ];
                }

            }
        }


        return ArrayHelper::map($arr,'Code','Name');
    }

    /* Get Animals 5*/

    public function getAnimals(){
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 5
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);



        $arr = []; $i=0;
        if(is_array($result))
        {
            foreach($result as $res)
            {

                $i++;
                if(!empty($res->Name)){
                    $arr[$i] = [
                        'Code' => $res->Code,
                        'Name' => $res->Name
                    ];
                }

            }
        }



        return ArrayHelper::map($arr,'Code','Name');
    }


    /*Dim 6*/

    public function getInstitutions(){
        $service = Yii::$app->params['ServiceName']['Institutions'];

        $filter = [

        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        //return $result;


        $arr = [];
        if(is_array($result))
        {
            foreach($result as $res)
            {

                if(!empty($res->Name)){
                    $arr[] = [
                        'Code' => $res->No,
                        'Name' => $res->Name
                    ];
                }

            }
        }



        return ArrayHelper::map($arr,'Code','Name');
    }




    public function actionView($ApplicationNo){
        $service = Yii::$app->params['ServiceName']['leaveApplicationCard'];
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