<?php

function getChurchID($e) {
	return $e[0]->ChurchID;
  foreach ($e as $value) {
   ChromePhp::warn( $value->EventName); 
   ChromePhp::warn($value->ChurchID);    
   }
   die();
}
?>
