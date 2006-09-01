<?PHP
  
  include("includes/verifica.php");
  include("includes/html/cabecalho.html");
  include("includes/html/menu.html");
  include("includes/config.php");
  

  function stringToDateMysql($data) {
	$dataFormatada = "";
	
	$dataFormatada = "". substr($data, 6). "-" . substr($data,3,2) . "-" . substr($data,0,2) ." 00:00:00";

	return $dataFormatada;
  }

  function datePhpToString($arrData){
	if(!$arrData) {
		$arrData = getdate();
	}


	$dia = "";
	$mes = "";
	$ano = "";
	
	$dia = $arrData["mday"];
    if($dia < 10 ) {
		$dia = "0" . $dia ;
	}

    $mes = $arrData["mon"];
    if($mes < 10 ) {
		$mes = "0" . $mes ;
	}

	$ano = $arrData["year"];
	$data = $dia."/".$mes."/".$ano;

	return $data;
  }
  
  
  //Resgatando os parâmetros 
  @$acao = $_POST["acao"];
  @$idUsuar = $_SESSION["valid_user"];
  @$idMovimento = $_POST["id"];
  @$idCusto = $_POST["idCusto"];
  @$idFavorecido = $_POST["idFavorecido"];
  @$dtMovimento = $_POST["dtMovimento"];
  @$fgDebCred = $_POST["fgDebCred"];
  @$vlMovimento = $_POST["vlMovimento"];
  @$fgExtrato = $_POST["fgExtrato"];
  @$nuDocumento = $_POST["nuDocumento"];
  @$dsMemo = $_POST["dsMemo"];
  
  
  //criando uma conexão com o banco
  $conn = mysql_connect($server,$db_user, $db_pass) or die ("Não foi possivel conectar ao banco");
  //Definindo a qual base de dados as operações se daram
   mysql_select_db($database) or die ("Base de dados não foi encontrada");
  
   //teste de que ação sera executada
   if($acao == "AdicionarMovimento") {
  	
    //montando comando de inserção
    $str_inserir = "insert into movimentos ".
                            " (id_usuar, id_custo, id_favorecido, dt_movimento, fg_deb_cred, vl_movimento, fg_extrato, nu_documento, ds_memo) ".
                            " values " .
                            "('$idUsuar', $idCusto, $idFavorecido, '". stringToDateMysql($dtMovimento) ."', '$fgDebCred', $vlMovimento, 'N', $nuDocumento, '$dsMemo')";

    
    //executando comando de inserção
    mysql_query($str_inserir);
  
    	
  } else if($acao == "ExcluirMovimento" && $idMovimento != "") {
    
    //montendo comando para exclusão de movimento 
    $str_deletar = "delete from movimentos ".                            
                   " where id_movimento = $idMovimento";
    
    // executando exclusão
    mysql_query($str_deletar);
        
  } else if($acao == "AlterarMovimento" && $idMovimento != "") {
    
    // montando comando de alteração dos dados
    $str_update = "update movimentos " .
                   " set id_custo = $idCusto " .
                   " , id_favorecido = $idFavorecido " .
                   " , dt_movimento = '". stringToDateMysql($dtMovimento) ."' " .
                   " , fg_deb_cred = '$fgDebCred' " .
                   " , vl_movimento = $vlMovimento " .
                   " , fg_extrato = '$fgExtrato' " .
                   " , nu_documento = '$nuDocumento' " .
                   " , ds_memo = '$dsMemo' " .
                   " where id_movimento = $idMovimento ";
    
    // executando alteração
    mysql_query($str_update);
        
  }
  
  
  // resgatando os centros de custos do usuário para popular combos
  $str_query_custo = " SELECT " .
                       "    id_custo " .
                       "    , nm_abrev_custo " .
                       " FROM " .
                       "    custo " .
                       " WHERE id_usuar = '$idUsuar' ";
   
   // execução do comando e obtenção dos dados
   $arrRegCusto = mysql_query($str_query_custo);                        
         
        
   // resgatando os favorecidos do usuário para popular combos     
   $str_query_favorecido = " SELECT " .
                            "    id_favorecido " .
                            "    , nm_abrev_favorecido " .
                            " FROM " .
                            "    favorecido " .
                            " WHERE id_usuar = '$idUsuar' ";
                                
   // execução do comando e obtenção dos dados
   $arrRegFavorecido = mysql_query($str_query_favorecido);
   
  // montagem do corpo da página HTML
?>

          <!-- Inicio centro da página-->
          <style type="text/css">
<!--
.style1 {font-size: xx-small}
-->
          </style>
                              
          <div class="div-01" style="div-01">
            <form id="formMovimento" name="formMovimento" method="post" action="movimento.php">
			  <input name="acao" type="hidden" value="" />
          	  <input name="id" type="hidden" value="" />
		  	  <table width="100%" border="0"align="center">
                  <tr>
                      <td colspan="5">&nbsp;</td>
                  </tr>
				  <tr>
                      <td colspan="5"><table width="52%" border="0" align="center" >
                          <tr>
                              <td colspan="6" class="respiro-03">Consulta de Movimento </td>
                          </tr>
                          <tr>
                              <td>&nbsp;</td>
                              <td nowrap="nowrap">&nbsp;</td>
                              <td colspan="2"></td>
                              <td></td>
                              <td>&nbsp;</td>
                          </tr>
                          <tr>
                              <td width="10%" height="24">&nbsp;</td>
                              <td width="19%" nowrap="nowrap">Per&iacute;odo: </td>
                              <td colspan="2">
                                  <select name="consultaMes" size="1" id="consultaMes">
                                      <option value="1">Janeiro</option>
                                      <option value="2">Fevereiro</option>
                                      <option value="3">Mar&ccedil;o</option>
                                      <option value="4">Abril</option>
                                      <option value="5">Maio</option>
                                      <option value="6">Junho</option>
                                      <option value="7">Julho</option>
                                      <option value="8">Agosto</option>
                                      <option value="9">Setembro</option>
                                      <option value="10">Outubro</option>
                                      <option value="11">Novembro</option>
                                      <option value="12">Dezembro</option>
                                  </select>								  </td>
								  <td width="23%"> 
                                  <select name="consultaAno" size="1" id="consultaAno">
                                      <?PHP
                                         $comboData = getdate();
                                         
                                         $comboData = $comboData["year"];
                                         
                                         for($ano = ($comboData - 6); $ano <= ($comboData + 6) ;$ano++){
                                         ?>	
                                             <option value="<?=$ano?>"><?=$ano?></option>
                                         <?PHP   
                                         }
                                      ?>
                                  </select></td><td width="23%"><label>							      
                                  <input name="Consultar" type="button" id="Consultar" 
								  onclick="javascript: submeteForm(document.formMovimento,'ConsultarMovimento', -1)" 
                                  value="Consultar" />
                              </label></td>
                          </tr>
            <!--
                          <tr>
                              <td>&nbsp;</td>
                              <td nowrap="nowrap">&nbsp;</td>
                              <td colspan="2"></td>
                              <td></td>
                              <td>&nbsp;</td>
                          </tr>
                          <tr>
                              <td>&nbsp;</td>
                              <td colspan="4" align="center"><label>							      
                                  <input name="Consultar" type="button" id="Consultar" 
								  onclick="javascript: submeteForm(document.formMovimento,'ConsultarMovimento', -1)" 
                                  value="Consultar" />
                              </label></td>
                              <td>&nbsp;</td>
                          </tr>
                          <tr>
                              <td height="21">&nbsp;</td>
                              <td>&nbsp;</td>
                              <td colspan="3">&nbsp;</td>
                              <td>&nbsp;</td>
                          </tr>
             -->
                      </table></td>
                  </tr>
				  
                  <tr>
                      <td colspan="5"><table border="0" align="center">
                          <tr class="respiro-03">
                              <td width="18" align="center" nowrap="nowrap">&nbsp;</td>
                              <td width="18" align="center" nowrap="nowrap">&nbsp;</td>
                              <td align="center" nowrap="nowrap">Nro Documento </td>
                              <td align="center" nowrap="nowrap">Centro Custo </td>
                              <td align="center" nowrap="nowrap">Favorecido</td>
                              <td align="center" nowrap="nowrap">Data</td>
                              <td align="center" nowrap="nowrap">Valor</td>
                              <td align="center" nowrap="nowrap">Descri&ccedil;&atilde;o</td>
                              <td width="18" align="center" nowrap="nowrap">Consolidado</td>
                          </tr>
                          <?PHP
                    
                    
                    // montando resultado da consulta sem restrições
                    $str_query =    " SELECT m . * " .
								    " , date_format(m.dt_movimento , '%d/%m/%Y') dt_movimento_f ".
									" , c.nm_abrev_custo " . 
									" , f.nm_abrev_favorecido " .
								    " FROM movimentos m " .
									" LEFT OUTER JOIN (custo c) ON ( m.id_custo = c.id_custo )  " .
									" LEFT OUTER JOIN (favorecido f) ON ( m.id_favorecido = f.id_favorecido )  " .
									" WHERE m.id_usuar = '$idUsuar'";
								   
                                   
                    @$consultaMes = $_POST["consultaMes"];
                    @$consultaAno = $_POST["consultaAno"];
                    
                    // adicionando restrições a consulta
                    if(!$consultaMes || !$consultaAno) {                        
                    	$dataAtual = getdate();
                       
                       $consultaMes = $dataAtual["mon"];
                       $consultaAno = $dataAtual["year"];
                    } 
                    
                    $dataMesAno = "$consultaMes/$consultaAno";
                    
                    if(strlen($dataMesAno) < 7) {
                    	$dataMesAno = "0$dataMesAno";
                    }
                     
                    $str_query .= " AND date_format(m.dt_movimento,'%m/%Y') = '$dataMesAno'";
                    
                    $str_query .= " ORDER BY m.dt_movimento ";
                    
                    
                    
                    // execução do comando e obtenção dos dados       
                    $arrRegistros = mysql_query($str_query);
                    
                    
                    // variavel que representa o estilo que cada linha vaireceber
                    $corLinha = 2;
                    
                    // variavel que contêm o html de resultados
                    $resultadoHTML = "";
                    $scriptCamposEdicao = "";
                    

                    // calculo do saldo anterior
                    $str_query_saldo_anterior =" SELECT ifnull(sum( ".
                                               " CASE m.fg_deb_cred".
                                               " WHEN 'D' ".
                                               " THEN m.vl_movimento * -1 ".
                                               " WHEN 'C' ".
                                               " THEN m.vl_movimento ".
                                               " END ),0) AS saldo ".
                                               " FROM movimentos m" .
                                               " WHERE m.id_usuar = '$idUsuar'".
                                               " AND date_format(m.dt_movimento,'%m/%Y') < '$dataMesAno'";
                    
                     
                    // execução do comando e obtenção dos dados       
                    $arrRegsSaldoAnterior = mysql_query($str_query_saldo_anterior);
                    $arrSaldoAnterior = mysql_fetch_assoc($arrRegsSaldoAnterior);
                    $saldoAnterior = number_format($arrSaldoAnterior["saldo"],2,',','.');
                    
                    mysql_free_result($arrRegsSaldoAnterior);
                          
                    $resultadoHTML .= "      <tr class='respiro-01'>";
                    $resultadoHTML .= "          <td colspan='6' align='right' nowrap='nowrap''>Saldo Anterior:</td>";
                    $resultadoHTML .= "          <td align='right' nowrap='nowrap'>$saldoAnterior&nbsp;</td>";
                    $resultadoHTML .= "          <td nowrap='nowrap'>&nbsp;</td>";
                    $resultadoHTML .= "      </tr>";
                    
                    
                    
                    // percorendo Array com os registros de Movimento
                    while($arrDados = mysql_fetch_assoc($arrRegistros)) {
                    	  
                          // resgatando os campos de cada registro retornado pela consulta
                          $idMovimentoReg = $arrDados["id_movimento"];
                          $idFavorecido = $arrDados["id_favorecido"];
                          $idCusto = $arrDados["id_custo"];
                          $nmAbrevFavorecido = $arrDados["nm_abrev_favorecido"];
                          $nmAbrevCusto = $arrDados["nm_abrev_custo"];
                          $dtMovimento = $arrDados["dt_movimento_f"];
                          $vlMovimento = number_format($arrDados["vl_movimento"],2,',','.');
                          $fgDebCred = $arrDados["fg_deb_cred"];
                          $fgExtrato = $arrDados["fg_extrato"];
                          $nuDocumento = $arrDados["nu_documento"];
                          $dsMemo = $arrDados["ds_memo"];
                        
                        if($idMovimento != $idMovimentoReg || $acao != "EditarMovimento") {                    
                            $resultadoHTML .=  "<tr class=\"respiro-0$corLinha\">\n";
                            $resultadoHTML .=  "       <td align='center' valign='middle' nowrap='nowrap'>\n";
                            $resultadoHTML .=  "            <img src='img/ico_alt.gif' alt='Alterar Movimento' width='16' height='15' onclick=\"javascript: submeteForm(document.formMovimento,'EditarMovimento', $idMovimentoReg)\" style='cursor:hand'/>\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td align='center' valign='middle' nowrap='nowrap'>\n";
                            $resultadoHTML .=  "            <img src='img/ico_del.gif' alt='Excluir Movimento' width='16' height='15' onclick=\"javascript: submeteForm(document.formMovimento,'ExcluirMovimento', $idMovimentoReg)\" style='cursor:hand'/>\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td align='right'>\n";
                            $resultadoHTML .=  "           <label>$nuDocumento</label>&nbsp;\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td >\n";
                            $resultadoHTML .=  "          <label>$nmAbrevCusto</label>&nbsp;\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td >\n";
                            $resultadoHTML .=  "           <label>$nmAbrevFavorecido</label>&nbsp;\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td align='center'>\n";
                            $resultadoHTML .=  "           <label>$dtMovimento</label>&nbsp;\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td align='right'>\n";
                            $resultadoHTML .=  "           <label>$vlMovimento $fgDebCred</label>&nbsp;\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td align='left'>\n";
                            $resultadoHTML .=  "           <label>$dsMemo</label>&nbsp;\n";
                            $resultadoHTML .=  "        </td>\n";

                            $resultadoHTML .=  "        <td align='center' valign='middle' nowrap='nowrap'>\n";
                            if ($fgExtrato=='S'){
                               $resultadoHTML .=  "     <img src='img/greenshd.gif' alt='Consolidado' width='16' height='15'/>\n";
                            }else{
                               $resultadoHTML .=  "     <img src='img/redshd.gif' alt='N&atilde;o Consolidado' width='16' height='15'/>\n";
                            }
                            $resultadoHTML .=  "        </td>\n";

                            $resultadoHTML .=  "      </tr>\n";
                        } else if ($acao == "EditarMovimento"){
                        	$resultadoHTML .=  "<tr class=\"respiro-0$corLinha\">\n";
                            $resultadoHTML .=  "       <td align='center' valign='middle' nowrap='nowrap'>\n";
                            $resultadoHTML .=  "            <img src='img/ico_sal.gif' alt='Salvar Altera&ccedil;&atilde;o no Movimento' width='16' height='15' onclick=\"javascript: submeteForm(document.formMovimento,'AlterarMovimento', $idMovimentoReg)\" style='cursor:hand'/>\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td align='center' valign='middle' nowrap='nowrap'>\n";
                            $resultadoHTML .=  "            <img src='img/ico_des.gif' alt='Desfazer Edi&ccedil;&atilde;o do Movimento' width='16' height='15' onclick=\"javascript: submeteForm(document.formMovimento,'', -1)\" style='cursor:hand'/>\n";
                            $resultadoHTML .=  "        </td>\n";
                            $resultadoHTML .=  "        <td nowrap='nowrap'>";
                            $resultadoHTML .=  "            <input name='nuDocumento' type='text' id='nuDocumento' value='$nuDocumento' size='15' maxlength='10' /> ";
                            $resultadoHTML .=  "        </td> ";
                            $resultadoHTML .=  "        <td nowrap='nowrap'>";
                            $resultadoHTML .=  "            <select name='idCusto' size='1' id='idCusto' title='Centro de Custo'>";
                                                                          
                                        mysql_data_seek($arrRegCusto,0);
                                        // listando os centros de custo na combo de inserção
                                        while($arrDadosCusto = mysql_fetch_assoc($arrRegCusto)) {
                                            $idCustoCombo = $arrDadosCusto["id_custo"];
                                            $nmCustoCombo = $arrDadosCusto["nm_abrev_custo"];                                  
                                  
                                            $resultadoHTML .=  "       <option value='$idCustoCombo'>$nmCustoCombo</option>";
                                  
                                        }
                                        mysql_free_result($arrRegCusto);
                                 
                            $resultadoHTML .=  "            </select> ";                              
                            $resultadoHTML .=  "        </td>";
                            $resultadoHTML .=  "        <td nowrap='nowrap'>";
                            $resultadoHTML .=  "              <select name='idFavorecido' size='1' id='idFavorecido' title='Favorecido'>";
                                   
                                        mysql_data_seek($arrRegFavorecido,0);
                                        // listando os favorecidos na combo de inserção                                       
                                        while($arrDadosFavorecido = mysql_fetch_assoc($arrRegFavorecido)) {
                                            $idFavorecidoCombo = $arrDadosFavorecido["id_favorecido"];
                                            $nmFavorecidoCombo = $arrDadosFavorecido["nm_abrev_favorecido"];                                  
                              
                                            $resultadoHTML .=  "            <option value='$idFavorecidoCombo'>$nmFavorecidoCombo</option>";
                                  
                                        }
                                        mysql_free_result($arrRegFavorecido);
                                  


                              $resultadoHTML .=  "              </select>";                              
                              $resultadoHTML .=  "          </td>";
                              $resultadoHTML .=  "          <td nowrap='nowrap'>";
                              $resultadoHTML .=  "                 <input name='dtMovimento' type='text' id='dtMovimento'"; 
                              $resultadoHTML .=  "                        onchange='javascript: dataVerifica(this)'"; 
                              $resultadoHTML .=  "                        onkeyup='javascript: dataCompleta(this)'"; 
                              $resultadoHTML .=  "                        size='12' maxlength='10' value='$dtMovimento' title='Data do Movimento'/>";
                              $resultadoHTML .=  "          </td>";
                              $resultadoHTML .=  "          <td nowrap='nowrap'>";
                              $resultadoHTML .=  "              <label>";
                              $resultadoHTML .=  "                  <input name='vlMovimento' type='text' id='vlMovimento' title='Valor do Movimento' size='20' value='$vlMovimento'/>";
                              $resultadoHTML .=  "                  <select name='fgDebCred' size='0' id='fgDebCred' title='Cr&eacute;dito ou D&eacute;bito'>";
                              $resultadoHTML .=  "                      <option value='C'>C</option>";
                              $resultadoHTML .=  "                      <option value='D'>D</option>";
                              $resultadoHTML .=  "                  </select>";
                              $resultadoHTML .=  "              </label>";
                              $resultadoHTML .=  "           </td>";
                              $resultadoHTML .=  "           <td nowrap='nowrap'>";
                              $resultadoHTML .=  "              <input name='dsMemo' type='text' id='dsMemo' maxlength='100' value='$dsMemo'/>";
                              $resultadoHTML .=  "           </td>";
                              $resultadoHTML .=  "        <td align='center' valign='middle' nowrap='nowrap'>\n";
                              if ($fgExtrato=='S'){
                                 $resultadoHTML .=  "     <img src='img/greenshd.gif' alt='Consolidado' width='16' height='15'/>\n";
                              }else{
                                 $resultadoHTML .=  "     <img src='img/redshd.gif' alt='N&atilde;o Consolidado' width='16' height='15'/>\n";
                              }
                              $resultadoHTML .=  "        </td>\n";
                              $resultadoHTML .=  "       </tr>";
                          
                              $scriptCamposEdicao = "\n<SCRIPT language='JavaScript'>\n" ;
                              
                                                 if($idCusto)   
                                                    $scriptCamposEdicao .= "    document.formMovimento.idCusto.value = $idCusto;\n";
                                                 if($idFavorecido)
                                                    $scriptCamposEdicao .= "    document.formMovimento.idFavorecido.value = $idFavorecido;\n";
                                                    
                              $scriptCamposEdicao .= "    document.formMovimento.fgDebCred.value = '$fgDebCred';\n";
                              $scriptCamposEdicao .= "</SCRIPT>\n";
                            
                        } 
                        
                        // mudando as cores das linhas da tabela de resultados    
                        if($corLinha == 1){
                        	$corLinha = 2;
                        } else {
                        	$corLinha = 1;
                        } 

                    }
                
                  // imprimindo a tabela HTML de resultados   
                  print $resultadoHTML;
                  
                  // desalocando Array com a lista resultado
                  mysql_free_result($arrRegistros);
                  
                
                  //montagem da parte de inser&ccedil;&atilde;o de um movimento
                    if($acao != "EditarMovimento") { 
                        
                ?>
                          <tr class="respiro-0<?=$corLinha?>">
                              <td width="18" align="center" nowrap="nowrap">&nbsp;</td>
                              <td width="18" align="center" nowrap="nowrap"><img src="img/ico_adi.gif" alt="Adicionar Movimento" width="16" height="15" onclick="javascript: submeteForm(document.formMovimento,'AdicionarMovimento', -1)"  style='cursor:hand'/> </td>
                              <td nowrap="nowrap"><input name="nuDocumento" type="text" id="nuDocumento" value="" size="15" maxlength="10" /></td>
                              <td nowrap="nowrap">
                              <select name="idCusto" size="1" id="idCusto" title="Centro de Custo">
                                  <?PHP                                        
                                        mysql_data_seek($arrRegCusto,0);
                                        // listando os centros de custo na combo de inserção
                                        while($arrDadosCusto = mysql_fetch_assoc($arrRegCusto)) {
                                            $idCustoCombo = $arrDadosCusto["id_custo"];
                                            $nmCustoCombo = $arrDadosCusto["nm_abrev_custo"];                                  
                                  ?>
                                            <option value="<?=$idCustoCombo?>"><?=$nmCustoCombo?></option>
                                  <?PHP
                                        }
                                        mysql_free_result($arrRegCusto);
                                  ?>
                              </select>                              </td>
                              <td nowrap="nowrap">
                              <select name="idFavorecido" size="1" id="idFavorecido" title="Favorecido">
                                  <?PHP 
                                        mysql_data_seek($arrRegFavorecido,0);
                                        // listando os favorecidos na combo de inserção                                       
                                        while($arrDadosFavorecido = mysql_fetch_assoc($arrRegFavorecido)) {
                                            $idFavorecidoCombo = $arrDadosFavorecido["id_favorecido"];
                                            $nmFavorecidoCombo = $arrDadosFavorecido["nm_abrev_favorecido"];                                  
                                  ?>
                                            <option value="<?=$idFavorecidoCombo?>"><?=$nmFavorecidoCombo?></option>
                                  <?PHP
                                        }
                                        mysql_free_result($arrRegFavorecido);
                                  
                                     //$arrData = getdate();
                                     //$strData = $arrData["mday"]."/".$arrData["mon"]."/".$arrData["year"];
									 $strData = datePhpToString(null);
                                  ?>
                              </select>                              </td>
                              <td nowrap="nowrap">
                                 <input name="dtMovimento" type="text" id="dtMovimento" 
                                        onchange="javascript: dataVerifica(this)" 
                                        onkeyup="javascript: dataCompleta(this)" 
                                        size="12" maxlength="10" value="<?= $strData ?>" title="Data do Movimento"/>                              </td>
                              <td nowrap="nowrap"><label>
                                  <input name="vlMovimento" type="text" id="vlMovimento" title="Valor do Movimento" size="20"/>
                                  <select name="fgDebCred" size="0" id="fgDebCred" title="Cr&eacute;dito ou D&eacute;bito">
                                      <option value="C">C</option>
                                      <option value="D" selected="selected">D</option>
                                  </select>
                              </label></td>
                              <td nowrap="nowrap"><input name="dsMemo" type="text" id="dsMemo" maxlength="100" /></td>
                              <td align='center' valign='middle' nowrap='nowrap'>
                              <img src='img/redshd.gif' alt='N&atilde;o Consolidado' width='16' height='15'/></td>
                          </tr>
                          <?PHP
                    }
                    
                    
                    
                    $str_query_saldo = " SELECT ifnull(sum( ".
                                       " CASE m.fg_deb_cred".
                                       " WHEN 'D' ".
                                       " THEN m.vl_movimento * -1 ".
                                       " WHEN 'C' ".
                                       " THEN m.vl_movimento ".
                                       " END ),0) AS saldo ".
                                       " FROM movimentos m" .
                                       " WHERE m.id_usuar = '$idUsuar'".
                                       " AND date_format(m.dt_movimento,'%m/%Y') <= '$dataMesAno'";
                    
                     
                    // execução do comando e obtenção dos dados       
                    $arrRegsSaldo = mysql_query($str_query_saldo);
                    $arrSaldo = mysql_fetch_assoc($arrRegsSaldo);
                    $saldo = number_format($arrSaldo["saldo"],2,',','.');
                    
                    mysql_free_result($arrRegsSaldo);
                ?>
                          
                          <tr class="respiro-03">
                              <td colspan="6" align="right" nowrap="nowrap">Saldo:</td>
                              <td align="right" nowrap="nowrap"><?=$saldo?>&nbsp;</td>
                              <td nowrap="nowrap">&nbsp;</td>
                          </tr>
                          <tr>
                              <td width="18" align="center" nowrap="nowrap">&nbsp;</td>
                              <td width="18" align="center" nowrap="nowrap">&nbsp;</td>
                              <td nowrap="nowrap">&nbsp;</td>
                              <td nowrap="nowrap">&nbsp;</td>
                              <td nowrap="nowrap">&nbsp;</td>
                              <td nowrap="nowrap">&nbsp;</td>
                              <td nowrap="nowrap">&nbsp;</td>
                              <td nowrap="nowrap">&nbsp;</td>
                          </tr>
                      </table></td>
                  </tr>
                  <tr>
                      <td colspan="5">					  </td>
                  </tr>
                  <tr>
                      <td width="15%">&nbsp;</td>
                      <td width="24%">
					  	  <input name="btCusto" type="button" id="btCusto" 
                          onclick="javascript: document.formGeracao.action = 'grafdespcusto.php'; document.formGeracao.submit();" 
                          value="Gr&aacute;fico Despesa por Custo" />
					  </td>
                      <td width="23%">
                          <input name="btDebCred" type="button" id="btDebCred" 
                          onclick="javascript: document.formGeracao.action = 'grafdebcred.php'; document.formGeracao.submit();"
                          value="Gr&aacute;fico D&eacute;bitos x Creditos" />
                      </td>
                      <td width="16%">
                            <input name="btPdf" type="button" id="btPdf" 
                            onclick="javascript: document.formGeracao.action = 'gerarPdf.php'; document.formGeracao.submit();"
                            value="Download PDF" /></td>
                      <td width="22%">&nbsp;</td>
                  </tr>
                  <tr>
                      <td colspan="5">&nbsp;</td>
                  </tr>
              </table>
            </form>
			<form action="" method="post" name="formGeracao" target="_blank" id="formGeracao">
		  	 <input name="dataParam" type="hidden" value="<?=$dataMesAno?>" />
		  </form>
          </div>              
          <!-- Fim centro da página-->
          
           
<?PHP
  mysql_close($conn);
  include("includes/html/rodape.html");
?>

<SCRIPT language="JavaScript">
    document.formMovimento.consultaMes.value = <?=$consultaMes?>;
    document.formMovimento.consultaAno.value = <?=$consultaAno?>;
</SCRIPT>     

<?=$scriptCamposEdicao?>
