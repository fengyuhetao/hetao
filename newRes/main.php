<?php
if (!isset($_SESSION)) {
  session_start();
  
  //*******************myself codes start*************
   if(!isset($_SESSION['MM_Username']))
  {
	  header("Location: index.php");
	  exit;}
	//*******************myself codes start*************
}
include_once('conn/conn.php');
$selectMenu=mysql_query("select * from tadayMenu ",$conn);
$selectBill=mysql_query("select * from bill",$conn);
$selectSeat=mysql_query("select * from seat",$conn);
	  


require "boot.php";
require_once('conn/conn.php');
include("mainRight.php");
?>
<script type="text/javascript">
var muser = document.getElementById('iuser');
muser.innerHTML = "<?php echo $_SESSION['MM_Username'];?>";
</script>

