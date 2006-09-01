<?PHP
     

     @$nmArq = $_GET["nmArq"];

	 $len = filesize("pdf/" . $nmArq . ".pdf");
	 header('Content-Description: Transferência de arquivo');
	 header("Content-type: application/pdf");
	 header("Content-Length: $len");
   	 header("Content-Disposition: attachment; filename=" . $nmArq . ".pdf"); // em alguns navegadores não	funciona
	 readfile("pdf/" . $nmArq . ".pdf");      
	 
?>

<html>
	<head>
	</head>
	<body>	
	</body>
</html>