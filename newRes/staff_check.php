<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
  
  //*******************myself codes start*************
   if(!isset($_SESSION['MM_Username']))
  {
      header("Location: index.php");
      exit;
  }
    //*******************myself codes start*************
}?>
<?php include("conn/conn.php");?>
<?php 
	if(isset($_GET['page'])){       //判断是否有$_GET['page']变量传进来
		$page=addslashes($_GET['page']);
	}
	else{
		$page=1;
	}
	$page_count=20;
	$sql=mysql_query("select date,checkwork.staffID,workPercentage,staff.name from checkwork left join staff on checkwork.staffID=staff.staffID");
	$row=mysql_num_rows($sql);                  //判断数据的数量
	$page_page=ceil($row/$page_count);          //判断页数
	$last_record=($page-1)*$page_count;          //获取上一页的最后一条记录
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="lib/bootstrap/css/bootstrap.css">
    
<link rel="stylesheet" type="text/css" href="stylesheets/theme.css">
<link rel="stylesheet" href="lib/font-awesome/css/font-awesome.css">

    <script src="lib/jquery-1.7.2.min.js" type="text/javascript"></script>
<script>
function iframeResizeHeight(frame_name,body_name,offset) {
	parent.document.getElementById(frame_name).height=document.getElementById(body_name).offsetHeight;
	//alert(parent.document.getElementById(frame_name).height);
}

function Resize(oframe,obody){
 	if(parent.document.getElementById(oframe)){
  		return iframeResizeHeight(oframe,obody,0);
 }
}

function stamp(obj)
{
	var oldStr=document.body.innerHTML;
	document.body.innerHTML=document.getElementById(obj).innerHTML;
	window.print();
	document.body.innerHTML=oldStr;
}
</script>
</head>
<body onLoad="Resize('main_info','main_check');">
	<div id="main_check">
    <a href="#page-stats" class="block-heading" data-toggle="collapse">页次<?php echo $page;?>/<?php echo $page_page;?>页 记录：<?php echo $row;?>条</a>
    	  <div class="well" style="padding:0px; border:0px;">
            <div id="page_stats" class="well" style="margin:0px;">
              <table class="table" id="show">
                  <tr align="center" valign="middle">
                      <th>日期</th>
                      <th>员工ID</th>
                      <th>姓名</th>
                      <th>工作量百分比</th>
                  </tr>
                  <?php 
                        $sqls=mysql_query("select date,checkwork.staffID,workPercentage,staff.name from checkwork left join staff on checkwork.staffID=staff.staffID limit $last_record,$page_count");
                        $array=mysql_fetch_array($sqls);
                        do{
                  ?>
                  <tr>
                      <td><?php echo $array['date'];?></td>
                      <td><?php echo $array['staffID'];?></td>
                      <td><?php echo $array['name'];?></td>
                      <td><?php echo $array['workPercentage'];?></td>
                  </tr>
                  <?php 
                  }while($array=mysql_fetch_array($sqls));
                  ?>
                  </table>
              </div>
              <div class="pagination" >
              <button class="btn btn-primary" onClick="GoToTianJia()"><i class="icon-plus"></i>添加</button>
                        <ul style="float:right;">
                      	    <li><a href="staff_check.php?page=1">首页</a></li>
                            <li><a href="staff_check.php?page=<?php if($page==1){echo $page=1; }else{ echo $page-1; }?>">上一页</a></li>
                            <li><a href="staff_check.php?page=<?php if($page<$page_page){echo $page+1;}else{ echo $page_page;}?>">下一页</a></li>
                            <li><a href="staff_check.php?page=<?php echo $page_page;?>">尾页</a></li>
                            <li><a href="#" onClick="stamp('page_stats');">打印</a></li>
                        </ul>
                </div>
<hr>
<div class="form-inline"><button class="btn" type="button"><i class="icon-search"></i> 姓名</button>
<input class="input-xlarge" type="text" style="height:30px;" id="name"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="搜索" id="searchButton" class="btn btn-primary" onClick="chaxun();"/>
</div>
<table id="search" class="table">
<tr align="center" valign="middle">
    <thread>
        <th>编号</th>
    	<th>日期</th>
        <th>员工ID</th>
        <th>姓名</th>
        <th>工作量百分比</th>
    </thread>
</tr>
</table>
</div>
</div>
</body>
<script>
	var oTab=document.getElementById('show');
	var oTab1=document.getElementById('search');
	
	var tbody= document.createElement('tbody'); //新建一个tbody类型的Element节点
	oTab1.appendChild(tbody);
	
	function chaxun()
	{
		var oName=document.getElementById('name');
		if(oName.value=="")
			alert("请输入名字");
		else
		{
			var test;
			var xmlhttp;
			if (window.XMLHttpRequest)
  			{// code for IE7+, Firefox, Chrome, Opera, Safari
  				xmlhttp=new XMLHttpRequest();
 			}
	  	 	else
  			{// code for IE6, IE5
  				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  			}
			xmlhttp.open("GET","test.php?name="+oName.value,true);
			xmlhttp.send();
			xmlhttp.onreadystatechange=function()
	  	    {
 				if (xmlhttp.readyState==4 && xmlhttp.status==200)
    			{
					var num=1;
    				test=xmlhttp.responseText;
					var b=new Array();
					b=test.split(" ");
					if(b[0]>0)
					{
						var id=1;
						oTab1.removeChild(tbody);
						tbody = document.createElement("tbody"); //新建一个tbody类型的Element节点
						oTab1.appendChild(tbody);
						for(var i=0;i<b[0];i++)
						{   	
							var oTr=document.createElement('tr');
			
							var oTd=document.createElement('td');
							oTd.innerHTML=id++;	
							oTr.appendChild(oTd);
							
							var oTd=document.createElement('td');
							oTd.innerHTML=b[num++];
							oTr.appendChild(oTd);
				
							var oTd=document.createElement('td');
							oTd.innerHTML=b[num++];
							oTr.appendChild(oTd);
					
							var oTd=document.createElement('td');
							oTd.innerHTML=b[num++];
							oTr.appendChild(oTd);
				
							var oTd=document.createElement('td');
							oTd.innerHTML=b[num++];
							oTr.appendChild(oTd);
					
							tbody.appendChild(oTr);		
							Resize('main_info','main_check');		
						}	
					}
					else
						alert("不存在此人");
    			}
			}
  		}
	}
	function GoToTianJia(){
		window.parent.location.href="add_staffcheckinfo.php";
	}
</script>