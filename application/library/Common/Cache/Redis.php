<?php
/**
 * Yindou Framework
 * (C)2015-2020 Yindou Inc. (http://www.yindou.com)
 *
 * Redis 缓存基类
 *
 * @Package Yindou Index
 * @version $Id: Redis.php 770 2015-09-06 06:15:37Z wuwenjia $
 **/

class Common_Cache_Redis
{

    public $mc;//连接成功时的标识
    private $rdscfg = null;
    private static $in = null;
	public static $connected = true;

    /**
     * 预留方法 扩展使用
     *
     **/
    public static function Factory()
    {
        if(null===self::$in) self::$in = new self();
		if(false===self::$connected) return false;
        return self::$in;
    }

    /**
     * 初始化对象
     *
     * @return void
     **/
    public function __construct()
    {
		$_config = Yaf_Registry::get("config")->get("rds");
        $this->rdscfg = isset($_config['data']) ? $_config['data'] : null;

        if(!isset($this->rdscfg['host']) || !isset($this->rdscfg['port'])){
            Data_LogModel::warning('Redis@config.ini is empty,it\'s use default config now', array($this->rdscfg));
            $this->rdscfg['host'] = '10.163.107.160'; //服务器内网ip 只能内网访问
            $this->rdscfg['port'] = '6379';
        }

        $this->mc=new Redis();
        if(@!$this->mc->connect($this->rdscfg['host'],$this->rdscfg['port'])){
            Data_LogModel::red('Redis can\'t connected', array($this->rdscfg));
            self::$connected = false;
        }

        !isset($this->rdscfg['pre']) && $this->rdscfg['pre']='xs_';
    }

    /**
     * 选择redis库
     * 切换操作的redis库
     * @param int $db
     */
    public function selectDB($dbn)
    {
        if($dbn>=0)
            $this->mc->select($db);
    }

    /**
     * 与set方法相同
     * 唯一的区别是: 增加对数组序列化功能
     *
     * @param  string $key    数据的标识
     * @param  string $value  实体内容
     * @param  string $expire 过期时间单位秒
     * @return bool
     **/
    public function sets($key,$value,$expire=0)
    {
        $expire>0 && $expire=self::setLifeTime($expire);
        return $expire>0 ? $this->mc->setex($this->rdscfg['pre'].$key,$expire,$value) : $this->mc->set($this->rdscfg['pre'].$key,$value);
    }

    /**
     * 获取数据缓存
     *
     * @param  string $key    数据的标识
     * @return string
     **/
    public function gets($key)
    {
        return $this->mc->get($this->rdscfg['pre'].$key);
    }

    /**
     * 设置数据缓存
     * 与add|replace比较类似
     * 唯一的区别是: 无论key是否存在,是否过期都重新写入数据
     *
     * @param  string $key    数据的标识
     * @param  string $value  实体内容
     * @param  string $expire 过期时间[天d|周w|小时h|分钟i] 如:8d=8天 默认为0永不过期
     * @param  bool   $iszip  是否启用压缩
     * @return bool
     **/
    public function set($key,$value,$expire=0)
    {
        $value=self::rdsCode($value,1);

        $expire>0 && $expire=self::setLifeTime($expire);
        return $expire>0 ? $this->mc->setex($this->rdscfg['pre'].$key,$expire,$value) : $this->mc->set($this->rdscfg['pre'].$key,$value);
    }

    /**
     * 获取数据缓存
     *
     * @param  string $key    数据的标识
     * @return string
     **/
    public function get($key)
    {
        $value=$this->mc->get($this->rdscfg['pre'].$key);
        return $value ? self::rdsCode($value) : $value;
    }

    /**
     * 新增数据缓存
     * 只有当key不存,存在但已过期时被设值
     *
     * @param  string $key    数据的标识
     * @param  string $value  实体内容
     * @param  string $expire 过期时间[天d|周w|小时h|分钟i] 如:8d=8天 默认为0永不过期
     * @param  bool   $iszip  是否启用压缩
     * @return bool   操作成功时返回ture,如果存在返回false否则返回true
     **/
    public function add($key,$value,$expire=0)
    {
        if($expire>0){
            $expire=self::setLifeTime($expire);
            if($this->mc->exists($this->rdscfg['pre'].$key)){
                return false;
            }else{
                return $this->set($key,$value,$expire);
            }
        }else{
            $value=self::rdsCode($value,1);
            return $this->mc->setnx($this->rdscfg['pre'].$key,$value);
        }
    }

    /**
     * 替换数据
     * 与 add|set 参数相同,与set比较类似
     * 唯一的区别是: 只有当key存在且未过期时才能被替换数据
     *
     * @param  string $key    数据的标识
     * @param  string $value  实体内容
     * @param  string $expire 过期时间[天d|周w|小时h|分钟i] 如:8d=8天 默认为0永不过期
     * @param  bool   $iszip  是否启用压缩
     * @return bool
     **/
    public function replace($key,$value,$expire=0)
    {
        if(self::iskey($key)){
            return self::set($key,$value,$expire);
        }
        return false;
    }

    /**
     * 检测缓存是否存在
     *
     * @param  string $key 数据的标识
     * @return bool
     **/
    public function isKey($key)
    {
        return $this->mc->exists($this->rdscfg['pre'].$key);
    }

    /**
     * 删除一个数据缓存
     *
     * @param  string $key    数据的标识
     * @param  string $expire 删除的等待时间,好像有问题尽量不要使用
     * @return bool
     **/
    public function del($key)
    {
        return $this->mc->del($this->rdscfg['pre'].$key);
    }


    /**
     * 格式化过期时间
     * 注意: 限制时间小于2592000=30天内
     *
     * @param  string $t 要处理的串
     * @return int
     **/
    private function setLifeTime($t)
    {
        if(!is_numeric($t)){
            switch(substr($t,-1)){
                case 'w'://周
                    $t=(int)$t*7*24*3600;
                    break;
                case 'd'://天
                    $t=(int)$t*24*3600;
                    break;
                case 'h'://小时
                    $t=(int)$t*3600;
                    break;
                case 'i'://分钟
                    $t=(int)$t*60;
                    break;
                default:
                    $t=(int)$t;
                    break;
            }
        }
        if($t>2592000) self::showMsg('Memcached Backend has a Limit of 30 days (2592000 seconds) for the LifeTime');
        return $t;
    }

    /**
     * 编码解码
     *
     * @param  string $str 串
     * @param  string $tp  类型,1编码0为解码
     * @return array|string
     **/
    private function rdsCode($str,$tp=0)
    {
        return $tp ? @serialize($str) : @unserialize($str);
    }

    //返回所有$str开头的key
    public function keys($str)
    {
        $rs = $this->mc->keys($this->rdscfg['pre'].$str.'*');
        if(is_array($rs) && count($rs)>0){
            foreach($rs as $k=>$v){
                $rs[$k] = str_replace($this->rdscfg['pre'],'',$v);
            }
        }
        return $rs;
    }
    public function lpush($queue_name, $str)
    {
        return $this->mc->lpush($queue_name, $str);
    }
    public function rpop($queue_name)
    {
        return $this->mc->rpop($queue_name);
    }
    public function lsize($queue_name)
    {
        return $this->mc->lsize($queue_name);
    }

}
