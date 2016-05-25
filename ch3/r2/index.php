<html>
<head>
 <title>Accessing node and attribute values</title>
</head>
<body>
 <p>
  <select id="bookList">
   <option value="">select a book</option>
    <?php
      $objXML = simplexml_load_file('../common.xml');
        if($objXML)
        {
         foreach($objXML->book as $book)
        {
          echo '<option value="'.$book['index'].'">'.$book->name.'</option>';
        }
        }
        else
         {
         echo '<option value="no">Im Sorry!</option>';	
         }
?>
   </select>
   <input type="button" id="year" value="Get Year of publication" />
   <input type="button" id="stories" value="Get story list" />
  </p>
  <p id="result"></p>
  <script type="text/javascript" src="../jquery.js"></script>
  <script type="text/javascript" src="../jquery-migrate.js"></script>  
  <script type="text/javascript">
   $(document).ready(function()
  	 {
  	 	$('input:button').click(function()
  	 	 {
  	 	 	if($('#bookList').val() != '')
  	 	 	 {
  	 	 	  	$.get(
  	 	 	  	 	'process.php',
  	 	 	  	 	{ id: $('#bookList').val(), action: $(this).prop('id') },
  	 	 	  	 	function(data)
  	 	 	  	 	 {
  	 	 	  	 	 	$('#result').html(data);
  	 	 	  	 	 }
  	 	 	  	);
  	 	 	 } // if $('#booklist').val() != ''
  	 	 }//$('input:button').click
  	 	);
  	 }//($document.ready
  	);
  </script>
</body>
</html>
