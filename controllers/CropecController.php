<?php
namespace app\controllers;

use app\models\CropCategory;
use yii\rest\Controller;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;

use Yii;

use app\models\Crop;

class CropecController extends Controller{
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

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Authorization");
        header('Content-type: ' . $content_type);
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
                 'Access-Control-Request-Headers' => ['*'],
            ],
            'actions' => [
                'delete' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['OPTIONS', 'DELETE', 'HEAD'],
                    'Access-Control-Request-Headers' => ['*'],
                ],
                'create' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['OPTIONS', 'POST', 'HEAD'],
                    'Access-Control-Request-Headers' => ['*'],
                ],
                'update' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['OPTIONS', 'PUT', 'HEAD'],
                    'Access-Control-Request-Headers' => ['*'],
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
                'delete' => ['OPTIONS', 'DELETE']
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
        $params = $request->getBodyParams();

        $crop                   = new Crop();
        $crop->crop_name        = $params['name'];
        $crop->crop_description = $params['description'];
        $crop->crop_category_id = $params['category']; //check if it exists
        $status = $crop->save();

        $response = $this->createResponse($status);

        return $response;
    }

    /**
     *  Update the specified resource
     */
    public function actionUpdate() {
        $request = Yii::$app->request;
        $params  = $request->getBodyParams();
        $uid     = $params['uid']; // Need to get the ID of the crop

        $crop = Crop::findOne($uid);

        if(!$crop){
            $response = $this->createResponse(0);
            return $response;
        };

        $crop->crop_name        = $params['name'];
        $crop->crop_description = $params['description'];

        //Check if the category exists first to prevent constraint violations
        $response = $this->categoryCheck($params['category']);

        if ($response['status'] == 0){
            return $response;
        }

        $crop->crop_category_id = $params['category'];
        $status = $crop->save();

        $response = $this->createResponse($status);
        return $response;
    }


    /**
     *  Delete the specified resource
     */
    public function actionDelete() {

        $request = Yii::$app->request;
        $params  = $request->getQueryParams();
        $uid     = $params['uid'];

        $crop = Crop::findOne($uid);

        if(!$crop){
            $response = $this->createResponse(0);
            return $response;
        };

        $status = $crop->delete();

        $response = $this->createResponse($status);
        return $response;
    }

    /**
     * Create a response to the browser after a request
     * @param $status
     * @return array
     */
    public function createResponse($status){
        $response = ['status' => 1, 'message' => 'Success'];

        if ($status != true || $status != 1 ){
            $response['status']  = 0;
            $response['message'] = "There was a problem performing the specified operation";
        }

        return $response;
    }

    public  function categoryCheck($category_id) {
        $category = CropCategory::findOne($category_id);
//        $response = [];
        if(!$category){
            $response = $this->createResponse(0);
        }else {
            $response = $this->createResponse(1);
        }

        return $response;
    }

}