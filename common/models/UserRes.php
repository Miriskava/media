<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_res".
 *
 * @property int $id_user
 * @property int $id_res
 * @property int $request
 *
 * @property Resource $res
 * @property User $user
 */
class UserRes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_res';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_res'], 'required'],
            [['id_user', 'id_res'], 'integer'],
            [['request'],'boolean'],
            [['id_user', 'id_res'], 'unique', 'targetAttribute' => ['id_user', 'id_res']],
            [['id_res'], 'exist', 'skipOnError' => true, 'targetClass' => Resource::className(), 'targetAttribute' => ['id_res' => 'id_res']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_user' => 'Id User',
            'id_res' => 'Id Res',
            'request' => 'Request',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRes()
    {
        return $this->hasOne(Resource::className(), ['id_res' => 'id_res']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}
