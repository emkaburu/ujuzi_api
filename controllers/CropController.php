<?php
namespace app\controllers;

use yii\rest\Controller;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;

use Yii;

use app\models\Crop;

class CropController extends Controller{
    public $enableCsrfValidation = false;
    public $modelClass = 'app\models\Crop';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public static function allowedDomains() {
        return [
            '*',                        // star allows all domains
            'http://localhost:4200'
        ];
    }

    public function actionPreflight() {
        $content_type = 'application/json';
        $status = 200;

        // set the status
       /* $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        header($status_header);*/
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Authorization");
        header('Content-type: ' . $content_type);
    }

    public  function makeHeaders(){
        $headers = Yii::$app->response->headers;
        $headers->add("Access-Control-Allow-Origin", "*");
        $headers->add("Access-Control-Allow-Methods", "OPTIONS, GET, POST, PUT, DELETE");
        $headers->add("Content-type", "application/json");
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = ['options'];

        unset($behaviors['authenticator']);

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => static::allowedDomains(),
                'Access-Control-Request-Method' => ['OPTIONS', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD'],
                'Access-Control-Request-Headers' => ['Origin', 'X-Requested-With', 'Content-Type', 'accept', 'Authorization'],
                //
                // 'Access-Control-Request-Headers' => ['*'],
            ],
            'actions' => [
                'delete' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['OPTIONS', 'DELETE', 'HEAD'],
                    'Access-Control-Request-Headers' => ['Origin', 'X-Requested-With', 'Content-Type', 'accept', 'Authorization'],
                ],
                'create' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['OPTIONS', 'POST', 'HEAD'],
                    'Access-Control-Request-Headers' => ['Origin', 'X-Requested-With', 'Content-Type', 'accept', 'Authorization'],
                ],
            ]
        ];

        $behaviors['verbs'] = [
              'class' => \yii\filters\VerbFilter::className(),
              'actions' => [
                  'list-all'  => ['GET'],
                  'view'   => ['GET'],
                  'create' => ['OPTIONS', 'POST'],
                  'update' => ['OPTIONS', 'PUT'],
                  'delete' => ['OPTIONS', 'DELETE'],
//                  'delete' => ['DELETE'],
              ]
          ];

        return $behaviors;
    }


    /**
     * @return ActiveDataProvider
     * Get all crops
     */
    public function actionListAll(){

        $data = new ActiveDataProvider([
            'query' => Crop::find(),
        ]);

        return $data;
    }

    /**
     *  View the specified resource
     */
    public function actionView() {
        $request = Yii::$app->request;
        $uid = $request->getQueryParam('uid');

        $crop = Crop::findOne($uid);

        if($crop == null){
            return ['status' => 404, 'message' => 'That record does not exist in our DB'];
        }
        return $crop;
    }

    /**
     *  Create a new resource
     * @param yii\web\Request $request
     */
    public function actionCreate() {
        //Get request object
        $request = Yii::$app->request;

        // get all parameters
//        $params = $request->getQueryParams();
        $params = $request->getBodyParams();
//return $params['name'];
        $crop                   = new Crop();
        $crop->crop_name        = $params['name'];
        $crop->crop_description = $params['description'];
        $crop->crop_category_id = $params['category']; //check if it exists
        $status = $crop->save();

        $response = $this->createResponse($status);

        return $response;
    }

    /**
     *  Delete the specified resource
     */
    public function actionUpdate() {
        $request = Yii::$app->request;
        $params  = $request->getQueryParams();
        $uid     = $params['uid'];

        $crop = Crop::findOne($uid);
        $crop->crop_name        = $params['name'];
        $crop->crop_description = $params['description'];
        $crop->crop_category_id = $params['category'];
        $status = $crop->save();

        $response = $this->createResponse($status);
        return $response;
    }


    /**
     *  Delete the specified resource
     */
    public function actionDelete() {

//        $this->makeHeaders();

        $request = Yii::$app->request;
        $params  = $request->getQueryParams();
        $uid     = $params['uid'];

        $crop = Crop::findOne($uid);
        $status = $crop->delete();

        $response = $this->createResponse($status);
        return $response;
    }

    public function createResponse($status){
        $response = ['status' => 1, 'message' => 'Success'];

        if ($status != true || $status != 1 ){
            $response['status']  = 0;
            $response['message'] = "There was a problem performing the specified operation";
        }

        return $response;
    }

}