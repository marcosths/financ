<?PHP
  include("includes/verifica.php");
  include("includes/html/cabecalho.html");
  include("includes/html/menu.html");
  include("includes/config.php");
  
  
  
  // resgatando informações do form e da session
  @$acao = $_POST["acao"];
  @$idUsuar = $_SESSION["valid_user"];
  @$idCusto = $_POST["id"];
  @$nmAbrevCusto = $_POST["nmAbrevCusto"];
  @$dsCusto = $_POST["dsCusto"];
  
  // abrindo conexão com o banco de dados
  $conn = mysql_connect($server,$db_user, $db_pass) or die ("Não foi posivel conectar ao banco");
   // definindo a base de dados
   mysql_select_db($database) or die ("Base de dados não foi encontrada");
  
  // verificando qual ação devera ser execultada
   if($acao == "AdicionarCusto") {
  	
    // montando comando de inserção do centro de custo
    $str_inserir = "insert into custo ".
                            " (id_usuar, nm_abrev_custo, ds_custo) ".
                            " values " .
                            "('$idUsuar','$nmAbrevCusto', '$dsCusto')";

    
    // executando comando de inserção
    mysql_query($str_inserir);
  
    	
  } else if($acao == "ExcluirCusto" && $idCusto != "") {
    
    // montando comando de exclusão de um centro de custo
    $str_deletar = "delete from custo ".                            
                   " where id_custo = $idCusto";
    
    // executando exclusão
    mysql_query($str_deletar);
        
  } else if($acao == "AlterarCusto" && $idCusto != "") {
    
    // montando comando de atualização de um centro de custo
    $str_update = "update custo ".
                   " set nm_abrev_custo = '$nmAbrevCusto'".                            
                   " , ds_custo = '$dsCusto'".
                   " where id_custo = $idCusto";
    
    // executando alteração
    mysql_query($str_update);
        
  }
  
  // inicio da construção do corpo da página HTML 
?>

          <!-- Inicio centro da página-->                    
          <div class="div-01" style="div-01">
            <form id="formCentro" name="formCentro" method="post" action="centro_custo.php">
			  <input name="acao" type="hidden" value="" />
          	  <input name="id" type="hidden" value="" />
		  	  <table width="100%" border="0"align="center">
                  <tr>
                      <td>&nbsp;</td>
                  </tr>
				  <tr>
                      <td><table border="0" align="center">
                          <tr>
                              <td colspan="4" class="respiro-03">Consulta de Centro de Custo</td>
                          </tr>
                          <tr>
                              <td width="8%">&nbsp;</td>
                              <td width="22%">Nome:</td>
                              <td width="63%"><label>
                                  <input name="cNmAbrevCusto" type="text" id="cNmAbrevCusto" />
                              </label></td>
                              <td width="7%">&nbsp;</td>
                          </tr>
                          <tr>
                              <td>&nbsp;</td>
                              <td>Descri&ccedil;&atilde;o:</td>
                              <td><label>
                                  <input name="cDsCusto" type="text" id="cDsCusto" size="60"/>
                              </label></td>
                              <td>&nbsp;</td>
                          </tr>
                          <tr>
                              <td>&nbsp;</td>
                              <td colspan="2" align="center"><label>
                                  <input name="Consultar" type="button" id="Consultar" onclick="javascript: submeteForm(document.formCentro,'ConsultarCusto', -1)" value="Consultar" />
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
                              <td align="center" nowrap="nowrap">Nome</td>
                              <td align="center" nowrap="nowrap">Descri&ccedil;&atilde;o</td>
                          </tr>
                          <?PHP
                    
                    
                    // monatagem de comando de pesquisa geral
                    $str_query =   " SELECT * ".
                                   " FROM custo".
                                   " WHERE id_usuar = '$idUsuar'";
                    // adicionando critérios a consulta
                    if($acao == "ConsultarCusto") {
                    	
                        // resgatando critérios de consulta
                        @$cNmAbrevCusto = $_POST["cNmAbrevCusto"];
                        @$cDsCusto = $_POST["cDsCusto"];                        
                                   
                        if($cNmAbrevCusto){
                        	$str_query .= " and nm_abrev_custo like '%$cNmAbrevCusto%'";
                        } 
                        
                        if($cDsCusto){
                            $str_query .= " and ds_custo like '%$cDsCusto%'";
                        } 
                        
                        
                    }
                    
                    // excutando e resgatando resultado da consulta        
                    $arrRegistros = mysql_query($str_query);
                    
                    // variavel responsalvel pela cor das linhas
                    $corLinha = 1;
                    $resultadoHTML = "";
                    
                    // montando linhas da tabela com os resultados da consulta
                    while($arrDados = mysql_fetch_assoc($arrRegistros)) {
                          $idCustoReg = $arrDados["id_custo"];
                          $nmAbrevCusto = $arrDados["nm_abrev_custo"];
                          $dsCusto = $arrDados["ds_custo"];
                          
                    // se nenhum centro de custo foi clicado para edição
                        if($idCusto != $idCustoReg || $acao != "EditarCusto") {                    
                            $resultadoHTML .=  "<tr class=\"respiro-0$corLinha\">\n";
                            $resultadoHTML .=  "       <td width='4%' align='center' valign='middle' nowrap='nowrap'>\n";
                            $resultadoHTML .=  "            <img src='img/ico_alt.gif' alt='Alterar Centro de Custo' width='16' height='15' onclick=\"javascript: submeteForm(document.formCentro,'EditarCusto', $idCustoReg)\" style='cursor:hand'/>\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td width='4%' align='center' valign='middle' nowrap='nowrap'>\n";
                            $resultadoHTML .=  "            <img src='img/ico_del.gif' alt='Excluir Centro de Custo' width='16' height='15' onclick=\"javascript: submeteForm(document.formCentro,'ExcluirCusto', $idCustoReg)\" style='cursor:hand'/>\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td width='22%'>\n";
                            $resultadoHTML .=  "          <label>$nmAbrevCusto</label>&nbsp;\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td width='65%''>\n";
                            $resultadoHTML .=  "           <label>$dsCusto</label>&nbsp;\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "      </tr>\n";
                        } else if ($acao == "EditarCusto"){
                        	$resultadoHTML .=  "<tr class=\"respiro-0$corLinha\">\n";
                            $resultadoHTML .=  "       <td width='4%' align='center' valign='middle' nowrap='nowrap'>\n";
                            $resultadoHTML .=  "            <img src='img/ico_sal.gif' alt='Salvar Altera&ccedil;&atilde;o no Centro de Custo' width='16' height='15' onclick=\"javascript: submeteForm(document.formCentro,'AlterarCusto', $idCustoReg)\" style='cursor:hand'/>\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td width='4%' align='center' valign='middle' nowrap='nowrap'>\n";
                            $resultadoHTML .=  "            <img src='img/ico_des.gif' alt='Desfazer Edi&ccedil;&atilde;o do Centro de Custo' width='16' height='15' onclick=\"javascript: submeteForm(document.formCentro,'', -1)\" style='cursor:hand'/>\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td width='22%'>\n";
                            $resultadoHTML .=  "          <input name='nmAbrevCusto' type='text' id='nomAbrevCusto' title='Nome do Centro de Custo' value='$nmAbrevCusto'/>&nbsp;\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td width='65%''>\n";
                            $resultadoHTML .=  "           <input name='dsCusto' type='text' id='dsCusto' title='descri&ccedil;&atilde;o do Centro de Custo' value='$dsCusto' />&nbsp;\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "      </tr>\n";
                            
                        } 
                        
                            
                        if($corLinha == 1){
                        	$corLinha = 2;
                        } else {
                        	$corLinha = 1;
                        } 

                    }
                
                  // imprimindo tabela HTML 
                  print $resultadoHTML;
                  
                  
                  // desalocando Array de registros
                  mysql_free_result($arrRegistros);
                  
                  // fechando conexão
                  mysql_close($conn);
                
                  //montagem da parte de inser&ccedil;&atilde;o de um custo
                    if($acao != "EditarCusto") {
                
                ?>
                          <tr class="respiro-0<?=$corLinha?>">
                              <td align="center" nowrap="nowrap">&nbsp;</td>
                              <td align="center" nowrap="nowrap"><img src="img/ico_adi.gif" alt="Adicionar Centro de Custo" width="16" height="15" onclick="javascript: submeteForm(document.formCentro,'AdicionarCusto', -1)"  style='cursor:hand'/> </td>
                              <td nowrap="nowrap"><label>
                                  <input name="nmAbrevCusto" type="text" id="nomAbrevCusto" title="Nome do Centro de Custo"/>
                              </label></td>
                              <td nowrap="nowrap"><label>
                                  <input name="dsCusto" type="text" id="dsCusto" title="descri&ccedil;&atilde;o do Centro de Custo" size="60"/>
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

