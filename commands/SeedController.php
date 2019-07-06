<?php
namespace app\commands;

use yii\console\Controller;
use app\models\User;
use app\models\CropCategory;
use Yii;

class SeedController extends Controller
{
    public function actionIndex()
    {
//        $faker = \Faker\Factory::create();
        $user_data1['id'] = 100;
        $user_data1['username'] = 'admin';
        $user_data1['password'] = 'admin';
        $user_data1['authKey'] = 'test100key';
        $user_data1['accessToken'] = '100-token';

        $user_data2['id'] = 101;
        $user_data2['username'] = 'demo';
        $user_data2['password'] = 'demo';
        $user_data2['authKey'] = 'test101key';
        $user_data2['accessToken'] = '101-token';

        $category_data1['category_name'] = "Cereal";
        $category_data2['category_name'] = "Root Tubers";
        $category_data3['category_name'] = "Other";

        $this->addUser($user_data1);
        $this->addUser($user_data2);
        $this->addCropCategory($category_data1);
        $this->addCropCategory($category_data2);
        $this->addCropCategory($category_data3);



    }

    public function addUser($user_data) {
        /*$user = new User();

        $user->id          = $user_data['id'];
        $user->username    = $user_data['username'];
        $user->password    = $user_data['password'];
        $user->authKey     = $user_data['authKey'];
        $user->accessToken = $user_data['accessToken'];

        $user->save();*/

        Yii::$app->db->createCommand()->insert('users', [
            'username'     => $user_data['username'],
            'password'     => $user_data['password'],
            'authKey'      => $user_data['authKey'],
            'accessToken'  => $user_data['accessToken'],
        ])->execute();
    }

    public function addCropCategory($crop_category_data){
        $crop_category = new CropCategory();
        $crop_category->category_name = $crop_category_data['category_name'];
        $crop_category->save();
    }
}