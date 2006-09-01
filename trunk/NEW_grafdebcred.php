<?php 
   session_start();
   include ("includes/config.php");

   $dataMesAno = $_POST["dataParam"];

   $con = mysql_connect($server, $db_user, $db_pass) or trigger_error(mysql_error(),E_USER_ERROR);
   mysql_select_db($database, $con);

   // Select gráfico Débitos x Créditos
   $query = "SELECT case m.fg_deb_cred " .
            "        when 'D' then 'Debito' " .
            "        when 'C' then 'Credito' " .
            "        end as fg_deb_cred, " .
            "        sum(m.vl_movimento) AS valor " .
            " FROM movimentos m " .
            " WHERE m.id_usuar = '" . $_SESSION["valid_user"] . "' " .
            " and date_format(m.dt_movimento,'%m/%Y') = '$dataMesAno' " .
            " group by m.fg_deb_cred";

   $resultado = mysql_query($query,$con);

   //Array de dados para o grafico
   $data = array();

   $i=0;
   if (mysql_num_rows($resultado))
   {
       while ($campo = mysql_fetch_array($resultado))
       {
          //Retornando os dados e armazenado nos arrays.
          $data[$i] = array($campo['fg_deb_cred'],$campo['valor']);
          $i++;
       }

       include ("includes/phplot.php"); // here we include the PHPlot code 
       $graph =& new PHPlot();   // here we define the variable graph
       $graph->SetDataValues($data);

       //Draw it
       $graph->DrawGraph();

       //print "<html><head><meta http-equiv='refresh' content='0;URL=exibegrafdebcred.php'></head>";
       //print "<html><head><meta http-equiv='refresh' content='0;URL=phPie.php?$parmPie'></head>";
       //print "<body>";
       //print "</body>";
       //print "</html>";

   }else{
      print "Não possui dados para gerar gráfico!";
   }
?>
