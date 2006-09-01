<?php 
   session_start();
   include ("includes/config.php");
   include "charts/charts.php"; 

   $dataMesAno = $_POST["dataParam"];

   $con = mysql_connect($server, $db_user, $db_pass) or trigger_error(mysql_error(),E_USER_ERROR);
   mysql_select_db($database, $con);

   // Select gráfico Depesas por Centro de Custo
   $query = "SELECT ifnull(c.nm_abrev_custo,'Nao atribuido') as custo, " .
            "       sum(m.vl_movimento) AS valor " .
            "FROM movimentos m " .
            "LEFT OUTER JOIN (custo c) ON ( m.id_custo = c.id_custo ) " .
            "WHERE m.id_usuar = '" . $_SESSION["valid_user"] . "' " .
            "and m.fg_deb_cred = 'D' " .
            //"and date_format(m.dt_movimento,'%m/%Y') = '07/2006' " . 
            "and date_format(m.dt_movimento,'%m/%Y') = '$dataMesAno' " .
            "group by c.nm_abrev_custo";

   $resultado = mysql_query($query,$con);

   //Este tipo de gráfico utiliza o Eixo X e Y
   $datay=array(); // array com os dado do Eixo Y
   $datax=array(); // array com os dado do Eixo X
   $datay[0] = "";
   $datax[0] = "";
   $i=1;
   if (mysql_num_rows($resultado))
   {
       while ($campo = mysql_fetch_array($resultado))
       {
          //Retornando os dados e armazenado nos arrays.
          $datay[$i] = $campo['valor'];   //dados Eixo Y
          $datax[$i] = $campo['custo'];  //dados Eixo X
          $i++;
          $parmPie .= "data[".$campo['custo']."]=".$campo['valor']."&";
       }
	 // fim do select nos dados.

	 $chart[ 'chart_data' ] = array ( $datax, $datay );
   	 $chart[ 'chart_grid_h' ] = array ( 'alpha'=>20, 'color'=>"000000", 'thickness'=>1, 'type'=>"solid" );
   	 $chart[ 'chart_rect' ] = array ( 'positive_color'=>"ffffff", 'positive_alpha'=>20, 'negative_color'=>"ff0000", 'negative_alpha'=>10 );
   	 $chart[ 'chart_type' ] = "pie";
   	 $chart[ 'chart_value' ] = array ( 'color'=>"ffffff", 'alpha'=>90, 'font'=>"arial", 'bold'=>true, 'size'=>10, 'position'=>"inside", 'prefix'=>"", 'suffix'=>"", 'decimals'=>0, 'separator'=>"", 'as_percentage'=>true );
   	 $chart[ 'draw' ] = array ( array ( 'type'=>"text", 'color'=>"000000", 'alpha'=>10, 'font'=>"arial", 'rotation'=>0, 'bold'=>true, 'size'=>30, 'x'=>0, 'y'=>140, 'width'=>400, 'height'=>150, 'text'=>"|||||||||||||||||||||||||||||||||||||||||||||||", 'h_align'=>"center", 'v_align'=>"bottom" )) ;
   	 $chart[ 'legend_label' ] = array ( 'layout'=>"horizontal", 'bullet'=>"circle", 'font'=>"arial", 'bold'=>true, 'size'=>13, 'color'=>"ffffff", 'alpha'=>85 ); 
   	 $chart[ 'legend_rect' ] = array ( 'fill_color'=>"ffffff", 'fill_alpha'=>10, 'line_color'=>"000000", 'line_alpha'=>0, 'line_thickness'=>0 ); 
   	 //$chart[ 'series_color' ] = array ( "ddaa41", "88dd11", "4e62dd", "ff8811", "4d4d4d", "5a4b6e" ); 
   	 $chart[ 'series_explode' ] = array ( 20, 0, 50 );
   	 SendChartData ( $chart );

       //print "<html><head><meta http-equiv='refresh' content='0;URL=exibegrafdespcusto.php'></head>";
       print "<html><head><meta http-equiv='refresh' content='0;URL=phPie.php?$parmPie'></head>";
       print "<body>";
       print "</body>";
       print "</html>";

   }else{
      print "Não possui dados para gerar gráfico!";
   }
?>
