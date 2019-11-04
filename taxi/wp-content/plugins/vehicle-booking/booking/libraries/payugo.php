<!DOCTYPE html>
<html>
<head>
	<title>Please wait !</title>
</head>
<body onload="document.frm1.submit();">

	<h1 style="text-align:center;color:silver;">Please wait !</h1>
	<h3 style="text-align:center;color:gray;">Now you will be redirected to pay you payment process !</h3>

	<form method="POST" action="<?php echo $url;?>" name="frm1">
	<?php foreach ($data as $key => $value) {
		echo '<input type="hidden" name="'.$key.'" value="'.$value.'" />';
	}	?>
	</form>
</body>
</html>