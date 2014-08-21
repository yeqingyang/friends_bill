<?php
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
		$this->mysqli = new mysqli ( MysqlDef::MYSQL_DB_IP, MysqlDef::MYSQL_DB_USER, "", MysqlDef::MYSQL_DB_NAME );
		if (mysqli_connect_error ()) {
			Logger::fatal ( 'Connect Error %d %s', mysqli_connect_errno (), mysqli_connect_error () );
		}
		$this->reset ();
	}
	public function init() {
		$this->mysqli = new mysqli ( MysqlDef::MYSQL_DB_IP, MysqlDef::MYSQL_DB_USER, "", MysqlDef::MYSQL_DB_NAME );
		if (mysqli_connect_error ()) {
			Logger::fatal ( 'Connect Error %d %s', mysqli_connect_errno (), mysqli_connect_error () );
		}
		$this->reset ();
	}
	public function query($query) {
		if (empty ( $this->mysqli )) {
			$this->init ();
		}
		try{
		$result = $this->mysqli->query ( $query );
		}catch (Exception $e){
			Logger::fatal('mysql query error! %s', $e->getMessage());
		}
		$return = array ();
		if ($result) {
			if ($result->num_rows > 0) { 
				while ( $row = $result->fetch_array () ) { 
					Logger::info ( "data $row %s", $row );
					$return [] = $row;
				}
				if (count ( $return ) > count ( $this->arrSelect )) {
					$arrTmpRow = array ();
					foreach ( $this->arrSelect as $col ) {
						$arrTmpRow [$col] = $return [$col];
					}
					$arrReturn [] = $arrTmpRow;
				} else {
					$arrReturn [] = $return;
				}
			}
		}
		if (! empty ( $result )) {
			$result->free ();
		}
		$this->mysqli->close ();
		return $arrReturn;
	}
	public function queryArrayToStirng() {
		$finalCommand = '';
		switch ($this->command) {
			case 'select' :
				$finalCommand .= 'select ';
				$finalCommand .= implode ( ',', $this->arrSelect );
				$finalCommand .= ' from ' . $this->table;
				$finalCommand .= ' where';
				foreach ( $this->arrWhere as $key => $value ) {
					switch ($value [0]) {
						case '>' :
						case '<' :
						case '>=' :
						case '<=' :
						case '!=' :
						case '=' :
							$finalCommand .= $key . $value [0] . $value [1];
							break;
						case 'BETWEEN' :
							$finalCommand .= $key . $value [0] . $value [1] [0] . ' and ' . $value [1] [1];
							break;
						case 'NOT IN' :
						case 'IN' :
							$finalCommand .= $key . $value [0] . ' (' . implode ( ',', $value ) . ')';
							break;
						case '==' :
						case '!==' :
						case 'LIKE' :
							$finalCommand .= $key . $value [0] . '\"' . $value [1] . '\"';
							break;
						default :
							$this->reset ();
							Logger::fatal ( "unsupported operand %s", $key );
							throw new Exception ( "inter" );
					}
				}
				$finalCommand .= ';';
		}
		Logger::info ( 'final command: %s', $finalCommand );
		return $finalCommand;
	}
	public function select($selectFields) {
		$this->command = 'select';
		$this->arrSelect = $selectFields;
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
