<?php 
//buildchurchlist.php
//creates a compact list of all churches in the ht.tblchurch table 
 $datafile = "data/churches.txt";
  $fp1 = fopen($datafile, 'w');
  $user_name = 'root';
  $password = 'DanaDenyse32';
  $database = 'ht';
  $server = '127.0.0.1';
  $db_handle = mysql_connect($server,$user_name,$password);
  $db_found = mysql_select_db($database,$db_handle);
  if($db_found)  {
      $sql = "select ChurchID as id,churchname as name,city,state as province,country,domain from ht.tblchurch where lower(state) = 'al' order by churchname";
      $result = mysql_query($sql) or die(mysql_error());
      fwrite($fp1,"["."\n");  
      $rowCount = 0;
      while($row = mysql_fetch_assoc($result)) {
      	if($rowCount == 0) {
      		$data = '{"id":"'.$row['id'].'","name":"'.str_replace(',','-',$row['name']).'","city":"'.$row['city'].'","province":"'.$row['province'].'","country":"'.$row['country'].'","domain":"'.$row['domain'].'"}'."\n";    
      } else {
      	 $data = ',{"id":"'.$row['id'].'","name":"'.str_replace(',','-',$row['name']).'","city":"'.$row['city'].'","province":"'.$row['province'].'","country":"'.$row['country'].'","domain":"'.$row['domain'].'"}'."\n";
      }
        fwrite($fp1,$data); 
        $rowCount ++;  
        }
        fwrite($fp1,"]"."\n");     
  }//db_found
  mysql_close($db_handle);
  fclose($fp1);
?>
//echo str_replace("world","Peter","Hello world!")