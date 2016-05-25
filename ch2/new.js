function addEvents()
 {
 	$('.bold').click(function()
 	 {
 	  $('#container').css('font-weight', 'bold');	
 	 }
 	);// $('.bold').click
 	
 	$('.color').click(function()
 	 {
 	 	$('#container').css('color','red');
 	 }
 	);//$('.color').click
 	
 	$('.change').click(function()
 	 {
 	 	$('#container').html('<em>New html inserted here</em>');
 	 }
 	);//$('.change').click
 }