<?php	
//----------------------------------------------------DESCRIPTION---------------------------------------------------------//
# This plugin helps create, read, and edit csv files.
//------------------------------------------------------------------------------------------------------------------------//


//-------------------------------------------------------USAGE------------------------------------------------------------//
# 		$CSV = new csv_interface($file_path)
#			$file_prefix - Full directory path of CSV file to be edited.
#
#		$array = $CSV->fetch_data()
#			Reads CSV data into an associative array and passes it to $array variables
#
#		$CSV->wrtie_data($array)
#			$array - An associative array representing a table. Each row must contain scalar values (strings or numbers, no arrays)
#
#		$CSV->edit_form()
#			Prints javascript and an HTML form to allow the user to edit the CSV directly in the web browser.
//------------------------------------------------------------------------------------------------------------------------//

class csv_interface
{
	public $fname = NULL;
	public $timestamp = NULL;
 	private $csv = NULL;
	private $use_headers = NULL;
	private $headers = NULL;
	private $np_regex = NULL;
	private $csv_data = NULL;
		
	//--------------------------------------------DEFINE CONSTRUCTOR FUNCTION-------------------------------------------------//
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
	//------------------------------------------------------------------------------------------------------------------------//
	
	
	//-------------------------------------PASSES CSV DATA INTO ASSOCIATIVE ARRAY---------------------------------------------//
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
	//------------------------------------------------------------------------------------------------------------------------//



	//----------------------------------------------PASS CSV DATA TO USER-----------------------------------------------------//
	public function fetch_data()
	{
		return $this->csv_data;
	}
	//------------------------------------------------------------------------------------------------------------------------//	
	
	
	//-------------------------------------------OVERWRITE FILE WITH NEW DATA-------------------------------------------------//
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
	//------------------------------------------------------------------------------------------------------------------------//	



	//---------------------------------------RETRIEVE LAST FILE MODIFICATION TIME---------------------------------------------//
	public function get_fmod_time()
	{
		return filemtime($this->fname);		
	}
	//------------------------------------------------------------------------------------------------------------------------//	


	//-----------------------------------------PRINT HTML FORM FOR EDITING DATA-----------------------------------------------//
	// Requires jQuery
	public function edit_form()
	{
		$table_name = 'csv_table_'.date('YmdHis');
		$this->read_data();
		?>
		<script type="text/javascript">
			function update_data()
			{
				var obj = {file_name: $('#file_name').html()};
				var csv_data = [];
				var row_data = {};
				$('.data_row').each(function(){
					row_data = {};
					$(this).find('.data_point').each(function(){
						row_data[$(this).data('header')] = $(this).val();
					});
					csv_data.push(row_data)
				});

				obj['csv_data'] = JSON.stringify(csv_data);
				var ajWRITECSV = $.ajax({
					url: '/rtd/plugins/includes/write_csv.php', 
					method: 'POST', 
					data: $.param(obj)
				});
				
				ajWRITECSV.done(function(data, status){
					console.log(data);
				});
				
				ajWRITECSV.fail(function(xhr, status, err){
					var err_msg = status+": "+err;
					console.log(err_msg);
				});
			}
			
			function add_column()
			{
				var header_count = 0;
				var col_name = '';
				if($('.col_delete').length > 0)
				{
					if($('.header_row').length > 0)
						{col_name = $('#col_name').val();}
					else
						{col_name = $('.data_row:first td:last').children('input').data('header') + 1;}
					
					$('.col_delete td:last').after('<td data-header="'+col_name+'"><button onclick="delete_column(\''+col_name+'\');">Delete Col</button></td>');
				}
				if($('.header_row').length > 0)
				{
					col_name = $('#col_name').val();
					$('.header_row th:last').after('<th data-header="'+col_name+'">'+col_name+'</th>');
					$('.data_row').each(function(){
						$(this).children('td:last').after('<td class="'+col_name+'" data-header="'+col_name+'"><input type="text" class="data_point" data-header="'+col_name+'"></td>');
					});
				} else {
					$('.data_row').each(function(){
						header_count = $(this).children('td:last').children('input').data('header')+1;
						$(this).children('td:last').after('<td><input type="text" class="data_point" data-header="'+header_count+'"></td>');
					});
				}
			}
			
			function add_row(table_name)
			{
				var new_row = '<tr class="data_row"><td class="row_delete"><button onclick="delete_row();">Delete Row</button></td>';
				var i = 0;
				if($('.header_row').length > 0)
				{
					$('.header_row:first th').each(function(){
						new_row += '<td class="'+$(this).html()+'">';
						new_row += '<input type="text" class="data_point" data-header="'+$(this).html()+'"></td>';
					});
					new_row += '</tr>';
				} else {
					i = 0;
					$('.data-row:last td').each(function(){
						new_row += '<td><input type="text" class="data_point" data-header="'+i+'"></td>';
						i++;
					});
					new_row += '</tr>';
				}
				$(table_name).append(new_row);				
			}
			
			function delete_row()
			{
				var target = event.target;
				target = $(target).parents('tr');
				$(target).remove();
			}
			
			function delete_column(header)
			{
				$('[data-header="'+header+'"]').each(function(){
					$(this).remove();
				});
			}
		</script>
		
		<div id="file_name" style="display:none"><?php echo $this->fname; ?></div>
		<div id="buttons" style="margin-bottom: 10px; margin-top: 10px;">
			<button onclick="update_data();">Update CSV</button>
			&nbsp;<button onclick="add_row('<?php echo '#'.$table_name; ?>');">Add Row</button>
			&nbsp;<button onclick="add_column();">Add Column</button>
			<input type="text" id="col_name"></input>
		</div>
		<div class="default_style">
		<table id="<?php echo $table_name; ?>" class="csv_table">
		<?php
		echo '<tr class="col_delete"><td></td>';
		foreach(current($this->csv_data) as $header => $value)
		{
			echo '<td data-header="'.$header.'"><button onclick="delete_column(\''.$header.'\');">Delete Col</button></td>';
		}
		echo '</tr>';
		if($this->use_headers)
		{
			echo '<tr class="header_row"><td class="row_delete"></td>';
			foreach($this->headers as $na => $header)
			{
				echo '<th data-header="'.$header.'">'.$header.'</th>';
			}
			echo '</tr>';
		}
		$skip_line = TRUE;
		foreach($this->csv_data as $na => $array)
		{
			echo '<tr class="data_row">';
			echo '<td class="row_delete"><button onclick="delete_row();">Delete Row</button></td>';
			foreach($array as $header => $value)
			{
				$input_hdr = ' data-header="'.$header.'"';
				if($this->use_headers)
					{$td_class = ' class="'.$header.'"';} 
				else 
					{$td_class = '';}

				echo '<td'.$td_class.' data-header="'.$header.'">';
				echo '<input type="text" class="data_point"'.$input_hdr.' value="'.htmlspecialchars($value).'">';
				echo '</td>';
			}
			echo '</tr>';
		}
		echo '</table></div>';
	}
	//------------------------------------------------------------------------------------------------------------------------//	
	

	//----------------------------------CLEAR OUT OBJECT VARIABLES, DEFINE DESTRUCTOR-----------------------------------------//
	public function cleanup_csv()
	{
		$this->csv_data = NULL;
		return;
	}
	
	
	public function __destruct()
	{
		$this->cleanup_csv();
	}
	//------------------------------------------------------------------------------------------------------------------------//	
}
?>