<?php

class BaseOperator
{

	protected $value;

	public function getValue()
	{

		return intval ( $this->value );
	}
}

class IncOperator extends BaseOperator
{

	public function __construct($value)
	{

		$this->value = intval ( $value );
	}
}

class DecOperator extends BaseOperator
{

	public function __construct($value)
	{

		$this->value = intval ( $value );
	}
}

class Data {
	private $mysqli;
	/**
	 * 数据库表名
	 * 
	 * @var string
	 */
	private $table;
	
	/**
	 * 数据库select操作列数组
	 * 
	 * @var array(string)
	 */
	private $arrSelect;
	
	/**
	 * 数据库insert操作列数组
	 * 
	 * @var array(string)
	 */
	private $arrInsert;
	
	/**
	 * 数据库update操作列数组
	 * 
	 * @var array(array(mixed))
	 */
	private $arrUpdate;
	
	/**
	 * SQL语句command
	 * 
	 * @var string
	 */
	private $command;
	/**
	 * 主键重复时需要更新的key
	 * 
	 * @var array
	 */
	private $arrDuplicateKey;
	
	/**
	 * SQL语句limit子句选取开始偏移量
	 * 
	 * @var int
	 */
	private $offset;
	
	/**
	 * SQL语句limit子句选取最大数量，被MAX_FETCH_SIZE所限制
	 * 
	 * @var int
	 */
	private $limit;
	
	/**
	 * 排序子句
	 * 
	 * @var array
	 */
	private $arrOrderBy;
	
	/**
	 * 用于id生成器生成唯一id
	 * 
	 * @var string
	 */
	private $uniqueKey;
	
	/**
	 * 判断条件
	 * 
	 * @var array
	 */
	private $arrWhere;
	
	/**
	 * 批量更新
	 * 
	 * @var BatchData
	 */
	private $batchData;
	
	/**
	 * 和 phpproxy的连接
	 * 
	 * @var PHPProxy
	 */
	private static $proxy = null;
	
	/**
	 * 清除缓存
	 * 
	 * @var bool
	 */
	private $noCache;
	
	/**
	 * 服务名称
	 * 
	 * @var string
	 */
	private $serviceName;
	
	/**
	 * 所使用的数据库名
	 * 
	 * @var string
	 */
	private $db;
	
	/**
	 * 查询缓存
	 * 
	 * @var IQueryCache
	 */
	public static $QUERY_CACHE = null;
	public function Data() {
		$this->mysqli = new mysqli ( MysqlDef::MYSQL_DB_IP, MysqlDef::MYSQL_DB_USER,  MysqlDef::MYSQL_DB_PASSWORD, MysqlDef::MYSQL_DB_NAME );
		if (mysqli_connect_error ()) {
			Logger::fatal ( 'Connect Error %d %s', mysqli_connect_errno (), mysqli_connect_error () );
		}
		$this->reset ();
	}
	public function init() {
		$this->mysqli = new mysqli ( MysqlDef::MYSQL_DB_IP, MysqlDef::MYSQL_DB_USER, MysqlDef::MYSQL_DB_PASSWORD, MysqlDef::MYSQL_DB_NAME );
		if (mysqli_connect_error ()) {
			Logger::fatal ( 'Connect Error %d %s', mysqli_connect_errno (), mysqli_connect_error () );
		}
	}
	public function query() {
		Logger::info('Data start query');
		if (empty ( $this->mysqli )) {
			$this->init ();
		}
		$command = self::queryArrayToStirng();
		Logger::info('final command:%s',$command);
		try{
			$result = $this->mysqli->query ( $command );
		}catch (Exception $e){
			Logger::fatal('mysql query error! %s', $e->getMessage());
		}
		if ($result!==TRUE && $result!==FALSE) {
			if ($result->num_rows > 0) { 
				while ( $row = $result->fetch_array () ) { 
					if (count ( $row ) >= count ( $this->arrSelect )) {
						$arrTmpRow = array ();
						foreach ( $this->arrSelect as $col ) {
							$arrTmpRow [$col] = $row [$col];
						}
						$arrReturn [] = $arrTmpRow;
					} else {
						Logger::debug('%d < %d',count ( $row ),count ( $this->arrSelect ));
						$arrReturn [] = $row;
					}
				}
			}
		}
		if ($result!==TRUE && $result!==FALSE) {
			$result->free ();
		}
		if($result==FALSE){
			Logger::fatal('mysql query error!');
			echo "no result</p>";
		}
		$this->mysqli->close ();
		if(isset($arrReturn)){
			return $arrReturn;
		}
	}
	public function queryArrayToStirng() {
		$finalCommand = '';
		switch ($this->command) {
			case 'select' :
				$finalCommand .= 'select ';
				$finalCommand .= implode ( ',', $this->arrSelect );
				$finalCommand .= ' from ' . $this->table;
				$finalCommand .= ' where ';
				$wnum=0;
				foreach ( $this->arrWhere as $key => $value ) {
					if($wnum++>0){
						$finalCommand .= ' and ';
					}
					switch ($value [0]) {
						case '>' :
						case '<' :
						case '>=' :
						case '<=' :
						case '!=' :
						case '=' :
							$finalCommand .= $key . ' '. $value [0] . $value [1];
							break;
						case 'BETWEEN' :
							$finalCommand .= $key . ' '. $value [0] . $value [1] [0] . ' and ' . $value [1] [1];
							break;
						case 'NOT IN' :
						case 'IN' :
							$finalCommand .= $key . ' '. $value [0] . ' (' . implode ( ',', $value ) . ')';
							break;
						case '==' :
						case '!==' :
						case 'LIKE' :
							$finalCommand .= $key . ' '. $value [0] . ' "' . $value [1] . '"';
							break;
						default :
							$this->reset ();
							Logger::fatal ( "unsupported operand %s", $key );
							throw new Exception ( "inter" );
					}
				}
				break;
			case 'insertInto':
			case 'insertIgnore':
				Logger::debug('arrinsert %s', $this->arrInsert);
				$finalCommand .= 'insert into';
				$finalCommand .= ' '.$this->table;
				$keyString='';
				$valueString='';
				foreach ($this->arrInsert as $key=>$value){
					Logger::debug('insert %s %s',$key, $value);
					if(substr($key,0,2)=='va'){
						$value[1]=serialize($value[1]);
					}
					$keyString .= $key.',';
					if(stristr($key,'name') != false || stristr($key,'mail') != false){
						$valueString .= '"'.$value[1].'",';
					}else{
						$valueString .=$value[1].',';
					}
				}
				$keyString = substr($keyString, 0, -1);
				$valueString = substr($valueString, 0, -1);
				$finalCommand .= '('.$keyString.') values ('.$valueString.')';
				break;
			case 'update':
				Logger::debug('arrUpdate %s', $this->arrUpdate);
				$finalCommand .= 'update';
				$finalCommand .= ' '.$this->table;
				$keyString='';
				$valueString='';
				foreach ($this->arrUpdate as $key=>$value){
					Logger::debug('update %s %s',$key, $value);
					if(substr($key,0,2)=='va'){
						$value[1]=serialize($value[1]);
					}
					$keyString .= $key.',';
					if(stristr($key,'name') != false || stristr($key,'mail') != false){
						$valueString .= '"'.$value[1].'",';
					}else{
						$valueString .=$value[1].',';
					}
				}
				$keyString = substr($keyString, 0, -1);
				$valueString = substr($valueString, 0, -1);
				$finalCommand .= '('.$keyString.') values ('.$valueString.')';
				break;
				
		}
		$finalCommand .= ';';
		Logger::info ( 'final command: %s', $finalCommand );
		return $finalCommand;
	}
	
	public function select($selectFields) {
		$this->command = 'select';
		$this->arrSelect = $selectFields;
		return $this;
	}
	
	/**
	 * insert into子句
	 *
	 * @param string $table
	 *
	 * @return CData
	 *
	 * @author
	 */
	public function insertInto($table)
	{
	
		$this->from ( $table );
		$this->command = 'insertInto';
		return $this;
	}
	
	/**
	 * insert ignore子句
	 *
	 * @param string $table
	 *
	 * @return CData
	 *
	 * @author
	 */
	public function insertIgnore($table)
	{
	
		$this->from ( $table );
		$this->command = 'insertIgnore';
		return $this;
	}
	
	/**
	 * update子句
	 *
	 * @param string $table
	 *
	 * @return CData
	 *
	 * @author
	 */
	public function update($table)
	{
	
		$this->setTable ( $table );
		$this->command = 'update';
		return $this;
	}
	
	/**
	 * set子句
	 *
	 * @param array $arrUpdate
	 *
	 * @return CData
	 *
	 * @throws Exception 如果command!=update,则throw Exception
	 *
	 * @author
	 */
	public function set($arrUpdate)
	{
	
		if ($this->command != 'update')
		{
			$this->reset ();
			Logger::fatal ( "call update first" );
			throw new Exception ( "inter" );
		}
		$this->arrUpdate = $this->escapeBody ( $arrUpdate );
		return $this;
	}
	
	/**
	 * value子句
	 *
	 * @param array $arrInsert array("uid"=>1, "name"=>"name")
	 *
	 * @return CData
	 *
	 * @throws 如果command!=insert***,或者参数为空,则会throw Exception
	 *
	 * @author
	 */
	public function values($arrInsert)
	{
	
		if (substr ( $this->command, 0, 6 ) != 'insert')
		{
			$this->reset ();
			Logger::fatal ( "call insertXXX first" );
			throw new Exception ( "inter" );
		}
	
		if (empty ( $arrInsert ))
		{
			$this->reset ();
			Logger::fatal ( "empty values not allowed" );
			throw new Exception ( "inter" );
		}
		$this->arrInsert = $this->escapeBody ( $arrInsert );
		return $this;
	}
	
	public function from($table) {
		$this->table = $table;
		return $this;
	}
	/**
	 * where子句(只支持单条件查询,判断操作子不支持对于非数字的>,<,<=,>=操作,支持=,!=)
	 *
	 * @param array(mixed) $arrRow
	 *        	array("uid", "=", 1)
	 *        	
	 * @return CData
	 *
	 * @throws Exception 如果参数为空,操作描述数组元素数不正确,对于判断操作子进行非数字的>,<,>=,<=操作,<br />
	 *         between和IN子句不符合要求,则会throw Exception
	 *        
	 * @author
	 *
	 */
	public function where() {
		$arrRow = func_get_args ();
		if (is_array ( $arrRow ) && count ( $arrRow ) == 1) {
			$arrRow = $arrRow [0];
		}
		
		if (empty ( $arrRow )) {
			$this->reset ();
			Logger::fatal ( "where can't be empty" );
			throw new Exception ( "inter" );
		}
		
		$arrOp = array ();
		if (count ( $arrRow ) != 3) {
			$this->reset ();
			Logger::fatal ( "invalid option, three values required" );
			throw new Exception ( "inter" );
		}
		$key = $arrRow [0];
		$op = strtoupper ( $arrRow [1] );
		$value = &$arrRow [2];
		switch ($op) {
			case '>' :
			case '<' :
			case '>=' :
			case '<=' :
			case '!=' :
			case '=' :
				if (! is_numeric ( $value )) {
					$this->reset ();
					Logger::fatal ( "operand %s must operate on number, field:%s is not", $op, $key );
					throw new Exception ( "inter" );
				}
				$value = intval ( $value );
				break;
			case 'BETWEEN' :
				if (! is_array ( $value ) || count ( $value ) != 2 || ! is_numeric ( $value [0] ) || ! is_numeric ( $value [1] )) {
					$this->reset ();
					Logger::fatal ( "invalid BETWEEN value, only 2-value numeric array required" );
					throw new Exception ( "inter" );
				}
				$value [0] = intval ( $value [0] );
				$value [1] = intval ( $value [1] );
				break;
			case 'NOT IN' :
			case 'IN' :
				if (! is_array ( $value ) || empty ( $value )) {
					$this->reset ();
					Logger::fatal ( "invalid IN value, only not empty array required" );
					throw new Exception ( "inter" );
				}
				
				$value = array_unique ( $value );
				$value = array_merge ( $value );
				
				if (count ( $value ) > self::MAX_FETCH_SIZE) {
					$this->reset ();
					Logger::fatal ( "too much value for IN" );
					throw new Exception ( "inter" );
				}
				
				foreach ( $value as $index => $col ) {
					if (! is_numeric ( $col )) {
						$this->reset ();
						Logger::fatal ( "only number is required for IN" );
						throw new Exception ( "inter" );
					}
					$value [$index] = intval ( $col );
				}
				break;
			case '==' :
			case '!==' :
			case 'LIKE' :
				$value = strval ( $value );
				break;
			default :
				$this->reset ();
				Logger::fatal ( "unsupported operand %s", $op );
				throw new Exception ( "inter" );
		}
		
		if (isset ( $this->arrWhere [$key] )) {
			Logger::fatal ( "key:%s already exists in where", $key );
			$this->reset ();
			throw new Exception ( 'inter' );
		}
		
		$this->arrWhere [$key] = array (
				$op,
				$value 
		);
		return $this;
	}
	public function limit($limit){
		$this->limit=$limit;
	}
	
	/**
	 * 转义数组,如果数据不是number或者string,则throw Exception
	 *
	 * @param array(mixed) $arrBody
	 *
	 * @return array(mixed)
	 *
	 * @throws Exception
	 *
	 * @author
	 */
	private function escapeBody($arrBody)
	{
	
		foreach ( $arrBody as $col => $value )
		{
			if ($value instanceof IncOperator)
			{
				$arrBody [$col] = array ("+=", $value->getValue () );
				$this->noCache = true;
			}
			else if ($value instanceof DecOperator)
			{
				$arrBody [$col] = array ("-=", $value->getValue () );
				$this->noCache = true;
			}
			else
			{
				$arrBody [$col] = array ('=', $value );
			}
		}
		return $arrBody;
	}
	/**
	 * 重置条件
	 */
	public function reset() {
		$this->limit = null;
		$this->offset = null;
		$this->uniqueKey = null;
		$this->table = null;
		$this->command = null;
		$this->arrInsert = array ();
		$this->arrSelect = array ();
		$this->arrUpdate = array ();
		$this->arrWhere = array ();
		$this->arrOrderBy = array ();
		$this->arrDuplicateKey = array ();
		$this->noCache = false;
		$this->db = MysqlDef::MYSQL_DB_NAME;
	}
	function __destruct() {
	}
}
