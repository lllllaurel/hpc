<?php 
session_start();
if (!isset($_SESSION['login'])) {
	header("Location: ../index.html");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>服务器控制平台</title>
	<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="../css/main.css">
</head>
<body>
<div class="top-content">
	<img src="../images/top-content.jpg">
</div>
<div class="body-content">
	<div class="body-main">
		<div class="body-left">
			<div class="body l-top">
				<ul class="list-group list-group-flush">
					<li class="list-group-item" style="font-weight: bold;font-family: '黑体'" id="cl-back"><span class="badge badge-warning">back</span>返回上一级</li>
				</ul>
			</div>
			<div class="body l-bottom">
					<button type="button" class="btn btn-outline-success" id="upload">上传文件</button>
					<button type="button" class="btn btn-outline-success" id="supermode">超级模式</button>
					<button type="button" class="btn btn-outline-success" id="makedir">创建目录</button>
			</div>
		</div>
		<div class="body-mid">
			<div class="mid-console">
				<span></span>
			</div>
		</div>
		<div class="body-right">
			<div class="body r-top">
				<div class="btn-group-vertical" role="group" aria-label="Vertical button group" id="menu-button" style="display: none">
					<button type="button" class="btn btn-outline-primary" id="cat">查看</button>
					<button type="button" class="btn btn-outline-primary" id="unzip">解压到当前文件夹</button>
					<button type="button" class="btn btn-outline-primary" id="delete">删除文件</button>
				</div>
				<div class="r-url">
					<form action="../controller/handleFile.php" method="post" enctype="multipart/form-data">
					  <div class="form-group">
					  	<label for="exampleFormControlFile1" style="color: #4fbbac">文件上传到/var/www/html/hpc/upload</label>
					    <input type="file" class="form-control-file" id="exampleFormControlFile1" name="file">
					    <input type="submit" name="submit" value="Submit" class="btn btn-success" style="float: right"/>
					  </div>
					</form>
				</div>
				<div class="r-makedirectory">
					<input class="form-control" type="text" placeholder="输入目录名称(在当前目录下创建)">
					<button type="button" class="btn btn-success" style="float: right" id="createdir">Creat!</button>
				</div>
			</div>
			<div class="body r-bottom"></div>
		</div>
	</div>
</div>
<script
  src="http://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous">
</script>
<script type="text/javascript" src="../js/homepage.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".mid-console>span").html("<p style='color:red'>警告! 请不要随意删除文件，否则后果自负!</p>");
		//show catalog
		var li='';
		$.get("../controller/homepage.php",{para:0},function(data,status){
			var data_obj = JSON.parse(data);
			var sub = new Array();
			for (var item in data_obj) {
				sub = data_obj[item].split(" ");
				if (sub[0][0]=='d') {
					li+='<li class="list-group-item" myAttr="cl-dr" style="color:#4169E1;" onclick="enterDirectory(this)">'+$.trim(sub[sub.length-1])+'</li>';
					sub.splice(0,sub.length);
					continue;
				}
				if ($.trim(sub[sub.length-1])=="ipt"||$.trim(sub[sub.length-1])=="ipt.c") 
					continue;
				li+='<li class="list-group-item" myAttr="cl-file" onclick="showMenu(this)">'+$.trim(sub[sub.length-1])+'</li>';
				sub.splice(0,sub.length);
			}
			$("#cl-back").after(li);
		});
		$("#cl-back").click(function(){
			var li='';
			$.get("../controller/homepage.php",{para:2},function(data,status){
				var data_obj = JSON.parse(data);
				var sub = new Array();
				for (var item in data_obj) {
					sub = data_obj[item].split(" ");
					if (sub[0][0]=='d') {
						li+='<li class="list-group-item" myAttr="cl-dr" style="color:#4169E1;" onclick="enterDirectory(this)">'+$.trim(sub[sub.length-1])+'</li>';
						sub.splice(0,sub.length);
						continue;
					}
					if ($.trim(sub[sub.length-1])=="ipt"||$.trim(sub[sub.length-1])=="ipt.c") 
						continue;
					li+='<li class="list-group-item" myAttr="cl-file" onclick="showMenu(this)">'+$.trim(sub[sub.length-1])+'</li>';
					sub.splice(0,sub.length);
				}
				$("#cl-back").siblings().remove();
				$("#cl-back").after(li);
			});
		});
		
	});
	function enterDirectory(t){
		var text = t.innerText;
		var li='';
		$.get("../controller/homepage.php",{para:1,dirname:text},function(data,status){
			var data_obj = JSON.parse(data);
			var sub = new Array();
			for (var item in data_obj) {
				sub = data_obj[item].split(" ");
				if (sub[0][0]=='d') {
					li+='<li class="list-group-item" myAttr="cl-dr" style="color:#4169E1;" onclick="enterDirectory(this)">'+$.trim(sub[sub.length-1])+'</li>';
					sub.splice(0,sub.length);
					continue;
				}
				if ($.trim(sub[sub.length-1])=="ipt"||$.trim(sub[sub.length-1])=="ipt.c") 
					continue;
				li+='<li class="list-group-item" myAttr="cl-file" onclick="showMenu(this)">'+$.trim(sub[sub.length-1])+'</li>';
				sub.splice(0,sub.length);
			}
			$("#cl-back").siblings().remove();
			$("#cl-back").after(li);
		})
	}
	function showMenu(t){
		globleFilename = t.innerText;
		t.style.cssText = "color:#993399";
		$(".r-url").css("display","none");
		$(".r-makedirectory").css("display","none");
		$("#menu-button").css('display','block');
	}
</script>
</body>
</html>