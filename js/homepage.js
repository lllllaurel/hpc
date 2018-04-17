$(document).ready(function(){
	$("#cat").click(function(){
		if (!judgeType()) {
			alert("该文件不是文本文件类型!");
			return false;
		}else{
			$.get("../controller/homepage.php",{para:3,filename:globleFilename},function(data,status){
				$(".mid-console>span").text(data);
			})	
		}
	});
	$("#upload").click(function(){
		$("#menu-button").css('display','none');
		$(".r-makedirectory").css("display","none");
		$(".r-url").css("display","block");
	});
	// $(".r-url>button").click(function(){
	// 	var url = $(".r-url>input").val();
	// 	$.get("../controller/homepage.php",{para:4,url:url},function(data,status){
	// 		$(".mid-console>span").text(data);
	// 	})
	// })
	$("#unzip").click(function(){
		var filename = globleFilename;
		var suffix_a = filename.split(".");
		var suffix = suffix_a[suffix_a.length-1];
		var types = new Array("zip","rar","tar","gz","bz2","z");
		for (var i = types.length - 1; i >= 0; i--) {
			if(types[i]==suffix.toLowerCase()){
				if (types[i]=="rar") {
					$(".mid-console>span").text("该服务器上没有相应的解压程序!");
				}else{
					$.get("../controller/homepage.php",{filename:filename,para:5,suffix:types[i]},function(data,status){
						if (status=="success") {
							$(".mid-console>span").text("解压成功!");
							refresh(data);
						}else{
							$(".mid-console>span").text("解压失败!");
						}
					})
				}
				return false;
			}
		}
		$(".mid-console>span").text("该文件不是压缩文件！");
	});
	$("#delete").click(function(){
		var filename = globleFilename;
		$.get("../controller/homepage.php",{filename:filename,para:6},function(data,status){
			if (status!="success") {
				$(".mid-console>span").text("通信错误！");
				return false;
			};
			if (data=="false") {
				$(".mid-console>span").text("删除失败");
			}else{
				// var li='';
				// var data_obj = JSON.parse(data);
				// var sub = new Array();
				// for (var item in data_obj) {
				// 	sub = data_obj[item].split(" ");
				// 	if (sub[0][0]=='d') {
				// 		li+='<li class="list-group-item" myAttr="cl-dr" style="color:#4169E1;" onclick="enterDirectory(this)">'+$.trim(sub[sub.length-1])+'</li>';
				// 		sub.splice(0,sub.length);
				// 		continue;
				// 	}
				// 	li+='<li class="list-group-item" myAttr="cl-file" onclick="showMenu(this)">'+$.trim(sub[sub.length-1])+'</li>';
				// 	sub.splice(0,sub.length);
				// }
				// $("#cl-back").siblings().remove();
				// $("#cl-back").after(li);
				refresh(data);
			}
		});
	});
	$("#makedir").click(function(){
		$(".r-url").css("display","none");
		$("#menu-button").css('display','none');
		$(".r-makedirectory").css("display","block");
	});
	$("#createdir").click(function(){
		var dirname = $(".r-makedirectory>input").val();
		$.get("../controller/homepage.php",{para:7,dirname:dirname},function(data,status){
			$(".mid-console>span").text("create!");
			refresh(data);
		})
	});
})

function judgeType(){
	var types = new Array("gzip","zip","7z","rar","tar","cab","gzip","arj","lzh",
		"ace","uue","bz2","jar","iso","z","mp4","mp3","jpg","png");
	var filename = globleFilename;
	var suffix_a = filename.split(".");
	suffix = suffix_a[suffix_a.length-1];
	for (var i = types.length - 1; i >= 0; i--) {
		if(types[i]==suffix.toLowerCase()){
			return false;
		}
	}
	return true;
};

function refresh(data){
	var li='';
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
}