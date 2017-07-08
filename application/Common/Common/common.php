<?php
/*******************
常用基础函数	201707
****************/
/*
生成验证码
*/    
function make_verifycode()
{
    return rand(100000,999999);
} 
/**
 * 检查手机号码格式
 * @param $mobile 手机号码
 */
function check_mobile($mobile){
    if(preg_match('/1[34578]\d{9}$/',$mobile))
        return true;
    return false;
}
/**
 * 检查邮箱地址格式
 * @param $email 邮箱地址
 */
function check_email($email){
    if(filter_var($email,FILTER_VALIDATE_EMAIL))
        return true;
    return false;
}

/*
    密码加密
*/
function pw_encrypt($str){
    return md5("tigonetwork".$str);
}
/*
    生成token
*/
function maketoken(){
    return md5('www.tigonetwork.com'.time());
}

/**
 * 获取二维数组中的某一列
 * @param type $arr 数组
 * @param type $key_name  列名
 * @param type $type  返回格式： arr 返回数组格式，str 返回以逗号分割的字符串
<<<<<<< HEAD
 * @param type $filter  1 为过滤重复键，0为不过滤
=======
>>>>>>> 75bcb38a70cefe742463292f9d583ef839696979
 * @return type  返回那一列的数组
例子：
$arr0 = [
		array('id' =>456,
			'goods_name' =>'小米手机',
			'price' =>1399),
		array('id' =>754,
			'goods_name' =>'苹果手机',
			'price' =>4999),				
		];	
dump(get_arr_column($arr0,'id'));
dump(get_arr_column($arr0,'id','str'));

结果：
array(2) {
  [0] => int(456)
  [1] => int(754)
}
string(7) "456,754"//拼接成字符串一般用于拼接成查询条件如： id in (456,754)

 */
function get_arr_column($arr, $key_name, $type='arr', $filter=1)

{
    $arr2 = array();
    foreach($arr as $key => $val){
        $arr2[] = $val[$key_name];        
    }
    if ($filter == 1) {
        $arr2 = array_flip(array_flip($arr2));
    }
    if ($type=='str') {
    	return implode(',',$arr2);
    }
    return $arr2; 
}
/**
 * 二维数组排序
 * @param $arr
 * @param $keys
 * @param string $type desc 为降序，asc 为升序
 * @return array
 */
function array_sort($arr, $keys, $type = 'desc')
{
    $key_value = $new_array = array();
    foreach ($arr as $k => $v) {
        $key_value[$k] = $v[$keys];
    }
    if ($type == 'asc') {
        asort($key_value);
    } else {
        arsort($key_value);
    }
    reset($key_value);
    foreach ($key_value as $k => $v) {
        $new_array[$k] = $arr[$k];
    }
    return $new_array;
}

/**
 * @param $arr
 * @param $key_name
  * @param $key_name2
 * @return array
 * 将二维数组以指定的 id 作为数组的键名 数组指定列为元素 组合成一个新数组
 例子：
$arr0 = [
	array('id' =>456,
		'goods_name' =>'小米手机',
		'price' =>1399),
	array('id' =>754,
		'goods_name' =>'苹果手机',
		'price' =>4999),				
	];

dump(get_id_val($arr0,'id','goods_name'));
结果：
array(2) {
  [456] => string(12) "小米手机"
  [754] => string(12) "苹果手机"
}

 */
function get_id_val($arr, $key_name,$key_name2)
{
	$arr2 = array();
	foreach($arr as $key => $val){
		$arr2[$val[$key_name]] = $val[$key_name2];
	}
	return $arr2;
}

/**
 * 将二维数组以元素的某个值作为键 并归类数组
 * array( array('name'=>'aa','type'=>'pay'), array('name'=>'cc','type'=>'pay') )
 * 结果：array('pay'=>array( array('name'=>'aa','type'=>'pay') , array('name'=>'cc','type'=>'pay') ))
 * @param $arr 数组
 * @param $key 分组值的key
 * @return array

 */
function group_same_key($arr,$key){
    $new_arr = array();
    foreach($arr as $k=>$v ){
        $new_arr[$v[$key]][] = $v;
    }
    return $new_arr;
}
/**
 * @param $arr
 * @param $key_name
 * @return array
 * 将数组指定的 id 作为新数组的键名 
 例子：
$arr1 = array( array('id'=>23,'name'=>'aa','type'=>'pay'), array('id'=>29,'name'=>'cc','type'=>'pay1'));
dump(convert_arr_key($arr1,'id'));
结果：
array(2) {
  [23] => array(3) {
    ["id"] => int(23)
    ["name"] => string(2) "aa"
    ["type"] => string(3) "pay"
  }
  [29] => array(3) {
    ["id"] => int(29)
    ["name"] => string(2) "cc"
    ["type"] => string(4) "pay1"
  }
}
 */
function convert_arr_key($arr, $key_name)
{
	$arr2 = array();
	foreach($arr as $key => $val){
		$arr2[$val[$key_name]] = $val;        
	}
	return $arr2;
}

/**
 * 多维数组转化为一维数组
 * @param 多维数组
 * @return array 一维数组
 */
function array_multi2single($array)
{
    static $result_array = array();
    foreach ($array as $value) {
        if (is_array($value)) {
            array_multi2single($value);
        } else
            $result_array [] = $value;
    }
    return $result_array;
}

/**
 * base64图片保存到指定目录
 * @param string $base64 base64格式的图片
 * @param string $uploadpath 图片上传路径,以"/"结尾
 * @return mixed
测试：
$imgbase64 = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAN0AAADNCAYAAAA8EnWBAAACVUlEQVR4nO3TMQHAIADAMOCcJXxhf3NRjiUK+nQ++7wDyKzbAfA3poOY6SBmOoiZDmKmg5jpIGY6iJkOYqaDmOkgZjqImQ5ipoOY6SBmOoiZDmKmg5jpIGY6iJkOYqaDmOkgZjqImQ5ipoOY6SBmOoiZDmKmg5jpIGY6iJkOYqaDmOkgZjqImQ5ipoOY6SBmOoiZDmKmg5jpIGY6iJkOYqaDmOkgZjqImQ5ipoOY6SBmOoiZDmKmg5jpIGY6iJkOYqaDmOkgZjqImQ5ipoOY6SBmOoiZDmKmg5jpIGY6iJkOYqaDmOkgZjqImQ5ipoOY6SBmOoiZDmKmg5jpIGY6iJkOYqaDmOkgZjqImQ5ipoOY6SBmOoiZDmKmg5jpIGY6iJkOYqaDmOkgZjqImQ5ipoOY6SBmOoiZDmKmg5jpIGY6iJkOYqaDmOkgZjqImQ5ipoOY6SBmOoiZDmKmg5jpIGY6iJkOYqaDmOkgZjqImQ5ipoOY6SBmOoiZDmKmg5jpIGY6iJkOYqaDmOkgZjqImQ5ipoOY6SBmOoiZDmKmg5jpIGY6iJkOYqaDmOkgZjqImQ5ipoOY6SBmOoiZDmKmg5jpIGY6iJkOYqaDmOkgZjqImQ5ipoOY6SBmOoiZDmKmg5jpIGY6iJkOYqaDmOkgZjqImQ5ipoOY6SBmOoiZDmKmg5jpIGY6iJkOYqaDmOkgZjqImQ5ipoOY6SBmOoiZDmKmg5jpIGY6iJkOYqaDmOkgZjqImQ5ipoOY6SBmOoiZDmKmg5jpIGY6iJkOYqaDmOkgZjqIfUNIA1RVBmKkAAAAAElFTkSuQmCC";

dump(base64_img_save($imgbase64,"./public/images/"));

 */

function base64_img_save($base64,$uploadpath='')
{
    if (empty($base64)){
        return array('status'=>-1,'msg'=>'图片上传错误');
        exit;
    }
    if (trim($uploadpath) == '') {        
        return array('status'=>-1,'msg'=>'上传图片保存路径为空');
        exit;
    }
    $size = 5242880; //5M
    $base64_head = substr(strstr($base64,';',1),11);
    $base64_body = substr(strstr($base64,','),1);
    if($base64_head == "jpeg"){
        $filefix=".jpg";
    }
    if($base64_head == "jpg"){
        $filefix=".jpg";
    }
    if($base64_head == "png"){
        $filefix=".png";
    }
    if($base64_head == "gif"){
        $filefix=".gif";
    }       
    $imgname =  date('Ymd') ."_". rand(0,9999999);
    $filename = $imgname . $filefix;
    $imgpath = $uploadpath.$filename;
    //dump($imgpath);exit;
    //判断能否上传
    if(!is_dir($uploadpath)){
        if(!mkdir($uploadpath,0777,true)){
            return array('status'=>-1,'msg'=>'文件上传目录不存在并且无法创建目录');
            exit;
        }elseif (!chmod($uploadpath,0755)) {            
            return array('status'=>-1,'msg'=>'文件上传目录权限无法设置为可读可写');            
            exit;
        }
    }
    $img = base64_decode($base64_body);
    $a = file_put_contents($imgpath, $img);//返回的是字节
    
    //dump($imgpath);
    if($a>0){
        return array('status'=>1,'msg'=>$filename); 
    }
    else{
        return array('status'=>-1,'msg'=>'上传异常'); 
    }
}

/**
 * 手机端图片上传先于表单提交，图片调用base64_img_save函数上传后保存在临时文件夹中，等表单提交再将临时图片移到正式路径中
 * 图片分类、图片保存路径根据项目自行修改
 * @param string $file_name 临时文件名
 * @param int $type 图片类型
 * @return bool|string 返回失败或返回新的相对地址
 */
function save_tmp_img($file_name,$type){
    $file = './public/images/tmp/'.$file_name;
    //dump($file);
    /*要保存的文件不存在也返回成功*/    
    $date = date("Ymd");    
    switch ($type){
        case 'AD'://广告图
        	$dir = './public/images/ad/';
            $path = $dir.$date.'/';
            $relative_path = $dir.$date.'/'.$file_name;
            break;
        case 'ARTICLE'://文章图
        	$dir = './public/images/ad/';
            $path = $dir.$date.'/';
            $relative_path = $dir.$date.'/'.$file_name;
            break;   
       
        default:
        	$dir = './public/images/other/';
            $path = $dir.$date.'/';
            $relative_path = $dir.$date.'/'.$file_name;
            break;
    }    
    if (!file_exists($file) && !file_exists($relative_path)){
    	return false;
    }
    	
    if (!file_exists($path)){
        mkdir($path,0777,true);
        if (!is_dir($path))return false;
    }
    $rs = 0;
    if (file_exists($relative_path)) {
    	$rs = 1;
    }else{
    	$rs = rename($file,$relative_path);
    }
    if ($rs){
    	return $relative_path;
    }else{
    	return false;
    }
    
}
/**
 * 保存临时图片数组，前端提交表单时传入多个图片名
 * @param string $imgs 临时文件名，多个用','分割
 * @param int $type 图片类型
 * @return bool|string 返回失败或返回新的相对地址
 */
function save_imgarr($imgs='',$type)
{
    $imgtmp = "";
    if ($imgs != "") {
        $imgarr = explode(',',$imgs);
        foreach ($imgarr as $key => $value) {
            // dump($value);exit;
            $img_path = save_tmp_img($value,$type);
            if (!$img_path) {                    
                return array('status'=>-1,'msg'=>$value.'上传异常，请重新上传','result'=>[],'code'=>-1);                   
            }  

            $img_path = 'http://'.$_SERVER['HTTP_HOST'].'/'.$img_path;
            if ($imgtmp == "") {
              $imgtmp = $img_path;
            }else{
              $imgtmp = $imgtmp.','.$img_path;
            }  

        }
    }
    return array('status'=>1,'msg'=>'上传成功','result'=>$imgtmp,'code'=>1);     
}

 
/**
 * 生成缩略图
 * @param string  src_img   源图绝对完整地址{带文件名及后缀名}
 * @param string  dst_img   目标图绝对完整地址{带文件名及后缀名}
 * @param int     width   缩略图宽{0:此时目标高度不能为0，目标宽度为源图宽*(目标高度/源图高)}
 * @param int     height   缩略图高{0:此时目标宽度不能为0，目标高度为源图高*(目标宽度/源图宽)}
 * @param int     cut   是否裁切{宽,高必须非0},0为否，1为是
 * @param int/float proportion 缩放{0:不缩放, 0<this<1:缩放到相应比例(此时宽高限制和裁切均失效)}
 * @param int  	  move_x 裁切x轴偏移量,如果为正数，裁切范围往左边移，如果为负数，裁切范围往右边移	
 * @param int  	  move_y 裁切y轴偏移量,如果为正数，裁切范围往上边移，如果为负数，裁切范围往下边移
 * @return boolean
 */
function img2thumb($src_img, $dst_img, $width = 75, $height = 75, $cut = 0, $proportion = 0, $move_x = 0, $move_y = 0)
{
    if(!is_file($src_img))
    {
        return false;
    }
    /*
		如果图片已经存在，直接返回true
    */
    if(file_exists($dst_img))
    {
    	return true;
    }
    $ot = pathinfo($dst_img, PATHINFO_EXTENSION);
    $otfunc = 'image' . ($ot == 'jpg' ? 'jpeg' : $ot);
    $srcinfo = getimagesize($src_img);
    $src_w = $srcinfo[0];
    $src_h = $srcinfo[1];
    $type  = strtolower(substr(image_type_to_extension($srcinfo[2]), 1));
    $createfun = 'imagecreatefrom' . ($type == 'jpg' ? 'jpeg' : $type);
 
    $dst_h = $height;
    $dst_w = $width;
    $x = $y = 0;
 
    /**
     * 缩略图不超过源图尺寸（前提是宽或高只有一个）
     */
    if(($width> $src_w && $height> $src_h) || ($height> $src_h && $width == 0) || ($width> $src_w && $height == 0))
    {
        $proportion = 1;
    }
    if($width> $src_w)
    {
        $dst_w = $width = $src_w;
    }
    if($height> $src_h)
    {
        $dst_h = $height = $src_h;
    }
 
    if(!$width && !$height && !$proportion)
    {
        return false;
    }
    if(!$proportion)
    {
        if($cut == 0)
        {
            if($dst_w && $dst_h)
            {
                if($dst_w/$src_w> $dst_h/$src_h)
                {
                    $dst_w = $src_w * ($dst_h / $src_h);
                    $x = 0 - ($dst_w - $width) / 2;
                }
                else
                {
                    $dst_h = $src_h * ($dst_w / $src_w);
                    $y = 0 - ($dst_h - $height) / 2;
                }
            }
            else if($dst_w xor $dst_h)
            {
                if($dst_w && !$dst_h)  //有宽无高
                {
                    $propor = $dst_w / $src_w;
                    $height = $dst_h  = $src_h * $propor;
                }
                else if(!$dst_w && $dst_h)  //有高无宽
                {
                    $propor = $dst_h / $src_h;
                    $width  = $dst_w = $src_w * $propor;
                }
            }
        }
        else
        {
            if(!$dst_h)  //裁剪时无高
            {
                $height = $dst_h = $dst_w;
            }
            if(!$dst_w)  //裁剪时无宽
            {
                $width = $dst_w = $dst_h;
            }
            $propor = min(max($dst_w / $src_w, $dst_h / $src_h), 1);
            $dst_w = (int)round($src_w * $propor);
            $dst_h = (int)round($src_h * $propor);
            $x = ($width - $dst_w) / 2 + $move_x;
            $y = ($height - $dst_h) / 2 + $move_y;

            printf("move_x=%d,move_y=%d",$move_x,$move_y);
            printf("x=%d,y=%d",$x,$y);

            // $x = $x < 0 ? 0:$x;
            // $y = $y < 0 ? 0:$y;
            // printf("x=%d,y=%d",$x,$y);
        }
    }
    else
    {
        $proportion = min($proportion, 1);
        $height = $dst_h = $src_h * $proportion;
        $width  = $dst_w = $src_w * $proportion;
    }
 
    $src = $createfun($src_img);
    $dst = imagecreatetruecolor($width ? $width : $dst_w, $height ? $height : $dst_h);
    $white = imagecolorallocate($dst, 255, 255, 255);
    imagefill($dst, 0, 0, $white);
 
    if(function_exists('imagecopyresampled'))
    {
        imagecopyresampled($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
    }
    else
    {
        imagecopyresized($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
    }
    $otfunc($dst, $dst_img);
    imagedestroy($dst);
    imagedestroy($src);
    return true;
}

/**
 * 友好时间显示
 * @param $time
 * @return bool|string
 例子：
 "1分钟前"
 "35分钟前"
 "06月23日"
 "2014年04月23日"
 "明天12:28"
 "2017年07月08日"
 */
function friend_date($time)
{
    if (!$time)
        return false;
    $fdate = '';
    $d = time() - intval($time);
    $ld = $time - mktime(0, 0, 0, 0, 0, date('Y')); //得出年
    $md = $time - mktime(0, 0, 0, date('m'), 0, date('Y')); //得出月
    $byd = $time - mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')); //前天
    $yd = $time - mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')); //昨天
    $dd = $time - mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天
    $td = $time - mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')); //明天
    $atd = $time - mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')); //后天
    if ($d == 0) {
        $fdate = '刚刚';
    } else {
        switch ($d) {
            case $d < $atd:
                $fdate = date('Y年m月d日', $time);
                break;
            case $d < $td:
                $fdate = '后天' . date('H:i', $time);
                break;
            case $d < 0:
                $fdate = '明天' . date('H:i', $time);
                break;
            case $d < 60:
                $fdate = $d . '秒前';
                break;
            case $d < 3600:
                $fdate = floor($d / 60) . '分钟前';
                break;
            case $d < $dd:
                $fdate = floor($d / 3600) . '小时前';
                break;
            case $d < $yd:
                $fdate = '昨天' . date('H:i', $time);
                break;
            case $d < $byd:
                $fdate = '前天' . date('H:i', $time);
                break;
            case $d < $md:
                $fdate = date('m月d日 H:i', $time);
                break;
            case $d < $ld:
                $fdate = date('m月d日', $time);
                break;
            default:
                $fdate = date('Y年m月d日', $time);
                break;
        }
    }
    return $fdate;
}

/**
 * 获取随机字符串,可用于验证码等需要生成随机字符串的地方
 * @param int $randLength  长度
 * @param int $addtime  是否加入当前时间戳
 * @param int $includenumber   是否包含数字
 * @return string
 */
function get_rand_str($randLength=6,$addtime=1,$includenumber=0){
    if ($includenumber){
        $chars='abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQEST123456789';
    }else {
        $chars='abcdefghijklmnopqrstuvwxyz';
    }
    $len=strlen($chars);
    $randStr='';
    for ($i=0;$i<$randLength;$i++){
        $randStr.=$chars[rand(0,$len-1)];
    }
    $tokenvalue=$randStr;
    if ($addtime){
        $tokenvalue=$randStr.time();
    }
    return $tokenvalue;
}

/**
 * CURL请求
 * @param $url 请求url地址
 * @param $method 请求方法 get post
 * @param null $postfields post数据数组
 * @param array $headers 请求header信息
 * @param bool|false $debug  调试开启 默认false
 * @return mixed
 */
function httpRequest($url, $method="POST", $postfields = null, $headers = array(), $debug = false) {
    $method = strtoupper($method);
    $ci = curl_init();
    /* Curl settings */
    curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
    curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
    switch ($method) {
        case "POST":
            curl_setopt($ci, CURLOPT_POST, true);
            if (!empty($postfields)) {
                $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
            }
            break;
        default:
            curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
            break;
    }
    $ssl = preg_match('/^https:\/\//i',$url) ? TRUE : FALSE;
    curl_setopt($ci, CURLOPT_URL, $url);
    if($ssl){
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
    }
    //curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
    curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
    curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ci, CURLINFO_HEADER_OUT, true);
    /*curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
    $response = curl_exec($ci);
    $requestinfo = curl_getinfo($ci);
    $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
    if ($debug) {
        echo "=====post data======\r\n";
        var_dump($postfields);
        echo "=====info===== \r\n";
        print_r($requestinfo);
        echo "=====response=====\r\n";
        print_r($response);
    }
    curl_close($ci);
    return $response;
	//return array($http_code, $response,$requestinfo);
}
/**
　　* 是否移动端访问访问
　　*
　　* @return bool
*/
function isMobile()
{
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
    return true;

    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
    {
    // 找不到为flase,否则为true
    return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = array ('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile');
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            return true;
    }
        // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT']))
    {
    // 如果只支持wml并且不支持html那一定是移动设备
    // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        }
    }
    return false;
} 

//php获取中文字符拼音首字母
function getFirstCharter($str){
      if(empty($str))
      {
            return '';          
      }
      $fchar=ord($str{0});
      if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0});
      $s1=iconv('UTF-8','gb2312',$str);
      $s2=iconv('gb2312','UTF-8',$s1);
      $s=$s2==$str?$s1:$str;
      $asc=ord($s{0})*256+ord($s{1})-65536;
     if($asc>=-20319&&$asc<=-20284) return 'A';
     if($asc>=-20283&&$asc<=-19776) return 'B';
     if($asc>=-19775&&$asc<=-19219) return 'C';
     if($asc>=-19218&&$asc<=-18711) return 'D';
     if($asc>=-18710&&$asc<=-18527) return 'E';
     if($asc>=-18526&&$asc<=-18240) return 'F';
     if($asc>=-18239&&$asc<=-17923) return 'G';
     if($asc>=-17922&&$asc<=-17418) return 'H';
     if($asc>=-17417&&$asc<=-16475) return 'J';
     if($asc>=-16474&&$asc<=-16213) return 'K';
     if($asc>=-16212&&$asc<=-15641) return 'L';
     if($asc>=-15640&&$asc<=-15166) return 'M';
     if($asc>=-15165&&$asc<=-14923) return 'N';
     if($asc>=-14922&&$asc<=-14915) return 'O';
     if($asc>=-14914&&$asc<=-14631) return 'P';
     if($asc>=-14630&&$asc<=-14150) return 'Q';
     if($asc>=-14149&&$asc<=-14091) return 'R';
     if($asc>=-14090&&$asc<=-13319) return 'S';
     if($asc>=-13318&&$asc<=-12839) return 'T';
     if($asc>=-12838&&$asc<=-12557) return 'W';
     if($asc>=-12556&&$asc<=-11848) return 'X';
     if($asc>=-11847&&$asc<=-11056) return 'Y';
     if($asc>=-11055&&$asc<=-10247) return 'Z';
     return null;
} 

 // 定义一个函数getIP() 客户端IP，
function getIP(){            
    if (getenv("HTTP_CLIENT_IP"))
         $ip = getenv("HTTP_CLIENT_IP");
    else if(getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if(getenv("REMOTE_ADDR"))
         $ip = getenv("REMOTE_ADDR");
    else $ip = "Unknow";
    
    if(preg_match('/^((?:(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))\.){3}(?:25[0-5]|2[0-4]\d|((1\d{2})|([1 -9]?\d))))$/', $ip))          
        return $ip;
    else
        return '';
}
// 服务器端IP
function serverIP(){   
	return gethostbyname($_SERVER["SERVER_NAME"]);   
}

/**
*   实现中文字串截取无乱码的方法
*/
function getSubstr($string, $start, $length) {
      if(mb_strlen($string,'utf-8')>$length){
          $str = mb_substr($string, $start, $length,'utf-8');
          return $str.'...';
      }else{
          return $string;
      }
}

/*
api接口成功返回json格式    
*/
function showTrueJson($data=array(), $msg='请求成功', $code=100)
{
    $result = array("data" => empty($data) ? array() : $data,
    				"status" => true, 
    				"msg" => $msg,
    				"code" => $code);
    header('Content-Type:application/json; charset=utf-8');
    exit(json_encode($result));
}
/*
api接口失败返回json格式    
*/
function showFalseJson($msg, $code = -1)
{
    $result = array("status" => false, 
    				"code" => $code,
    				"msg" => $msg);
    header('Content-Type:application/json; charset=utf-8');
    exit(json_encode($result));
}
<<<<<<< HEAD
/*ajax简单返回json格式*/
function reutrn_json($data)
{
    header('Content-Type:application/json; charset=utf-8');
    exit(json_encode($data));
}
=======
>>>>>>> 75bcb38a70cefe742463292f9d583ef839696979
?>