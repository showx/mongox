<?php
namespace mongox;
/**
 * php mongo扩展操作类
 * @Author:show
 */
class db_mongo implements mongo_base
{
    public $conn;
    public $db;
    public $cols;
    public $dbname;
    public function  __construct($host,$username,$password,$dbname='')
    {
        self::init_mongo($host,$username,$password,$dbname);
    }
    /**
     * @desc 初始化mongo
     */
    function init_mongo($host,$username,$password,$dbname='')
    {
        try 
        {
            $authString = "";
            if ($username && $password) {
                $authString = "{$username}:{$password}@";
            }
            // $replicaSet && $replicaSet = '?replicaSet='.$replicaSet;
            $dsn = "mongodb://{$authString}{$host}";

            //使用经典的Mongo,没使用MongoClient
            $this->conn = new \Mongo($dsn, array('timeout'=>500, 'connect'=>true) );
            if($dbname)
            {
                self::select($dbname);
            }
        }
        catch ( MongoConnectionException $e ) 
        {
            echo 'mongo die';
        }
    }
    /**
     * @desc 创建集合
     * @param collection_name 集合名
     * @param size 空间大小 
     */
    function createCollection($collection_name,$size='')
    {
        if(empty($size) && !empty(MONGOX_SIZE))
        {
            $size = MONGOX_SIZE;
        }
        if(!empty($size))
        {
            return $this->db->createCollection( $collection_name );
        }else{
            //创建固定集合的
            return $this->db->createCollection( $collection_name,array('capped'=>true,'size'=>$size));
        }
        
    }
    /**
     * @desc 选择集合
     * @param collection_name 集合名称
     */
    function selectCollection($collection_name)
    {
        return $this->db->selectCollection( $collection_name );
    }
    /**
     * @desc 选择数据库
     * @param dbname 数据库名称
     */
    function select($dbname)
    {
        $this->db = $this->conn->selectDB($dbname);
	    return $this->db;
    }
    /**
     * @desc 获取信息
     * @param collection_name string 集合名称
     * @param condition array 条件
     * @param fields array 字段
     */
    public function get_one( $collection_name='', $condition=array(), $fields=array(), $key='' )
    {

        $rs = $this->db->selectCollection($collection_name)->findOne($condition, $fields);
        return empty($key) ? $rs :( isset($rs[$key]) ? $rs[$key] : false );
    }
    /**
     * @desc 插入数据
     * @param collection_name 集合名称
     */
    public function insert( $collection_name='', $docarray=array(), $options=array() )
    {
    	return $this->db->selectCollection($collection_name)->insert( $docarray, $options );

    }
}
