<?php


namespace app\entrance\controller\JD\management;


use app\entrance\base\BaseController;

use app\entrance\model\Admin;

use think\Request;


/**
 * @title 小程序端接口
 * @description 接口说明
 * @header name:token type:varchar require:1 default:1 other:'' desc:数据表明
 */
class WxAdmin extends BaseController

{

//查询管理员所有用户信息
    public function admin_select(Request $request){
        
        $length = $request->param('length');
    
        $offset = $request->param('offset');
    
        $keyword = $request->param('keyword');
    
        if($keyword != null){
    
            //模糊搜索
    
            $data = Admin::where('username|name|mobile','like',"%".$keyword."%")
    
            ->limit($offset,$length)->select();
    
            $count = $data->count();
    
        }
    
        else{
    
            //limit分页
    
            $data = Admin::limit($offset,$length)->select();
    
            $count = Admin::count();
    
        }
    
        return $this->jsonSuccessData($data,$count);
    
        
    }


    //管理员登录
    public function login($request){
        
        $username = $request->param('username');
    
        $password = $request->param('password');
    
        $data = Admin::where('username',$username)->where('password',$password)->find();
    
        return $this->jsonSuccessData($data);
    
    }


    //删除管理员数据


    public function delete($request){
    
        $a = $request->param('id');
    
        $message  = admin::where('id',$a)->delete();
    
        return $this->jsonSuccessData($message);
    
    }

    //增加admin数据

    public function in($request){
    
        // $id = $request->param('id');
    
        $username = $request->param('username');
    
        $password = $request->param('password');
    
        $role_id = $request->param('role_id');
    
        $user_id = $request->param('user_id');
    
        $name = $request->param('name');
    
        $mobile = $request->param('mobile');
    
        $remake = $request->param('remake');
    
        $data = [
    
            'username' => $username,
    
            'password'=>$password,'role_id' => $role_id,
    
            'user_id'=>$user_id,'name' => $name,
    
            'mobile'=>$mobile,'remake' => $remake,
    
        ];
    
        // $da = Admin::select();
    
        Admin::insert($data);
    
        // return $this->jsonSuccessData($da);
    
    }

    //增加管理员数据

    public function admin_create($request){
    
        $username = $request->param('username');
    
        $password = $request->param('password');
    
        $role_id = $request->param('role_id');
    
        $user_id = $request->param('user_id');
    
        $name = $request->param('name');
    
        $mobile = $request->param('mobile');
    
        $remake = $request->param('remake');
    
        $data = [
    
            'username' => $username,
    
            'password'=>$password,
    
            'role_id' => $role_id,
    
            'user_id'=>$user_id,
    
            'name' => $name,
    
            'mobile'=>$mobile,
    
            'remake' => $remake,
    
        ];
    
        $message = Admin::insert($data);
    
        return $this->jsonSuccessData($message);
    
    }

    

    //管理员单条查找

    public function admin_find($request){
    
        $id = $request->param('id');
    
        $message = Admin::where('id', $id)->find();
    
        return $this->jsonSuccessData($message);
    
    }



    //更新修改管理员数据

    public function admin_update($request){
    
        $id = $request->param('id');
    
        $username = $request->param('username');
    
        $password = $request->param('password');
    
        $role_id = $request->param('role_id');
    
        $user_id = $request->param('user_id');
    
        $name = $request->param('name');
    
        $mobile = $request->param('mobile');
    
        $remake = $request->param('remake');
    
        $message = Admin::where('id', $id)->update([
    
            'username' => $username,
    
            'password'=>$password,
    
            'role_id' => $role_id,
    
            'user_id'=>$user_id,
    
            'name' => $name,
    
            'mobile'=>$mobile,
    
            'remake' => $remake,
    
        ]);
        
        return $this->jsonSuccessData($message);

    }

}