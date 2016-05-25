<?php
  include 'ChromePhp.php';
   $username = 'root';
  $password = 'DanaDenyse32';
  $dbname = 'ht';
  $host = '127.0.0.1';
  $c = array( 'status' => '',
  'action' => '',
  'error' =>  '',
  'sql' => '');
   try {
     $cn = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
        $domain = "mzbc.net";
        $placeid = "ChIJxeFM3kJoYogRoK5qVBCLq34";
        $sql = $cn->prepare("select * from ht.tblChurch where domain  = :do or GoogleMapPlaceID = :pl");
//          print_r($sql." \n");
          $sql->bindParam(':do',$domain,PDO::PARAM_STR,50);
          $sql->bindParam(':pl',$placeid,PDO::PARAM_STR,50);
          print_r('Query submitted \n');
          if (!$sql->execute()){
                     print_r($pkg->name.' not found, adding to database \n');
                     insertchurch($pkg);
          } else {
          print_r('Church found! Domain : '.$dbfield['domain'].' PlaceID is: '.$dbfield['GoogleMapPlaceID']." \n");
            $dbfield = $sql->fetch(PDO::FETCH_ASSOC);	
            $info = array('status' => "ok",
                     'action' => 'Google Map Church Lookup in ht.tblchurch',
                     'error' => '',
                     'sql' => $sql,
                     'source' => 'ht.tblChurch',
                     'id' => $dbfield['ChurchID'],
                     'name' => $dbfield['churchname'],
                     'domain' => $domain,
                     'placeid' => $placeid,
                     'street' => $dbfield['address'],
                     'city' => $dbfield['city'],
                     'phone' => $dbfield['phone'],
                     'website' => $dbfield['website'],
                     'province' => $dbfield['state'],
                     'postalcode' => $dbfield['zipcode'],
                     'vicinity' => '',
                     'address' => $dbfield['address'],
                     'country' => $dbfield['country'],
                     'id' => $dbfield['ChurchID']
                     );
                    if(empty($dbfield['GoogleMapPlaceId'])) {                    	
                     	$isql = $cn->prepare("Update ht.tblchurch set GoogleMapPlaceId = :pl where ChurchID = :ci");
                     	print_r('Record found but GoogleMapPlaceID is empty, updating.... \n');
                     	$isql->bindParam(':pl',$placeid,PDO::PARAM_STR,50);
                     	$isql->bindParam(':ci',$info['id'],PDO::PARAM_INT);
                     	if($isql->execute()) {
                     	  print_r("GoogleMapPlaceID updated successfully for ".$info['churchname']." \n");
                     	} else {
                     	  print_r("Unable to update GoogleMapPlaceID for  ".$info['churchname']." \n");	
                     	 }
                       } 
                 print_r(json_encode($info));

          }

  }catch (PDOException $e) {
      print_r('Query Unsuccessful \n');
      $c['status'] = 'no';
      $c['action'] = 'query';
      $c['error'] = $e->getMessage;
      $c['sql'] = $sql;
      print_r(json_encode($c));
	
  }	
?>
