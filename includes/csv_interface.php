<?php	
//----------------------------------------------------DESCRIPTION---------------------------------------------------------//
# This plugin helps create, read, and edit csv files.
//------------------------------------------------------------------------------------------------------------------------//


//---------------------------------------------------------USAGE----------------------------------------------------------//
# 		$CSV = new csv_interface($file_path)
#			$file_path - Full directory path of CSV file to be edited.
#
#		$array = $CSV->fetch_data()
#			Reads CSV data into an associative array and passes it to $array variable
#
#		$CSV->wrtie_data($array)
#			$array - An associative array representing a table. Each row must contain scalar 
#				 values (strings or numbers, no arrays)
//-----------------------------------------------------------------------------------------------------------------------//

class csv_interface
{
	public $fname = NULL;
	public $timestamp = NULL;
 	private $csv = NULL;
	private $use_headers = NULL;
	private $headers = NULL;
	private $np_regex = NULL;
	private $csv_data = NULL;
		
	//--------------------------------------------DEFINE CONSTRUCTOR FUNCTION---------------------------------------------//
	public function __construct($file_name, $headers = TRUE)
	{
		$this->cleanup_regex = '/[^,"]/';
		$this->np_regex = '/[\x00-\x1F]/';
		$this->use_headers = $headers;
		$this->fname = $file_name;

		if(!file_exists($file_name))
		{
			$file = fopen($file_name, 'w');
			fclose($file);
		}
		$this->read_data();
	}
	//--------------------------------------------------------------------------------------------------------------------//
	
	
	//---------------------------------------PASSES CSV DATA INTO ASSOCIATIVE ARRAY---------------------------------------//
	private function read_data()
	{
		$data_array = array();
		$this->headers = array();
		$count = 0;
		$rows = file($this->fname);
		if(is_array($rows))
		{
			for($i = 0; $i < count($rows); $i++)
			{
				$rows[$i] = preg_replace($this->np_regex, '', $rows[$i]);

				$split = explode(',', $rows[$i]);
				$text = array();
				$count = 0;
				$new_array = array();

				foreach($split as $key => $value)
				{
					$count += strlen($value) - strlen(str_replace('"', '', $value));
					$text[] = $value;
					// CSV files escape quotation marks and commas using double-quotes
					// Characters within a distinct field always contain an even number of double-quotes
					if($count % 2 == 0)
					{
						$result = implode(',', $text);
						if(substr($result, 0, 1) == '"')
							{$result = str_replace('""', '"', substr($result, 1, strlen($result)-2));}
						$new_array[] = $result;
						$count = 0;
						$text = array();
					}
				}

				if($i == 0 && $this->use_headers)
				{
					foreach($new_array as $key => $header)
					{
						$this->headers[$key] = $key == 0 ? preg_replace('/[\x00-\x1F\x80-\xFF]/', '', str_replace('"', '', $header)) : $header;
					}
				} else {
					$tmp = array();
					foreach($new_array as $key => $value)
					{
						if($this->use_headers)
						{
							if(!isset($this->headers[$key]))
							{
								$err = 'Headers do not contain key #'.$key;
								$err .= print_r($this->headers, TRUE).'<br>';
								$err .= print_r($rows[$i], TRUE).'<br>';
								$err .= print_r($new_array, TRUE).'<br>';
								$err .= '<br>';
								$err .= '<b>File: </b>'.$this->fname;
								trigger_error($err);
							} else 
								{$tmp[$this->headers[$key]] = $value;}
						} 
						else
							{$tmp[] = $value;}
					}
					$data_array[] = $tmp;
				}
			}
			$this->csv_data = $data_array;
			$data_array = null;
			unset($data_array);
			$rows = null;
			unset($rows);
		}
	}
	//--------------------------------------------------------------------------------------------------------------------//



	//-----------------------------------------------PASS CSV DATA TO USER------------------------------------------------//
	public function fetch_data()
	{
		return $this->csv_data;
	}
	//--------------------------------------------------------------------------------------------------------------------//
	
	
	//--------------------------------------------OVERWRITE FILE WITH NEW DATA--------------------------------------------//
	public function write_data($assoc_array)
	{
		if($this->use_headers)
			{$write_headers = TRUE;}
		else
			{$write_headers = FALSE;}

		$file = fopen($this->fname, 'w');
		foreach($assoc_array as $row => $array)
		{
			if($write_headers)
			{
				fputcsv($file, array_keys($array));
				$write_headers = FALSE;
			}
			fputcsv($file, array_values($array));
		}
		fclose($file);
		$this->read_data();
	}
	//--------------------------------------------------------------------------------------------------------------------//



	//----------------------------------------RETRIEVE LAST FILE MODIFICATION TIME----------------------------------------//
	public function get_fmod_time()
	{
		return filemtime($this->fname);		
	}
	//--------------------------------------------------------------------------------------------------------------------//


	//-----------------------------------CLEAR OUT OBJECT VARIABLES, DEFINE DESTRUCTOR------------------------------------//
	public function cleanup_csv()
	{
		$this->csv_data = NULL;
		return;
	}
	
	
	public function __destruct()
	{
		$this->cleanup_csv();
	}
	//--------------------------------------------------------------------------------------------------------------------//
}
?>
