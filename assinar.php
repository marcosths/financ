<?
// Inicia sessões
session_start();

include "./includes/html/cabecalho.html";
include "./includes/config.php";
include "./confirmassinar.php";
$acao = "inser";
$labelsenha = "Digite uma senha:";
$labelsubmit = "Assinar agora";
$readonly = "";
// Verifica se existe os dados da sessão de login
if(isset($_SESSION["valid_user"]))
{
   include "./includes/html/menu.html";
   $db_conn = mysql_connect($server, $db_user, $db_pass) or die ("Erro na conexão com a Base de Dados"); 
   mysql_select_db($database, $db_conn);
   $query = "select * from usuar where apelido='".$_SESSION["valid_user"]."'";
   $result = mysql_query($query, $db_conn);
   $dados = mysql_fetch_array($result);
    $apelido = $dados["apelido"];
    $senha = $dados["senha"];
    $nome = $dados["nome"];
    $cpf = $dados["cpf"];
    $endereco = $dados["endereco"];
    $numero = $dados["numero"];
    $bairro = $dados["bairro"];
    $cidade = $dados["cidade"];
    $estado = $dados["estado"];
    $cep = $dados["cep"];
    $telefone = $dados["telefone"];
    $celular = $dados["celular"];
    $email = $dados["email"];
    $msn = $dados["msn"];
    $acao = "alter";
    $labelsenha = "Senha Atual:";
    $labelsubmit = "Atualizar";
    $readonly = "readonly";
}

?>
       
<table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='#C5FFC4'>
<tr>
   <td colspan='3' bgcolor='#FFFFFF' height='5'>   </td>
</tr>
<tr>
   <td align='right' colspan='3' bgcolor='#3AEF75' height='18'>
   <p align='left'>&nbsp;Sua Chave:</td>
</tr>
<tr>
   <td width="33%" bgcolor='#C5FFC4' style='border-style: solid; border-color: #C5FFC4'>
      <form action=assinar.php method=post >
      <div align='center'>
          <center>
          <table width='100%' border='0' cellspacing='0' cellpadding='0' height='416'>
          <tr>
             <td align='right' style='border-top: 1 solid #000000' height='24' colspan="2"><font color='#000000'><b>Escolha um apelido:</b>&nbsp;&nbsp;&nbsp;</font></td>
             <td width="56%" height='24' align="left" style='border-top: 1 solid #000000' >
                 <font color='#000000'>
                 <input type='text' name='apelido' <?=$readonly?> value="<?=$apelido?>" maxlength='16' size='16' >
                    tudo junto sem acento. <small>(até 16 caracteres)</small>
            </font></td>
          </tr>
          <tr>
             <td align='right' style='border-top: 1 solid #000000' height='24' colspan="2"><font color='#000000'><b><?=$labelsenha?></b>&nbsp;&nbsp;&nbsp;</font></td>
             <td height='24' align="left" style='border-top: 1 solid #000000'><font color='#000000'>
               <input type='password' name='senha' <?=$readonly?> value="<?=$senha?>" size='10' maxlength='10' >
            tudo junto sem acento.</font><small>(até 10 caracteres)</small></td>
          </tr>
          <? if ($acao == "alter"){ ?>
             <tr>
                <td align='right' style='border-top: 1 solid #000000' height='24' colspan="2"><font color='#000000'><b>Nova Senha:</b>&nbsp;&nbsp;&nbsp;</font></td>
                <td height='24' align="left" style='border-top: 1 solid #000000'><font color='#000000'>
                  <input type='password' name='novasenha' value="" size='10' maxlength='10' >
                     tudo junto sem acento.</font><small>(até 10 caracteres)</small></td>
             </tr>
          <?}?>
          <tr>
             <td align='right' colspan='3' bgcolor='#3AEF75' height='18'>
            <p align='left'>&nbsp;Seus dados:</td>
          </tr>
          <center>
          <tr>
		     <td width="31%" height='25' align='right' class="respiro-01" >&nbsp;</td>
             <td width="13%" height='25' align='left' class="respiro-01" >Nome:</td>
            <td height='25' align="left" class="respiro-01"><input type='text' name='nome' size='40' value="<?=$nome?>" ></td>
          </tr>
          <tr>
		  	 <td class="respiro-02" align='right' height='25' >&nbsp;</td>
             <td class="respiro-02" align='left' height='25'> CPF:</td>
             <td height='25' align="left" class="respiro-02"><input type='text' name='cpf' value="<?=$cpf?>" size='11' >(opcional)</td>
          </tr>
          <tr>
		  	 <td class="respiro-01" align='right' height='25' >&nbsp;</td>
             <td class="respiro-01" align='left' height='25'>Endereço:</td>
             <td height='25' align="left" class="respiro-01"><input type='text' name='endereco' value="<?=$endereco?>" size='30' ></td>
          </tr>
          <tr>
		  	 <td class="respiro-02" align='right' height='25' >&nbsp;</td>
             <td class="respiro-02" align='left' height='25'>Número:</td>
             <td height='25' align="left" class="respiro-02"><input type='text' name='numero' value="<?=$numero?>" size='5' ></td>
          </tr>
          <tr>
		  	 <td class="respiro-01" align='right' height='25' >&nbsp;</td>
             <td class="respiro-01" align='left' height='25'>Bairro:</td>
             <td height='25' align="left" class="respiro-01"><input type='text' name='bairro' value="<?=$bairro?>" size='18' ></td>
          </tr>
          <tr>
		  	 <td class="respiro-02" align='right' height='25' >&nbsp;</td>
             <td class="respiro-02" align='left' height='25'>Cidade:</td>
             <td height='25' align="left" class="respiro-02"><input type='text' name='cidade' value="<?=$cidade?>" size='25' ></td>
          </tr>
          <tr>
		  	 <td class="respiro-01" align='right' height='25' >&nbsp;</td>
             <td class="respiro-01" align='left' height='25'>Estado:</td>
             <td height='25' align="left" class="respiro-01"><select size="1" name="estado" style="font-size: 8 pt" >
                        <option <? if($estado==''){print 'selected';}?> >--</option>
                        <option <? if($estado=='AC'){print 'selected';}?> >AC</option>
                        <option <? if($estado=='AL'){print 'selected';}?> >AL</option>
                        <option <? if($estado=='AM'){print 'selected';}?> >AM</option>
                        <option <? if($estado=='AP'){print 'selected';}?> >AP</option>
                        <option <? if($estado=='BA'){print 'selected';}?> >BA</option>
                        <option <? if($estado=='CE'){print 'selected';}?> >CE</option>
                        <option <? if($estado=='DF'){print 'selected';}?> >DF</option>
                        <option <? if($estado=='ES'){print 'selected';}?> >ES</option>
                        <option <? if($estado=='GO'){print 'selected';}?> >GO</option>
                        <option <? if($estado=='MA'){print 'selected';}?> >MA</option>
                        <option <? if($estado=='MG'){print 'selected';}?> >MG</option>
                        <option <? if($estado=='MS'){print 'selected';}?> >MS</option>
                        <option <? if($estado=='MT'){print 'selected';}?> >MT</option>
                        <option <? if($estado=='PA'){print 'selected';}?> >PA</option>
                        <option <? if($estado=='PB'){print 'selected';}?> >PB</option>
                        <option <? if($estado=='PE'){print 'selected';}?> >PE</option>
                        <option <? if($estado=='PI'){print 'selected';}?> >PI</option>
                        <option <? if($estado=='PR'){print 'selected';}?> >PR</option>
                        <option <? if($estado=='RJ'){print 'selected';}?> >RJ</option>
                        <option <? if($estado=='RN'){print 'selected';}?> >RN</option>
                        <option <? if($estado=='RO'){print 'selected';}?> >RO</option>
                        <option <? if($estado=='RR'){print 'selected';}?> >RR</option>
                        <option <? if($estado=='RS'){print 'selected';}?> >RS</option>
                        <option <? if($estado=='SC'){print 'selected';}?> >SC</option>
                        <option <? if($estado=='SE'){print 'selected';}?> >SE</option>
                        <option <? if($estado=='SP'){print 'selected';}?> >SP</option>
                        <option <? if($estado=='TO'){print 'selected';}?> >TO</option>
                        <option <? if($estado=='EXT'){print 'selected';}?> >EXT</option>
            </select></td>
          </tr>
          <tr>
		  	 <td class="respiro-02" align='right' height='25' >&nbsp;</td>
             <td class="respiro-02" align='left'  height='25'>CEP:</td>
             <td height='25' align="left" class="respiro-02"><input type='text' name='cep' value="<?=$cep?>" size='8' ></td>
          </tr>
          <tr>
		  	 <td class="respiro-01" align='right' height='25' >&nbsp;</td>
             <td class="respiro-01" align='left' height='25'>Telefone:</td>
             <td height='25' align="left" class="respiro-01"><input type='text' name='telefone' value="<?=$telefone?>" size='18' ></td>
          </tr>
          <tr>
		  	 <td class="respiro-02" align='right' height='25' >&nbsp;</td>
             <td class="respiro-02" align='left' height='25'>Celular:</td>
             <td height='25' align="left" class="respiro-02"><input type='text' name='celular' value="<?=$celular?>" size='18' ></td>
          </tr>
          <tr>
		  	 <td class="respiro-01" align='right' height='25' >&nbsp;</td>
             <td class="respiro-01" align='left' height='25'><b>E-mail:</b></td>
             <td height='25' align="left" class="respiro-01"><input type='text' name='email' value="<?=$email?>" size='25' ></td>
          </tr>
          <tr>
		  	 <td class="respiro-02" align='right' height='25' >&nbsp;</td>
             <td class="respiro-02" align='left' height='25'><b>MSN:</b></td>
             <td height='25' align="left" class="respiro-02"><input type='text' name='msn' value="<?=$msn?>" size='25' >(alternativo)</td>
          </tr>
          </center>
          <tr>
			 <td height='27' colspan='3' align="center" valign="middle" bgcolor="#3AEF75">
             <div align='center'>
                 <input name="acao" type="hidden" value="<?=$acao?>" >
                 <p>
				 <input type='submit' value='<?=$labelsubmit?>' name='submit' >
             </div>
            </td>
          </tr>
            <? if(!isset($_SESSION["valid_user"])){ ?>
      		<tr> 
					<td colspan="2" class="" align="center" height="33" nowrap="nowrap" valign="bottom"><a href="index.php">Abandonar</a>									</td> 
				</tr>
             <?}?>        
      </table>
          </center>
         </div></form>
    </td>
  </tr>
</table>
<p align='center'>&nbsp;</p>

    	
<? 
  include "./includes/html/rodape.html";
?>
