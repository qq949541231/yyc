<?php
namespace app\entrance\base;

use Exception;

class BaseTool{

	private static $instance= null;

    private function __construct(){}

    static public function getInstance(){
        if(self::$instance==null)
        {
            self::$instance=new BaseTool();
        }
        return self::$instance;
	}
	
	//保存图片
    public function SaveImage($filename, $file_savepath, $url,$imageName,$type, $pic_code='')
    {
        if ($type == 'Binary') //二进制图片
        {
            $imageUrl=null;
            $image = $pic_code; //图片二进制数据		
            $file = fopen($file_savepath . $filename, "w"); //打开文件准备写入
            fwrite($file, $image); //写入
            fclose($file); //关闭

            $filePath = $file_savepath . $filename;

            //图片是否存在
            if (file_exists($filePath)) {
                $imageUrl=$url.$imageName;
                return $imageUrl;
            } else {
                return $imageUrl;
            }
        }
    }

	/* 创建文件夹-递归创建-返回创建后文件路径----用户相册保存路径 */
	public function Gemini_CreateFile($type="image",$filename='Other') { //文件名称   上传类型
		$date=date("Y-m-d");
		$path = array ();

		if(!strcmp($type,'image')){ 
			$path ['URL'] ="static/".$filename."/image/"; // 存储在数据库的地址
			$file_Path = "static/".$filename."/image";
		}
		if(!strcmp($type,'file')){
			$path ['URL'] ="static/".$filename."/file_video/"; // 存储在数据库的地址
			$file_Path = "static/".$filename."/file_video";		
		}
		

		if (! file_exists ( $file_Path )) {
			// 用户文件夹不存在
			$file_Path_Noexist = $file_Path;
			mkdir ( $file_Path_Noexist, 0777, true );
			$path ['PATH'] = $file_Path_Noexist . "/";
		} else {
			// 用户文件夹存在
			$file_Path_Exist = $file_Path;
			// mkdir ( $file_Path_Exist );
			$path ['PATH'] = $file_Path_Exist . "/";
		}
		return $path;
	}
	
	// 删除目录及目录下的文件
	public function Gemini_DeleteFile($dir) {
		// 先删除目录下的文件------目录下无子文件夹，直接删除文件(若有子目录要用is_dir判断是否还有根目录)
		$dh = opendir ( $dir );
		while ( $file = readdir ( $dh ) ) {
			if ($file != "." && $file != "..") {
				$fullpath = $dir . "/" . $file;
				unlink ( $fullpath );
			}
		}
		closedir ( $dh );
		// 删除当前文件夹：
		if (rmdir ( $dir ))
			return true;
		else
			return false;
	}
	
	// 删除目录下的子文件
	public function Gemini_DeleteChildFile($dir) {
		// 先删除目录下的文件------目录下无子文件夹，直接删除文件(若有子目录要用is_dir判断是否还有根目录)
		$dh = opendir ( $dir );
		while ( $file = readdir ( $dh ) ) {
			if ($file != "." && $file != "..") {
				$fullpath = $dir . "/" . $file;
				unlink ( $fullpath );
			}
		}
		closedir ( $dh );
	}

	/** http get 请求*/
	public  function Gemini_GetReq($url, $param = array()) {
		if (! is_array ( $param )) {
			throw new Exception ( "参数必须为array" );
		}
		$p = '';
		foreach ( $param as $key => $value ) {
			$p = $p . $key . '=' . $value . '&';
		}
		if (preg_match ( '/\?[\d\D]+/', $url )) { // matched ?c
			$p = '&' . $p;
		} else if (preg_match ( '/\?$/', $url )) { // matched ?$
			$p = $p;
		} else {
			$p = '?' . $p;
		}
		$p = preg_replace ( '/&$/', '', $p );
		$url = $url . $p;
		$httph = curl_init ( $url );
		curl_setopt ( $httph, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt ( $httph, CURLOPT_SSL_VERIFYHOST, 2 );
		curl_setopt ( $httph, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $httph, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)" );
		
		curl_setopt ( $httph, CURLOPT_RETURNTRANSFER, 1 );
		// curl_setopt ( $httph, CURLOPT_HEADER, 1 );
		$rst = curl_exec ( $httph );
		curl_close ( $httph );
		return $rst;
	}
	/**http post 请求 */
	public function Gemini_PostReq($url, $param) {
		// if (! is_array ( $param )) {
		// throw new Exception ( "参数必须为array" );
		// }
		$httph = curl_init ( $url );
		curl_setopt ( $httph, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt ( $httph, CURLOPT_SSL_VERIFYHOST, 2 );
		curl_setopt ( $httph, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $httph, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)" );
		curl_setopt ( $httph, CURLOPT_POST, 1 ); // 设置为POST方式
		curl_setopt ( $httph, CURLOPT_POSTFIELDS, $param );
		curl_setopt ( $httph, CURLOPT_RETURNTRANSFER, 1 );
		// curl_setopt ( $httph, CURLOPT_HEADER, 1 );
		$rst = curl_exec ( $httph );
		curl_close ( $httph );
		return $rst;
	}
  /*http 物流*/
  public function Gemin_WULIUGet($querys,$appcode,$path){//参数  appcode API访问后缀
		$host = "http://wuliu.market.alicloudapi.com";//api访问链接
		//$path = "/kdi";//API访问后缀
		$method = "GET";

		$headers = array();
		array_push($headers, "Authorization:APPCODE " . $appcode);
		// $querys =;  //参数写在这里
		$bodys = "";
		$url = $host . $path . "?" . $querys;//url拼接
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_FAILONERROR, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		//curl_setopt($curl, CURLOPT_HEADER, true); 如不输出json, 请打开这行代码，打印调试头部状态码。
		//状态码: 200 正常；400 URL无效；401 appCode错误； 403 次数用完； 500 API网管错误
		if (1 == strpos("$".$host, "https://"))
		{
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		}
		return json_decode(curl_exec($curl));
	}

   /**
	* 获取地址 
	* $longitude 经度
	* $latitude 纬度
	*/
   public  function getaddress($longitude,$latitude,$url,$key){
	   $location=$latitude.','.$longitude;
	   $param=array(
		   'location'=>$location,
		   'key'=>$key,
		   'get_poi'=>0
	   );
	   $address_object=json_decode($this->Gemini_GetReq($url,$param),true);
		   return $address_object;
   }
   /**
	* 用地址获取经 纬度  
	* $address  地址
	*/
   public  function getlocation($address,$url,$key){
		//地址
		$param=array(
			'address'=>$address,
			'key'=>$key,
			//   'region'=>'中山市' //提示可以考虑自己设置先
		);
		$address_object=json_decode($this->Gemini_GetReq($url,$param),true);
		return $address_object;
   }
   /**
	* 获取距离  （直线距离） 米 两经纬度距离
	* latitude1
	* longitude1
	* 
	* latitude2
	* longitude2
	*/
   public static function getdistance($data){//经度,纬度
		   // 将角度转为狐度 
		   $radLat1 = deg2rad($data['latitude1']); //deg2rad()函数将角度转换为弧度
		   $radLng1 = deg2rad($data['longitude1']);
		   
		   $radLat2 = deg2rad($data['latitude2']);
		   $radLng2 = deg2rad($data['longitude2']);

		   $a = $radLat1 - $radLat2;
		   $b = $radLng1 - $radLng2;
		   $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
		   return $s;
	 
   }
   /**
	* 计算某个经纬度的周围某段距离的正方形的四个点
	* 通过获取4个点，从而获取到经度的最大值与最小值已经纬度的最大最小值，通过数据库查询获取到在此范围内的数据
	* @param       radius 地球半径 平均6371km
	* @param    lng float 经度
	* @param      lat float 纬度
	* @param    distance float 该点所在圆的半径，该圆与此正方形内切，默认值为1千米
	* @return array 正方形的四个点的经纬度坐标
	*/
   public static function returnSquarePoint($lng, $lat, $distance = 1, $radius = 6371)
   {
	   $dlng = 2 * asin(sin($distance / (2 * $radius)) / cos(deg2rad($lat)));
	   $dlng = rad2deg($dlng);

	   $dlat = $distance / $radius;
	   $dlat = rad2deg($dlat);
	   return array(
		   'left-top' => array(
			   'lat' => $lat + $dlat,
			   'lng' => $lng - $dlng
		   ),
		   'right-top' => array(
			   'lat' => $lat + $dlat,
			   'lng' => $lng + $dlng
		   ),
		   'left-bottom' => array(
			   'lat' => $lat - $dlat,
			   'lng' => $lng - $dlng
		   ),
		   'right-bottom' => array(
			   'lat' => $lat - $dlat,
			   'lng' => $lng + $dlng
		   )
	   );
   }

}

	