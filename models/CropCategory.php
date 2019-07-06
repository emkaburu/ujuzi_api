<?php
namespace app\models;

use yii\base\Model;
use yii\db\ActiveRecord;
use app\models\Crop;

class CropCategory extends ActiveRecord
{
    public $unique_id;
    public $name;
    public $description;

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return '{{crop_categories}}';
    }

    public function fields()
    {
        // Cast field names to not expose real DB column names
        return [
            'uid'  => 'id',
            'name'        => 'category_name',
            'description' => 'category_description'
        ];
    }

    public function getCrops(){
    
        return $this->hasMany(Crop::className(), ['crop_category_id' => 'id']);
    }


}