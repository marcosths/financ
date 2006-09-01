<?PHP
  include("includes/verifica.php");
  include("includes/html/cabecalho.html");
  include("includes/html/menu.html");
?>
   <!-- Inicio centro da página-->
   <div class="div-01" style="div-01">

   <form enctype="multipart/form-data" action="pegaarquivo.php" method="post">
      <input type="hidden" name="MAX_FILE_SIZE" value="100000">
      <table width="70%" border="0" align="center">
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
    <tr class="respiro-03">
        <td colspan="4">Consolidar Extrato </td>
        </tr>
    <tr>
        <td width="8%">&nbsp;</td>
        <td width="29%">&nbsp;</td>
        <td width="13%">&nbsp;</td>
        <td width="21%">&nbsp;</td>
        </tr>
    <tr>
        <td>Arquivo:</td>
        <td><input name="arquivo" type="file"></td>
        <td><input type="submit" value="Enviar arquivo"></td>
        <td>&nbsp;</td>
        </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
</table>

   </form>              

   </div>              
   <!-- Fim centro da página-->
<?PHP
   include("includes/html/rodape.html");
?>

