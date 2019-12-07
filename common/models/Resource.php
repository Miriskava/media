<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "resource".
 *
 * @property int $id_res
 * @property string $name
 * @property string $type
 * @property string $ganre
 * @property string $way
 * @property int $kol
 * @property int $acc
 *
 * @property UserRes[] $userRes
 * @property User[] $users
 */
class Resource extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resource';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type', 'ganre', 'way', 'kol'], 'required'],
            [['kol'], 'integer'],
            [['acc'],'boolean'],
            [['name'], 'string', 'max' => 255],
            [['type', 'ganre', 'way'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_res' => 'Id Res',
            'name' => 'Наименование',
            'type' => 'Тип',
            'ganre' => 'Жанр',
            'way' => 'Файл',
            'kol' => 'Kol',
            'acc' => 'Не доступна всем',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRes()
    {
        return $this->hasMany(UserRes::className(), ['id_res' => 'id_res']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'id_user'])->viaTable('user_res', ['id_res' => 'id_res']);
    }

    public function saveFile($filename)
    {
        $this->way=$filename;
        return $this->save(false);
    }
}
