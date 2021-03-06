<?php


namespace app\entrance\controller\JD\management;


use app\entrance\base\BaseController;

use app\entrance\model\Banner;

use think\Request;



class WxBanner extends BaseController

{



    
    /**
     * 查找banner数据
     */

    //查找Banner数据
    
    public function banner_select($request){
    
        $length = $request->param('length');
    
        $offset = $request->param('offset');
    
        $keyword = $request->param('keyword');
    
        if($keyword != null){
    
            //模糊搜索
    
            $data = Banner::where('name','like',"%".$keyword."%")
    
            ->limit($offset,$length)->select();
    
            $count = $data->count();
    
        }
    
        else if($length != null){
    
            //limit分页
    
            $data = Banner::limit($offset,$length)->select();
    
            $count = Banner::count();
    
        }
    
        else{
    
            $data = Banner::where('status','1')->select();
    
            $count = $data->count();
    
        }
    
        return $this->jsonSuccessData($data,$count);  
    
    }


    //删除Banner数据


    
    public function banner_delete($request){
    
        $a = $request->param('id');
    
        $message  = Banner::where('id',$a)->delete();
    
        return $this->jsonSuccessData($message);
    
    }


    
    //查找单条记录
    
    public function banner_find($request){
    
        $id = $request->param('id');
    
        $message = Banner::where('id', $id)->find();
    
        $status = Banner::where('id', $id)->value('status');
    
        if($status == '1'){
    
            $swit = true;
    
        }
    
        else if($status == '2'){
    
            $swit = false;
    
        }
    
        return $this->jsonSuccessData($message,$swit);
    
    }
    

    //更新修改Banner数据

    public function banner_update($request){
    
        $id = $request->param('id');
    
        $name = $request->param('name');
    
        $url = $request->param('url');
    
        $swit = $request->param('swit');
    
        if($swit == 'true'){
    
            $status = '1';
    
        }
    
        else if($swit == 'false'){
    
            $status = '2';
    
        }
    
        $message = Banner::where('id', $id)->update([
    
            'name' => $name,
    
            'url' => $url,
    
            'status' => $status,
    
        ]);
        
        return $this->jsonSuccessData($message);
    
    }


    //增加Banner数据

    public function banner_create($request){
        $name = $request->param('name');
        $url = $request->param('url');
        $swit = $request->param('swit');
        $create_time = date('Y-m-d H:i:s',time());;
        if($swit == 'true'){
            $status = '1';
        }
        else if($swit == 'false'){
            $status = '2';
        }
        $data = [
            'name' => $name,
            'url' => $url,
            'status' => $status,
            'create_time' => $create_time,
           ];
        $message = Banner::insert($data);
        return $this->jsonSuccessData($message);
    }

}