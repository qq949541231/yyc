<?php
/*
 * @Author: your name
 * @Date: 2020-06-15 09:38:06
 * @LastEditTime: 2020-06-16 10:16:10
 * @LastEditors: your name
 * @Description: In User Settings Edit
 * @FilePath: \JD618\route\route.php
 */ 
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------



//组件依赖
Route::rule('component', 'index/Index/component');
//目录结构
Route::rule('directory', 'index/Index/directory');


Route::rule('entrance/:version/no/:method', 'entrance/Entrance/noentrance')->allowCrossDomain();

//entrance 事件入口
Route::rule('entrance/:version/:method', 'entrance/Entrance/entrance')->middleware('JwtApi')->allowCrossDomain();

return [
    // //展示页
    // 'index' => 'index/Index/index',
    // //组件依赖
    // 'component' => 'index/Index/component',
    // //目录结构
    // 'directory' => 'index/Index/directory',
    // //entrance 事件入口
    // 'entrance/:version/:method' => 'entrance/Entrance/entrance', //method--访问控制器的api路径(entrance\Method\methodApi)
];
