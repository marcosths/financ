<?php

     include("includes/verifica.php");
     include("includes/config.php");
     include("fpdf/fpdf.php");
     
     
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



     @$dataParam = $_POST["dataParam"];
     @$idUsuar = $_SESSION["valid_user"];
     
     // Instanciando a classe geradora de PDFs
     $pdf = new FPDF('P','cm','A4');
     
     // colocando autor do documento
    $pdf->SetAuthor('Financ - O seu sistema finaceiro');
    
    // Colocando o titulo do documento
    $pdf->SetTitle('Movimentação - Financ');
    
    //SetMargins(float left, float top [, float right])
    $pdf->SetMargins(2, 2, 2);
    
    // Adiciona página
    $pdf->AddPage();
    
    // Formatando fonte da linha
    $pdf->SetFont('Arial', 'B', 14);
    
    $pdf->setFillColor(200,200,200);
    
	$pdf->Cell(0, 0.5,"Gerado em: ".datePhpToString(null), 0, 1, 'C',1);
    $pdf->Cell(0, 0.5,"Período: ".$dataParam, 0, 1, 'C',1);

	$pdf->ln();

     
     //criando uma conexão com o banco
     $conn = mysql_connect($server,$db_user, $db_pass) or die ("Não foi possivel conectar ao banco");
     //Definindo a qual base de dados as operações se daram
     mysql_select_db($database) or die ("Base de dados não foi encontrada");
     
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
							   " AND date_format(m.dt_movimento,'%m/%Y') < '$dataParam'";
	
	 
	// execução do comando e obtenção dos dados       
	$arrRegsSaldoAnterior = mysql_query($str_query_saldo_anterior);
	$arrSaldoAnterior = mysql_fetch_assoc($arrRegsSaldoAnterior);
	$saldoAnterior = number_format($arrSaldoAnterior["saldo"],2,',','.');
	
	mysql_free_result($arrRegsSaldoAnterior);


     $str_query_saldo = " SELECT ifnull(sum( ".
					   " CASE m.fg_deb_cred".
					   " WHEN 'D' ".
					   " THEN m.vl_movimento * -1 ".
					   " WHEN 'C' ".
					   " THEN m.vl_movimento ".
					   " END ),0) AS saldo ".
					   " FROM movimentos m" .
					   " WHERE m.id_usuar = '$idUsuar'".
					   " AND date_format(m.dt_movimento,'%m/%Y') <= '$dataParam'";
                    
                     
	// execução do comando e obtenção dos dados       
	$arrRegsSaldo = mysql_query($str_query_saldo);
	$arrSaldo = mysql_fetch_assoc($arrRegsSaldo);
	$saldo = number_format($arrSaldo["saldo"],2,',','.');
	
	mysql_free_result($arrRegsSaldo);
     
     // montando resultado da consulta detalhada por dia
     $str_query =   " SELECT m . * " .
                    " , date_format(m.dt_movimento , '%d/%m/%Y') dt_movimento_f ".
                    " , c.nm_abrev_custo " . 
                    " , f.nm_abrev_favorecido " .
                    " FROM movimentos m " .
                    " LEFT OUTER JOIN (custo c) ON ( m.id_custo = c.id_custo )  " .
                    " LEFT OUTER JOIN (favorecido f) ON ( m.id_favorecido = f.id_favorecido )  " .
                    " WHERE m.id_usuar = '$idUsuar'".
                    " AND date_format(m.dt_movimento,'%m/%Y') = '$dataParam'".
                    " ORDER BY m.dt_movimento ";
     
     // execução do comando e obtenção dos dados       
     $arrRegistros = mysql_query($str_query);

     
     // Formatando fonte da linha
     $pdf->SetFont('Arial', '', 12);
     $pdf->setFillColor(58,239,117); 

     //Cell(float w [, float h [, string txt [, mixed border [, int ln [, string align [, int fill [, mixed link]]]]]]])
     $pdf->Cell(2,0.75,"Nro Doc.", 1, 0, 'C', 1);
     $pdf->Cell(3,0.75,"Centro Custo", 1, 0, 'C', 1);
     $pdf->Cell(3,0.75,"Favorecido", 1, 0, 'C', 1);
     $pdf->Cell(2.5,0.75,"Data", 1, 0, 'C', 1);
     $pdf->Cell(2.5,0.75,"Valor", 1, 0, 'C', 1);
     $pdf->Cell(5,0.75,"Descrição", 1, 0, 'C', 1);
		

	 // Formatando fonte da linha
     $pdf->SetFont('Arial', '', 12);

	 $pdf->ln();
     $pdf->Cell(10.5 ,0.5,"Saldo Anterior: ", 1, 0, 'R');
	 $pdf->Cell(2.5,0.5,$saldoAnterior, 1, 0, 'R');
	 $pdf->Cell(5,0.5,"", 1, 0, 'C');

     $pdf->setFillColor(240,240,240); 
     
	 //linha cinza
	 $corDaLinha = 1;

     while($arrDados = mysql_fetch_assoc($arrRegistros)) {
     	// resgatando os campos de cada registro retornado pela consulta
          $idMovimento = $arrDados["id_movimento"];
          $idFavorecido = $arrDados["id_favorecido"];
          $idCusto = $arrDados["id_custo"];
          $nmAbrevFavorecido = $arrDados["nm_abrev_favorecido"];
          $nmAbrevCusto = $arrDados["nm_abrev_custo"];
          $dtMovimento = $arrDados["dt_movimento_f"];
          $vlMovimento = number_format($arrDados["vl_movimento"],2,',','.');
          $fgDebCred = $arrDados["fg_deb_cred"];
          $nuDocumento = $arrDados["nu_documento"];
          $dsMemo = $arrDados["ds_memo"];
          
          $pdf->Ln();
          
		  //Cell(float w [, float h [, string txt [, mixed border [, int ln [, string align [, int fill [, mixed link]]]]]]])
		  $pdf->Cell(2,0.5,$nuDocumento, 1, 0, 'R',$corDaLinha);
		  $pdf->Cell(3,0.5,$nmAbrevCusto, 1, 0, 'L',$corDaLinha);
		  $pdf->Cell(3,0.5,$nmAbrevFavorecido, 1, 0, 'L',$corDaLinha);
		  $pdf->Cell(2.5,0.5,$dtMovimento, 1, 0, 'C',$corDaLinha);
		  $pdf->Cell(2.5,0.5,$vlMovimento . " " . $fgDebCred, 1, 0, 'R',$corDaLinha);
		  $pdf->Cell(5,0.5,$dsMemo, 1, 0, 'L',$corDaLinha);
          
          
		  if($corDaLinha == 1) {
			 $corDaLinha = 0;
		  } else {
			 $corDaLinha = 1;
		  }

     }
     



     $pdf->setFillColor(58,239,117); 


	 $pdf->ln();
     $pdf->Cell(10.5 ,0.5,"Saldo: ", 1, 0, 'R',1);
	 $pdf->Cell(2.5,0.5,$saldo, 1, 0, 'R',1);
	 $pdf->Cell(5,0.5,"", 1, 0, 'C',1);
     
     
     
     mysql_free_result($arrRegistros);
     
     mysql_close($conn);
     $pdf->Output("pdf/$idUsuar.pdf",'F');
     
?>

 <html>
    <head>
		<meta http-equiv="refresh" content="0;URL=trnsfArquiv.php?nmArq=<?=$idUsuar?>">
    </head>

	<body>
	   Gerando arquivo .....
	</body>
 </html>

