<?
@$apelido = $_POST["apelido"];
@$senha = $_POST["senha"];
@$novasenha = $_POST["novasenha"];
@$nome = $_POST["nome"];
@$cpf = $_POST["cpf"];
@$endereco = $_POST["endereco"];
@$numero = $_POST["numero"];
@$bairro = $_POST["bairro"];
@$cidade = $_POST["cidade"];
@$estado = $_POST["estado"];
@$cep = $_POST["cep"];
@$telefone = $_POST["telefone"];
@$celular = $_POST["celular"];
@$email = $_POST["email"];
@$msn = $_POST["msn"];
@$acao = $_POST["acao"];

if ($acao == "alter")
{
  if ($email == '') {print "<p><font color='#FF0000'><b>O campo e-mail é obrigatório !&nbsp;</b></font></p>";}
  if ($nome == '') {print "<p><font color='#FF0000'><b>O campo nome é obrigatório !</b></font></p>";}
  if ($email != '' and $nome != ''){
     $query = "update usuar
			      set  senha    = '".md5($novasenha)."'
                   ,nome     = '$nome'
                   ,cpf      = '$cpf'
                   ,endereco = '$endereco'
                   ,numero   = '$numero'
                   ,bairro   = '$bairro'
                   ,cidade   = '$cidade'
                   ,estado   = '$estado'
                   ,cep      = '$cep'
                   ,telefone = '$telefone'
                   ,celular  = '$celular'
                   ,email    = '$email'
                   ,msn      = '$msn'
                   ,data     = '$date'
               where apelido = '$apelido'";

     mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 8)"); 
     mysql_db_query($database, $query) or die(mysql_error()); 
     session_start();
     $_SESSION["boasvindas"] = "Seja bem vindo $nome!";
   }
}else{ if ($acao == "inser"){
   if ($apelido == '') {print "<p><font color='#FF0000'><b>O campo apelido é obrigatório !&nbsp;</b></font></p>";}
   if ($senha == '') {print "<p><font color='#FF0000'><b>O campo senha é obrigatório !&nbsp;</b></font></p>";}
   if ($email == '') {print "<p><font color='#FF0000'><b>O campo e-mail é obrigatório !&nbsp;</b></font></p>";}
   if ($nome == '') {print "<p><font color='#FF0000'><b>O campo nome é obrigatório !</b></font></p>";}

   if ($apelido != '')
   {
		mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 8)"); 
		$conec = mysql_db_query($database, "select apelido from usuar where apelido='$apelido'");
		$jaexiste = mysql_num_rows($conec);
		
		if ($jaexiste != "")
		{
		   print "<b><font color='#FF0000'>Já existe um usuário com esse apelido!</font></b>";
        }else {
    	   if ($senha != '' and $email != '' and $nome != '')
           {
              mysql_db_query($database, 
			            "INSERT INTO usuar
			                  ( apelido,senha,nome,cpf,endereco,numero,bairro
							   ,cidade,estado,cep,telefone,celular,email,msn,data)
						  VALUES
						      ( '$apelido','".md5($senha)."','$nome','$cpf','$endereco'
							   ,'$numero','$bairro','$cidade','$estado','$cep'
							   ,'$telefone','$celular','$email','$msn','$date')")
			  or die(mysql_error()); 
              //include "obrigado.php"; 
			  ?>
				 <html><head><meta http-equiv="refresh" content="0;URL=principal.php"></head>
					<body>
					   Redirecionando .....
					</body>
				 </html>
			  <?
			}
		}
    } 
}}
?> 
