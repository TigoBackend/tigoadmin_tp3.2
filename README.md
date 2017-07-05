20170705 jewey
添加基础函数文件common.php，包含常用的基础函数

## 常用基础函数	201707

#### 基础函数

1. make_verifycode 	生成验证码

1. check_mobile 	检查手机号码格式

1. check_email 	检查邮箱地址格式

1. pw_encrypt 	密码加密

1. maketoken 	生成token

#### 操作数组，主要为将数组转换成更方便数据库操作或者业务相关的结构

1. get_arr_column 	获取二维数组中的某一列

1. array_sort 	二维数组排序

1. get_id_val 	将二维数组以指定的 id 作为数组的键名 数组指定列为元素 组合成一个新数组

1. group_same_key 	将二维数组以元素的某个值作为键 并归类数组

1. convert_arr_key 	将数组指定的 id 作为新数组的键名

1. array_multi2single 	多维数组转化为一维数组

#### 处理图片
1. base64_img_save 	base64图片保存到指定目录

1. save_tmp_img 	将临时图片保存到正式目录

1. save_imgarr 	将多张临时图片保存到正式目录

1. img2thumb 	生成缩略图，并可自由裁切缩放


#### API统一返回格式
1. showTrueJson 	api接口成功返回json格式    

1. showFalseJson 	api接口失败返回json格式  

#### 其他函数

1. friend_date 	友好时间显示

1. get_rand_str 	获取随机字符串,可用于验证码等需要生成随机字符串的地方

1. httpRequest 	CURL请求

1. isMobile 	是否移动端访问访问

1. getFirstCharter 	获取中文字符拼音首字母

1. getIP 	获取客户端IP

1. serverIP 	获取服务器端IP

1. getSubstr 	实现中文字串截取无乱码的方法



## 201707 README
该项目使用thinkcmfX2.1.0 的tp3.2版本进行改造，非常感谢thinkcmf团队开源该项目，为我们项目的开发带来了很大的便利。


## INSTALL
安装请执行http://yourdomain/install/index.php

安装完成后请删除或改名install/index.php


## 使用建议

请在您的网站首页加上ThinkCMF相关链接，O(∩_∩)O~ ！
  
## 捐赠ThinkCMF
http://www.thinkcmf.com/donate/index.html
  
您的每一份帮助都将支持ThinkCMF做的更好，走的更远！
ThinkCMF 正在为你开放更多....
