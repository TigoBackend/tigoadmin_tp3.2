<?php
/*
用户角色管理

*/

namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class UserroleController extends AdminbaseController{
	protected $users_model;
	protected $region_model;

	function _initialize() {
		parent::_initialize();
		$this->users_model = D("Common/Users");
		$this->region_model = M('region');
	}
	/*
	区域经理
	*/
	public function region_manager()
	{
		$where = array();
		/*筛选条件*/
		if ($_POST) {
			$formget['start_time'] = $start_time = I("start_time","");
			$start_time = strtotime($start_time);
			
			$formget['end_time'] = $end_time = I("end_time","");
			/*由于是算0点，因此这里要加多一天*/
			$end_time = strtotime($end_time);

			$formget['keyword'] = $keyword = I("keyword","");
			$formget['province'] = $province = I("province",0);
			$formget['city'] = $city = I("city",0);
			$formget['district'] = $district = I("district",0);
			$formget['user_status'] = $user_status = I("user_status",-1);

			if ($start_time > 0 && $end_time > 0 && $start_time > $end_time) {
				$this->error("查询条件起始时间不能大于结束时间");
			}
			if (!empty($start_time)) {
				$where['create_time'] = array('EGT',$start_time);
			}
			if (!empty($end_time)) {
				$end_time = $end_time + 86400;
				$where['create_time'] = array('ELT',$end_time);
			}	
			if (!empty($start_time) && !empty($end_time)) {
				$where['create_time'] = array('between',$start_time.','.$end_time);
			}
			if (!empty($keyword)) {
				$where['username'] = array('like',"%".$keyword."%");
			}
			if ($province != 0) {
				$where['province'] = $province;
				/*用于防止查询条件被刷新,返回城市列表*/
				$city_list = get_region('city',array('parent_id' => $province));
			}	
			if ($city != 0) {
				$where['city'] = $city;
				/*用于防止查询条件被刷新,返回区列表*/
				$district_list = get_region('district',array('parent_id' => $city));
			}
			if ($district != 0) {
				$where['district'] = $district;
			}
			if ($user_status != -1) {
				$where['user_status'] = $user_status;
			}			
			//dump($formget);				
		}else{
			$formget['user_status'] = -1;
		}
		/*城市列表*/
		$province_list = get_region('province');			
		$status_arr = C('USER_STATUS');
		/*用于分页*/
		$count=$this->users_model->my_select('REGION_MANAGER',$where,'count');		
		$page = $this->page($count, 20);

		$limit = $page->firstRow . ',' . $page->listRows;
		$order = "create_time DESC";
		$users_list = $this->users_model->my_select('REGION_MANAGER',$where,'select',$order,$limit);		
		
		/*把区域转换成中文*/
		$area_list = $this->get_area_name($users_list);		
		foreach ($users_list as $key => $value) {
			$users_list[$key]['province'] = $area_list[$value['province']];
			$users_list[$key]['city'] = $area_list[$value['city']];
			$users_list[$key]['district'] = $area_list[$value['district']];
		}		
		//dump($page->show());exit;
		$this->assign("Page", $page->show("Admin"));
		$this->assign("users_list",$users_list);
		$this->assign("status_arr",$status_arr);
		$this->assign("province",$province_list);
		$this->assign("city",$city_list);
		$this->assign("district",$district_list);	
		$this->assign("formget",$formget);	
		$this->display();		
	}

	/*
	新增/编辑区域经理
	*/
	public function edit_region_manager()	
	{
		$province = get_region('province');		

		$user_id = I('user_id',-1);
		if ($user_id != -1) {			
			$where['id'] = $user_id;
			$users_list = $this->users_model->my_select('REGION_MANAGER',$where,'find');

			$city_where['parent_id'] = $users_list['province'];
			$city = get_region('city',$city_where);	

			$district_where['parent_id'] = $users_list['city'];
			$district = get_region('district',$district_where);										
		}else{
			$users_list['user_status'] = 1;
		}	
		//dump($users_list);
		// dump($district);exit;
		//$status_arr = array(array("id"=>0,"name"=>"禁用"),array("id"=>1,"name"=>"启用"));
		$status_arr = C('USER_STATUS');
		$this->assign("status_arr",$status_arr);
		$this->assign("users_list",$users_list);
		$this->assign("province",$province);
		$this->assign("city",$city);
		$this->assign("district",$district);
		$this->display();		
	}	
	/*
		区域经理提交
	*/
	public function region_manager_post()
	{
		$user_id = intval(I('id',0));
		/*如果是编辑，并且密码为空，则说明没改密码*/
		if($user_id != 0 && empty($_POST['user_pass'])){
			unset($_POST['user_pass']);
		}
		if ($this->users_model->create()) {
			if ($user_id != 0) {	
				$result=$this->users_model->save();								
			}else{
				/*添加成功返回用户id*/				
				$user_id = $result=$this->users_model->add();	
			}			
		}else {
			$this->error($this->users_model->getError());
		}
		/*不管是添加还是编辑用户，都先删除权限再添加权限*/
		if ($result!==false) {
			$role_user_model=M("RoleUser");
			$role_user_model->where(array("user_id"=>$user_id))->delete();

			$role_id = C("USER_ROLE.REGION_MANAGER");
			$role_user_model->add(array("role_id"=>$role_id,"user_id"=>$user_id));

			$this->success("保存成功！");
		} else {
			$this->error("保存失败！");
		}		
	}
	/*
		删除区域经理
	*/
	public function del_region_manager()
	{
		$id = intval(I("get.user_id"));
		if($id==1){
			$this->error("最高管理员不能删除！");
		}
		
		if ($this->users_model->where("id=$id")->delete()!==false) {
			M("RoleUser")->where(array("user_id"=>$id))->delete();
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}		
	}
	// /*
	// 产权部经理
	// */
	// public function property_manager()
	// {
	// 	echo "property_manager";
	// }
	/*
	产权部经理
	*/
	public function property_manager()
	{
		$where = array();
		/*筛选条件*/
		if ($_POST) {
			$formget['start_time'] = $start_time = I("start_time","");
			$start_time = strtotime($start_time);
			
			$formget['end_time'] = $end_time = I("end_time","");
			/*由于是算0点，因此这里要加多一天*/
			$end_time = strtotime($end_time);

			$formget['keyword'] = $keyword = I("keyword","");
			// $formget['province'] = $province = I("province",0);
			// $formget['city'] = $city = I("city",0);
			// $formget['district'] = $district = I("district",0);
			$formget['user_status'] = $user_status = I("user_status",-1);

			if ($start_time > 0 && $end_time > 0 && $start_time > $end_time) {
				$this->error("查询条件起始时间不能大于结束时间");
			}
			if (!empty($start_time)) {
				$where['create_time'] = array('EGT',$start_time);
			}
			if (!empty($end_time)) {
				$end_time = $end_time + 86400;
				$where['create_time'] = array('ELT',$end_time);
			}	
			if (!empty($start_time) && !empty($end_time)) {
				$where['create_time'] = array('between',$start_time.','.$end_time);
			}
			if (!empty($keyword)) {
				$where['username'] = array('like',"%".$keyword."%");
			}
			// if ($province != 0) {
			// 	$where['province'] = $province;
			// 	/*用于防止查询条件被刷新,返回城市列表*/
			// 	$city_list = get_region('city',array('parent_id' => $province));
			// }	
			// if ($city != 0) {
			// 	$where['city'] = $city;
			// 	/*用于防止查询条件被刷新,返回区列表*/
			// 	$district_list = get_region('district',array('parent_id' => $city));
			// }
			// if ($district != 0) {
			// 	$where['district'] = $district;
			// }
			if ($user_status != -1) {
				$where['user_status'] = $user_status;
			}			
			//dump($formget);				
		}else{
			$formget['user_status'] = -1;
		}
		/*城市列表*/
		// $province_list = get_region('province');			
		$status_arr = C('USER_STATUS');
		/*用于分页*/
		$count=$this->users_model->my_select('REGION_MANAGER',$where,'count');		
		$page = $this->page($count, 20);

		$limit = $page->firstRow . ',' . $page->listRows;
		$order = "create_time DESC";
		$users_list = $this->users_model->my_select('REGION_MANAGER',$where,'select',$order,$limit);		
		
		/*把区域转换成中文*/
		// $area_list = $this->get_area_name($users_list);		
		// foreach ($users_list as $key => $value) {
		// 	$users_list[$key]['province'] = $area_list[$value['province']];
		// 	$users_list[$key]['city'] = $area_list[$value['city']];
		// 	$users_list[$key]['district'] = $area_list[$value['district']];
		// }		
		//dump($page->show());exit;
		$this->assign("Page", $page->show("Admin"));
		$this->assign("users_list",$users_list);
		$this->assign("status_arr",$status_arr);
		// $this->assign("province",$province_list);
		// $this->assign("city",$city_list);
		// $this->assign("district",$district_list);	
		$this->assign("formget",$formget);	
		$this->display();		
	}

	/*
	新增/编辑产权部经理
	*/
	public function edit_property_manager()	
	{
		$province = get_region('province');		

		$user_id = I('user_id',-1);
		if ($user_id != -1) {			
			$where['id'] = $user_id;
			$users_list = $this->users_model->my_select('REGION_MANAGER',$where,'find');

			$city_where['parent_id'] = $users_list['province'];
			$city = get_region('city',$city_where);	

			$district_where['parent_id'] = $users_list['city'];
			$district = get_region('district',$district_where);										
		}else{
			$users_list['user_status'] = 1;
		}	
		//dump($users_list);
		// dump($district);exit;
		//$status_arr = array(array("id"=>0,"name"=>"禁用"),array("id"=>1,"name"=>"启用"));
		$status_arr = C('USER_STATUS');
		$this->assign("status_arr",$status_arr);
		$this->assign("users_list",$users_list);
		$this->assign("province",$province);
		$this->assign("city",$city);
		$this->assign("district",$district);
		$this->display();		
	}	
	/*
		产权部经理提交
	*/
	public function property_manager_post()
	{
		$user_id = intval(I('id',0));
		/*如果是编辑，并且密码为空，则说明没改密码*/
		if($user_id != 0 && empty($_POST['user_pass'])){
			unset($_POST['user_pass']);
		}
		if ($this->users_model->create()) {
			if ($user_id != 0) {	
				$result=$this->users_model->save();								
			}else{
				/*添加成功返回用户id*/				
				$user_id = $result=$this->users_model->add();	
			}			
		}else {
			$this->error($this->users_model->getError());
		}
		/*不管是添加还是编辑用户，都先删除权限再添加权限*/
		if ($result!==false) {
			$role_user_model=M("RoleUser");
			$role_user_model->where(array("user_id"=>$user_id))->delete();

			$role_id = C("USER_ROLE.REGION_MANAGER");
			$role_user_model->add(array("role_id"=>$role_id,"user_id"=>$user_id));

			$this->success("保存成功！");
		} else {
			$this->error("保存失败！");
		}		
	}
	/*
		删除产权部经理
	*/
	public function del_property_manager()
	{
		$id = intval(I("get.user_id"));
		if($id==1){
			$this->error("最高管理员不能删除！");
		}
		
		if ($this->users_model->where("id=$id")->delete()!==false) {
			M("RoleUser")->where(array("user_id"=>$id))->delete();
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}		
	}	
	/*
	产权部助理
	*/
	public function property_assistant()
	{
		echo "property_assistant";
	}	
	/*
	产权员
	*/
	public function property_owner()
	{
		echo "property_owner";
	}		
	/*
	行政经理
	*/
	public function admin_manager()
	{
		echo "admin_manager";
	}
	/*
	行政专员
	*/
	public function administration_owner()
	{
		echo "administration_owner";
	}	
	/*
	财务经理
	*/
	public function finance_manager()
	{
		echo "finance_manager";
	}
	/*
	财务助理
	*/
	public function finance_assistant()
	{
		echo "finance_assistant";
	}	
	/*
	财务专员
	*/
	public function finance_owner()
	{
		echo "finance_owner";
	}	
	/*
	分店列表
	*/
	public function shop_list()
	{
		echo "shop_list";
	}
	/*
	小组列表
	*/
	public function group_list()
	{
		echo "group_list";
	}
	/*
	店长列表
	*/
	public function shopowner_list()
	{
		echo "shopowner_list";
	}
	/*
	分店助理
	*/
	public function shop_assistant()
	{
		echo "shop_assistant";
	}
	/*
	业务员
	*/
	public function salesman()
	{
		echo "salesman";
	}

	/*获取所有的区域id，并查询出中文名返回*/
	public function get_area_name($arr)
	{
		$province_column = get_arr_column($arr,'province','str');
		$province_column = empty($province_column) ? '0' : $province_column;
		$city_column = get_arr_column($arr,'city','str');
		$city_column = empty($city_column) ? '0' : $city_column;
		$district_column = get_arr_column($arr,'district','str');
		$district_column = empty($district_column) ? '0' : $district_column;

		$province_arr = M('region')->where("id in ($province_column) and level = 1")->getField("id,name");
		$city_arr = M('region')->where("id in ($city_column) and level = 2")->getField("id,name");
		$district_arr = M('region')->where("id in ($district_column) and level = 3")->getField("id,name");
		/*合并数组，此时 不可以使用array_merge，否则会重拼key*/
		return $province_arr + $city_arr + $district_arr;		
	}	
	/*区域联动*/
	public function selectarea()
	{
		$pid = I('pid',0);
		$where['parent_id'] = $pid;
		$areamessage = get_region('',$where);
		reutrn_json($areamessage);

	}	
}