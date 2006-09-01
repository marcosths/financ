<?  session_start();

  include("includes/verifica.php");
  include("includes/html/cabecalho.html");
  include("includes/html/menu.html");
  include("includes/config.php");

// Gera um nome único para o arquivo e verifica se já não existe
// Caso exista, gera outro nome e assim sucessivamente....
// Função Recursiva
function nomeArq()
{
   // Gera um nome único para o arquivo
   $temp = $_SESSION["valid_user"] . substr(md5(uniqid(time())), 0, 10);
   $path_arquivo = "../tmp/$temp.ofc";
   //print $path_arquivo;

   // Verifica se o arquivo já existe, caso positivo, chama essa função novamente
   if(file_exists($path_arquivo))
   {
      $path_arquivo = nomeArq();
   }
   return $path_arquivo;
}

//dados do arquivo
$nome_arquivo = $_FILES['arquivo']['name']; 
$tipo_arquivo = $_FILES['arquivo']['type']; 
$tamanho_arquivo = $_FILES['arquivo']['size']; 
$arquivo = nomeArq();

//print "Nome: $nome_arquivo <br>";
//print "Tipo: $tipo_arquivo <br>";
//print "Tamanho: $tamanho_arquivo <br>";
//print "OFC: " . strpos($tipo_arquivo, "ofc") . "<br>";
//print "Arquivo no Servidor: $arquivo <br>";


//Verifica se as características do arquivo são as desejadas 
if (!((strpos($tipo_arquivo, "ofc") || strpos($tipo_arquivo, "plain")) && ($tamanho_arquivo < 100000))) { 
   echo "A extensão ou o tamanho do arquivo não é correta. <br><br>";
   echo "<table><tr><td><li>Permitem-se arquivos .ofc <br><li>Permitem-se arquivos de 100 Kb máximo.</td></tr></table>"; 
}else{ 
   if (move_uploaded_file($HTTP_POST_FILES['arquivo']['tmp_name'], $arquivo))
   { 
      echo "O arquivo foi carregado correctamente. <br>"; 

      //Abre o arquivo para somente leitura
      $hnd = fopen ("../$arquivo", "r");
      if ( $hnd )
      {
         $buffer = trim(fgets($hnd, 4096));
         If ($buffer !== "<OFC>"){
            fclose($hnd);
            die("Arquivo não é do tipo extrato msMoney!");
         }

         print "<table align='center' width='70%'> \n";
         print "<tr class='respiro-03'><th>Data</th><th>Número</th><th>Valor</th><th>Descrição</th><th>Novo ou Consolidado</th></tr>\n";
         
         $corLinha = 1;
         
         while (!feof($hnd)) 
         {
            $buffer = trim(fgets($hnd, 4096));
            If ($buffer == "<STMTTRN>"){
               $buffer = trim(fgets($hnd, 4096));
               $TRNTYPE = substr($buffer,9);
               $buffer = trim(fgets($hnd, 4096));
               $DTPOSTED = substr($buffer,10,4) . "-" . substr($buffer,14,2) . "-" . substr($buffer,16,2);
               $buffer = trim(fgets($hnd, 4096));
               $decimal = strpos($buffer,".");
               $TRNAMT = substr($buffer,8);
               $DEBCRE = ($TRNAMT<0) ? "D" : "C";
               $TRNAMT = ($TRNAMT<0) ? ($TRNAMT*-1) : $TRNAMT;
               $buffer = trim(fgets($hnd, 4096));
               $FITID = substr($buffer,7);
               $buffer = trim(fgets($hnd, 4096));
               $CHKNUM = substr($buffer,8);
               $buffer = trim(fgets($hnd, 4096));
               $MEMO = substr($buffer,6);
               If ($FITID !== $CHKNUM){
                  fclose($hnd);
                  die("Arquivo de extrato está corrompido. Solicite novo arquivo!");
               }
               mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 8)"); 
               $conec = mysql_db_query($database, "select * from movimentos
                                                   where id_usuar = '".$_SESSION["valid_user"]."'
                                                   and nu_documento = $FITID
                                                   and ds_memo like '%$MEMO%'
                                                   and vl_movimento = $TRNAMT
                                                   and dt_movimento = '$DTPOSTED'");
               $jaexiste = mysql_num_rows($conec);
               
               if ($jaexiste != "")
               {
                  $arrDados = mysql_fetch_assoc($conec);
                  print "<tr class='respiro-0$corLinha'><td align='center'>$DTPOSTED</td><td align='right'>$FITID</td><td align='right'>$TRNAMT</td><td>$MEMO</td><td>Consolidado</td></tr>\n";
                  //$aviso = "O sistema considerou estas transações sendo a mesma: <br>\n" .
                  //         " - Extrato: Número: $FITID <br>\n" . 
                  //         "            Observ: $MEMO <br>\n" . 
                  //         "            Valor : $TRNAMT <br>\n" . 
                  //         "            Data  : $DTPOSTED <br>\n" . 
                  //         " - Lançado: Número: " . $arrDados["nu_documento"] . "<br>\n" .
                  //         "            Observ: " . $arrDados["ds_memo"] . "<br>\n" .
                  //         "            Valor : " . $arrDados["vl_movimento"] . "<br>\n" .
                  //         "            Data  : " . $arrDados["dt_movimento"] . "<br>\n";
                  //print "<b><font color='#FF0000'>$aviso</font></b>";
                  mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 8)"); 
                  $conec = mysql_db_query($database, "update movimentos
                                                      set fg_extrato = 'S'
                                                      where id_usuar = '".$_SESSION["valid_user"]."'
                                                      and nu_documento = $FITID
                                                      and ds_memo like '%$MEMO%'
                                                      and vl_movimento = $TRNAMT
                                                      and dt_movimento = '$DTPOSTED'");
               }else {
                  mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 8)");
                  mysql_db_query($database,"INSERT INTO movimentos(id_usuar,id_custo,nu_documento,
                                                                   id_favorecido,dt_movimento,
                                                                   fg_deb_cred,vl_movimento,
                                                                   fg_extrato,ds_memo)
                                            VALUES('".$_SESSION["valid_user"]."',NULL,$FITID,NULL,
                                                   '$DTPOSTED','$DEBCRE',$TRNAMT,'S','$MEMO')")
                  or die(mysql_error());
                  print "<tr class='respiro-0$corLinha'><td align='center'>$DTPOSTED</td><td align='right'>$FITID</td><td align='right'>$TRNAMT</td><td>$MEMO</td><td>Novo Movimento</td></tr>\n";
               }
                
                if($corLinha == 1){
                    $corLinha = 2;
                } else {
                    $corLinha = 1;
                }
            }
         }
         fclose($hnd);
      }else{
         die( "Falha na importação do Extrato!");
      }

   }else{ 
      echo "Erro ao enviar o arquivo."; 
   } 
} 
  include("includes/html/rodape.html");
?>

