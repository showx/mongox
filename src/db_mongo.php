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
    //初始化mongo
    function init_mongo($host,$username,$password,$dbname)
    {
        try 
        {
            //使用经典的Mongo,没使用MongoClient
            $this->conn = new Mongo($host, array('timeout'=>500, 'connect'=>true) );
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
    //创建集合
    function createCollection($collection_name)
    {
        return $this->db->createCollection( $collection_name );
    }
    //选择集合
    function selectCollection($collection_name)
    {
        return self::$db->selectCollection( $collection_name );
    }
    //选择数据库
    function select($dbname)
    {
            $this->db = $this->conn->selectDB($dbname);
    }
    //获取信息
     public function get_one( $collection_name='', $condition=array(), $fields=array(), $key='' )
    {
        if( !isset( self::$cols[$collection_name] ) )
        {
            self::$cols[$collection_name] = self::select( $collection_name );
        }
        $rs = self::$cols[$collection_name]->findOne($condition, $fields);
        return empty($key) ? $rs :( isset($rs[$key]) ? $rs[$key] : false );
    }
    //插入
    public function insert( $collection_name='', $docarray=array(), $options=array() )
    {
        if( !isset( self::$cols[$collection_name] ) )
        {
            self::$cols[$collection_name] = self::select( $collection_name );
        }
        return self::$cols[$collection_name]->insert( $docarray, $options );
    }
}
