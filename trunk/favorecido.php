<?PHP
  include("includes/verifica.php");
  include("includes/html/cabecalho.html");
  include("includes/html/menu.html");
  include("includes/config.php");
  
  
  
  
  @$acao = $_POST["acao"];
  @$idUsuar = $_SESSION["valid_user"];
  @$idFavorecido = $_POST["id"];
  @$nmAbrevFavorecido = $_POST["nmAbrevFavorecido"];
  @$nmFavorecido = $_POST["nmFavorecido"];
  
  
  $conn = mysql_connect($server,$db_user, $db_pass) or die ("Não foi posivel conectar ao banco");
   mysql_select_db($database) or die ("Base de dados não foi encontrada");
  
   if($acao == "AdicionarFavorecido") {
  	
    $str_inserir = "insert into favorecido ".
                            " (id_usuar, nm_abrev_favorecido, nm_favorecido) ".
                            " values " .
                            "('$idUsuar','$nmAbrevFavorecido', '$nmFavorecido')";

    
    mysql_query($str_inserir);
  
    	
  } else if($acao == "ExcluirFavorecido" && $idFavorecido != "") {
    
    $str_deletar = "delete from favorecido ".                            
                   " where id_favorecido = $idFavorecido";
    
    mysql_query($str_deletar);
        
  } else if($acao == "AlterarFavorecido" && $idFavorecido != "") {
    
    $str_update = "update favorecido ".
                   " set nm_abrev_favorecido = '$nmAbrevFavorecido'".                            
                   " , nm_favorecido = '$nmFavorecido'".
                   " where id_favorecido = $idFavorecido";
    
    
    mysql_query($str_update);
        
  }
?>

          <!-- Inicio centro da página-->                    
          <div class="div-01" style="div-01">
            <form id="formFavorecido" name="formFavorecido" method="post" action="favorecido.php">
			  <input name="acao" type="hidden" value="" />
          	  <input name="id" type="hidden" value="" />
		  	  <table width="100%" border="0"align="center">
                  <tr>
                      <td>&nbsp;</td>
                  </tr>
				  <tr>
                      <td><table width="75%" border="0" align="center">
                          <tr>
                              <td colspan="4" class="respiro-03">Consulta de Favorecido</td>
                          </tr>
                          <tr>
                              <td width="6%">&nbsp;</td>
                              <td width="21%" nowrap="nowrap">Nome Abreviado: </td>
                              <td width="67%"><label>
                                  <input name="cNmAbrevFavorecido" type="text" id="cNmAbrevFavorecido" />
                              </label></td>
                              <td width="6%">&nbsp;</td>
                          </tr>
                          <tr>
                              <td>&nbsp;</td>
                              <td nowrap="nowrap">Nome Completo:</td>
                              <td><label>
                                  <input name="cNmFavorecido" type="text" id="cNmFavorecido" size="60"/>
                              </label></td>
                              <td>&nbsp;</td>
                          </tr>
                          <tr>
                              <td>&nbsp;</td>
                              <td colspan="2" align="center"><label>
                                  <input name="Consultar" type="button" id="Consultar" onclick="javascript: submeteForm(document.formFavorecido,'ConsultarFavorecido', -1)" value="Consultar" />
                              </label></td>
                              <td>&nbsp;</td>
                          </tr>
                          <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                          </tr>
                      </table></td>
                  </tr>
				  
                  <tr>
                      <td><table width="85%" border="0" align="center">
                          <tr class="respiro-03">
                              <td align="center" nowrap="nowrap">&nbsp;</td>
                              <td align="center" nowrap="nowrap">&nbsp;</td>
                              <td align="center" nowrap="nowrap">Nome Abreviado</td>
                              <td align="center" nowrap="nowrap">Nome Completo</td>
                          </tr>
                          <?PHP
                    
                    
    
                    $str_query =   " SELECT * ".
                                   " FROM favorecido ".
                                   " WHERE id_usuar = '$idUsuar'";
                                   
                    if($acao == "ConsultarFavorecido") {
                    	
                        @$cNmAbrevFavorecido = $_POST["cNmAbrevFavorecido"];
                        @$cNmFavorecido = $_POST["cNmFavorecido"];                        
                                   
                        if($cNmAbrevFavorecido){
                        	$str_query .= " and nm_abrev_favorecido like '%$cNmAbrevFavorecido%'";
                        } 
                        
                        if($cNmFavorecido){
                            $str_query .= " and nm_favorecido like '%$cNmFavorecido%'";
                        } 
                        
                        
                    }        
                    $arrRegistros = mysql_query($str_query);
                    
                    $corLinha = 1;
                    $resultadoHTML = "";
                    
                    while($arrDados = mysql_fetch_assoc($arrRegistros)) {
                          $idFavorecidoReg = $arrDados["id_favorecido"];
                          $nmAbrevFavorecido = $arrDados["nm_abrev_favorecido"];
                          $nmFavorecido = $arrDados["nm_favorecido"];
                          
                    
                        if($idFavorecido != $idFavorecidoReg || $acao != "EditarFavorecido") {                    
                            $resultadoHTML .=  "<tr class=\"respiro-0$corLinha\">\n";
                            $resultadoHTML .=  "       <td width='4%' align='center' valign='middle' nowrap='nowrap'>\n";
                            $resultadoHTML .=  "            <img src='img/ico_alt.gif' alt='Alterar Favorecido' width='16' height='15' onclick=\"javascript: submeteForm(document.formFavorecido,'EditarFavorecido', $idFavorecidoReg)\" style='cursor:hand'/>\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td width='4%' align='center' valign='middle' nowrap='nowrap'>\n";
                            $resultadoHTML .=  "            <img src='img/ico_del.gif' alt='Excluir Favorecido' width='16' height='15' onclick=\"javascript: submeteForm(document.formFavorecido,'ExcluirFavorecido', $idFavorecidoReg)\" style='cursor:hand'/>\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td width='22%'>\n";
                            $resultadoHTML .=  "          <label>$nmAbrevFavorecido</label>&nbsp;\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td width='65%''>\n";
                            $resultadoHTML .=  "           <label>$nmFavorecido</label>&nbsp;\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "      </tr>\n";
                        } else if ($acao == "EditarFavorecido"){
                        	$resultadoHTML .=  "<tr class=\"respiro-0$corLinha\">\n";
                            $resultadoHTML .=  "       <td width='4%' align='center' valign='middle' nowrap='nowrap'>\n";
                            $resultadoHTML .=  "            <img src='img/ico_sal.gif' alt='Salvar Altera&ccedil;&atilde;o no Favorecido' width='16' height='15' onclick=\"javascript: submeteForm(document.formFavorecido,'AlterarFavorecido', $idFavorecidoReg)\" style='cursor:hand'/>\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td width='4%' align='center' valign='middle' nowrap='nowrap'>\n";
                            $resultadoHTML .=  "            <img src='img/ico_des.gif' alt='Desfazer Edi&ccedil;&atilde;o do Favorecido' width='16' height='15' onclick=\"javascript: submeteForm(document.formFavorecido,'', -1)\" style='cursor:hand'/>\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td width='22%'>\n";
                            $resultadoHTML .=  "          <input name='nmAbrevFavorecido' type='text' id='nomAbrevFavorecido' title='Nome Abreviado do Favorecido' value='$nmAbrevFavorecido'/>&nbsp;\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td width='65%''>\n";
                            $resultadoHTML .=  "           <input name='nmFavorecido' type='text' id='nmFavorecido' title='Nome Completo do Favorecido' value='$nmFavorecido' size='60'/>&nbsp;\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "      </tr>\n";
                            
                        } 
                            
                        if($corLinha == 1){
                        	$corLinha = 2;
                        } else {
                        	$corLinha = 1;
                        } 

                    }
                
                    
                  print $resultadoHTML;
                  
                  mysql_free_result($arrRegistros);
                  mysql_close($conn);
                
                  //montagem da parte de inser&ccedil;&atilde;o de um custo
                    if($acao != "EditarFavorecido") {
                
                ?>
                          <tr class="respiro-0<?=$corLinha?>">
                              <td align="center" nowrap="nowrap">&nbsp;</td>
                              <td align="center" nowrap="nowrap"><img src="img/ico_adi.gif" alt="Adicionar Favorecido" width="16" height="15" onclick="javascript: submeteForm(document.formFavorecido,'AdicionarFavorecido', -1)"  style='cursor:hand'/> </td>
                              <td nowrap="nowrap"><label>
                                  <input name="nmAbrevFavorecido" type="text" id="nomAbrevFavorecido" title="Nome Abreviado do Favorecido"/>
                              </label></td>
                              <td nowrap="nowrap"><label>
                                  <input name="nmFavorecido" type="text" id="nmFavorecido" title="Nome Completo do Favorecido" size="60"/>
                              </label></td>
                          </tr>
                          <?PHP
                    }
                ?>
                          <tr>
                              <td align="center" nowrap="nowrap">&nbsp;</td>
                              <td align="center" nowrap="nowrap">&nbsp;</td>
                              <td nowrap="nowrap">&nbsp;</td>
                              <td nowrap="nowrap">&nbsp;</td>
                          </tr>
                      </table></td>
                  </tr>
                  <tr>
                      <td>&nbsp;</td>
                  </tr>
              </table>
            </form>
          </div>              
          <!-- Fim centro da página-->
           
<?PHP
  include("includes/html/rodape.html");
?>

