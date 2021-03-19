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
use frontend\models\Probationkpi;
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

class ProbationKpiController extends Controller
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
                        'actions' => ['logout','index','create','update','delete'],
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
                'only' => [''],
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

    public function actionCreate($Employee_No, $Appraisal_No,$KRA_Line_No){

        $model = new Probationkpi();
        $service = Yii::$app->params['ServiceName']['ProbationKPIs'];
        $model->Appraisal_No = $Appraisal_No;
        $model->Employee_No = $Employee_No;
        $model->KRA_Line_No = $KRA_Line_No;

        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Probationkpi'],$model)  && $model->validate() ){


            $result = Yii::$app->navhelper->updateData($service,$model);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if(is_object($result)){

                return ['note' => '<div class="alert alert-success">Record Added Successfully. </div>'];

            }else{

                return ['note' => '<div class="alert alert-danger">Error : '.$result.'</div>' ];

            }

        }//End Saving experience

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('create', [
                'model' => $model,
                'ratings' => $this->getRatings(),
            ]);
        }

        return $this->render('create',[
            'model' => $model,
        ]);
    }


    /*Set/commit Weight*/


    public function actionSetweight(){
        $model = new Probationkpi();
        $service = Yii::$app->params['ServiceName']['ProbationKPIs'];

        $model->Weight = Yii::$app->request->post('Weight');
        $model->Objective = Yii::$app->request->post('Objective');
        $model->Appraisal_No = Yii::$app->request->post('Appraisal_No');
        $model->Employee_No = Yii::$app->request->post('Employee_No');
        $model->KRA_Line_No = Yii::$app->request->post('KRA_Line_No');
        $model->Key = Yii::$app->request->post('Key');


        $result = Yii::$app->navhelper->updateData($service,$model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;

    }


    /*Commit KPI*/

    public function actionSetkpi(){
        $model = new Probationkpi();
        $service = Yii::$app->params['ServiceName']['ProbationKPIs'];

        /*Do initial request*/
        
        $model->Objective = Yii::$app->request->post('Objective');
        $model->Appraisal_No = Yii::$app->request->post('Appraisal_No');
        $model->Employee_No = Yii::$app->request->post('Employee_No');
        $model->KRA_Line_No = Yii::$app->request->post('KRA_Line_No');

        $request = Yii::$app->navhelper->postData($service, $model);
        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;
        return $request; 

    }


    public function actionUpdate(){
        $model = new Probationkpi() ;
        $model->isNewRecord = false;
        $service = Yii::$app->params['ServiceName']['ProbationKPIs'];
        $filter = [
            'KRA_Line_No' => Yii::$app->request->get('KRA_Line_No'),
            'Employee_No' => Yii::$app->request->get('Employee_No'),
            'Appraisal_No' => Yii::$app->request->get('Appraisal_No'),
            'Line_No' => Yii::$app->request->get('Line_No'),
        ];
        $result = Yii::$app->navhelper->getData($service,$filter);

        if(is_array($result)){
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($result[0],$model) ;
        }else{
            Yii::$app->recruitment->printrr($result);
        }

        //  Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Probationkpi'],$model)
        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Probationkpi'],$model) && $model->validate() ){
            $result = Yii::$app->navhelper->updateData($service,$model);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if(!is_string($result)){

                return ['note' => '<div class="alert alert-success">KPI Updated Successfully. </div>' ];
            }else{

                return ['note' => '<div class="alert alert-danger">Error Updating KPI: '.$result.'</div>'];
            }

        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('update', [
                'model' => $model,
                'ratings' => $this->getRatings(),
            ]);
        }

        return $this->render('update',[
            'model' => $model,
            'ratings' => $this->getRatings(),
        ]);
    }

    public function actionDelete(){
        $service = Yii::$app->params['ServiceName']['ProbationKPIs'];
        $result = Yii::$app->navhelper->deleteData($service,Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!is_string($result)){
            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        }else{
            return ['note' => '<div class="alert alert-danger">Error Purging Record: '.$result.'</div>' ];
        }
    }

    public function getRatings()
    {
          $service = Yii::$app->params['ServiceName']['AppraisalRating'];
          $data = Yii::$app->navhelper->getData($service, []);
          $result = Yii::$app->navhelper->refactorArray($data,'Rating','Rating_Description');
          return $result;
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