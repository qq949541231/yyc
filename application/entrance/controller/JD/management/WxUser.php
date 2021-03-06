<?php


namespace app\entrance\controller\JD\management;


use app\entrance\base\BaseController;

use app\entrance\model\User;

use think\Request;


/**
 * @title 小程序端接口
 * @description 接口说明
 * @header name:token type:varchar require:1 default:1 other:'' desc:数据表明
 */
class WxUser extends BaseController

{


    //用户查询
    
    public function user_select($request){

        $length = $request->param('length');

        $offset = $request->param('offset');

        $keyword = $request->param('keyword');

        if($keyword != null){

            //模糊搜索

            $data = User::where('nickname|mobile','like',"%".$keyword."%")

            ->limit($offset,$length)->select();

            $count = $data->count();

        }

        else{

            //limit分页

            $data = User::limit($offset,$length)->select();

            $count = User::count();

        }

        return $this->jsonSuccessData($data,$count);

    } 




    
    //删除用户数据




    
    public function user_delete($request){
    
        $a = $request->param('id');
    
        $message  = User::where('id',$a)->delete();
    
        return $this->jsonSuccessData($message);
    
    }


    //查找单条记录
    
    public function user_find($request){
    
        $id = $request->param('id');
    
        $message = User::where('id', $id)->find();
    
        return $this->jsonSuccessData($message);
    
    }



    //更新修改用户数据

    public function user_update($request){
    
        $id = $request->param('id');
    
        $nickname = $request->param('nickname');
    
        $avatar = $request->param('avatar');
    
        $gender = $request->param('gender');
    
        $city = $request->param('city');
    
        $mobile = $request->param('mobile');
    
        $message = User::where('id', $id)->update([
    
            'nickname' => $nickname,
    
            'avatar'=>$avatar,
    
            'gender' => $gender,
    
            'city'=>$city,
    
            'mobile' => $mobile,
    
        ]);
        
        return $this->jsonSuccessData($message);

    }


    //增加用户数据

    public function user_create($request){
  
        $nickname = $request->param('nickname');
  
        $avatar = $request->param('avatar');
  
        $gender = $request->param('gender');
  
        $city = $request->param('city');
  
        $mobile = $request->param('mobile');
  
        $data = [
  
            'nickname' => $nickname,
  
            'avatar'=>$avatar,
  
            'gender' => $gender,
  
            'city'=>$city,
  
            'mobile'=>$mobile,
  
        ];
  
        $message = User::insert($data);
  
        return $this->jsonSuccessData($message);
  
    }

}