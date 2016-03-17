<?php
namespace Profile\Controller;
use Think\Controller;
class ProfileController extends Controller {
	public $user_model;
	public $comment_model;
	
    public function _initialize(){
		$this->user_model=D('user');
		$this->comment_model=D('comment');
	}
	
}