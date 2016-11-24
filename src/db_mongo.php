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
    /**
     * @desc ��ʼ��mongo
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
    /**
     * @desc ��������
     * @param collection_name ������
     * @param size �ռ��С 
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
            //�����̶����ϵ�
            return $this->db->createCollection( $collection_name,array('capped'=>true,'size'=>$size));
        }
        
    }
    /**
     * @desc ѡ�񼯺�
     * @param collection_name ��������
     */
    function selectCollection($collection_name)
    {
        return $this->db->selectCollection( $collection_name );
    }
    /**
     * @desc ѡ�����ݿ�
     * @param dbname ���ݿ�����
     */
    function select($dbname)
    {
        $this->db = $this->conn->selectDB($dbname);
	    return $this->db;
    }
    /**
     * @desc ��ȡ��Ϣ
     * @param collection_name string ��������
     * @param condition array ����
     * @param fields array �ֶ�
     */
    public function get_one( $collection_name='', $condition=array(), $fields=array(), $key='' )
    {

        $rs = $this->db->selectCollection($collection_name)->findOne($condition, $fields);
        return empty($key) ? $rs :( isset($rs[$key]) ? $rs[$key] : false );
    }
    /**
     * @desc ��������
     * @param collection_name ��������
     */
    public function insert( $collection_name='', $docarray=array(), $options=array() )
    {
    	return $this->db->selectCollection($collection_name)->insert( $docarray, $options );

    }
}
