<?php
/*
	绩效管理
*/
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class ExamController extends AdminbaseController{
	
	function _initialize() {
		parent::_initialize();
	}

	/*
		绩效管理列表
	*/
	function exam_record(){
		echo "exam_record";
	}
	/*
		店长绩效详情
	*/
	function edit_exam_record(){
		echo "edit_exam_record";
	}
	/*
		绩效管理表
	*/
	function exam_list(){
		echo "exam_list";
	}		
	/*
		绩效考核表详情
	*/
	function edit_exam(){
		echo "edit_exam";
	}		

	
	
}