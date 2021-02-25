<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/22/2020
 * Time: 2:53 PM
 */

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\BadRequestHttpException;

use frontend\models\Leave;
use yii\web\HttpException;
use yii\web\Response;
use kartik\mpdf\Pdf;

class P9Controller extends Controller
{

    public function beforeAction($action) {
        $this->enableCsrfValidation = ($action->id !== "index"); // <-- here
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup','index'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout','index'],
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
                'only' => ['getleaves'],
                'formatParam' => '_format',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    //'application/xml' => Response::FORMAT_XML,
                ],
            ]
        ];
    }

    public function actionIndex(){

        $service = Yii::$app->params['ServiceName']['PortalReports'];

        if(Yii::$app->request->post()){
            $data = [
                'empNo' => Yii::$app->user->identity->{'Employee_No'},
                'selectedYear' =>Yii::$app->request->post('p9year'),
             ];
            $path = Yii::$app->navhelper->PortalReports($service,$data,'IanGeneratep9');
            if(is_file($path['return_value'])){
                $binary = file_get_contents($path['return_value']); //fopen($path['return_value'],'rb');
                $content = chunk_split(base64_encode($binary));
                //delete the file after getting it's contents --> This is some house keeping
                unlink($path['return_value']);
                //Yii::$app->recruitment->printrr($content);
                return $this->render('index',[
                    'report' => true,
                    'content' => $content,
                    'p9years' =>  $this->getP9years()
                ]);
            }
            // no report scenario
            return $this->render('index',[
                'report' => false,
                'p9years' => $this->getP9years(),
                'content' => null,
            ]);
        }
        return $this->render('index',[
            'report' => false,
            'p9years' => $this->getP9years(),
            'content' => true,
        ]);

    }

    public function getP9years(){
        $service = Yii::$app->params['ServiceName']['P9YEARS'];

        $periods = \Yii::$app->navhelper->getData($service);
        if(is_array($periods)){
            krsort( $periods);//sort  keys in descending order

            $res = [];
            foreach($periods as $p){
                $res[] = [
                    'Year' => $p->Period_Year,
                    'desc' => $p->Period_Year
                ];
            }
            return ArrayHelper::map($res,'Year','desc');
        }else{
            return [];
        }


    }



}