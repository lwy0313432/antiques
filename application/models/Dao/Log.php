<?php
class Dao_LogModel  extends Dao_CommonAntiquesModel {
 	private static $table_name = 'devel_log';
	
	private static $log_level = 16;
	private static $log_path = "log"; 
	private static $auto_rotate=1;
	private static $database_name='antiques';
	public static function warning($msg,$vars=array()){
		self::write_log('warning',$msg,$vars);
	}
	public static function notice($msg,$vars=array()){
		self::write_log('notice',$msg,$vars);
	}
	public static function red($msg,$vars=array()){
		self::write_log('red',$msg,$vars);
	}
	private static function write_log($type,$msg,$vars){
		$trace = debug_backtrace();
		$current_file = isset( $trace[1]['file'] ) 
			                  ? $trace[1]['file'] : "" ;
		$current_line = isset( $trace[1]['line'] ) 
			                  ? $trace[1]['line'] : "";
		$current_function = isset( $trace[2]['function'] ) 
			                      ? $trace[2]['function'] : "";
		$current_class = isset( $trace[2]['class'] ) 
			                   ? $trace[2]['class'] : "" ;
		 
		if (is_array($vars)) {
			$vars = serialize($vars);
		} elseif(empty($vars)) {
			$vars = '';
		}
		$path = $current_file.";".$current_class.'::'.$current_function.'@'.$current_line;
		$arrInput = array(
			"flag"=>$type,
			"msg"=>$msg,
			"filename"=>$path,
			"vars"=>$vars,
			"dt"=>date("Y-m-d H:i:s"),
		); 
 		Common_Db::Factory(self::$database_name)->insert(self::$table_name, $arrInput);
	}
	
	 
	private static function environment_content(){
		
		$str_content  = "[".date("Y-m-d H:i:s")."] ";
		if(PHP_SAPI != "cli"){
			$str_content .= "[IP:".Common_Tools::getip()."] ";
			$str_content .= "[URI:".@$_SERVER['REQUEST_URI']."] ";
			
			$str_content .= "[REFER:".@$_SERVER['HTTP_REFERER']."] ";
			$str_content .= "[COOKIE:".json_encode($_COOKIE)."] ";
		}
		return $str_content;
	}
	private static function get_file_name($type){
		$dir_name="";
		if(defined('LOG_PATH') && LOG_PATH !=""){
			$dir_name = LOG_PATH;
		}else{
			$dir_name = PATH_BASE_SRC.DIRECTORY_SEPARATOR.self::$log_path;
		}
		$dir_name .= DIRECTORY_SEPARATOR.APP_NAME;  //$file_name="/home/work/log/hf";
		if(!is_dir($dir_name)){
			mkdir($dir_name);  //创建模块级别的目录；
		}
		$file_name = "";
		switch($type){
			case "warning":		$file_name	.=	"error.log";	break;
			case "notice":		$file_name 	.=	"notice.log";	break;
			default: 			$file_name 	.=	"log.log";		break;
		}
		if(self::$auto_rotate){
			$file_name .= date("Ymd");
		}
		return $dir_name.DIRECTORY_SEPARATOR.$file_name;
	}
 
}
