<?php
namespace Common\Model;
use Common\Model\CommonModel;
class UsersModel extends CommonModel
{
	
	protected $_validate = array(
		//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
		array('username', 'require', '姓名不能为空！', 1, 'regex', CommonModel:: MODEL_INSERT  ),		
		array('province', 0, '省份不能为空', 1, 'notequal', CommonModel:: MODEL_INSERT ),
		array('city', 0, '市不能为空', 1, 'notequal', CommonModel:: MODEL_INSERT ),
		array('district', 0, '区不能为空', 1, 'notequal', CommonModel:: MODEL_INSERT ),
		array('user_login', 'require', '登录账号不能为空！', 1, 'regex', CommonModel:: MODEL_INSERT  ),		
		array('user_pass', 'require', '密码不能为空！', 1, 'regex', CommonModel:: MODEL_INSERT ),

		array('username', 'require', '姓名不能为空！', 0, 'regex', CommonModel:: MODEL_UPDATE  ),		
		array('province', 0, '省份不能为空', 0, 'notequal', CommonModel:: MODEL_UPDATE ),
		array('city', 0, '市不能为空', 0, 'notequal', CommonModel:: MODEL_UPDATE ),
		array('district', 0, '区不能为空', 0, 'notequal', CommonModel:: MODEL_UPDATE ),

		array('user_login', 'require', '用户名称不能为空！', 0, 'regex', CommonModel:: MODEL_UPDATE  ),
		array('user_pass', 'require', '密码不能为空！', 0, 'regex', CommonModel:: MODEL_UPDATE  ),
		array('user_login','','用户名已经存在！',0,'unique',CommonModel:: MODEL_BOTH ), // 验证user_login字段是否唯一
		//array('user_email','','邮箱帐号已经存在！',0,'unique',CommonModel:: MODEL_BOTH ), // 验证user_email字段是否唯一
		//array('user_email','email','邮箱格式不正确！',0,'',CommonModel:: MODEL_BOTH ), // 验证user_email字段格式是否正确
	);
	
	protected $_auto = array(
	    array('create_time','mGetDate',CommonModel:: MODEL_INSERT,'callback'),
	    array('birthday','',CommonModel::MODEL_UPDATE,'ignore')
	);
	
	//用于获取时间，格式为2012-02-03 12:12:12,注意,方法不能为private
	function mGetDate() {
		//return date('Y-m-d H:i:s');
		return time();
	}
	
	function my_select($user_role,$my_where = array(),$action = 'select',$order = "",$limit = "")
	{
		$where = $my_where;
		$role_id = C("USER_ROLE.".$user_role) ? C("USER_ROLE.".$user_role) : -1; 
		$where['role_id'] = $role_id;

		if ($action == 'select') {
			$data = $this->alias('u')
					->join("cmf_role_user as ru on u.id = ru.user_id")
					->where($where)
					->order($order)
					->limit($limit)
					->select();
		}elseif ($action == 'find') {
			$data = $this->alias('u')
					->join("cmf_role_user as ru on u.id = ru.user_id")
					->where($where)
					->order($order)
					->limit($limit)
					->find();			
		}else {
			$data = $this->alias('u')
					->join("cmf_role_user as ru on u.id = ru.user_id")
					->where($where)
					->count();			
		}
		return $data;
	}	
	protected function _before_write(&$data) {
		parent::_before_write($data);
		
		if(!empty($data['user_pass']) && strlen($data['user_pass'])<25){
			$data['user_pass']=sp_password($data['user_pass']);
		}
	}
    // 查询成功后的回调方法
    protected function _after_select(&$resultSet,$options) {    	
    	parent::_after_select($resultSet,$options);
    	/*获取用户状态*/
    	$status_arr = C('USER_STATUS');
    	$sex = C('SEX');
    	//dump($status_arr);exit;
    	foreach ($resultSet as $key => $value) {
    		$resultSet[$key]['create_time'] = date("Y-m-d h:m:s",$value['create_time']);
    		$resultSet[$key]['last_login_time'] = date("Y-m-d h:m:s",$value['last_login_time']);
    		$resultSet[$key]['user_status'] = $status_arr[$value['user_status']];
    		$resultSet[$key]['sex'] = $sex[$value['sex']];
    	}
    	//dump($resultSet);
    }    

	
}

