<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "holiday".
 *
 * @property int $id
 * @property int $user_id
 * @property int $approve
 * @property string $date_start
 * @property string $date_end
 */
class Holiday extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'holiday';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'date_start', 'date_end'], 'required'],
            [['user_id', 'approve'], 'integer'],
            [['date_start', 'date_end'], 'date', 'format' => 'php:Y-m-d'],
            ['date_end', 'compare', 'compareAttribute' => 'date_start', 'operator' => '>=', 'type' => 'date',
                'message' => 'Дата окончания отпуска должна быть равна или позже даты начала'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'date_start' => 'Дата начала отпуска',
            'date_end' => 'Дата окончания отпуска',
            'approve' => 'Утвержден',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
