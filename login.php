<?
$userid = $_POST["username"];
$password = $_POST["passwd"];
if ($userid and $password)
{
   // iniciando sessão
   session_start();
   
   include "./includes/config.php";
   // abrindo uma conexão com o banco de dados
   $db_conn = mysql_connect($server, $db_user, $db_pass) or die ("Erro na conexão com a Base de Dados");
   // definindo a base de dados 
   mysql_select_db($database, $db_conn);
   
   //montando comando de select
   $query = "select * from usuar where apelido='$userid' and senha='".md5($password)."'";
   
   //print $query;
   // resgatando resultado da pesquida executada no banco
   $result = mysql_query($query, $db_conn);

   // testando se usuário existe
   if (mysql_num_rows($result) == 1 )
   {
   	  // resgatando dados do registro retornado pela pesquisa
      $dados = mysql_fetch_array($result);
      
      //colocando mensagem de boas vindas na sessão
      $_SESSION["boasvindas"] = "Seja bem vindo " . $dados["nome"] . "!";
      
      // Se usuario válido entao registra o usuario na sessão
      $_SESSION["valid_user"] = $userid;
   }
}

// se existir um usuário na sessão
if ($_SESSION["valid_user"])
{
	
    // redirecionando usuário a página principal
  ?>
	   <html><head><meta http-equiv="refresh" content="0;URL=principal.php"></head>
		<body>
	       Redirecionando .....
		</body>
	 </html>
  <?
}else{
	
   $erros = "Falha na autenticacao do usuario informado";
   // não está logado.
   include "index.php";
}

?>
