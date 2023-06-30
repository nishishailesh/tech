<?php
//hi
//////////////////////////////////////////////////
///////////functions with defined action///////////
//////////////////////////////////////////////////
//Read new data
function add($link,$d,$t,$default=array(),$style='display:none;')
{
	$fld=get_key($link,$d,$t);
	$option=prepare_option_from_fk($link,$d,$t);
	
	echo '<button class="btn btn-dark"type=button onclick="showHideClass(\''.$d.'_'.$t.'\')" >';
			echo '<span class="badge badge-danger">'.$d.'</span>&nbsp;';	
			echo '<span class="badge badge-danger">'.$t.'</span>&nbsp;';	
			echo '<span class="badge badge-warning">+</span>';
	echo '</button>';
		
	echo '<form method=post enctype="multipart/form-data" >';
	echo '<div class="container bg-warning '.$d.'_'.$t.'" style="'.$style.'">';
		echo '<input type=hidden name=^database readonly size=\''.strlen($d).'\' value=\''.$d.'\'>';
		echo '<input type=hidden name=^table readonly size=\''.strlen($t).'\' value=\''.$t.'\'>';
		echo '<div class="row text-center">';
			echo '<div class="col-sm-12">';
				echo '<button  class="btn btn-success"  type=submit name=action value=insert>Save</button>';
			echo '</div>';	
		echo '</div>';	
	//my_print_r($option);
	foreach($fld as $k=>$v)
	{
		echo '<div class="row">';
			echo '<div class="col-sm-4 border  bg-secondary border-dark rounded">';
				echo $v['Field'];
			echo '</div>';
			echo '<div class="col-sm-8">';		
		if(array_key_exists($v['Field'],$default))
		{
			$vvv=$default[$v['Field']];
			$readonly='readonly';
		}
		else
		{
			$vvv='';
			$readonly='';
		}
	
		//no default
		if($v['Extra']=='auto_increment')
		{
				echo 'Server generated';
		}
		
		//no default
		elseif(substr($v['Field'],0,1)=='_')
		{	
			if($v['Type']=='blob' || $v['Type']=='mediumblob' || $v['Type']=='largeblob')
			{
				echo '<input style="width:100%" type="file" name=\''.$v['Field'].'\'>';
			}
			else
			{
				echo 'Same as upload';
			}
		}
		
		//default
		elseif( isset($option[$v['Field']]))
		{
			if($readonly=='')
			{
				mk_select_from_array_return_key($v['Field'],$option[$v['Field']],$readonly,$vvv);
			}
			else
			{
				echo '<input style="width:100%" type=text '.$readonly.' name=\''.$v['Field'].'\' value=\''.$vvv.'\'>';
			}
		}
		
		//default
		elseif(substr($v['Type'],0,7)=='varchar')
		{
			$varchar_len=substr($v['Type'],8,-1);
			if($varchar_len>$GLOBALS['textarea_size'])
			{
				echo '<textarea 	style="width:100%" maxlength=\''.$varchar_len.'\'
									title=\'maximum '.$varchar_len.' letters\'
									name=\''.$v['Field'].'\' '.$readonly.'>'.$vvv.'</textarea>';
			}
			else
			{
				//	pattern="[A-Za-z]{3}" title="Three letter country code"  
				echo '<input style="width:100%" 
									maxlength=\''.$varchar_len.'\'
									title=\'maximum '.$varchar_len.' letters\'
									type=text name=\''.$v['Field'].'\' '.$readonly.'
									value=\''.$vvv.'\'>';				
			}
		}
		//default
		elseif($v['Type']=='datetime')
		{
			read_datetime($v['Field'],$v['Field'],bindec("00111111"),$vvv,$readonly);
		}	
		
		//default
		elseif($v['Type']=='date')
		{
			read_datetime($v['Field'],$v['Field'],bindec("00111000"),$vvv,$readonly);
		}
		
		//default
		elseif($v['Type']=='time')
		{
			read_datetime($v['Field'],$v['Field'],bindec("000000111"),$vvv,$readonly);
		}		
		//default
		elseif(substr($v['Type'],0,3)=='int')
		{
			echo '<input style="width:100%" type=number '.$readonly.' value=\''.$vvv.'\' name=\''.$v['Field'].'\'>';				
		}	
		//default
		elseif(substr($v['Type'],0,6)=='bigint')
		{
			echo '<input  style="width:100%" value=\''.$vvv.'\' '.$readonly.' type=number name=\''.$v['Field'].'\'>';				
		}	
		//default
		elseif($v['Type']=='float' || substr($v['Type'],0,7)=='decimal')
		{

//title shown like <pre>. so no unnecessary space
			echo '<input 
											style="width:100%" 		type=text 
pattern="[0-9]*.[0-9]*" 
title="{correct->2.3, 2.0, 0.3, .3,3.} 
{incorrect-> {2xd , y2}"							'.$readonly.'
													value=\''.$vvv.'\'
													name=\''.$v['Field'].'\'>
										';				
		}
		elseif(substr($v['Type'],0,4)=='enum')
		{
			if($readonly=='')
			{
				$enum_csv=substr($v['Type'],5,-1);
				$enum_array=str_getcsv($enum_csv,",","'");
				mk_select_from_array($v['Field'],$enum_array,$readonly,$vvv);
			}
			else
			{
				echo '<input  style="width:100%" value=\''.$vvv.'\' '.$readonly.' type=number name=\''.$v['Field'].'\'>';	
			}
		}				
		//default
		else
		{
			echo '<input style="width:100%" type=text '.$readonly.' name=\''.$v['Field'].'\' value=\''.$vvv.'\' >';				
		}
		
	echo '</div></div>';	
	}
	
	echo '<div class="row text-center">';
		echo '<div class="col-sm-12">';
			echo '<button  class="btn btn-success"  type=submit name=action value=insert>Save</button>';
		echo '</div>';	
	echo '</div>';	

	echo '</div></form>';
}

function insert($link,$d,$t,$post,$files)
{
    //my_print_r($files);
    $fld=get_key($link,$d,$t);
   
    $sql='insert into `'.$t.'` ';
    $sql_fld='(';
    $sql_val='values(';
   
    foreach($fld as $k=>$v)
    {   
		//echo '<h1>'.$v['Type'].'</h1>';
		if(	isset($post[$v['Field']]) || 
			isset($files[$v['Field']]) || 
			isset($post[$v['Field'].'_year']) ||
			isset($post[$v['Field'].'_month']) ||
			isset($post[$v['Field'].'_day'])
			)
		{
			if($v['Extra']=='auto_increment')
			{
				//DO NOTHING
			}
			
			//upload_max_filesize = 8M ---->in php.ini
			//post_max_size = 8M
			elseif(substr($v['Field'],0,1)=='_')
			{
				if($v['Type']=='blob' || $v['Type']=='mediumblob' || $v['Type']=='largeblob')
				{
					$dt=  file_to_str($link,$files[$v['Field']]);
					$sql_fld=$sql_fld.'`'.$v['Field'].'`, ';
					$sql_val=$sql_val.'\''.$dt.'\' , ';
					
					$dt=$files[$v['Field']]['name'];
					$sql_fld=$sql_fld.'`'.$v['Field'].'_name`, ';
					$sql_val=$sql_val.'\''.$dt.'\', ';				
				}
			}
			elseif($v['Type']=='datetime' )
			{
				$dt=    $post[$v['Field'].'_year'].'-'.
						$post[$v['Field'].'_month'].'-'.
						$post[$v['Field'].'_day'].' '.
						$post[$v['Field'].'_hour'].':'.
						$post[$v['Field'].'_min'].':'.
						$post[$v['Field'].'_sec'];
				$sql_fld=$sql_fld.'`'.$v['Field'].'`, ';
				$sql_val=$sql_val.'\''.$dt.'\', ';
			}
			elseif($v['Type']=='date')
			{
				$dt=    $post[$v['Field'].'_year'].'-'.
						$post[$v['Field'].'_month'].'-'.
						$post[$v['Field'].'_day'];
				//echo ;
				//echo '<h1>'.$dt.'</h1>';
				$sql_fld=$sql_fld.'`'.$v['Field'].'`, ';
				$sql_val=$sql_val.'\''.$dt.'\', ';
			}
			elseif($v['Type']=='time')
			{
				$dt=    $post[$v['Field'].'_hour'].':'.
						$post[$v['Field'].'_min'].':'.
						$post[$v['Field'].'_sec'];
				$sql_fld=$sql_fld.'`'.$v['Field'].'`, ';
				$sql_val=$sql_val.'\''.$dt.'\', ';
			}  
		   
			else
			{
				//echo '<h1>'.$v['Type'].'</h1>';
				$dt=my_safe_text($link,$post[$v['Field']]);
				$sql_fld=$sql_fld.'`'.$v['Field'].'`, ';
				$sql_val=$sql_val.'\''.$dt.'\', ';
			}
		}
    }
    $sql_fld=substr($sql_fld,0,-2);
    $sql_fld=$sql_fld.')  ';

    $sql_val=substr($sql_val,0,-2);
    $sql_val=$sql_val.')';   
   
    $sql=$sql.$sql_fld.$sql_val;
    //echo '<h3>'.$sql.'</h3>';
    $result=run_query($link,$d,$sql);
    if($result==false)
    {
        echo '<h3 style="color:red;">No record inserted</h3>';
    }
    else
    {
        echo '<h3 style="color:green;">'.$result.' record inserted</h3>';
    }
}


//search window
function search($link,$d,$t,$default)
{
	$fld=get_key($link,$d,$t);
	$option=prepare_option_from_fk($link,$d,$t);	//my_print_r($option);
	//onclick="showhide(\'search_body\')"
	
	echo '<form method=post>';
	echo '<div class="container bg-light">';
			echo '<div class="row bg-warning">';	
					echo '<input type=hidden name=^database readonly size=\''.strlen($d).'\' value=\''.$d.'\'>';
					echo '<input type=hidden name=^table readonly size=\''.strlen($t).'\' value=\''.$t.'\'>';

				echo '<div class="col-sm-12 text-center">';	
					echo '<span class="badge badge-danger border border-dark">'.$d.'</span>';
					echo '<span class="badge badge-danger border border-dark">'.$t.'</span>';				
					echo '<span class="badge badge-danger border border-dark">Search</span> (only first '.$GLOBALS['search_limit'].' will be displayed)';
				echo '</div>';

			echo '</div>';

			echo '<div class="row">';	
				echo '<div class="col text-center">';	
					echo '<button  class="btn btn-success"  type=submit name=action value=show_search_rows>Display Search Results</button>';
				echo '</div>';
			echo '</div>';			
	foreach($fld as $k=>$v)
	{
		echo '<div class="row">';		
		if(array_key_exists($v['Field'],$default))
		{
			$vvv=$default[$v['Field']];
			$readonly='readonly';
			$it='type=hidden';
			$exact='<input type=hidden name=\'ex_'.$v['Field'].'\'>';
		}
		else
		{
			$vvv='';
			$readonly='';
			$it='type=checkbox';
			$exact='';
		}
		
		if( isset($option[$v['Field']]))
		{
			echo $exact;
			echo '<div class="col-sm-4 border border-dark  bg-secondary rounded">';
				echo '<input '.$it.' name=\'cb_'.$v['Field'].'\' >'.$v['Field'].'';
			echo '</div>';
			echo '<div class="col-sm-8">';
				if($readonly=='')
				{
					mk_select_from_array_return_key($v['Field'],$option[$v['Field']],$readonly,$vvv);
				}
				else
				{
					echo '<input style="width;100%;" type=text '.$readonly.' name=\''.$v['Field'].'\' value=\''.$vvv.'\'>';
				}
			echo '</div>';			
		}
		elseif(substr($v['Type'],0,7)=='varchar')
		{
			$varchar_len=substr($v['Type'],8,-1);
			echo $exact;
				echo '<div class="col-sm-4  bg-secondary border border-dark rounded">';			
			if($varchar_len>$GLOBALS['textarea_size'])
			{
				echo '<input '.$it.' name=\'cb_'.$v['Field'].'\' >'.$v['Field'];
				echo '</div>';
				echo '<div class="col-sm-8">';
				echo '<textarea 			maxlength=\''.$varchar_len.'\'
											title=\'maximum '.$varchar_len.' letters\'
											style="width:100%"
											'.$readonly.'
											name=\''.$v['Field'].'\'>'.$vvv.'</textarea>';
			}
			else
			{
				echo '<input '.$it.' name=\'cb_'.$v['Field'].'\' >'.$v['Field'];
				echo '</div>';
				echo '<div class="col-sm-8">';
				echo '<input 
						maxlength=\''.$varchar_len.'\'
						title=\'maximum '.$varchar_len.' letters\'
						type=text 
						style="width:100%"
						'.$readonly.'
						value=\''.$vvv.'\'
						name=\''.$v['Field'].'\'>';				
			}
				echo '</div>';
		}	
		elseif(substr($v['Type'],0,3)=='int')
		{
				echo $exact;
			echo '<div class="col-sm-4  bg-secondary border border-dark rounded">';			
				echo '<input '.$it.' name=\'cb_'.$v['Field'].'\' >'.$v['Field'];
			echo '</div>';
			echo '<div class="col-sm-8">';				
				echo '<input 	type=number
				style="width:100%;"
					'.$readonly.'
					value=\''.$vvv.'\'
					name=\''.$v['Field'].'\'>';				
			echo '</div>';
		}	
		elseif(substr($v['Type'],0,6)=='bigint')
		{
				echo $exact;			
			echo '<div class="col-sm-4  bg-secondary border border-dark rounded">';	
				echo '<input '.$it.' name=\'cb_'.$v['Field'].'\' >'.$v['Field'];
			echo '</div>';
			echo '<div class="col-sm-8">';			
				echo '<input 	type=number style="width:100%;"
				'.$readonly.'
				value=\''.$vvv.'\'			
				name=\''.$v['Field'].'\'>';				
			echo '</div>';
		}	
		elseif($v['Type']=='float' || substr($v['Type'],0,7)=='decimal')
		{
			echo $exact;
			//title shown like <pre>. so no unnecessary space
			echo '<div class="col-sm-4  bg-secondary border border-dark rounded">';	
				echo '<input '.$it.' name=\'cb_'.$v['Field'].'\' >'.$v['Field'];
			echo '</div>';
			echo '<div class="col-sm-8">';			
				echo '<input 
						type=text style="width:100%;"
						'.$readonly.'
						value=\''.$vvv.'\'															
						pattern="[0-9]*.[0-9]*" 
						title="{correct->2.3, 2.0, 0.3, .3,3.} 
						{incorrect-> {2xd , y2}"
						name=\''.$v['Field'].'\'>';				
			echo '</div>';
		}	
		else
		{
			echo $exact;
			echo '<div class="col-sm-4  bg-secondary border border-dark rounded">';		
				echo '<input '.$it.' name=\'cb_'.$v['Field'].'\' >'.$v['Field'];
			echo '</div>';
			echo '<div class="col-sm-8">';
				echo '<input 	type=text style="width:100%;"
					'.$readonly.'
					value=\''.$vvv.'\'		
					name=\''.$v['Field'].'\'>';				
			echo '</div>';			
		}
		echo '</div>';
	}
			echo '<div class="row">';	
				echo '<div class="col text-center">';	
					echo '<button  class="btn btn-success"  type=submit name=action value=show_search_rows>Display Search Results</button>';
				echo '</div>';
			echo '</div>';
	echo '</div>';
	echo '</form>';
}

//show search result based on POST
function show_search_rows($link,$d,$t,$post)
{
	echo '<div class="row">';	
		echo '<div class="col text-center">';	
			echo '<button class="btn btn-warning" type=button onclick="showHideClass(\'hdn\')">Toggle Show</button>';
		echo '</div>';
	echo '</div>';				
	$result=get_search_result($link,$d,$t,$post);
	while($data=get_single_row($result))
	{	
		show($link,$d,$t,$data);
	}
}


function show_all_rows($link,$d,$t,$offset,$limit,$default)
{
	$sql_where='';
	foreach($default as $k=>$v)
	{	
		$sql_where=$sql_where.' `'.$k.'` = \''.$v.'\' and ';
	}
	$sql_where=substr($sql_where,0,-4);
	
	if(strlen($sql_where)<=0){$sql='select * from`'.$t.'` limit '.$offset.','.$limit;}
	else{$sql='select * from`'.$t.'` where '.$sql_where.' limit '.$offset.','.$limit;}
	//echo $sql;
	$result=run_query($link,$d,$sql);
	echo '<div class="row">';	
		echo '<div class="col text-center">';	
			echo '<button class="btn btn-warning" type=button onclick="showHideClass(\'hdn\')">Toggle show</button>';

			if($offset>0)
			{
			echo '<form method=post  style="display:inline">';
			echo '<button class="btn btn-warning" type=submit>Previous Page('.max(($offset-$GLOBALS['limit']),1).'-'.$offset.')</button>';
			echo '<input type=hidden name=offset value=\''.max(($offset-$GLOBALS['limit']),0).'\'>';
			echo '<input type=hidden name=action value=show_all_rows>';
			echo '<input type=hidden name=^database readonly value=\''.$d.'\'>';
			echo '<input type=hidden name=^table readonly value=\''.$t.'\'>';				
			echo '</form>';
			}
						
			echo '<form method=post style="display:inline">';
			echo '<button class="btn btn-warning" type=submit>Next Page('.($offset+$GLOBALS['limit']+1).'-'.($offset+2*$GLOBALS['limit']).')</button>';
			echo '<input type=hidden name=offset value=\''.($offset+$GLOBALS['limit']).'\'>';
			echo '<input type=hidden name=action value=show_all_rows>';
			echo '<input type=hidden name=^database readonly value=\''.$d.'\'>';
			echo '<input type=hidden name=^table readonly value=\''.$t.'\'>';			
			echo '</form>';
			
		echo '</div>';
	echo '</div>';		
	
	while($data=get_single_row($result))
	{	
		show($link,$d,$t,$data);
	}
}



function only_show_all_rows($link,$d,$t,$offset,$limit,$default)
{
	$sql_where='';
	foreach($default as $k=>$v)
	{	
		$sql_where=$sql_where.' `'.$k.'` = \''.$v.'\' and ';
	}
	$sql_where=substr($sql_where,0,-4);
	
	if(strlen($sql_where)<=0){$sql='select * from`'.$t.'` limit '.$offset.','.$limit;}
	else{$sql='select * from`'.$t.'` where '.$sql_where.' limit '.$offset.','.$limit;}
	//echo $sql;
	$result=run_query($link,$d,$sql);
	echo '<div class="row">';	
		echo '<div class="col text-center">';	
			echo '<button class="btn btn-warning" type=button onclick="showHideClass(\'hdn\')">Toggle show</button>';

			if($offset>0)
			{
			echo '<form method=post  style="display:inline">';
			echo '<button class="btn btn-warning" type=submit>Previous Page('.max(($offset-$GLOBALS['limit']),1).'-'.$offset.')</button>';
			echo '<input type=hidden name=offset value=\''.max(($offset-$GLOBALS['limit']),0).'\'>';
			echo '<input type=hidden name=action value=show_all_rows>';
			echo '<input type=hidden name=^database readonly value=\''.$d.'\'>';
			echo '<input type=hidden name=^table readonly value=\''.$t.'\'>';				
			echo '</form>';
			}
						
			echo '<form method=post style="display:inline">';
			echo '<button class="btn btn-warning" type=submit>Next Page('.($offset+$GLOBALS['limit']+1).'-'.($offset+2*$GLOBALS['limit']).')</button>';
			echo '<input type=hidden name=offset value=\''.($offset+$GLOBALS['limit']).'\'>';
			echo '<input type=hidden name=action value=show_all_rows>';
			echo '<input type=hidden name=^database readonly value=\''.$d.'\'>';
			echo '<input type=hidden name=^table readonly value=\''.$t.'\'>';			
			echo '</form>';
			
		echo '</div>';
	echo '</div>';		
	
	while($data=get_single_row($result))
	{	
		only_show($link,$d,$t,$data);
	}
}


function show_search_buttons($link,$d,$t,$post)
{
	$result=get_search_result($link,$d,$t,$post);
	
	while($data=get_single_row($result))
	{	
		show($link,$d,$t,$data);
	}
}

function show_search_rows_by_pka($link,$d,$t,$pka)
{
	$result=get_search_result_by_pka($link,$d,$t,$pka);
	
	while($data=get_single_row($result))
	{
		show($link,$d,$t,$data);
	}
}

function show_search_rows_by_pka_full($link,$d,$t,$pka)
{
	$result=get_search_result_by_pka($link,$d,$t,$pka);
	
	while($data=get_single_row($result))
	{
		show_full($link,$d,$t,$data);
	}
}



function print_rows_by_pka($link,$d,$t,$pka)
{
	$result=get_search_result_by_pka($link,$d,$t,$pka);
	
	while($data=get_single_row($result))
	{
		//my_print_r($data);
		only_show($link,$d,$t,$data);
	}
}

function print_dependent_rows($link,$d,$t,$pka)
{
	$dep=get_dependant_table($link,$d,$t);

	foreach ($dep as $v)
	{
		print_rows_by_pka($link,$v['TABLE_SCHEMA'],$v['TABLE_NAME'],$pka);
	}
}


function print_parent_rows($link,$d,$t,$pka)
{
	$pk_array=get_primary_key($link,$d,$t);
	$fld=get_key($link,$d,$t);
	$fk=get_foreign_key($link,$d,$t);
		
	$parent=array('database'=>$d);
	foreach($fld as $k=>$v)
	{
		if($found=in_subarray($fk,'COLUMN_NAME',$v['Field']))
		{
				$sql='select * from `'.$found['REFERENCED_TABLE_NAME'].'` where 
							`'.$found['REFERENCED_COLUMN_NAME'].'`=\''.$pka[$v['Field']].'\'';
				//echo $sql;
				$result_fk=run_query($link,$d,$sql);
				$fk_data=get_single_row($result_fk);
				$parent['table']=$found['REFERENCED_TABLE_NAME'];
				$parent['pk'][$found['REFERENCED_COLUMN_NAME']]=$pka[$v['Field']];
		}
	}
	
	//my_print_r($fk);
	//my_print_r($pk_array);
	//my_print_r($parent);

	if(isset($parent['table']))
	{
		print_rows_by_pka($link,$parent['database'],$parent['table'],$parent['pk']);
	}
}

//delete data
function delete($link,$d,$t,$pk_array)
{
	$sql=mk_delete_sql_from_pk($link,$d,$t,$pk_array);
	
	$result=run_query($link,$d,$sql);
	if($result==false)
	{
		echo '<h3 style="color:red;">No record deleted</h3>';
	}
	else
	{
		echo '<h3 style="color:green;">'.$result.' record deleted</h3>';
	}
}


//edit data window
function edit($link,$d,$t,$pkva,$default)
{
	//my_print_r($_POST);
	$sql=mk_select_sql_from_pk($link,$d,$t,$pkva);
	//echo $sql;
	$result=run_query($link,$d,$sql);
	$data=get_single_row($result);
	//my_print_r($data);
	$fld=get_key($link,$d,$t);
	$pk_array=get_primary_key($link,$d,$t);
	//my_print_r($pk_array);
	$option=prepare_option_from_fk($link,$d,$t);
	//my_print_r($option);

	echo '<form method=post enctype="multipart/form-data" >';
	echo '<div class="container bg-light">';
		echo '<div class="row bg-warning">';		
				echo '<input type=hidden name=^database readonly size=\''.strlen($d).'\' value=\''.$d.'\'>';
				echo '<input type=hidden name=^table readonly size=\''.strlen($t).'\' value=\''.$t.'\'>';
				//echo '<div class="col-sm-4 text-right">';	
					//echo '<img src=edit.png style="width:5%;">';
				//echo '</div>';
				echo '<div class="col-sm-12 text-center">';
					echo '<span class="badge badge-danger border border-dark">'.$d.'</span>';
					echo '<span class="badge badge-danger border border-dark">'.$t.'</span>';
					echo '<span class="badge badge-dark border border-dark">Edit</span>';
				echo '</div>';
				//echo '<div class="col-sm-4 text-left">';	
					//echo '<img src=edit.png style="width:5%;">';
				//echo '</div>';
		echo '</div>';
	foreach($fld as $k=>$v)
	{
		///////If PRI, create POST
		if(in_subarray($pk_array,'Field',$v['Field']))
		{
			echo '<input type=hidden name=\'__'.$v['Field'].'\' value=\''.$data[$v['Field']].'\'>';
		}

		if(array_key_exists($v['Field'],$default))
		{
			$readonly='readonly';
		}
		else
		{
			$readonly='';
		}

		echo '<div class="row">';
			echo '<div class="col-sm-4 border border-dark bg-secondary rounded">';
				echo $v['Field'];
			echo '</div>';	
			echo '<div class="col-sm-8">';		
		//////If autoincriment just display ,it is always primary, it will be passed as POST
		if($v['Extra']=='auto_increment')
		{
			echo '<input 	style="width:100%;" type=text 
							readonly 
							id=\''.$v['Field'].'\' 
							name=\''.$v['Field'].'\' 
							value=\''.$data[$v['Field']].'\'>';
		}

		//_file and _file_name fields must be preceded by underscore and _file_name myst have _name follwoing it
		elseif(substr($v['Field'],0,1)=='_')
		{
			
			if($v['Type']=='blob' || $v['Type']=='mediumblob' || $v['Type']=='largeblob')
			{
				if($readonly=='')
				{
					echo '<input style="width:100%;" type="file" name=\''.$v['Field'].'\' id=\''.$v['Field'].'\'>';
				}
				else
				{
					echo 'Can not change';
				}
			}			
			else
			{
				//always readonly, never to be posted
				echo $data[$v['Field']].'(current)';
			}
		}
		
		
		/////if foreign key, prepare dropdown
		elseif( isset($option[$v['Field']]))
		{
			if($readonly=='')
			{
				mk_select_from_array_return_key($v['Field'],$option[$v['Field']],$readonly,$data[$v['Field']]);
			}
			else
			{
				echo '<input style="width:100%;" type=text '.$readonly.' name=\''.$v['Field'].'\' 
				id=\''.$v['Field'].'\' value=\''.$data[$v['Field']].'\'>';
			}
		}
		
		//////otherthings
		elseif(substr($v['Type'],0,7)=='varchar')
		{
			$varchar_len=substr($v['Type'],8,-1);
			if($varchar_len>$GLOBALS['textarea_size'])
			{
				echo '<textarea 	style="width:100%;" maxlength=\''.$varchar_len.'\'
											title=\'maximum '.$varchar_len.' letters\'
											'.$readonly.'
											id=\''.$v['Field'].'\' 
											name=\''.$v['Field'].'\'>'.$data[$v['Field']].'</textarea>';
			}
			else
			{
				echo '<input style="width:100%;"
							maxlength=\''.$varchar_len.'\'
							title=\'maximum '.$varchar_len.' letters\'
							type=text 
							'.$readonly.'
							value=\''.$data[$v['Field']].'\'
							id=\''.$v['Field'].'\' 
							name=\''.$v['Field'].'\'>';	
			}
		}
		elseif($v['Type']=='datetime')
		{
			read_datetime($v['Field'],$v['Field'],bindec("00111111"),$data[$v['Field']],$readonly);
		}	
		elseif($v['Type']=='date')
		{
			read_datetime($v['Field'],$v['Field'],bindec("00111000"),$data[$v['Field']],$readonly);
		}
		elseif($v['Type']=='time')
		{
			read_datetime($v['Field'],$v['Field'],bindec("000000111"),$data[$v['Field']],$readonly);
		}		
		elseif(substr($v['Type'],0,3)=='int')
		{
			echo '<input style="width:100%;" type=number id=\''.$v['Field'].'\' name=\''.$v['Field'].'\' 
									onchange="manage_marks(this);"
						'.$readonly.' value=\''.$data[$v['Field']].'\' >';				
		}	
		elseif(substr($v['Type'],0,6)=='bigint')
		{
			echo '<input style="width:100%;" type=number  id=\''.$v['Field'].'\' name=\''.$v['Field'].'\'
						onchange="manage_marks(this);"'
						.$readonly.' value=\''.$data[$v['Field']].'\'  >';				
		}	
		elseif($v['Type']=='float' || substr($v['Type'],0,7)=='decimal')
		{

//title shown like <pre>. so no unnecessary space
			echo '<input style="width:100%;"
					type=text 
pattern="[0-9]*.[0-9]*" 
title="{correct->2.3, 2.0, 0.3, .3,3.} 
{incorrect-> {2xd , y2}"
													
													name=\''.$v['Field'].'\' '.$readonly.' 
													 id=\''.$v['Field'].'\' 
													 onchange="manage_marks(this);"
													value=\''.$data[$v['Field']].'\' >';				
		}
		elseif(substr($v['Type'],0,4)=='enum')
		{
			if($readonly=='')
			{
				$enum_csv=substr($v['Type'],5,-1);
				$enum_array=str_getcsv($enum_csv,",","'");
				//echo $data[$v['Field']];
				mk_select_from_array($v['Field'],$enum_array,$readonly,$data[$v['Field']]);
			}
			else
			{
				echo '<input  style="width:100%"  id=\''.$v['Field'].'\' 
				value=\''.$data[$v['Field']].'\' '.$readonly.' type=number name=\''.$v['Field'].'\'>';	
			}
		}	
		else
		{
			echo '<input style="width:100%;"  id=\''.$v['Field'].'\'  type=text name=\''.$v['Field'].'\' '.$readonly.' 
			value=\''.$data[$v['Field']].'\' >';				
		}
		echo '</div>';
		echo '</div>';
	}
		echo '<div class="npk row">';		
			echo '<div class="col-sm-12 text-center">';
				echo '<input class="btn btn-success"   type=submit name=action value=save>';
				echo '<input class="btn btn-danger"  type=submit name=action value=delete>';
			echo '</div>';
		echo '</div>';			
		
	echo '</div>';
	echo '</form>';		
}

//////////////////////////////////////////////////
///////////functions with defined action End here///////////
//////////////////////////////////////////////////

////////support functions///////////

//save edited data
function save($link,$d,$t,$post,$files)
{
	//my_print_r($post);
	$fld=get_key($link,$d,$t);
	
	$sql='update `'.$t.'` ';
	$sql_set=' set ';
	$sql_where=' where ';
	
	//$sql_pwhere=' where ';
	
	$pk_array=get_primary_key($link,$d,$t);
	
	foreach($pk_array as $pk)
	{
		$sql_where=$sql_where.'`'.$pk['Field'].'`='.'\''.$post['__'.$pk['Field']].'\' and ';
	}
	$sql_where=substr($sql_where,0,-4);


	foreach($fld as $k=>$v)
	{
		$dt='';
		if($v['Type']=='datetime' )
		{
			$dt=	$post[$v['Field'].'_year'].'-'.
					$post[$v['Field'].'_month'].'-'.
					$post[$v['Field'].'_day'].' '.
					$post[$v['Field'].'_hour'].':'.
					$post[$v['Field'].'_min'].':'.
					$post[$v['Field'].'_sec'];
			$sql_set=$sql_set.'`'.$v['Field'].'`=\''.$dt.'\' , ';

		}
		elseif($v['Type']=='date')
		{
			$dt=	$post[$v['Field'].'_year'].'-'.
					$post[$v['Field'].'_month'].'-'.
					$post[$v['Field'].'_day'];
			$sql_set=$sql_set.'`'.$v['Field'].'`=\''.$dt.'\' , ';
		}
		elseif($v['Type']=='time')
		{
			$dt=	$post[$v['Field'].'_hour'].':'.
					$post[$v['Field'].'_min'].':'.
					$post[$v['Field'].'_sec'];
			$sql_set=$sql_set.'`'.$v['Field'].'`=\''.$dt.'\' , ';
		}
		
			//upload_max_filesize = 8M ---->in php.ini
			//post_max_size = 8M
		elseif(substr($v['Field'],0,1)=='_')
		{	
			if($v['Type']=='blob' || $v['Type']=='mediumblob' || $v['Type']=='largeblob')
			{
				if($files[$v['Field']]['size']>0)
				{
					$dt= file_to_str($link,$files[$v['Field']]);
					$sql_set=$sql_set.'`'.$v['Field'].'`=\''.$dt.'\' , ';
				
					$dt= $files[$v['Field']]['name'];
					$sql_set=$sql_set.'`'.$v['Field'].'_name`=\''.$dt.'\' , ';
				}
			}
		}		
		else
		{
			$dt=$post[$v['Field']];
			$sql_set=$sql_set.'`'.$v['Field'].'`=\''.my_safe_text($link,$dt).'\' , ';
		}
		
		//added to all ifelse
		//$sql_set=$sql_set.'`'.$v['Field'].'`=\''.$dt.'\' , ';
			
			
		//if(in_subarray($pk_array,'Field',$v['Field']))
		//{
		//	$sql_pwhere=$sql_pwhere.'`'.$v['Field'].'`='.'\''.$dt.'\' and ';
		//}
	}
	
	$sql_set=substr($sql_set,0,-2);
	//$sql_pwhere=substr($sql_pwhere,0,-4);

	$sql=$sql.$sql_set.$sql_where;
	
	//echo '<h3>'.$sql.'</h3>';
	
	$result=run_query($link,$d,$sql);
	if($result==false)
	{
		echo '<h3 style="color:red;">No record updated</h3>';
	}
	else
	{
		echo '<h3 style="color:green;">'.$result.' record updated</h3>';
	}
	
	//$psql='select * from `'.$t.'`'.$sql_pwhere;
	//echo $psql;
	
	//show_sql($link,$d,$t,$psql);
}



function prepare_search_where_from_array($link,$d,$t,$post,$extra='')
{
	//my_print_r($_POST);	
	$fld=get_key($link,$d,$t);
	
	$sql='select * from `'.$t.'` where ';
	$sql_where=' ';
	
	foreach($fld as $k=>$v)
	{	
		if(isset($post['cb_'.$v['Field']]))
		{
			$value=$post[$v['Field']];
			$sql_where=$sql_where.' `'.$v['Field'].'` like \'%'.$value.'%\' and ';
		}
	}
	$sql_where=substr($sql_where,0,-4);
	
	return $sql=$sql.$sql_where.$extra;
}

function get_search_result($link,$d,$t,$post)
{
	//my_print_r($post);	
	$fld=get_key($link,$d,$t);
	
	$sql='select * from `'.$t.'` where ';
	$sql_where=' ';
	
	foreach($fld as $k=>$v)
	{	
		if(isset($post['cb_'.$v['Field']]))
		{
			if(isset($post['ex_'.$v['Field']]))
			{
			$value=$post[$v['Field']];
			$sql_where=$sql_where.' `'.$v['Field'].'` = \''.$value.'\' and ';
			}
			else
			{
				$value=$post[$v['Field']];
				$sql_where=$sql_where.' lower(`'.$v['Field'].'`) like lower(\'%'.$value.'%\') and ';		
			}
		}
	}
	$sql_where=substr($sql_where,0,-4);	
	
	if(strlen($sql_where)<=0){return false;}
	
	$sql=$sql.$sql_where.' limit '.$GLOBALS['search_limit'];
	//echo $sql;
	$result=run_query($link,$d,$sql);
	return $result;
}


function get_search_result_by_pka($link,$d,$t,$pka)
{
	//my_print_r($post);	
	$fld=get_key($link,$d,$t);
	
	$sql='select * from `'.$t.'` where ';
	$sql_where=' ';
	
	foreach($fld as $k=>$v)
	{	
		if(isset($pka[$v['Field']]))
		{
			$value=$pka[$v['Field']];
			$sql_where=$sql_where.' `'.$v['Field'].'` = \''.$value.'\' and ';
		}
	}
	$sql_where=substr($sql_where,0,-4);	
	
	if(strlen($sql_where)<=0){return false;}
	
	$sql=$sql.$sql_where;
	//echo $sql;
	$result=run_query($link,$d,$sql);
	return $result;
}



function show($link,$d,$t,$data)
{
	$pk_array=get_primary_key($link,$d,$t);
	//my_print_r($pk_array);
	$pk_str_full=$d.'_'.$t.'_';
	$pk_str='';
	foreach ($pk_array as $pkk=>$pkv)
	{
		$pk_str=$pk_str.$data[$pkv['Field']].'_';
		$pk_str_full=$pk_str_full.$data[$pkv['Field']].'_';
	}
	$fld=get_key($link,$d,$t);
	$fk=get_foreign_key($link,$d,$t);
		
	//my_print_r($data);
		echo '<button class="btn btn-info"type=button onclick="showHideClass(\''.$pk_str_full.'\')" >';
				echo '<span class="badge badge-danger">'.$d.'</span>&nbsp;';	
				echo '<span class="badge badge-danger">'.$t.'</span>&nbsp;';	
				echo '<span class="badge badge-danger">'.$pk_str.'</span>';
		echo '</button>';
		echo '<form method=post>';
		echo '<input type=hidden name=^database readonly size=\''.strlen($d).'\' value=\''.$d.'\'>';
		echo '<input type=hidden name=^table readonly size=\''.strlen($t).'\' value=\''.$t.'\'>';
	echo '<div class="container bg-warning border hdn '.$pk_str_full.'" style="display:none">';

	$parent=array('database'=>$d);
	$pk_filled=array();
	foreach($fld as $k=>$v)
	{
		if(in_subarray($pk_array,'Field',$v['Field']))
		{
			echo '<input type=hidden name=\''.$v['Field'].'\' value=\''.$data[$v['Field']].'\'>';
			$pk_filled[$v['Field']]=$data[$v['Field']];
		}
			
		echo '<div class="row">';
		//echo '<div class="row nnn">';
			echo '<div class="col-sm-4 bg-secondary border border-dark rounded">';
				echo $v['Field'];
			echo '</div>';
			echo '<div class="col-sm-8 border border-success rounded">';
		if($found=in_subarray($fk,'COLUMN_NAME',$v['Field']))
		{
				$sql='select * from `'.$found['REFERENCED_TABLE_NAME'].'` where 
							`'.$found['REFERENCED_COLUMN_NAME'].'`=\''.$data[$v['Field']].'\'';
				//echo $sql;
				$result_fk=run_query($link,$d,$sql);
				$fk_data=get_single_row($result_fk);
				//my_print_r($fk_data);
				$dv='';
				foreach($fk_data as $kk=>$vv)
				{
						if($kk=='password' ||$kk=='epassword')
						{
							$dv=$dv.'|XXX';
						}
						elseif(substr($kk,0,1)=='_'){}
						else
						{
							$dv=$dv.'|'.$vv;
						}
				}
				echo substr($dv,0,40);
				$parent['table']=$found['REFERENCED_TABLE_NAME'];
				$parent['pk'][$found['REFERENCED_COLUMN_NAME']]=$data[$v['Field']];
		}
		
		elseif($v['Type']=='blob' || $v['Type']=='mediumblob' || $v['Type']=='largeblob')
		{
			echo '<input type=hidden value=\''.$v['Field'].'\' name=blob_field>
						<button class="btn btn-primary"  
						formtarget=_blank
						type=submit
						name=action
						value=download>Download</button>';
		}
		else
		{
			echo htmlspecialchars($data[$v['Field']]);			
		}
			echo '</div>';
			
					
		echo '</div>';
	}
	
		echo '<div class="row">';		
			echo '<div class="col text-center">';
				echo '<input  class="btn btn-primary"  type=submit name=action value=edit>
					<input class="btn btn-danger"  type=submit name=action value=delete>';
	echo '</form>';

		echo '<form method=post class="d-inline">';
				echo '<input type=hidden name=^database readonly value=\''.$d.'\'>';
				echo '<input type=hidden name=^table readonly  value=\''.$t.'\'>';
				$detail_str='';
				foreach($pk_filled as $ff_k => $ff_v)
				{
					echo '<input type=hidden name=\''.$ff_k.'\' readonly value=\''.$ff_v.'\'>';			
					echo '<input type=hidden name=\'cb_'.$ff_k.'\' readonly >';	
					$detail_str=$detail_str.$ff_v.'_';		
				}
				echo '<button type=submit class="btn btn-secondary" name=action value=show_search_details>Detail View</button>';
				echo '<button type=submit class="btn btn-success" name=action value=print>Print</button>';				
			echo '</div>';	
		echo '</div>';
		echo '</form>';
	echo '</div>';	
}

function show_full($link,$d,$t,$data)
{
	$pk_array=get_primary_key($link,$d,$t);
	//my_print_r($pk_array);
	$pk_str_full=$d.'_'.$t.'_';
	$pk_str='';
	foreach ($pk_array as $pkk=>$pkv)
	{
		$pk_str=$pk_str.$data[$pkv['Field']].'_';
		$pk_str_full=$pk_str_full.$data[$pkv['Field']].'_';
	}
	$fld=get_key($link,$d,$t);
	$fk=get_foreign_key($link,$d,$t);
		
	//my_print_r($data);
		echo '<button class="btn btn-info"type=button onclick="showHideClass(\''.$pk_str_full.'\')" >';
				echo '<span class="badge badge-danger">'.$d.'</span>&nbsp;';	
				echo '<span class="badge badge-danger">'.$t.'</span>&nbsp;';	
				echo '<span class="badge badge-danger">'.$pk_str.'</span>';
		echo '</button>';
		echo '<form method=post>';
		echo '<input type=hidden name=^database readonly size=\''.strlen($d).'\' value=\''.$d.'\'>';
		echo '<input type=hidden name=^table readonly size=\''.strlen($t).'\' value=\''.$t.'\'>';
	echo '<div class="container bg-warning border hdn '.$pk_str_full.'">';

	$parent=array('database'=>$d);
	$pk_filled=array();
	foreach($fld as $k=>$v)
	{
		if(in_subarray($pk_array,'Field',$v['Field']))
		{
			echo '<input type=hidden name=\''.$v['Field'].'\' value=\''.$data[$v['Field']].'\'>';
			$pk_filled[$v['Field']]=$data[$v['Field']];
		}
			
		echo '<div class="row">';
		//echo '<div class="row nnn">';
			echo '<div class="col-sm-4 bg-secondary border border-dark rounded">';
				echo $v['Field'];
			echo '</div>';
			echo '<div class="col-sm-8 border border-success rounded">';
		if($found=in_subarray($fk,'COLUMN_NAME',$v['Field']))
		{
				$sql='select * from `'.$found['REFERENCED_TABLE_NAME'].'` where 
							`'.$found['REFERENCED_COLUMN_NAME'].'`=\''.$data[$v['Field']].'\'';
				//echo $sql;
				$result_fk=run_query($link,$d,$sql);
				$fk_data=get_single_row($result_fk);
				//my_print_r($fk_data);
				$dv='';
				foreach($fk_data as $kk=>$vv)
				{
						if($kk=='password' ||$kk=='epassword')
						{
							$dv=$dv.'|XXX';
						}
						elseif(substr($kk,0,1)=='_'){}
						else
						{
							$dv=$dv.'|'.$vv;
						}
				}
				echo substr($dv,0,40);
				$parent['table']=$found['REFERENCED_TABLE_NAME'];
				$parent['pk'][$found['REFERENCED_COLUMN_NAME']]=$data[$v['Field']];
		}
		
		elseif($v['Type']=='blob' || $v['Type']=='mediumblob' || $v['Type']=='largeblob')
		{
			echo '<input type=hidden value=\''.$v['Field'].'\' name=blob_field>
						<button class="btn btn-primary"  
						formtarget=_blank
						type=submit
						name=action
						value=download>Download</button>';
		}
		else
		{
			echo htmlspecialchars($data[$v['Field']]);			
		}
			echo '</div>';
			
					
		echo '</div>';
	}
	
		echo '<div class="row">';		
			echo '<div class="col text-center">';
				echo '<input  class="btn btn-primary"  type=submit name=action value=edit>
					<input class="btn btn-danger"  type=submit name=action value=delete>';
	echo '</form>';

		echo '<form method=post class="d-inline">';
				echo '<input type=hidden name=^database readonly value=\''.$d.'\'>';
				echo '<input type=hidden name=^table readonly  value=\''.$t.'\'>';
				$detail_str='';
				foreach($pk_filled as $ff_k => $ff_v)
				{
					echo '<input type=hidden name=\''.$ff_k.'\' readonly value=\''.$ff_v.'\'>';			
					echo '<input type=hidden name=\'cb_'.$ff_k.'\' readonly >';	
					$detail_str=$detail_str.$ff_v.'_';		
				}
				echo '<button type=submit class="btn btn-secondary" name=action value=show_search_details>Detail View</button>';
				echo '<button type=submit class="btn btn-success" name=action value=print>Print</button>';				
			echo '</div>';	
		echo '</div>';
		echo '</form>';
	echo '</div>';	
}

function show_button($link,$d,$t,$data)
{
	$pk_array=get_primary_key($link,$d,$t);
	//my_print_r($pk_array);
	$pk_str_full=$d.'_'.$t.'_';
	$pk_str='';
	foreach ($pk_array as $pkk=>$pkv)
	{
		$pk_str=$pk_str.$data[$pkv['Field']].'_';
		$pk_str_full=$pk_str_full.$data[$pkv['Field']].'_';
	}
	$fld=get_key($link,$d,$t);
	$fk=get_foreign_key($link,$d,$t);
		
	//my_print_r($data);
		echo '<form method=post>';
		foreach($pk_array as $k=>$v)
		{
			echo '<input type=hidden name=\''.$v['Field'].'\' value=\''.$data[$v['Field']].'\'>';
		}		
		
		echo '<button class="btn btn-info" type=submit name=action value=show_single_by_pk>';
				//echo '<span class="badge badge-danger">'.$d.'</span>&nbsp;';	
				//echo '<span class="badge badge-danger">'.$t.'</span>&nbsp;';	
				//echo '<span class="badge badge-danger">'.$pk_str.'</span>';
				echo '<span class="badge badge-danger">Edit</span>';
		echo '</button>';
		echo '<input type=hidden name=^database readonly  value=\''.$d.'\'>';
		echo '<input type=hidden name=^table readonly  value=\''.$t.'\'>';
		echo '</form>';
}

function only_show($link,$d,$t,$data)
{
	$pk_array=get_primary_key($link,$d,$t);
	//my_print_r($pk_array);
	$pk_str_full=$d.'_'.$t.'_';
	$pk_str='';
	foreach ($pk_array as $pkk=>$pkv)
	{
		$pk_str=$pk_str.$data[$pkv['Field']].'_';
		$pk_str_full=$pk_str_full.$data[$pkv['Field']].'_';
	}
	$fld=get_key($link,$d,$t);
	$fk=get_foreign_key($link,$d,$t);
		
	//my_print_r($data);
		echo '<button class="btn btn-info"type=button onclick="showHideClass(\''.$pk_str_full.'\')" >';
				echo '<span class="badge badge-danger">'.$d.'</span>&nbsp;';	
				echo '<span class="badge badge-danger">'.$t.'</span>&nbsp;';	
				echo '<span class="badge badge-danger">'.$pk_str.'</span>';
		echo '</button>';
		echo '<form method=post>';
		echo '<input type=hidden name=^database readonly size=\''.strlen($d).'\' value=\''.$d.'\'>';
		echo '<input type=hidden name=^table readonly size=\''.strlen($t).'\' value=\''.$t.'\'>';
	echo '<div class="container bg-warning border hdn '.$pk_str_full.'" style="display:none">';

	$parent=array('database'=>$d);
	$pk_filled=array();
	foreach($fld as $k=>$v)
	{
		if(in_subarray($pk_array,'Field',$v['Field']))
		{
			echo '<input type=hidden name=\''.$v['Field'].'\' value=\''.$data[$v['Field']].'\'>';
			$pk_filled[$v['Field']]=$data[$v['Field']];
		}
			
		echo '<div class="row">';
		//echo '<div class="row nnn">';
			echo '<div class="col-sm-4 bg-secondary border border-dark rounded">';
				echo $v['Field'];
			echo '</div>';
			echo '<div class="col-sm-8 border border-success rounded">';
		if($found=in_subarray($fk,'COLUMN_NAME',$v['Field']))
		{
				$sql='select * from `'.$found['REFERENCED_TABLE_NAME'].'` where 
							`'.$found['REFERENCED_COLUMN_NAME'].'`=\''.$data[$v['Field']].'\'';
				//echo $sql;
				$result_fk=run_query($link,$d,$sql);
				$fk_data=get_single_row($result_fk);
				//my_print_r($fk_data);
				$dv='';
				foreach($fk_data as $kk=>$vv)
				{
						if($kk=='password' ||$kk=='epassword')
						{
							$dv=$dv.'|XXX';
						}
						elseif(substr($kk,0,1)=='_'){}
						else
						{
							$dv=$dv.'|'.$vv;
						}
				}
				echo substr($dv,0,40);
				$parent['table']=$found['REFERENCED_TABLE_NAME'];
				$parent['pk'][$found['REFERENCED_COLUMN_NAME']]=$data[$v['Field']];
		}
		
		elseif($v['Type']=='blob' || $v['Type']=='mediumblob' || $v['Type']=='largeblob')
		{
			echo '<input type=hidden value=\''.$v['Field'].'\' name=blob_field>
						<button class="btn btn-primary"  
						formtarget=_blank
						type=submit
						name=action
						value=download>Download</button>';
		}
		else
		{
			echo htmlspecialchars($data[$v['Field']]);			
		}
			echo '</div>';
			
					
		echo '</div>';
	}
		echo '</form>';
	echo '</div>';	
}


function show_horizontal_header($link,$d,$t)
{
	$fld=get_key($link,$d,$t);
	echo '<tr><td>Action</td>';
	foreach($fld as $k=>$v)
	{
		echo '<td>';
		echo $v['Field'];
		echo '</td>';
	}
	echo '</tr>';
}

function print_horizontal_header_pdf($link,$d,$t)
{
	$fld=get_key($link,$d,$t);
	echo '<tr><td>Sr No</td>';
	foreach($fld as $k=>$v)
	{
		echo '<td>';
		echo $v['Field'];
		echo '</td>';
	}
	echo '</tr>';
}

function show_horizontal_single_row($data)
{
	foreach($data as $k=>$v)
	{
		echo '<td>';
			echo htmlspecialchars($v);			
		echo '</td>';
	}
}

function show_vertical_single_row($data)
{
	echo '<table border="0.3" >';
	
	foreach($data as $k=>$v)
	{
		if(substr($k,0,1)!='_')
		{
		echo '<tr><td width="33%">'.$k.'</td><td width="67%">';
			echo htmlspecialchars($v);			
		echo '</td></tr>';
	    }
	}
	echo '</table>';
	
}

function show_horizontal_all($link,$d,$t,$offset,$limit,$default)
{
	$sql_where='';
	foreach($default as $k=>$v)
	{	
		$sql_where=$sql_where.' `'.$k.'` = \''.$v.'\' and ';
	}
	$sql_where=substr($sql_where,0,-4);
	
	if(strlen($sql_where)<=0){$sql='select * from`'.$t.'` limit '.$offset.','.$limit;}
	else{$sql='select * from`'.$t.'` where '.$sql_where.' limit '.$offset.','.$limit;}

	echo '<div class="row">';	
		echo '<div class="col text-center">';	
			echo '<button class="btn btn-warning" type=button onclick="showHideClass(\'hdn\')">Toggle show</button>';

			if($offset>0)
			{
			echo '<form method=post  style="display:inline">';
			echo '<button class="btn btn-warning" type=submit>Previous Page('.max(($offset-$GLOBALS['limit']),1).'-'.$offset.')</button>';
			echo '<input type=hidden name=offset value=\''.max(($offset-$GLOBALS['limit']),0).'\'>';
			echo '<input type=hidden name=action value=show_horizontal_all>';
			echo '<input type=hidden name=^database readonly value=\''.$d.'\'>';
			echo '<input type=hidden name=^table readonly value=\''.$t.'\'>';				
			echo '</form>';
			}
						
			echo '<form method=post style="display:inline">';
			echo '<button class="btn btn-warning" type=submit>Next Page('.($offset+$GLOBALS['limit']+1).'-'.($offset+2*$GLOBALS['limit']).')</button>';
			echo '<input type=hidden name=offset value=\''.($offset+$GLOBALS['limit']).'\'>';
			echo '<input type=hidden name=action value=show_horizontal_all>';
			echo '<input type=hidden name=^database readonly value=\''.$d.'\'>';
			echo '<input type=hidden name=^table readonly value=\''.$t.'\'>';			
			echo '</form>';
			
		echo '</div>';
	echo '</div>';		
		
	show_horizontal_all_sql($link,$d,$t,$sql);
	
}
function show_horizontal_all_sql($link,$d,$t,$sql)
{
	//echo $sql;
	//return;
	$result=run_query($link,$d,$sql);
	echo '<table border=1>';
	show_horizontal_header($link,$d,$t);
	while($ar=get_single_row($result))
	{	
		echo '<tr>';
		echo '<td>';
		show_button($link,$d,$t,$ar);
		echo '</td>';
		show_horizontal_single_row($ar);
		echo '</tr>';
	}
	echo '</table>';
}

function show_parent_button($link,$d,$t,$pka)
{
	$pk_array=get_primary_key($link,$d,$t);
	$fld=get_key($link,$d,$t);
	$fk=get_foreign_key($link,$d,$t);
		
	$parent=array('database'=>$d);
	foreach($fld as $k=>$v)
	{
		if($found=in_subarray($fk,'COLUMN_NAME',$v['Field']))
		{
				$sql='select * from `'.$found['REFERENCED_TABLE_NAME'].'` where 
							`'.$found['REFERENCED_COLUMN_NAME'].'`=\''.$pka[$v['Field']].'\'';
				//echo $sql;
				$result_fk=run_query($link,$d,$sql);
				$fk_data=get_single_row($result_fk);
				//my_print_r($fk_data);
				$dv='';
				foreach($fk_data as $kk=>$vv)
				{
						if($kk=='password' ||$kk=='epassword')
						{
							$dv=$dv.'|XXX';
						}
						elseif(substr($kk,0,1)=='_'){}
						else
						{
							$dv=$dv.'|'.$vv;
						}
				}
				$parent['table']=$found['REFERENCED_TABLE_NAME'];
				$parent['pk'][$found['REFERENCED_COLUMN_NAME']]=$pka[$v['Field']];
		}
	}
	
	//my_print_r($fk);
	//my_print_r($pk_array);
	//my_print_r($parent);

	if(isset($parent['table']))
	{
		echo '<form method=post>';
			echo '<input type=hidden name=^database readonly value=\''.$parent['database'].'\'>';
			echo '<input type=hidden name=^table readonly  value=\''.$parent['table'].'\'>';
			$parent_str='';
			foreach($parent['pk'] as $ff_k => $ff_v)
			{
				echo '<input type=hidden name=\''.$ff_k.'\' readonly value=\''.$ff_v.'\'>';			
				echo '<input type=hidden name=\'cb_'.$ff_k.'\' readonly >';	
				$parent_str=$parent_str.$ff_v.'_';		
			}
			echo '<button type=submit class="btn btn-secondary" name=action value=show_search_rows>';
				echo '<span class="badge badge-dark">GO TO</span>&nbsp;';	
				echo '<span class="badge badge-danger">'.$parent['database'].'</span>&nbsp;';	
				echo '<span class="badge badge-danger">'.$parent['table'].'</span>&nbsp;';	
				echo '<span class="badge badge-danger">'.$parent_str.'</span>';
			echo '</button>';
		echo '</form>';
	}
}


function show_parent_rows($link,$d,$t,$pka)
{
	$pk_array=get_primary_key($link,$d,$t);
	$fld=get_key($link,$d,$t);
	$fk=get_foreign_key($link,$d,$t);
		
	$parent=array('database'=>$d);
	foreach($fld as $k=>$v)
	{
		if($found=in_subarray($fk,'COLUMN_NAME',$v['Field']))
		{
				$sql='select * from `'.$found['REFERENCED_TABLE_NAME'].'` where 
							`'.$found['REFERENCED_COLUMN_NAME'].'`=\''.$pka[$v['Field']].'\'';
				//echo $sql;
				$result_fk=run_query($link,$d,$sql);
				$fk_data=get_single_row($result_fk);
				//my_print_r($fk_data);
				$dv='';
				foreach($fk_data as $kk=>$vv)
				{
						if($kk=='password' ||$kk=='epassword')
						{
							$dv=$dv.'|XXX';
						}
						elseif(substr($kk,0,1)=='_'){}
						else
						{
							$dv=$dv.'|'.$vv;
						}
				}
				$parent['table']=$found['REFERENCED_TABLE_NAME'];
				$parent['pk'][$found['REFERENCED_COLUMN_NAME']]=$pka[$v['Field']];
		}
	}
	
	//my_print_r($fk);
	//my_print_r($pk_array);
	//my_print_r($parent);

	if(isset($parent['table']))
	{
		show_search_rows_by_pka($link,$parent['database'],$parent['table'],$parent['pk']);
	}
}

function show_dependent_button($link,$d,$t,$pka)
{
	$dep=get_dependant_table($link,$d,$t);

	foreach ($dep as $v)
	{
			if(result_count(get_search_result_by_pka($link,$v['TABLE_SCHEMA'],$v['TABLE_NAME'],$pka))>0)
			{
				echo '<form method=post>';
				echo '<input type=hidden name=^database readonly value=\''.$v['TABLE_SCHEMA'].'\'>';
				echo '<input type=hidden name=^table readonly  value=\''.$v['TABLE_NAME'].'\'>';
				$dep_str='';
				foreach($pka as $ff_k => $ff_v)
				{
					echo '<input type=hidden name=\''.$ff_k.'\' readonly value=\''.$ff_v.'\'>';			
					echo '<input type=hidden name=\'cb_'.$ff_k.'\' readonly >';	
					$dep_str=$dep_str.$ff_v.'_';		
				}
				echo '<button type=submit class="btn btn-secondary" name=action value=show_search_rows>';
					echo '<span class="badge badge-dark">GO TO</span>&nbsp;';	
					echo '<span class="badge badge-danger">'.$v['TABLE_SCHEMA'].'</span>&nbsp;';	
					echo '<span class="badge badge-danger">'.$v['TABLE_NAME'].'</span>&nbsp;';	
					echo '<span class="badge badge-danger">'.$dep_str.'</span>';
				echo '</button>';
				echo '</form>';
			}
				//show_search_rows_by_pka($link,$v['TABLE_SCHEMA'],$v['TABLE_NAME'],$pka);
	}
}


function in_subarray($a,$k,$v)
{
		foreach($a as $sa)
		{
			if(isset($sa[$k]))
			{
				if($sa[$k]==$v)
				{
					return $sa;
				}
			}
		}
		return false;
}

function mk_select_sql_from_pk($link,$d,$t,$pk_value_array)
{
	$sql_pwhere=' where ';
	
	$pk_array=get_primary_key($link,$d,$t);
	
	foreach($pk_array as $pk)
	{
		$sql_pwhere=$sql_pwhere.'`'.$pk['Field'].'`='.'\''.$pk_value_array[$pk['Field']].'\' and ';
	}
	$sql_pwhere=substr($sql_pwhere,0,-4);
	
	$psql='select * from `'.$t.'`'.$sql_pwhere;
//echo $psql;
	
	return $psql;
}


function mk_select_sql_from_default($link,$d,$t,$default)
{
	$sql_pwhere='';
	
	foreach($default as $k=>$v)
	{
		$sql_pwhere=$sql_pwhere.'`'.$k.'`='.'\''.$v.'\' and ';
	}
	
	$sql_pwhere=substr($sql_pwhere,0,-4);
	
	if(strlen($sql_pwhere)>0)
	{
		$sql_pwhere=' where '.$sql_pwhere;
	}
	$psql='select * from `'.$t.'` '.$sql_pwhere.' limit '.$GLOBALS['limit'];
	//echo $psql;
	
	return $psql;
}



function mk_delete_sql_from_pk($link,$d,$t,$pk_value_array)
{
	$sql_pwhere=' where ';
	
	$pk_array=get_primary_key($link,$d,$t);
	
	foreach($pk_array as $pk)
	{
		$sql_pwhere=$sql_pwhere.'`'.$pk['Field'].'`='.'\''.$pk_value_array[$pk['Field']].'\' and ';
	}
	$sql_pwhere=substr($sql_pwhere,0,-4);
	
	$psql='delete from `'.$t.'`'.$sql_pwhere;
//echo $psql;
	
	return $psql;
}

function my_print_r($a)
{
	echo '<pre>';
	print_r($a);
	echo '</pre>';
}

function get_key($link,$d,$t)
{
	$sql='desc `'.$t.'`';
	//echo $sql;
	$result=run_query($link,$d,$sql);
	$ret=array();
	while($data=get_single_row($result))
	{
		$ret[]=$data;
	}
	return $ret;
}

function get_primary_key($link,$d,$t)
{
	$sql='desc `'.$t.'`';
	//echo $sql;
	$result=run_query($link,$d,$sql);
	$ret=array();
	while($data=get_single_row($result))
	{
		//print_r($data);echo '<br>';
		if($data['Key']=='PRI')
		{
			$ret[]=$data;
		}
	}
	//print_r($ret);
	return $ret;
}

function get_foreign_key($link,$d,$t)
{
	$sql='select * from KEY_COLUMN_USAGE 
				where 
					constraint_schema=\''.$d.'\' and 
					table_name=\''.$t.'\' and
					REFERENCED_COLUMN_NAME is not null';
	//echo $sql;
	$result=run_query($link,'information_schema',$sql);
	$ret=array();
	while($data=get_single_row($result))
	{
		$ret[]=$data;
	}
	return $ret;
}

function get_dependant_table($link,$d,$t)
{
	$sql=	'SELECT * FROM `KEY_COLUMN_USAGE` 
				WHERE `REFERENCED_TABLE_SCHEMA`=\''.$d.'\' and 
				`REFERENCED_TABLE_NAME`=\''.$t.'\'';
	
	$result=run_query($link,'information_schema',$sql);
	
	$ret=false;
	
	while($data=get_single_row($result))
	{
		$ret[]=$data;
	}
	//return mysqli_fetch_all($result,MYSQLI_ASSOC);
	
	return $ret;
}

function show_dependent_rows($link,$d,$t,$pka)
{
	$dep=get_dependant_table($link,$d,$t);

	foreach ($dep as $v)
	{
		show_search_rows_by_pka($link,$v['TABLE_SCHEMA'],$v['TABLE_NAME'],$pka);
	}
}

function add_dependent_rows($link,$d,$t,$pka)
{
	$result=get_search_result_by_pka($link,$d,$t,$pka);
	if(!$result){return false;}

	if(result_count($result)>0)
	{
		$dep=get_dependant_table($link,$d,$t);
		foreach ($dep as $v)
		{
			add($link,$v['TABLE_SCHEMA'],$v['TABLE_NAME'],$pka);
		}
	}
}

function add_dependent_button($link,$d,$t,$pka)
{
	$result=get_search_result_by_pka($link,$d,$t,$pka);
	if(!$result){return false;}

	if(result_count($result)>0)
	{
		$dep=get_dependant_table($link,$d,$t);
		foreach ($dep as $v)
		{
			add($link,$v['TABLE_SCHEMA'],$v['TABLE_NAME'],$pka);
		}
	}
}

function prepare_option_from_fk($link,$d,$t)
{
	$fk_array=get_foreign_key($link,$d,$t);
	//my_print_r($fk_array);
	$option=array();
	foreach($fk_array as $fk)
	{
		if(substr($fk['CONSTRAINT_NAME'],-4)!='text')	//to inhibit long listing where required
		{
			$sql='select * 
					from `'.$fk['REFERENCED_TABLE_NAME'].'` group by  `'.$fk['REFERENCED_COLUMN_NAME'].'`';
			//echo $sql;
			$result=run_query($link,$d,$sql);
			while($ar=get_single_row($result))
			{
				$option[$fk['REFERENCED_COLUMN_NAME']][$ar[$fk['REFERENCED_COLUMN_NAME']]]=$ar[$fk['REFERENCED_COLUMN_NAME']];
			}
		}
	}
	//my_print_r($option);
	return $option;
}


function mk_select_from_array_return_key($name, $select_array,$disabled,$default)
{
	//print_r($select_array);
		//echo $default.'<<<<';
		
		echo '<select  '.$disabled.'  id=\''.$name.'\' name=\''.$name.'\'>';
		foreach($select_array as $key=>$value)
		{
			if($key==$default)
			{
				echo '<option  selected value=\''.$key.'\' > '.$key.'*'.$value.' </option>';
			}
			else
			{
				echo '<option  value=\''.$key.'\' > '.$key.'*'.$value.' </option>';
			}
		}
		echo '</select>';	
		return TRUE;
}

function mk_select_from_array($name, $select_array,$disabled,$default)
{
	//print_r($select_array);
		//echo $default.'<<<<';
		
		echo '<select  '.$disabled.' id=\''.$name.'\' name=\''.$name.'\'>';
		foreach($select_array as $key=>$value)
		{
					//echo $default.'?'.$value;
			if($value==$default)
			{
				echo '<option  selected > '.$value.' </option>';
			}
			else
			{
				echo '<option > '.$value.' </option>';
			}
		}
		echo '</select>';	
		return TRUE;
}
/////////////database functions//////////////////////

function get_link($u,$p,$role='')
{
	$link=mysqli_connect('127.0.0.1',$u,$p);
	if(!$link)
	{
		echo 'error1:'.mysqli_error($link); return false;
	}
	else
	{
		if($role==''){return $link;}
		else
		{
			mysqli_query($link,'set role \''.$role.'\'');
			return $link;
		}
	}	
}


function run_query($link,$db,$sql)
{
	$db_success=mysqli_select_db($link,$db);
	
	if(!$db_success)
	{
		echo 'error2:'.mysqli_error($link); return false;
	}
	else
	{
		$result=mysqli_query($link,$sql);
	}
	
	if(!$result)
	{
		echo 'error3:'.mysqli_error($link); return false;
	}
	else
	{
		return $result;
	}
}

function result_count($result)
{
	if(!$result)
	{
		return 0;
	}
	else
	{
		return mysqli_num_rows($result);
	}
}
function get_single_row($result)
{
		if($result!=false)
		{
			return mysqli_fetch_assoc($result);
		}
		else
		{
			return false;
		}
}

function my_safe_text($link,$str)
{
	return mysqli_real_escape_string($link,$str);
}
///////////////////general functions///////////////////

function read_number($name,$id,$from,$to,$default='',$readonly='')
{
	if($readonly=='')
	{
		echo '<select '.$readonly.' title="'.$name.'" name=\''.$name.'\' id=\''.$id.'\' style="padding:0 !important;margin:0 !important">';
		for($i=$from;$i<=$to;$i++)
		{
			if($i==$default)
			{
				echo '<option selected>'.$default.'</option>';
			}
			else
			{
				echo '<option >'.$i.'</option>';			
			}
		}
		echo '</select>';
	}
	else
	{
		echo '<input style="padding:0 !important;margin:0 !important" type=text '.$readonly.' size=3 title="'.$name.'" value=\''.$default.'\' name=\''.$name.'\' id=\''.$id.'\'>';
	}
	
}


function read_datetime($name,$id,$include,$default='',$readonly='')
{

	//64=year,32=month,16=day,8=hr,4=min,2=sec
	if($default=='')
	{
		$date=date_parse(date('Y-M-d h:r:s'));
	}
	else
	{
		$date=date_parse($default);		
	}
	//my_print_r($date);
	echo '<table class="text-nowrap"><tr>';
	if(($include&32)==32)
	{
		echo '<td><input size=3  '.$readonly.' title=\''.$name.'_year\' min=0 max=9999
							type=number style="width:5em" placeholder=YYYY name=\''.$name.'_year\' id=\''.$id.'_year\' 
							value=\''.$date['year'].'\'></td>';
	}

	if(($include&16)==16)
	{						
		echo '		<td>';read_number($name.'_month',$id.'_month',0,12,$date['month'],$readonly);echo '</td>';
	}
	if(($include&8)==8)
	{	
		//echo '<td>';read_number($name.'_day',$id.'_day',0,31,$date['day'],$readonly,$fun,);echo '</td>';
		//for js
		if($readonly=='')
		{
			echo '<td><select 
					onchange="return chk_date(\''.$id.'_year\',\''.$id.'_month\',\''.$id.'_day\')";'
					.$readonly.' title="'.$name.'_day" name=\''.$name.'_day\' id=\''.$id.'_day\' style="padding:0 !important;margin:0 !important">';
			for($i=0;$i<=31;$i++)
			{
				if($i==$date['day'])
				{
					echo '<option selected>'.$i.'</option>';
				}
				else
				{
					echo '<option >'.$i.'</option>';			
				}
			}
			echo '</select></td>';
		}
		else
		{
			echo '<td><input style="padding:0 !important;margin:0 !important" type=text '.$readonly.' size=3 title="'.$name.'" value=\''.$default.'\' name=\''.$name.'\' id=\''.$id.'\'></td>';
		}
	}
	if(($include&4)==4)
	{	
		echo '		<td>';read_number($name.'_hour',$id.'_hour',0,23,$date['hour'],$readonly);echo ':</td>';
	}
	if(($include&2)==2)
	{	
		echo '		<td>';read_number($name.'_min',$id.'_min',0,59,$date['minute'],$readonly);echo ':</td>';
	}
	if(($include&1)==1)
	{	
		echo '		<td>';read_number($name.'_sec',$id.'_sec',0,59,$date['second'],$readonly);echo '</td>';
	}
	echo '		</tr></table>';
}

///////////////Verify application user//////////////////

function verify_ap_user($du,$dp,$role,$ud,$ut,$uf,$uv,$pf,$pv)
{
    $expirydate_field=$GLOBALS['expirydate_field'];
	
    $link=get_link($du,$dp,$role);
    $sql='select * from `'.$ut.'` where `'.$uf.'` = \''.$uv.'\'';
    $result=run_query($link,$ud,$sql);
    if($result===FALSE){echo mysqli_error($link);return false;}
    if(result_count($result)<1)
	{
	echo 'Application user not verified. No such user';return false;
	return false;
	}
    $result_array=get_single_row($result);
    //echo $pf.'=>'.$result_array[$pf].'=>'.$pv;
    if(!password_verify($pv,$result_array[$pf]))
	{
		echo 'Application user not verified. Password not verified';return false;
	}
    
    if(strtotime($result_array[$expirydate_field]) < strtotime(date("Y-m-d")))
    {
			echo '<!DOCTYPE html>
					<html lang="en">
						<head>
							<meta charset="utf-8">
							<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
							<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">		
						</head>
					  <body>';
		echo '<div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">';
		     
		echo '<h5 class="text-info bg-warning text-center">Password Change Required</h5>';
				    
	    echo '<form method=post><button 
					class="btn btn-primary btn-block"  
					formaction=change_expired_password.php 
					type=submit onclick="hidemenu()" 
					name=change_pwd>Change Password</button></form>';
		echo	'</div></div></div></body></html>';		
		exit(0);
	}
	
    return true;
}



function verify_ap_user_without_expiry($du,$dp,$role,$ud,$ut,$uf,$uv,$pf,$pv)
{
    $link=get_link($du,$dp,$role);
    $sql='select * from `'.$ut.'` where `'.$uf.'` = \''.$uv.'\'';
    $result=run_query($link,$ud,$sql);
    if($result===FALSE){echo mysqli_error($link);return false;}
    $result_array=get_single_row($result);
    //echo $pf.'=>'.$result_array[$pf].'=>'.$pv;
    if(!password_verify($pv,$result_array[$pf])){echo 'Application user not verified';return false;}
    return true;
}

function file_to_str($link,$file)
{
	if($file['size']>0)
	{
	$fd=fopen($file['tmp_name'],'r');
	$size=$file['size'];
	$str=fread($fd,$size);
	return mysqli_real_escape_string($link,$str);
	}
	else
	{
		return false;
	}
}

function logout($message='')
{
	session_destroy();
	session_start();	
	header("location:index.php?".$message);
}

function is_valid_password($pwd){
// accepted password length minimum 8 its contain lowerletter,upperletter,number,special character.
    if (preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,}$/", $pwd))
   {
   
        return true;
	}
    else{
		
        return false;
	}
}

function update_password($du,$dp,$role,$ud,$ut,$uf,$uv,$pf,$pv,$expiry_period)
{
	$link=get_link($du,$dp,$role);
	$eDate = date('Y-m-d');
    $eDate = date('Y-m-d', strtotime($expiry_period, strtotime($eDate)));
    // echo $eDate;	
	$sqli='update  `'.$ut.'` set `'.$pf.'` =\''.password_hash($pv,PASSWORD_BCRYPT).'\',expirydate=\''.$eDate.'\' where `'.$uf.'`=\''.$uv.'\'';	
	//echo $sqli;
	$user_pwd=run_query($link,$ud,$sqli);
	if($user_pwd>0)
	{
		return true;	
	}
	else
	{
		return false;	
	}
}


//New with MD5 to encrypt transition
function check_user($link,$u,$p)
{
	$sql='select * from where id=\''.$u.'\'';
	//echo $sql;
	if(!$result=run_query($link,'staff',$sql)){return FALSE;}
	$result_array=get_single_row($result);
	//check validation
	
	
	//First verify encrypted password
	if(password_verify($p,$result_array['epassword']))
	{
		//echo strtotime($result_array['expirydate']).'<'.strtotime(date("Y-m-d"));
		
		if(strtotime($result_array['expirydate']) < strtotime(date("Y-m-d")))
	    {
		
		echo '
		     <div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">
               <form method=post>
                   <table  class="table table-striped" >
                    <tr>
		                  <th colspan=2 style="background-color:lightblue;text-align: center;">
		                      <h3>Password Expired</h3>
		                  </th>   
		            </tr>
		            <tr>
		                  <td></td>
		                  <td></td>
		            </tr>
	                <tr>
		                 <th>
			                  Login Id
		                 </th>
		                 <td>
			                  <input type=text readonly name=login id=id value=\''.$_SESSION['login'].'\'>
		                 </td>
	                </tr>
                 
	                 <tr>
		                <td></td>
		                <td>
                            <button class="btn btn-success" name=action type=submit value="change_password_step_1" formaction="../student/change_expired_pass.php">Change Password</button>
	               	    </td>
	               </tr>
	              </table>
	              </form>
	              </div>
	              </div>
	              </div>';

			exit(0);
	    }
	    else
	    {
			//do nothing
	    }
		return true;	
	}
	
else if(strlen($result_array['epassword'])>0)
    {	
		if(password_verify($p)==$result_array['epassword'])		//last chance for md5
		{
			 $sqli="update user set epassword='".password_hash($p,PASSWORD_BCRYPT)."' where id='$u'";	
	         //echo $sqli;
	         $user_pwd=run_query($link,'staff',$sqli);
	        // echo $user_pwd;
	         return true;	
	     }
	     else
	     {
		       return false;	//if encrypted password is not written
	     }
	}
	
	else //if encrypt fail and md5 lenght is zero, get out
	{
		return false;
	}
}

function set_session()
{
	if(!isset($_SESSION['login']))
	{
		$_SESSION['login']=$_POST['login'];
	}

	if(!isset($_SESSION['password']))
	{
		$_SESSION['password']=$_POST['password'];
	}
	
	if(!verify_ap_user	(	$GLOBALS['main_user'],$GLOBALS['main_pass'],"",
							$GLOBALS['user_database'],$GLOBALS['user_table'],
							$GLOBALS['user_id'],$_SESSION['login'],
							$GLOBALS['user_pass'],$_SESSION['password'])
						)
						{exit(0);}					
	$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);
	if(!$link){exit(0);}
	return $link;
}

function set_session_without_expiry()
{
	if(!isset($_SESSION['login']))
	{
		$_SESSION['login']=$_POST['login'];
	}

	if(!isset($_SESSION['password']))
	{
		$_SESSION['password']=$_POST['password'];
	}
	
	if(!verify_ap_user_without_expiry	(	$GLOBALS['main_user'],$GLOBALS['main_pass'],"",
							$GLOBALS['user_database'],$GLOBALS['user_table'],
							$GLOBALS['user_id'],$_SESSION['login'],
							$GLOBALS['user_pass'],$_SESSION['password'])
						)
						{exit(0);}					
	$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);
	if(!$link){exit(0);}
	return $link;
}
function read_password()
{
  echo '<br><br>
    <div class="container-fluid">
      <div class="row">				
	    <div class="col-*-6 bg-light text-center mx-auto">
	      <form method=post>
            <table border="1">
	           <tr>
	              <th colspan=2 class="text-info bg-dark text-center">
	                 <h4>Change Password</h4>
	              </th>
	           </tr>
	           <tr>
	              <td>Login ID</td>
	              <td><input readonly=yes type=text name=id value=\''.$_SESSION['login'].'\'></td>
	           </tr>
	           <tr>
	               <td>Old Password</td>
	               <td><input type=password name=old_password></td>
	           </tr>
	           <tr>
	               <td>New Password</td>
	               <td><input type=password name=password_1  title=" contain at least one lowercase letter, one uppercase letter, one numeric digit, and one special character at least 8 or more characters" required></td>
	           </tr>
	           <tr>
	                <td>Repeat New Password</td>
	               	<td><input type=password name=password_2></td>
	           </tr>
	           <tr>
	                <td colspan=2 align=center><button  class="btn btn-success btn-sm"  type=submit name=action value=change_password>Change Password</button></td>
	           </tr>
	         </table>
	       </form>
	     </div>
	   </div>
	 </div>';
	echo '<div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">
            <table class="table">
			<tr><td colspan=3 style="text-align:center;" class="text-info bg-dark"><h5>>8 characters, One capital, One number, One special</h5></td></tr>
			<tr><td>iamgood</td><td>Unacceptable</td><td>No capital, no number, no special character, less than 8</td></tr>
			<tr><td>Iamgood007</td><td>Unacceptable</td><td>no special character</td></tr>
			<tr><td>Iamgood007$</td><td>Acceptable</td><td>special characters-> ! @ # $ % ^ & * ( ) _ - += { [ } ] | \ / &lt; , &gt; . ; : " \'</td></tr>
            </table>
            </div>
            </div>
            </div>';	
}

function head()
{
	if($GLOBALS['nojunk']==TRUE){return;}
	echo '<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">	';	
	run_js();	
	echo '</head>
  <body><div class="container">';
} 	

function tail()
{
	if($GLOBALS['nojunk']==TRUE){return;}
	echo '</div></body></html>';
}

function download($link,$d,$t,$f,$pkva)
{
	$sql=mk_select_sql_from_pk($link,$d,$t,$pkva);
	$result=run_query($link,$d,$sql);
	$ar=get_single_row($result);
	$h='Content-Disposition: attachment; filename="'.$ar[$f.'_name'].'"';
	header($h);
	echo $ar[$f];
}

function run_js()
{
	if($GLOBALS['nojunk']==TRUE){return;}
	echo '<script>

	function showhide_with_label(one,labell,textt) {
					if(document.getElementById(one).style.display == "none")
					{
						document.getElementById(one).style.display = "block";
						labell.innerHTML="Hide "+textt;
					}
					else
					{
						document.getElementById(one).style.display = "none";
						labell.innerHTML="Show "+textt;
					}

			}
	function run_ajax(str,rid)
	{
		//create object
		xhttp = new XMLHttpRequest();
		
		//4=request finished and response is ready
		//200=OK
		//when readyState status is changed, this function is called
		//responceText is HTML returned by the called-script
		//it is best to put text into an element
		xhttp.onreadystatechange = function() {
		  if (this.readyState == 4 && this.status == 200) {
			document.getElementById(rid).innerHTML = this.responseText;
		  }
		};

		//Setting FORM data
		xhttp.open("POST", "save_salary.php", true);
		
		//Something required ad header
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		
		// Submitting FORM
		xhttp.send(str);
		
		//used to debug script
		//alert("Used to check if script reach here");
	}

	function make_post_string(id,idd,t)
	{
		k=encodeURIComponent(t.id);					//to encode almost everything
		v=encodeURIComponent(t.value);					//to encode almost everything
		post=\'field=\'+k+\'&value=\'+v+\'&staff_id=\'+id+\'&bill_group=\'+idd;
		return post;							
	}

	function do_work(id,idd,t)
	{
		str=make_post_string(id,idd,t);
		//alert(post);
		run_ajax(str,\'response\');
	}

	function getfrom(one,two) {
				document.getElementById(two).value =one.value;
			}
		

	function hide(one) {
					document.getElementById(one).style.display = "none";
			}



	function showhide(one) {
		if(document.getElementById(one).style.display == "none")
		{
			document.getElementById(one).style.display = "block";
		}
		else
		{
			document.getElementById(one).style.display = "none";
		}
	}

	function showHideClass(one) {
		elements=document.getElementsByClassName(one);
		for(var i = 0; i < elements.length; i++)
		{
			if(elements[i].style.display == "none")
			{
				elements[i].style.display = "block";
			}
			else
			{
				elements[i].style.display = "none";
			}
		}
	}
	
	function read_bn()
	{
		xx=prompt(\'Copy to bill number:\');
		
	}

	function showhidemenu(one) 
	{		
		xx=document.getElementsByClassName(\'menu\');			
		for(var i = 0; i < xx.length; i++)
		{
			if(xx[i]!=document.getElementById(one))
			{
				xx[i].style.display = "none";		
			}
			
			else if(xx[i]==document.getElementById(one))
			{
				if(xx[i].style.display == "block")
				{
					xx[i].style.display = "none";
				}
				else
				{
					xx[i].style.display = "block";
				}		
			}
		}	
	}

	function hidemenu() {

		xx=document.getElementsByClassName(\'menu\');
		for(var i = 0; i < xx.length; i++)
		{
			xx[i].style.display = "none";		
		}
	}


	//The year can be evenly divided by 4; 
    //If the year can be evenly divided by 100, it is NOT a leap year, unless;
    //The year is also evenly divisible by 400. Then it is a leap year.
	
	// x % 400 = 0 => leap
	// x% 100 !=0 add x%4==0 =>leap
	function chk_date(yn,mn,dn)
	{
		y=document.getElementById(yn).value;
		m=document.getElementById(mn).value;
		d=document.getElementById(dn).value;
		if(m==1 || m==3 || m==5 || m==7 || m==8 || m==10 || m==12)
		{
			if(d<=31){ return true;}else{alert("Wrong Date:"+y+"-"+m+"-"+d+". -> 00-00-0000 will be saved");return false;}
		}
		else if(m==4 || m==6 || m==9 || m==11)
		{
			if(d<=30){ return true;}else{alert("Wrong Date:"+y+"-"+m+"-"+d+". -> 00-00-0000 will be saved");return false;}
		}
		else if(m==2)
		{
			if( ((y%4==0) && (y%100)!=0) || (y%400==0) )
			{
				if(d<=29){ return true;}else{alert("Wrong Date:"+y+"-"+m+"-"+d+". -> 00-00-0000 will be saved");return false;}
			}
			else
			{
				if(d<=28){ return true;}else{alert("Wrong Date:"+y+"-"+m+"-"+d+". -> 00-00-0000 will be saved");return false;}
			}
		}
		return false;
	}
	
	
	function manage_marks(id)
	{ 
	
	
			if(		id.name == "final_year_marks_obtained" || 
					id.name == "final_year_marks_max" ||
					 
					id.name == "final_year_SGPA" ||
					
					id.name == "5th_sem_marks_obtained" ||
					id.name == "5th_sem_marks_max" ||
					id.name == "6th_sem_marks_obtained" ||
					id.name == "6th_sem_marks_max" ||
					
					id.name == "5th_sem_SGPA" ||
					id.name == "6th_sem_SGPA")
					{
						//alert(id.name);
						if( 	(id.name=="final_year_marks_obtained" ||
								id.name=="final_year_marks_max")		
								&& id.value>0
							)
						{
							//alert("Other Marks/Grade fields not relevent will be set to 0");
							
							document.getElementById("final_year_SGPA").value=0;
							
							document.getElementById("5th_sem_marks_obtained").value=0;
							document.getElementById("5th_sem_marks_max").value=0;
							document.getElementById("6th_sem_marks_obtained").value=0;
							document.getElementById("6th_sem_marks_max").value=0;
							
							document.getElementById("5th_sem_SGPA").value=0;
							document.getElementById("6th_sem_SGPA").value=0;
						}

						else if( id.name=="final_year_SGPA" && id.value>0)
						{
							//alert("Other Marks/Grade fields not relevent will be set to 0");
							
							document.getElementById("final_year_marks_obtained").value=0;
							document.getElementById("final_year_marks_max").value=0;
							
							document.getElementById("5th_sem_marks_obtained").value=0;
							document.getElementById("5th_sem_marks_max").value=0;
							document.getElementById("6th_sem_marks_obtained").value=0;
							document.getElementById("6th_sem_marks_max").value=0;
							
							document.getElementById("5th_sem_SGPA").value=0;
							document.getElementById("6th_sem_SGPA").value=0;
						}	

						else if((id.name=="5th_sem_marks_obtained" 	||
								id.name=="5th_sem_marks_max"		||
								id.name=="6th_sem_marks_obtained" 	||
								id.name=="6th_sem_marks_max")
								
								&& id.value>0)
						{
							//alert("Other Marks/Grade fields not relevent will be set to 0");

							document.getElementById("final_year_SGPA").value=0;
							
							document.getElementById("final_year_marks_obtained").value=0;
							document.getElementById("final_year_marks_max").value=0;
													
							document.getElementById("5th_sem_SGPA").value=0;
							document.getElementById("6th_sem_SGPA").value=0;
						}						
						
						else if((id.name=="5th_sem_SGPA" ||
								id.name=="6th_sem_SGPA")
								
								&& id.value>0
							)
						{
							//alert("Other Marks/Grade fields not relevent will be set to 0");
							
							document.getElementById("final_year_marks_obtained").value=0;
							document.getElementById("final_year_marks_max").value=0;
							
							document.getElementById("final_year_SGPA").value=0;
							
							document.getElementById("5th_sem_marks_obtained").value=0;
							document.getElementById("5th_sem_marks_max").value=0;
							document.getElementById("6th_sem_marks_obtained").value=0;
							document.getElementById("6th_sem_marks_max").value=0;
							
						}											
		

					}
	}

	</script>';
}


function search_tables($link,$d,$table_array,$post)
{
	foreach($table_array as $t)
	{
		show_search_rows($link,$d,$t,$post);
	}
}

function mk_menu()
{
	//my_print_r($GLOBALS['menu']);
	foreach($GLOBALS['menu'] as $main_menu_name=>$submenu)
	{
		echo '<td>
						<button  class=" btn btn-primary btn-block"  
								type=button onclick="showhidemenu(\'menu_'.$main_menu_name.'\' )">'.$main_menu_name.'
						</button>
							<table  id=\'menu_'.$main_menu_name.'\' 
									class="menu" 
									style="position: absolute;display:none;z-index:100;">';
		foreach($submenu as $entry_name=>$action)
		{
						echo 	'<tr><td>
										   <form method=post style="display:inline">
												<button class="btn btn-primary btn-block"  
														formaction=\''.$action[1].'\' 
														type=submit 
														name=action 
														'.$action[2].'
														value=\''.$action[0].'\'  
														onclick="hidemenu()" >'.$entry_name.'</button>
											</form>
								   </td></tr>';
					   
		}
								 
							echo '</table>';
		echo '</td>';
	}	
}

function menu()
{	
	if($GLOBALS['nojunk']==TRUE){return;}

	echo '
<div class="container">
<div class="row">
<div class="col-md-12">	
	<table>		
			<tr>';

		mk_menu();

		echo'	<td>
					<button  class=" btn btn-primary btn-block"  type=button onclick="showhidemenu(\'button3\')">Manage My Account('.$_SESSION['login'].')
					</button>
						<table  id="button3" class="menu" style="position: absolute;display:none;z-index:100;">
						   <tr><td>
								<form method=post style="display:inline">
									<button class="btn btn-primary btn-block"  
									formaction=index.php 
									type=submit 
									onclick="hidemenu()" 
									name=logout>Logout</button>
						   		</form>
							</td></tr>
							<tr><td>
								<form method=post style="display:inline">
									<button class="btn btn-primary btn-block"  
									formaction=change_password.php 
									type=submit 
									onclick="hidemenu()" 
									name=change_pwd>Change Password</button>
								</form>
							</td></tr>
						</table>	
				</td>
			</tr>
		</table>
	</div>
</div>
</div>	
	 ';

}


function print_pdf_h($link,$d,$t,$sql)
{
	class ACCOUNT extends TCPDF 
	{
		public function Header() 
		{
		}
		
		public function Footer() 
		{
			$this->SetY(-10);
			$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		}	
	}

	ob_start();
	echo $sql;
	$result=run_query($link,$d,$sql);
	echo '<h3>(database):'.$d.' (table):'.$t.'</h3>';
	echo '<table border="0.3" >';
	print_horizontal_header_pdf($link,$d,$t);
	$counter=1;
	while($ar=get_single_row($result))
	{	
		echo '<tr>';
		echo '<td>';
		echo $counter++;
		echo '</td>';
		show_horizontal_single_row($ar);
		echo '</tr>';
	}
	echo '</table>';
	$myStr = ob_get_contents();
	ob_end_clean();
	
	$pdf = new ACCOUNT('P', 'mm', 'A4', true, 'UTF-8', false);
	$pdf->SetFont('dejavusans', '', 9);
	$pdf->SetMargins(30, 20, 30);
	$pdf->AddPage();
	$pdf->writeHTML($myStr, true, false, true, false, '');
	$pdf->Output('xxx.pdf', 'I');
}

function pdf_head()
{
	echo '<table >
	<tr><br>
	  <td width="20%"> <img src="college_logo.jpg" alt="college logo"><br> </td>
	  <td width="60%" align="center"><h3><br> Online application<br> 36th Lab/X-Ray Technician <br>
	                         Training course(2023-24)<br>
	                     Government Medical College Surat</h3>
	  </td>
	  <td width="20%"border="1" align="center"><h4><br><br><br>Self Attested<br> photograph</h4></td>
	</tr>
	</table>';
	
	
}

function pdf_tail()
{
	echo'<br><br><table >
	     <tr><th width="4%">1.</th><td width="96%">I hereby give undertaking to abide by all rules & regulations set by Government Of Gujarat for Lab/X-Ray Technician Course.</td></tr>
	     <tr><th width="4%">2.</th><td width="96%">I hereby abide to follow all instiutional rules & regulations during entire period of training.</td></tr>
	     <tr><th width="4%">3.</th><td width="96%">I hearby apply to impart my services in case of epidemic/pandemic/disaster and/or as and when called upon by the department/institution </td></tr>
	     <tr><th width="4%">4.</th><td width="96%">All infomation provided by me are correct.I understand that I will be debarred from admission process and training,if found to provide incorrect infromation.</td></tr>
	     <tr><th width="4%">5.</th><td width="96%">I will submit all required original documents for verification during submission of this application in person.</td></tr>
             <tr><th width="4%">6.</th><td width="96%">Photocopies of all required original documents are attached with application form.</td></tr>
	     <tr><td width="70%"></td><td width="30%"><br><br>___________________________</td></tr>
	     <tr><td width="70%"></td><td width="30%">Signature of Applicant<br></td></tr>
	    </table>';
	    
	echo'<table  border="0.3">
		<tr><th colspan=3 align="center"><h4>For Office Use only</h4></th></tr>
	     <tr align="center"><th width="40%">Verification of Orignal Documents</th><th width="40%">Remarks</th><th width="20%">Signature of officer</th></tr>
	     <tr><td>photo id proof </td><td></td><td><br><br></td></tr>
	     <tr><td>date of birth proof </td><td></td><td><br><br></td></tr>
	     <tr><td>BSC mark/grade </td><td></td><td><br><br></td></tr>
	     <tr><td>category certificate (ST/SC/SEBC/EWS) </td><td></td><td><br><br></td></tr>
	     <tr><td>non-creamy layer certificate</td><td></td><td><br><br></td></tr>
	     <tr><td>Physically Hadicapped Certificate </td><td></td><td><br><br></td></tr>
	     <tr><td>Other</td><td></td><td><br><br></td></tr>
	     </table>';
	    
}

function print_pdf_v($link,$d,$t,$sql,$head='',$foot='')
{
	class ACCOUNT extends TCPDF 
	{
		public function Header() 
		{
		}
		
		public function Footer() 
		{
			$this->SetY(-10);
			$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		}	
	}

	ob_start();
	pdf_head();
	//echo $sql;
	$result=run_query($link,$d,$sql);
	echo '<h3>'.$d.'/'.$t.'</h3>';	
	while($ar=get_single_row($result))
	{	
		show_vertical_single_row($ar);
	}
	pdf_tail();
	$myStr = ob_get_contents();
	ob_end_clean();
	
	$pdf = new ACCOUNT('P', 'mm', 'A4', true, 'UTF-8', false);
	$pdf->SetFont('dejavusans', '', 9);
	$pdf->SetMargins(30, 20, 30);
	$pdf->AddPage();
	$pdf->writeHTML($myStr, true, false, true, false, '');
	//$pdf->Output('xxx.pdf', 'I');
	$pdf->Output($_SESSION['login'].'.pdf', 'I');
}

function send_sms($sms,$num)
{
	//$str='http://mobi1.blogdns.com/httpmsgid/SMSSenders.aspx';
	$str=$GLOBALS['sms_site'];
	$getdata = http_build_query
		(
		array(
			'UserID' => $GLOBALS['sms_UserID'],
			'UserPass' => $GLOBALS['sms_UserPass'],
			'Message'=>$sms,
			'MobileNo'=>$num,
			'GSMID'=>$GLOBALS['sms_GSMID']
			)
		);
								
	$hdr = "Content-Type: application/x-www-form-urlencoded";
                    
	$opts = array('http' =>
					array(
						'method'  => 'GET',
						'content' => $getdata,
						'header'  => $hdr
						)
				);

	$context  = stream_context_create($opts);
	//echo $str;
 	$ret=file_get_contents($str,false,$context);
	return $ret;
}


function help()
{
		if($GLOBALS['nojunk']==TRUE){return;}
//<span class="bg-success rounded" onclick="showhide_with_label(\'help\',this,\'Help\');">Hide Help</span>
			echo'<div class="row">
					<div class="col-sm-12 bg-light mx-auto">
					<h4 class="bg-danger rounded">Read Following during filling application and its submission</h4>
					<ul id=help style="display:block;">
					<li>Fill Application (Main Menu -> Application -> Fill )</li>
					<li>
						<ol> <u>Read following to correctly fill all fields</u>
						<li><b>id:</b>id is mobile number given at time of registration. Keep this mobile handy, because it will be used for sending required messages during application and selection process</li>
						<li><b>course:</b>If you wish to apply for both Lab and XRay technician course, select "Both". There is no need to make two saparate application.</li>
						<li><b>name:</b>Write your name exactly the way it is in your BSC Marksheet.</li>
						<li><b>address:</b>Address with pin code will be used for official postal communication, if required.</li>
						<li><b>email:</b>Email will be used for official postal communication, if required.</li>
						<li><b>mobile:</b>It should preferably be same as the one given during registration. However, you may change communication-mobile to be different from registration-mobile</li>
						<li><b>dob:</b>Date of Birth must be identical to the one mentioned in Birth Certificate Provided</li>
						<li><b>catagory:</b>Select appropriate catagory. New category of EWS(Economicaly Weaker Section) is added. SEBC candicate must have non-creamy layer certificate valid on last date of application.</li>
						<li><b>physically_handicapped:</b>Physically Handicapped applicant will choose \'Y\' if they wish to avail the PH quota</li>
						<li><b>sex:</b>Choose "M" for male, "F" for female, "O" for others</li>
						<li><b>BSC:</b>Choose you main subject of BSC degree. For subjects not listed, choose "Other"</li>
						<li><b>university:</b>Write full name of university, exactly shown in marksheet/grade-sheet. Applicant with univercities recognized by the state are eligible See <a href="http://gujarat-education.gov.in/education/alluniversity.htm" target="_blank">http://gujarat-education.gov.in/education/alluniversity.htm</a></li>
						<li> <h5 class="bg-primary rounded"> Read Following carefully before filling your marks/SGPA</h5>
							 <ul class="bg-warning rounded">
								<li>Some fields will become 0, if not relevent</li>
								<li><b>yearly marks:</b> Fill only <b>final_year_marks_obtained</b> and <b>final_year_marks_max</b>. Keep other marks/SGPA fields 0</li>
								<li><b>yearly SGPA:</b> Fill only <b>final_year_SGPA</b> . Keep other marks/SGPA fields 0</li>
								<li><b>semesterwise marks:</b> Fill only 
								<b>5th_sem_marks_obtained</b> and <b>5th_sem_marks_max</b> and 
								<b>6th_sem_marks_obtained</b> and <b>6th_sem_marks_max</b>.
								Keep other marks/SGPA fields 0</li>
								<li><b>semesterwise SGPA:</b> Fill only <b>5th_sem_SGPA</b> and <b>6th_sem_SGPA</b>. Keep other marks/SGPA fields 0</li>
							</ul>
						</li>
						<li><h5 class="bg-primary rounded"> Upload scanned/photo of following documents, as applicable</h5>
							 <ul class="bg-warning rounded">
							<li><b>photo id proof:</b>  Upload Photo ID proof (Any Government Authorized ID Proof e.g. Aadhar Card/Driving Liscence/PAN Card/Voting Card) in  .jpg, .jpeg, .png & pdf format only.</li>
							<li><b>date of birth proof:</b> Upload date of birth proof (Birth Certificate issued by office of registrar of birth and death , SSC passing certificate, School leaving) in  .jpg, .jpeg, .png & pdf format only. </li>
							<li><b>BSC mark/grade:</b> Upload University Final year BSC Marksheet/Grade sheet or University 5th and 6th semester Marksheet/Grade sheet in  .jpg, .jpeg, .png & pdf format only. There is facility to upload two files. If you have to upload more than two files,prepare single pdf with multiple pages and upolad in _bsc_mark_or_grade_1 and _bsc_mark_or_grade_2. File name field are automaticaly taken from uploaded filename</li>
							<li><b> category certificate:</b> Upload category certificate for SC/ST/SEBC/EWS in  .jpg, .jpeg, .png & pdf format only.</li>
							<li><b> non creamylayer certificate :</b> Upload valid non-creamy layer certificate from appropriate government authority For SEBC in .jpg, .jpeg, .png & pdf format only.</li>
							<li><b>Physically Hadicapped Certificate:</b> upload Physically handicapped certificate from appropriate government authority in .jpg, .jpeg, .png & pdf format only. </li>
							</ul>
						</li>
						</ol>
					</li>
					<li>Print Application (Application -> Print)</li>
					<li>Apply Photo, Sign over it</li>
					<li>Read all parts of application carefully. Then, sign it</li>
					<li>Deliver personally to the college office before last date mentioned on login page. If Applicant can not come personally, applicant must sign an authority letter given to his/her representative. 
					During personal delivery, the representative must bring all relavent original documents for verification.The authority letter will be as follows
					<hr>
					<p>
					<h5 class="bg-primary rounded">Authority Application</h5><div class="bg-warning">
					"I <b>[Applicant Name]</b> authorize my representative <b>[Representative Name]</b> to deliver my application for 36th Lab/XRay Tech Training course at GMC Surat. My representative also carries his/her identification proof, all required apllication and original documents. </p><p>His/Her signature is as follows: <b>[Signature of Representative]</b>.
					</p>
					<p><b>[Signature of Applicant]</b></p>
					<p><b>[Name of Applicant]</b></p>
					<p><b>[Application ID of Applicant]</b>
					</div>
					</p>
					<hr>
					</li>
					<li>Application is not received by post/Courrier. because, verification of original documents will done in person at given period of time.</li>
					<li><h5 class="bg-primary rounded"> Following Original Documents must be verified at the time of application submission</h5>
							 <ul class="bg-warning rounded">
								<li>Photo Identity Card/Certificate (Any Government Authorized ID Proof e.g. Aadhar Card/Driving Liscence/PAN Card/Voting Card)</li>
								<li>Date of Birth Proof (Birth Certificate/SSC passing certificate)</li>
								<li>For SC/ST/SEBC/EWS: Valid certificate</li>
								<li>For SEBC: Valid non-creamy layer certificate from appropriate government authority</li>
								<li>For Physically handicapped: Physically handicapped certificate from appropriate government authority</li>
								<li>University Final year BSC Marksheet/Grade sheet <br>or <br>University 5th and 6th semester Marksheet/Grade sheet</li>
							</ul>
					</li>
					</ul>	
					</div>
				</div>';
	
	
}
function validate($link,$d,$t,$pka){
$sql=mk_select_sql_from_pk($link,$d,$t,$pka);
$result=run_query($link,$d,$sql);
$data=get_single_row($result);
$ret=TRUE;
//my_print_r($data);
if(strlen($data['name'])==0){ echo'<h3 style="color:red;">Name can not be empty</h3>';$ret=false;}
if(strlen($data['address'])==0){ echo'<h3 style="color:red;">Address can not be empty</h3>';$ret=false;}
if(strlen($data['email'])==0){ echo'<h3 style="color:red;">Email can not be empty</h3>';$ret=false;}
if($data['mobile']==0){ echo'<h3 style="color:red;">Mobile can not be empty</h3>';$ret=false;}
if($data['dob']=='0000-00-00'){ echo'<h3 style="color:red;">Date of birth can not be empty</h3>';$ret=false;}
if(strlen($data['university'])==0){ echo'<h3 style="color:red;">University can not be empty</h3>';$ret=false;}
if(
    $data['final_year_marks_obtained']==0 &&
    $data['final_year_marks_max']==0 &&
    $data['final_year_SGPA']==0 &&
    $data['5th_sem_marks_obtained']==0 &&
    $data['5th_sem_marks_max']==0 &&
    $data['6th_sem_marks_obtained']==0 &&
    $data['6th_sem_marks_max']==0 &&
    $data['5th_sem_SGPA']==0 &&
    $data['6th_sem_SGPA']==0){echo '<h3 style="color:red;">No marks/grades entered</h3>'; $ret=false;}

    if(
    ($data['5th_sem_marks_obtained']+$data['5th_sem_marks_max']+$data['6th_sem_marks_obtained']+$data['6th_sem_marks_max'])>0
    &&
    (
    $data['5th_sem_marks_obtained']==0 ||
    $data['5th_sem_marks_max']==0 ||
    $data['6th_sem_marks_obtained']==0 ||
    $data['6th_sem_marks_max']==0 )
    )                                    {echo '<h3 style="color:red;">Both 5th and 6th semester marks_obtained and Both 5th and 6th semester max_marks are required</h3>'; $ret=false;}

    if(
        ($data['5th_sem_SGPA'] + $data['6th_sem_SGPA'])>0
            &&
        ($data['5th_sem_SGPA']==0 || $data['6th_sem_SGPA']==0)
      )
    {
        echo '<h3 style="color:red;">Both 5th and 6th semester SGPA are required</h3>';$ret=false;
    }
        if(
        ($data['final_year_marks_obtained'] + $data['final_year_marks_max'])>0
            &&
        ($data['final_year_marks_obtained']==0 || $data['final_year_marks_max']==0)
      )
    {
        echo '<h3 style="color:red;">Both final_year_marks_obtained and final_year_marks_max are required</h3>';$ret=false;
    }
return $ret;
}
?>
