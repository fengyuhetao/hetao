<?php include('boot.php');?>
<?php require_once('conn/conn.php');
if(isset($_GET['id']) && $_GET["id"]!="")
{
	$id=addslashes($_GET["id"]);
	$deleteSQL="delete from repertory where repertoryID='".$id."'";
	$sql=mysql_query($deleteSQL,$conn) or die(mysql_error());
  $deleteSQL1="delete from ingredientrepertory where repertoryID=$id";
  mysql_query($deleteSQL1,$conn) or die(mysql_error());
	echo "<script>window.location.href='repertoryinfo.php';</script>";
}
else
{
	$currentPage = "repertoryinfo.php";
	$maxRows = 5;
	$pageNum = 0;
	if (isset($_GET['pageNum'])) {
	  $pageNum = addslashes($_GET['pageNum']);
	}
	$startRow = $pageNum * $maxRows;
	
	$query = "select * from repertory";
	$query_limit = sprintf("%s limit %d, %d", $query, $startRow, $maxRows);
	$sql = mysql_query($query_limit, $conn) or die(mysql_error());
	$row = mysql_fetch_assoc($sql);
	
	if (isset($_GET['totalRows'])) {
	  $totalRows_rs1 = $_GET['totalRows'];
	} else {
	  $all = mysql_query($query);
	  $totalRows = mysql_num_rows($all);
	}
	$totalPages = ceil($totalRows/$maxRows)-1;
}
?>
    

    
    <div class="content">
        
        <div class="header">
            
            <h1 class="page-title">仓库管理</h1>
        </div>
        
        <ul class="breadcrumb">
            <li><a href="index.php">首页</a> <span class="divider">/</span></li>
            <li class="active">仓库信息</li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
<div class="header">
	<label>共有<strong><?php echo $totalRows ?></strong> 条记录，目前显示第<strong><?php echo ($startRow + 1) ?></strong>条至第<strong><?php echo min($startRow + $maxRows, $totalRows) ?></strong>条
    </label>
</div>
<div class="well">
    <table class="table">
      <thead>
        <tr>
          <th>仓库ID</th>
          <th>管理员编号</th>
          <th>仓库量</th>
          <th>仓库面积</th>
          <th>仓库位置</th>
          <th style="width: 45px;"></th>
        </tr>
      </thead>
      <tbody>
       <?php 
	  		do {
			?>
          <?php if ($totalRows > 0) { // Show if recordset not empty ?>
        <tr>
          <td><?php echo $row['repertoryID'];?></td>
          <td><?php echo $row['staffID'];?></td>
          <td><?php echo $row['capacity'];?></td>
          <td><?php echo $row['area'];?></td>
          <td><?php echo $row['position'];?></td>
          <td>
              <a href="modify_repertoryinfo.php?id=<?php echo $row['repertoryID'];?>"><i class="icon-pencil"></i></a>
              <a href="#myModal" role="button" data-toggle="modal"><i id="<?php echo $row['repertoryID'];?>" onClick="PassID(this)" class="icon-remove"></i></a>
          </td>
        </tr>
		   <?php } // Show if recordset not empty ?>
<?php } while ($row = mysql_fetch_assoc($sql)); ?>
       <!-- <tr>
          <td>2</td>
          <td>Ashley</td>
          <td>Jacobs</td>
          <td>ash11927</td>
          <td>
              <a href="user.html"><i class="icon-pencil"></i></a>
              <a href="#myModal" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
          </td>
        </tr>
        <tr>
          <td>3</td>
          <td>Audrey</td>
          <td>Ann</td>
          <td>audann84</td>
          <td>
              <a href="user.html"><i class="icon-pencil"></i></a>
              <a href="#myModal" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
          </td>
        </tr>
        <tr>
          <td>4</td>
          <td>John</td>
          <td>Robinson</td>
          <td>jr5527</td>
          <td>
              <a href="user.html"><i class="icon-pencil"></i></a>
              <a href="#myModal" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
          </td>
        </tr>
        <tr>
          <td>5</td>
          <td>Aaron</td>
          <td>Butler</td>
          <td>aaron_butler</td>
          <td>
              <a href="user.html"><i class="icon-pencil"></i></a>
              <a href="#myModal" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
          </td>
        </tr>
        <tr>
          <td>6</td>
          <td>Chris</td>
          <td>Albert</td>
          <td>cab79</td>
          <td>
              <a href="user.html"><i class="icon-pencil"></i></a>
              <a href="#myModal" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
          </td>
        </tr>-->
      </tbody>
    </table>
</div>
<div class="pagination">
    <button class="btn btn-primary" onClick="GoToTianJia()"><i class="icon-plus"></i>添加</button>
    <ul style="float:right"><!--自己加的右漂移-->
      <!--  <li><a href="#">Prev</a></li>
        <li><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
        <li><a href="#">Next</a></li>-->
        <li><a href="<?php printf("%s?pageNum=%d", $currentPage, 0); ?>">第一页</a></li>
        <li><a href="<?php printf("%s?pageNum=%d", $currentPage, max(0, $pageNum - 1)); ?>">上一页</a></li>
        <li><a href="<?php printf("%s?pageNum=%d", $currentPage, min($totalPages, $pageNum + 1)); ?>">下一页</a></li>
        <li><a href="<?php printf("%s?pageNum=%d", $currentPage, $totalPages); ?>">最后一页</a></li>
    </ul>
</div>

<div class="modal small hide fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">提醒</h3>
    </div>
    <div class="modal-body">
        <p class="error-text"><i class="icon-warning-sign modal-icon"></i>确定要删除此条信息?删除之前请您确保该仓库没有储藏任何东西!!!</p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">取消</button>
        <button class="btn btn-danger" data-dismiss="modal" id="del" onclick="Del()">删除</button>
    </div>
</div>


                    
                    <footer>
                        <hr>
                        <p>&copy; 2015 by sunrise laboratory </p>
                </footer>
                    
            </div>
        </div>
    </div>
    <!--自定义的js-->
    <script>
		function GoToTianJia(){
			window.location.href="add_repertoryinfo.php";
		}
		var repertoryid;
		function PassID(obj){
			repertoryid=obj.id;
		}
		function Del(){
			window.location.href="repertoryinfo.php?id="+repertoryid;
		}
	</script>
  </body>
</html>
<?php
mysql_free_result($sql);
?>

