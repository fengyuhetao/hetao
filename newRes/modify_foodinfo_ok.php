<?php
include "conn/conn.php";
function CleanHtmlTags( $content )
{
	$content = htmlspecialchars( $content );
	$content = str_replace( '\n', '<br />', $content );
	$content = str_replace( '  ', '&nbsp;&nbsp;' , $content );
	return str_replace( '\t', '&nbsp;&nbsp;&nbsp;&nbsp;', $content );
}
?>
<?php
$name=$_FILES[uploadfile][name];
 $id=$_GET[id];
 if($name!="")
{ 
    $selectsql="select * from food where foodID=$id";
    echo "<br/>";
    $select=mysql_query($selectsql,$conn) or die(mysql_error());
    $array=mysql_fetch_array($select);
    if($array)
    {
        unlink($array[imageLocation]);
    }
}
 ?>
<?php 
//上传文件的路径
$dir = '.\images\food\\';
/*
$_FILES:用在当需要上传二进制文件的地方,获得该文件的相关信息
$_FILES['userfile']['name'] 客户端机器文件的原名称。 
$_FILES['userfile']['type'] 文件的 MIME 类型，需要浏览器提供该信息的支持，例如“image/gif” 
$_FILES['userfile']['size'] 已上传文件的大小，单位为字节
$_FILES['userfile']['tmp_name'] 文件被上传后在服务端储存的临时文件名,注意不要写成了$_FILES['userfile']['temp_name']很容易写错的，虽然tmp就是代表临时的意思，但是这里用的缩写
$_FILES['userfile']['error'] 和该文件上传相关的错误代码。['error'] 
*/
if($name!="")
{
    if($_FILES['uploadfile']['error'] != UPLOAD_ERR_OK)
    {
    switch($_FILES['uploadfile']['error'])
    {
        case UPLOAD_ERR_INI_SIZE: //其值为 1，上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值
            die('The upload file exceeds the upload_max_filesize directive in php.ini');
        break;
        case UPLOAD_ERR_FORM_SIZE: //其值为 2，上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值
            die('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.');
        break;
        case UPLOAD_ERR_PARTIAL: //其值为 3，文件只有部分被上传
            die('The uploaded file was only partially uploaded.');
        break;
        case UPLOAD_ERR_NO_FILE: //其值为 4，没有文件被上传
            die('No file was uploaded.');
        break;
        case UPLOAD_ERR_NO_TMP_DIR: //其值为 6，找不到临时文件夹
            die('The server is missing a temporary folder.');
        break;
        case UPLOAD_ERR_CANT_WRITE: //其值为 7，文件写入失败
            die('The server failed to write the uploaded file to disk.');
        break;
        case UPLOAD_ERR_EXTENSION: //其他异常
            die('File upload stopped by extension.');
        break;
    }
    }
}
/*getimagesize方法返回一个数组，
$width : 索引 0 包含图像宽度的像素值，
$height : 索引 1 包含图像高度的像素值，
$type : 索引 2 是图像类型的标记：
= GIF，2 = JPG， 3 = PNG， 4 = SWF， 5 = PSD， 6 = BMP， 
= TIFF(intel byte order)，8 = TIFF(motorola byte order)，
= JPC，10 = JP2，11 = JPX，12 = JB2，13 = SWC，14 = IFF，15 = WBMP，16 = XBM，
$attr : 索引 3 是文本字符串，内容为“height="yyy" width="xxx"”，可直接用于 IMG 标记
*/
if($name!="")
{
    list($width,$height,$type,$attr) = getimagesize($_FILES['uploadfile']['tmp_name']);

//imagecreatefromgXXX方法从一个url路径中创建一个新的图片
    switch($type)
    {
    case IMAGETYPE_GIF:
        $image = imagecreatefromgif($_FILES['uploadfile']['tmp_name']) or die('The file you upload was not supported filetype');
    break;
    case IMAGETYPE_JPEG:
        $image = imagecreatefromjpeg($_FILES['uploadfile']['tmp_name']) or die('The file you upload was not supported filetype');
    break;    
    case IMAGETYPE_PNG:
        $image = imagecreatefrompng($_FILES['uploadfile']['tmp_name']) or die('The file you upload was not supported filetype');
    break;    
    default    :
        die('The file you uploaded was not a supported filetype.');
    }
}
$desc=CleanHtmlTags($_POST['desc']);
$ls="images/food/";
$location=$ls.$name;
if($name=="")
{
    $query = "update food set foodID=$_POST[id],foodName='$_POST[name]',price=$_POST[price],foodType='$_POST[type]',description='$desc' where foodID=$id";
    mysql_query($query,$conn) or die(mysql_error());
}
else
   {
    $query = "update food set foodID=$_POST[id],foodName='$_POST[name]',price=$_POST[price],foodType='$_POST[type]',description='$desc',imageLocation='$location' where foodID=$id";
    mysql_query($query,$conn) or die(mysql_error());
}

//有url指定的图片创建图片并保存到指定目录
if($name!="")
{
    switch($type)
    {
    case IMAGETYPE_GIF:
        imagegif($image,$dir.$name);
    break;
    case IMAGETYPE_JPEG:
        imagejpeg($image,$dir.$name);
    break;
    case IMAGETYPE_PNG:
        imagepng($image,$dir.$name);
    break;
    }
    //销毁由url生成的图片
    imagedestroy($image);
}
echo "<script> alert('修改成功！');window.location.href='foodinfo.php'</script>";	
?>

