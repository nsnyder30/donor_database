<?php
//----------------------------------------------------DESCRIPTION---------------------------------------------------------//
# This plugin defines a class for establishing connectiosn to various datasources. 
# This is primarily used for MySQL database connections.
//------------------------------------------------------------------------------------------------------------------------//

class dataSource
{
	//-----------------------------------------------DEFINE CLASS VARIABLES-----------------------------------------------//
	private $source_array = array();
	private $connection;
	private $conntype;
	private $multi_query = FALSE;
	public $source;
	public $result;
	public $err_message;
	//--------------------------------------------------------------------------------------------------------------------//


	//--------------------------------------------DEFINE CONSTRUCTOR FUNCTION---------------------------------------------//
	// Takes an input source as a string and reads the corresponding connection credentials from the dataSource class. 
	// It then attempts to establish a connection and returns an error if the connection can't be established.
	function __construct($inputsource, $err_mode = 'html') {
		$this->source = $inputsource;
		$creds = parse_ini_file($GLOBALS['cfg_file'], TRUE, INI_SCANNER_RAW)[$inputsource];
		$this->conntype = $creds['conntype'];
		$this->connection = mysqli_connect($creds['server'], $creds['user'], $creds['password'], $creds['db']);
		
		switch($this->conntype)
		{
			case 'MySQL':
				$this->connection = mysqli_connect($creds['server'], $creds['user'], $creds['password'], $creds['db']);
				$this->connection->set_charset("utf8");
				break;
			case 'PDO':
				$conn = 'mysql:host='.$creds['server'].';dbname='.$creds['db'].';charset=utf8';
				$options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
								 PDO::ATTR_EMULATE_PREPARES => false, 
								 PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
				$this->connection = new PDO($conn, $creds['user'], $creds['password'], $options);
				$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				break;
			default:
				$this->connection = null;
				trigger_error('Connection type not recognized: '.$this->dataSource_list[$this->Current_source]['Type']);
		}

		if ($this->conntype == 'MySQL' && $this->connection->connect_errno)
		{
			$this->err_message = "Error: Failed to connect to MySQL database <font color='blue'><b>" . $this->source . "</b></font><br>";
			$this->err_message .= "Error Number: " . $this->connection->connect_errno . "<br>";
			$this->err_message .= "Error Description: " . $this->connection->mysqli->connect_error. "<br>";
			$this->result = FALSE;
		}	 else {

		}	
	}
	//--------------------------------------------------------------------------------------------------------------------//
	

	//----------------------------------------------FUNCTION: EXECUTE QUERY-----------------------------------------------//
	// Takes an input query as text and attempts to execute using established connection. 
	// Returns error text with query information if the query fails or returns no results.
	public function execQuery($query, $multi_query = FALSE, $params = array())
	{
		$this->result = NULL;
		$this->multi_query = $multi_query;
		if($multi_query)
		{
			$this->result = NULL;
			switch($this->conntype)
			{
				case 'MySQL':
					if (!$this->result = $this->connection->query($query))
					{
						$this->err_message = "Error: MySQL query failed to execute.<br>";
						$this->err_message .= "Error Number: " . $this->connection->errno . "<br>";
						$this->err_message .= "Error Description: " . $this->connection->error. "<br>";
						$this->err_message .= "Query Text:<br>";
						$this->err_message .= $query;
						print_r($this->err_message);
						$this->result = FALSE;
					}
					elseif (stripos($query, 'SELECT') !== FALSE)
					{
						if($this->result->num_rows == 0)
						{
							$this->err_message = "The data query for <font color='blue'><b>" . $this->source . "</b></font> returned 0 results.";
							$this->err_message .= "Query Text: <br>";
							$this->err_message .= $query;
							print_r($this->err_message);
							$this->result = FALSE;
						}
					} else {
					}
					break;
				case 'ODBC':
					$this->result = FALSE;
					break;
				case 'PDO':
					try
					{
						$this->connection->beginTransaction();
						if(!is_array($query))
							{$queries = array($query);}
						else
							{$queries = $query;}
						$this->resultset = array();
						if(count($params) == 0)
							{$params[] = array('query_key' => 0, 'params' => array());}

						foreach($params as $parray)
						{
							if(isset($parray['query_key']))
								{$query_key = $parray['query_key'];}
							else
								{$query_key = 0;}
							$stmt = $this->connection->prepare($queries[$query_key]);
							$stmt->execute($parray['params']);
							if($stmt->rowCount() > 0)
								{$this->resultset[] = $stmt;
							}
						}						
						$this->connection->commit();
						if(count($this->resultset) > 0)
							{$this->result = current($this->resultset);}
						else
							{$this->result = FALSE;}
					} catch (Exception $e) {
						$this->connection->rollback();
						$error_data = array();
						$error_data['ecode'] = $e->getCode();
						$error_data['emsg'] = $e->getMessage();
						$error_data['etrace'] = $e->getTrace();
						$error_data['query'] = $query;
						if(!isset($query_key))
							{$query_key = 0;}
						if(isset($queries))
							{$error_data['active query'] = $queries[$query_key];}
						$error_data['active_params'] = current($params);
						echo print_r($error_data, TRUE);
					}
					break;
				default:
					$this->result = FALSE;
					trigger_error('Connection type not recognized: '.$this->conntype);
			}
		} else {
			switch($this->conntype)
			{
				case 'MySQL':
					$result = $this->connection->query ($query);
					if($result === FALSE)
						{print_r($query);}
					$this->result = $result;
					break;
				case 'ODBC':
					$result = odbc_exec($this->connection, $query);
					if($result === FALSE)
						{print_r($query);}
					$this->result = $result;
					break;
				case 'PDO':
					$this->result = $this->connection->prepare($query);
					$this->result->execute($params);
					break;
				default:
					trigger_error('Connection type not recognized: '.$this->conntype);
			}		
		}
	}
	//--------------------------------------------------------------------------------------------------------------------//

	
	//--------------------------------------------FUNCTION: FETCH QUERY RESULTS-------------------------------------------//
	function fetchArray()
	{
		switch($this->conntype)
		{
			case 'MySQL':
				if($this->multi_query)
				{
					if($row = mysqli_fetch_assoc($this->result))
						{return $row;}
					else
					{
						while($this->connection->more_results() && $this->connection->next_result())
						{
							$this->result = $this->connection->store_result();
							if($this->result !== FALSE)
								{break;}
						}
						
						if($this->result === FALSE)
						{
							if($this->connection->errno !== 0)
								{print_r($query);}
							else
								{return FALSE;}
						} else {
							$row = mysqli_fetch_assoc($this->result);
							return $row;
						}
					}
				} else {
					if(isset($this->result))
						{return mysqli_fetch_assoc($this->result);}
					else
					{
						$this->err_message = "No query has been executed to retrieve results from.<br>";
						$this->err_message .= "Run the <font color='green'><b>execQuery</b></font> function of the 
											   <font color='#d85504'><b>dataSource</b></font> class before attempting to access results.";
						echo $this->err_message.'<br>';
					}
				}
				break;
			case 'ODBC':
				return odbc_fetch_array($this->result);
				break;
			case 'PDO':
				if($this->multi_query)
				{
					$row = $this->result->fetch();					
					if(!is_array($row))
					{
						do
						{
							$this->result = next($this->resultset);
							if($this->result !== FALSE)
								{$row = $this->result->fetch();}
						} while (!is_array($row) && key($this->resultset) != max(array_keys($this->resultset)) && key($this->resultset) !== NULL);
					}
					return $row;
				} else 
					{return $this->result->fetch();}
				break;
			default:
				trigger_error('Connection type not recognized: '.$this->conntype);
				return FALSE;
		}
	}
	//--------------------------------------------------------------------------------------------------------------------//


	//---------------------------------------------FUNCTION: CLOSE CONNECTION---------------------------------------------//
	function closeDataSource()
	{
		switch($this->conntype)
		{
			case 'MySQL':
				$this->connection->close();
				break;
			case 'ODBC':
				odbc_close($this->connection);
				break;
			case 'PDO':
				$this->connection = null;
				break;
			default:
				$this->connection = null;
				trigger_error('Connection type not recognized: '.$this->conntype);
		}
	}
	//--------------------------------------------------------------------------------------------------------------------//
}
?>
