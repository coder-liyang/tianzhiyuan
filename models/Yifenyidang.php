<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yifenyidang".
 *
 * @property string $id
 * @property integer $year
 * @property string $province
 * @property string $wl
 * @property integer $score
 * @property integer $num
 * @property integer $num_total
 */
class Yifenyidang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yifenyidang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['year', 'province', 'wl', 'score'], 'required'],
            [['year', 'score', 'num', 'num_total'], 'integer'],
            [['province'], 'string', 'max' => 32],
            [['wl'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year' => 'Year',
            'province' => 'Province',
            'wl' => 'Wl',
            'score' => 'Score',
            'num' => 'Num',
            'num_total' => 'Num Total',
        ];
    }
}
