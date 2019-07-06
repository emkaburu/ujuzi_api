<?php
namespace app\models;

//use yii\base\Model;
use yii\db\ActiveRecord;
use app\models\CropCategory;

class Crop extends ActiveRecord
{
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return '{{crops}}';
    }

    public function fields()
    {
        // Cast field names to not expose real DB column names
        return [
            'uid'  => 'id',
            'name'        => 'crop_name',
            'description' => 'crop_description',
            'created' => 'created_at',
            'updated' => 'updated_at',

        ];
    }

    public function getCropCategory(){
        return $this->hasOne(CropCategory::className(), ['id' => 'crop_category_id']);
    }

    public function extraFields()
    {
        return ['cropCategory'];
    }
}