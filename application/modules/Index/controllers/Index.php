<?php
/**
* 文件描述
*
* @author      liuwenyong 305381968@qq.com
* @date 2016-11-6 下午01:53:53
* @version 1.0.0
* @copyright  Copyright 2016 antiques.com
*/
class IndexController extends BaseContro{

	public function IndexAction(){
		
 		echo self::getView()->render("index.tpl");
	}
}