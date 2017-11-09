<?php
namespace Common\Model;
use Common\Model\CommonModel;
class LogModel extends CommonModel
{
	
	protected $_validate = array(
	);
	
	protected $_auto = array(
	    array('create_time','mGetDate',CommonModel:: MODEL_INSERT,'callback'),
	);
	
	//用于获取时间方法不能为private
	function mGetDate() {
		return time();
	}
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}
    // 查询成功后的回调方法
    protected function _after_select(&$resultSet,$options) {    	
    	parent::_after_select($resultSet,$options);
    	//dump($status_arr);exit;
    	foreach ($resultSet as $key => $value) {
    		$resultSet[$key]['create_time'] = date("Y-m-d H:i:s",$value['create_time']);
    	}
    	//dump($resultSet);
    } 	
	
}

