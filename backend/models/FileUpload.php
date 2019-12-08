<?php
/**
 * Created by PhpStorm.
 * User: Miriskava
 * Date: 03.12.2019
 * Time: 9:30
 */

namespace backend\models;


use yii\base\Model;
use yii\web\UploadedFile;

class FileUpload extends Model
{
    public $file;

    public function rules()
    {
        return [
            [['file'],'required'],
            [['file'],'file','extensions'=>'mp3,mp4']
        ];
    }

    public function uploadFile(UploadedFile $f){
        $this->file=$f;
        $filename=md5($f->baseName).'.'.$f->extension;
        $f->saveAs('https://vipvolpi.host/admin/resource/'.$filename);
        return $filename;
    }
}