<?php


namespace app\entrance\controller\msj\resource;

use app\entrance\base\BaseController;


class Upload extends BaseController
{
    /**
     *  保存文件配置
     */
    public $imageConfig = [
        'size' => 5242880,
        'ext'  => 'jpg,png,jpeg,JPG'
    ];
    public $videoConfig = [
        'size' => 5242880,
        'ext'  => 'mp4,rmvb,wmv,3gp,AVI,MPEG,MKV'
    ];
    public $mp3Config = [
        'size' => 10485760,
        'ext'  => 'mp3'
    ];

    public function getUploadConfig($type)
    {
        $config = null;
        switch ($type) {
            case 'image':
                $config = $this->imageConfig;
                break;
            case 'video':
                $config = $this->videoConfig;
                break;
            case 'mp3':
                $config = $this->mp3Config;
                break;
        }
        return $config;
    }

    public function upload($request)
    {
        //获取文件 
        $file = $request->file('resource');
        $type = $request->param('type');

        if ($file) {
            $path = $this->saveResource($type);
            $uploadConfig = $this->getUploadConfig($type);
           
            $info = $file->validate($uploadConfig)->move($path['PATH']);
            if ($info) {
                $resourUrl=$path['URL'].date('Ymd').  "/" . $info->getFilename();
                return $this->jsonSuccessData([
                    'url' => $resourUrl
                ]);
            } 
            else {
                return $this->jsonData(ErrCode_UPLOAD_IMAGE,ErrMsg_UPLOAD_IMAGE);
            }
        }
    }

    public function uploadBase64($request)
    {   
        $img=$request->param('img');
        $type = $request->param('type')?:"image";
        $img = explode(',',$img);
        $img = $img[1];
        $path = $this->saveResource($type);
        $imgName=time().rand(1111,9999).'.png';

        $imageSrc=$path['PATH'].date('Ymd').  "/".$imgName;
        $resourUrl=$path['URL'].date('Ymd').  "/".$imgName;

        $file_put= file_put_contents($imageSrc, base64_decode($img));
        if($file_put){
            return $this->jsonSuccessData([
                'url' => $resourUrl
            ]);
        }else{
            return $this->jsonData(ErrCode_UPLOAD_IMAGE,ErrMsg_UPLOAD_IMAGE);
        }
 
    }

    //图片上传
    public function imageUploadSome($request){
        $file=$request->file('');
    }
}
