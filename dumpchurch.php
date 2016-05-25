<?php
//dumpchurch.php
//exports ht.tblchurchtable to json for import to Mongo
$province='';
if (PHP_SAPI === 'cli') {
    $cl = $argv[0];
    $province = $argv[1];
} else {
  die();
}

  $username = 'root';
  $password = 'DanaDenyse32';
  $dbname = 'ht';
  $host = '127.0.0.1';
   if (!empty($province)) {
  	print_r("Collecting all church records for the state of ".$province." \n");
  try {
  	   $myfile = "./htApp/files/church.json";
  	   $flatfile = $province."_"."churches.txt";
  	   $handle = fopen($flatfile, 'w') or die("Cannot open ".$flatfile." \n");
  	   $arr_data = array();
  	   $jsondata = file_get_contents($myfile);
  	   $arr_data = json_decode($jsondata, true);
  	   print_r("church.json file ready for use \n");
  	   date_default_timezone_set('America/Chicago');
  	   $today = date('Y-m-d H:i:s');
  	    print_r("Opening Data Connection \n");
 	    $db = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
        $row=$db->prepare("select ChurchID as churchid, 
        churchname, 
        domain, 
        address, 
        IFNULL(city,'') as city,
        IFNULL(zipcode,'') as zipcode,
        IFNULL(state,'') as state, 
        IFNULL(phone,'') as phone, 
        IFNULL(country,'') as country, 
        IFNULL(GoogleMapPlaceID,'') as googlemapplaceid, 
        IFNULL(email,'') as email,
        IFNULL(DefaultEventsCreated,false) as defaulteventscreated,
        IFNULL(DefaultRatingsCreated,false) as defaultratingscreated,
        IFNULL(website,'') as website,
        datecreated
         from ht.tblchurch
         where state = '".$province."' order by churchname");  
        $row->execute();
        $json_data=array();
        $json_stats = array();
        foreach($row as $rec){  
         $churchJson = $arr_data;
         $json_array['churchid']=$rec['churchid'];
          $json_array['domain'] = $rec['domain'];  
          $json_array['churchname']=$rec['churchname'];
          $json_array['address'] = $rec['address'];  
          $json_array['city']=$rec['city'];  
          $json_array['state']=$rec['state'];  
          $json_array['zipcode']=$rec['zipcode'];  
          $json_array['phone']=$rec['phone'];  
          $json_array['country']=$rec['country'];
          $json_array['website']=$rec['website'];
          $json_array['contactemail'] = $rec['email'];  
          $json_array['googlemapplaceid']=$rec['googlemapplaceid'];  
		  $json_array["created_on"] = $today;
		  $json_array["updated_at"] = $today;
          $json_stats['hasdefaultevents']=$rec['defaulteventscreated'];
		  $json_stats["membercount"] = 0;
		  $json_stats["surveysample"] =  0;
		  $json_stats["averageeventscore"] =  0;
		  $json_stats["membersurveyscore"] = 0;
		  $json_stats["peersurveyscore"] = 0;
          $churchJson['profile'] = $json_array;
          $churchJson['stats'] = $json_stats;
          $churchdata = json_encode($churchJson); 
          print_r($rec['churchname']." ".$rec['city']." \n");
          fwrite($handle, $churchdata." \n");
        }
         fclose($handle);  
   }catch (PDOException $e) {
      print_r("Connection attempt Bombed \n");
      print_r($e->getMessage()." \n");
  }	
  } else {
  print_r("Don't come back unless you are serious... \n");	
  }
?>