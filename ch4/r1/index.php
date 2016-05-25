<?php
$travelDetails = array('origin' => 'Delhi',
'origin'  => 'London',
'passengers' => array (
  array('name' => 'Mr. Perry Mason', 'type' => 'Adult',
         'age' => 28),
  array('name' => 'Miss Irene Adler', 'type' => 'Adult',
         'age' => 28),
  array('name' => 'Mr. Jimmie Wilson','type' => 'Adult',
        'age' => 68)
     ),
    'travelDate' => '17-Dec-2010'
    );
    echo json_encode($travelDetails);
?>
