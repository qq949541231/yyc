<?php


namespace app\entrance\controller\JD\management;


use app\entrance\base\BaseController;

use app\entrance\model\Role;

use think\Request;


/**
 * @title 小程序端接口
 * @description 接口说明
 * @header name:token type:varchar require:1 default:1 other:'' desc:数据表明
 */
class WxRole extends BaseController

{


    //查找权限数据
    
    public function role_select($request){
    
        $length = $request->param('length');
    
        $offset = $request->param('offset');
    
        $keyword = $request->param('keyword');
    
        if($keyword != null){
    
            //模糊搜索
    
            $data = Role::where('name','like',"%".$keyword."%")
    
            ->limit($offset,$length)->select();
    
            $count = $data->count();
    
        }
    
        else if($length != null){
    
            //limit分页
    
            $data = Role::limit($offset,$length)->select();
    
            $count = Role::count();
    
        }
    
        else{
    
            $data = Role::select();
    
            $count = Role::count();
    
        }
    
        return $this->jsonSuccessData($data,$count);  
    
    }


    //删除权限数据


    
    public function role_delete($request){
    
        $a = $request->param('id');
    
        $message  = Role::where('id',$a)->delete();
    
        return $this->jsonSuccessData($message);
    
    }


    
    //查找单条记录
    
    public function role_find($request){
    
        $id = $request->param('id');
    
        $message = Role::where('id', $id)->find();
    
        return $this->jsonSuccessData($message);
    
    }
    

    //更新修改权限数据

    
    public function role_update($request){
    
        $id = $request->param('id');
    
        $name = $request->param('name');
    
        $admin_route = $request->param('admin_route');
    
        $message = Role::where('id', $id)->update([
    
            'name' => $name,
    
            'admin_route' => $admin_route,
    
        ]);
        
        return $this->jsonSuccessData($message);
    
    }


    //增加权限数据

    public function role_create($request){
    
        $name = $request->param('name');
    
        $admin_route = $request->param('admin_route');
    
        $data = [
    
            'name' => $name,
    
            'admin_route' => $admin_route,
    
        ];
    
        $message = Role::insert($data);
    
        return $this->jsonSuccessData($message);
    
    }

}