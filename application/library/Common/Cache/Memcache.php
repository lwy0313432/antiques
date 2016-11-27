<?php

class Common_Cache_Memcache
{
	private $mc = array();
	private $mc_cfg = array();
	private static $type = null;
    private static $in = array();
    public static $connected = array();

    public static function Factory($t=1)
    {
		self::$type = $t;
		if(!isset(self::$connected[$t])) self::$connected[$t] = true;
        if(!isset(self::$in[$t])) self::$in[$t] = new self($t);
		if(false === self::$connected[$t]) return false;
        return self::$in[$t];
    }

    public function __construct($t)
    {
		$this->mc_cfg = Yaf_Registry::get("config")->get("mc");

		if(!isset($this->mc_cfg[$t])){
			Data_LogModel::red('Memcached conf error', array('mc_cfg' => $this->mc_cfg,'t'=>$t));
			self::$connected[$t] = false;
			return false;
		}

        $this->mc[$t]=new Memcache;
		if(@!$this->mc[$t]->connect($this->mc_cfg[$t]['host'], $this->mc_cfg[$t]['port'])){
			Data_LogModel::red('Memcached can\'t connected', array($this->mc_cfg[$t]));
			self::$connected[$t] = false;
			return false;
		}
    }

    public function set($key,$value,$expire=0,$iszip=false)
    {
        $expire>0 && $expire=self::setLifeTime($expire);
        return $this->mc[self::$type]->set($this->mc_cfg[self::$type]['pre'].$key,$value,$iszip,$expire);
    }

    public function get($key)
    {
        return $this->mc[self::$type]->get($this->mc_cfg[self::$type]['pre'].$key);
    }

    public function add($key,$value,$expire=0,$iszip=false)
    {
        $expire>0 && $expire=self::setLifeTime($expire);
        return $this->mc[self::$type]->add($this->mc_cfg[self::$type]['pre'].$key,$value,$iszip,$expire);
    }

    public function replace($key,$value,$expire=0,$iszip=false)
    {
        $expire>0 && $expire=self::setLifeTime($expire);
        return $this->mc[self::$type]->replace($this->mc_cfg[self::$type]['pre'].$key,$value,$iszip,$expire);
    }

    public function isKey($key)
    {
        if($this->mc[self::$type]->add($this->mc_cfg[self::$type]['pre'].$key,1)){
            $this->mc[self::$type]->delete($key,0);
            return false;
        }else{
            return true;
        }
    }

    public function del($key,$expire=0)
    {
        return $this->mc[self::$type]->delete($this->mc_cfg[self::$type]['pre'].$key,$expire);
    }

	//使所有元素失效 注意：本操作不能真正释放资源
	public function clear() {
		return $this->mc[self::$type]->flush();
	}

	//数值加n
	public function inc($key, $step=1) {
		return $this->mc[self::$type]->increment($key, $step);
	}

	//数值减n
	public function dec($key, $step=1) {
		return $this->mc[self::$type]->decrement($key, $step);
	}

    private function setLifeTime($t)
    {
        if(!is_numeric($t)){
            switch(substr($t,-1)){
                case 'w':
                    $t=(int)$t*7*24*3600;
                    break;
                case 'd':
                    $t=(int)$t*24*3600;
                    break;
                case 'h':
                    $t=(int)$t*3600;
                    break;
                case 'i':
                    $t=(int)$t*60;
                    break;
                default:
                    $t=(int)$t;
                    break;
            }
        }
        if($t>2592000) Data_LogModel::red('Memcached Backend has a Limit of 30 days (2592000 seconds) for the LifeTime', array());
        return $t;
    }

}

?>