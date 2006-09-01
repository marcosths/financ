<?
   session_start();
   session_destroy();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Bem vindo ao Financ</title>


	<link rel="stylesheet" type="text/css" href="css/padrao-verde.css" >
	<SCRIPT language="JavaScript" src="js/funcoes.js"></SCRIPT>		
	<script type="text/javascript">
	</script>
	</head>



	<body onLoad="" bgcolor="#ffffff" link="#0000ff" vlink="#0000ff">

	<table align="center" border="0" cellpadding="0" cellspacing="0" width="95%">
		<tbody>
			<tr valign="top">
	    		<td width="1%">
					<img src="img/logo1.jpg" align="left" border="0" height="112" vspace="10" width="149">
				</td>

    			<td bgcolor="#ffffff" valign="top" width="99%">
			      <table cellpadding="1" width="100%">
        			<tbody>
						<tr valign="bottom">
          					<td>
								<div align="right">&nbsp;
								</div>
							</td>
        				</tr>
        				<tr>
          					<td height="83" nowrap="nowrap">
            					<table style="margin-bottom: 5px;" align="center" bgcolor="#c3d9ff" cellpadding="0" cellspacing="0" width="100%">
              						<tbody>
									<tr>
                						<td class="bubble tl" align="left" valign="top">											
										</td>
                						<td class="bubble" rowspan="2" style="padding: 3px 0pt; font-family: arial; text-align: left; font-weight: bold;">
											<b>Seja Bem Vindo ! </b>
										</td>
                						<td class="bubble tr" align="right" valign="top">
											
										</td>
					              </tr>
              					  <tr>
                					<td class="bubble bl" align="left" valign="bottom">
									</td>
                					<td class="bubble br" align="right" valign="bottom">&nbsp;
										
									</td>
              					  </tr>
            					</tbody>
							</table>
          				</td>
        			</tr>
      				</tbody>
				</table>
    		</td>
  		</tr>
		</tbody>
	</table>
	<br>

	<table align="center" cellpadding="5" cellspacing="1" width="94%">

  		<tbody>
		<tr>
      		<td width="66%" height="305" valign="top">

      			<table border="0" cellpadding="5" cellspacing="1" width="100%">
					<tbody>
						<tr>
							<td>&nbsp;</td>
							<td>
								<font color="red">
									<b>Nova!</b>
								</font> 
								<span class="style1">Novas noticias</span>
	  							<p><font size="3"> Hoje Dia 20/06/2006 Andr&eacute; e Emerson <br>
													apresentam a primeira vers&atilde;o <br>
													do Financ - Sistema Financeiro para WEB!
								</font></p>
							</td>
						</tr>
						<tr>

							<td>&nbsp;</td>
							<td>
								<br>
	  							<span class="style1">Outras noticia</span>
								<p><font color="#000000" size="3">Financ lan&ccedil;a vers&atilde;o Beta !!! </font></p>

								<p><font color="#3300FF" size="3">Autores: 
																<br>	Andr&eacute; Felipe Laus
																<br>    Emerson Immianovsky</font></p>
	    					</td>
						</tr>
				</tbody>
			</table>

      </td>
	  <td width="34%" valign="top">
        <!-- caixa de login -->
        <table class="form-noindent" bgcolor="#C5FFC4" cellpadding="5" cellspacing="3" width="100%">
          <tbody>
		  	<tr bgcolor="#e8eefa">
            	<td valign="top" nowrap="nowrap" bgcolor="#C5FFC4" style="text-align: center;">

					<div id="login">

						<div style="background: rgb(197, 255, 196) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial;" id="gaia_loginbox" class="body"> 
							<form action="login.php" onSubmit="" id="loginform" method="post" >
	  						<table id="" align="center" border="0" cellpadding="1" cellspacing="0">
								<tbody>
									<tr> 
										<td colspan="2" align="center">  
											<font size="-1">  Entrar na sua conta Financ </font>               
					
											<table> 
											<tbody>
												<tr>  
													<td valign="top"> 
														<img src="img/logo2.gif" width="68" height="47">									
													</td>  
												</tr> 
											</tbody>
										</table>
									</td> 
								</tr>                     
								<tr> 
									<td colspan="2" align="center"> 
										<div class="errorbox-good">
										   <font size="-1" color="red"><i><b><?=$erros?></b></i></font>
										</div>
									</td> 
								</tr> 
								<tr> 
									<td width="88" height="34" nowrap="nowrap"> 
										<div align="right"> 
											<span class="gaia le lbl"> Username: </span>					
										</div>
									</td> 
									<td width="112">
										<input name="username" id="username" value="" class="gaia le val" id="username" size="18" type="text">
									</td> 
								</tr> 
								<tr> 
									<td></td> 
									<td align="left">
									</td>
								</tr> 
								<tr> 
									<td align="right"> 
											<span > Password: </span>
									</td> 
									<td> 
										<input name="passwd" id="passwd" size="18" type="password" 
										onkeypress="javascript: 
															if (enterKey(event)) {
																document.getElementById('btEntrar').onclick();
															}
												   "
										>
									</td> 
								</tr>
								<tr> 
									<td>
									</td>
									<td align="left">
									</td> 
								</tr>   
								<tr> 
									<td align="right" valign="top">&nbsp;</td> 
									<td>&nbsp;</td> 
								</tr>
			
								<tr> 
									<td></td> 
									<td align="left">
										<input name="btEntrar" id="btEntrar" value="Entrar" class="" type="button" onclick="javascript: this.disabled='disabled'; this.form.submit();">										
									</td> 
								</tr>      
								<tr id=""> 
									<td colspan="2" class="" align="center" height="33" nowrap="nowrap" valign="bottom"><a href="assinar.php">Criar conta</a>									</td> 
								</tr>        
							</tbody>
						</table> 
					</form> 
				</div>    
			</div>

        </td>
          </tr></tbody></table>
        <br></td>
  </tr></tbody></table>
<br>
<? include "./includes/html/rodape.html" ?>

<script type="text/javascript">
	document.getElementById('username').focus();
</script>