<?php
namespace Admin\Controller;
/*用户操作记录*/
use Common\Controller\AdminbaseController;

class LogController extends AdminbaseController{

	protected $log_model;

	public function _initialize() {
		parent::_initialize();
		$this->log_model = D("Common/log");
	}
	// 管理员列表
	public function index(){
		// /**搜索条件**/
		$user_login = I('request.user_login');
		$module = I('request.module');
		$describe = I('request.describe');
		if($user_login){
			$where['u.user_login'] = array('like',"%$user_login%");
		}
		if($module){
			$where['l.module_name'] = array('like',"%$module%");
		}
		if($describe){
			$where['l.describe'] = array('like',"%$describe%");
		}
		
		$count=$this->log_model
				->alias('l')
				->join("left join cmf_users u on u.id = l.user_id")
				->where($where)->count();
		$page = $this->page($count, 20);
		$field = "log_id,u.user_login,module_name,action_type,describe,login_ip,l.create_time";
        $log_list = $this->log_model
        	->alias('l')
        	->field($field)
        	->join("left join cmf_users u on u.id = l.user_id")
            ->where($where)
            ->order("create_time DESC")
            ->limit($page->firstRow, $page->listRows)
            ->select();
		$this->assign("page", $page->show('Admin'));
		$this->assign("log_list",$log_list);
		$this->display();
	}
}