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
    public function  __construct($host,$username,$password,$dbname='')
    {
        self::init_mongo($host,$username,$password,$dbname);
    }
    //��ʼ��mongo
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

            //ʹ�þ����Mongo,ûʹ��MongoClient
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
    //��������
    function createCollection($collection_name)
    {
        return $this->db->createCollection( $collection_name );
    }
    //ѡ�񼯺�
    function selectCollection($collection_name)
    {
        return $this->db->selectCollection( $collection_name );
    }
    //ѡ�����ݿ�
    function select($dbname)
    {
            $this->db = $this->conn->selectDB($dbname);
	    return $this->db;
    }
    //��ȡ��Ϣ
     public function get_one( $collection_name='', $condition=array(), $fields=array(), $key='' )
    {

        $rs = $this->db->selectCollection($collection_name)->findOne($condition, $fields);
        return empty($key) ? $rs :( isset($rs[$key]) ? $rs[$key] : false );
    }
    //����
    public function insert( $collection_name='', $docarray=array(), $options=array() )
    {
    	return $this->db->selectCollection($collection_name)->insert( $docarray, $options );
    
        /*
	if( !isset( $this->cols[$collection_name] ) )
        {
            $this->cols[$collection_name] = $this->select( $collection_name );
        }
        return $this->cols[$collection_name]->insert( $docarray, $options );
	*/
    }
}
