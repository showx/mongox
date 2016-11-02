<?php
namespace mongox;
/**
 * php mongo��չ������
 * @Author:show
 */
class db_mongo implements mongo_base
{
    public $conn;
    public $db;
    public $cols;
    public $dbname;
    //��ʼ��mongo
    function init_mongo($host,$username,$password,$dbname)
    {
        try 
        {
            //ʹ�þ����Mongo,ûʹ��MongoClient
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
    //��������
    function createCollection($collection_name)
    {
        return $this->db->createCollection( $collection_name );
    }
    //ѡ�񼯺�
    function selectCollection($collection_name)
    {
        return self::$db->selectCollection( $collection_name );
    }
    //ѡ�����ݿ�
    function select($dbname)
    {
            $this->db = $this->conn->selectDB($dbname);
    }
    //��ȡ��Ϣ
     public function get_one( $collection_name='', $condition=array(), $fields=array(), $key='' )
    {
        if( !isset( self::$cols[$collection_name] ) )
        {
            self::$cols[$collection_name] = self::select( $collection_name );
        }
        $rs = self::$cols[$collection_name]->findOne($condition, $fields);
        return empty($key) ? $rs :( isset($rs[$key]) ? $rs[$key] : false );
    }
    //����
    public function insert( $collection_name='', $docarray=array(), $options=array() )
    {
        if( !isset( self::$cols[$collection_name] ) )
        {
            self::$cols[$collection_name] = self::select( $collection_name );
        }
        return self::$cols[$collection_name]->insert( $docarray, $options );
    }
}
