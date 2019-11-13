var js = document.scripts;
var js_path = js[js.length - 1].src.substring(0, js[js.length - 1].src.lastIndexOf("/") + 1);
//插入网络视频
function editor_add_video(obj){
	if($('#editor-video-text').val() == '')
		return;
	$remove_img = $('<img onclick="del_now_video(this)" title="移除图片" class="video-list-remove" src="'+js_path+'close.png">');
	$li = $('<li><video class="upload-video-item" src="'+$('#editor-video-text').val()+'" controls="controls">您的浏览器不支持 video 标签。</video></li>');
	$li.append($remove_img);
	$('#editor-video-text').val('');
	$('#editor-video-list2').append($li);
	if($(obj).parents('.hy-editor-modal-box').height()>=$(window).height()){
		$(obj).parents('.hy-editor-modal').css('align-items','flex-start');
	}else{
		$(obj).parents('.hy-editor-modal').css('align-items','center');
	}

}
//点击tab
function touch_video_tab(obj){
	var _this = $(obj);
	 $("#hy-editor-video-div>.hy-editor-tabs>li,#hy-editor-video-div .hy-editor-tab-pane").removeClass("active"); 
	 $("#"+_this.data("id")).addClass("active");
	 _this.addClass("active");

	if(_this.parents('.hy-editor-modal-box').height()>=$(window).height()){
		_this.parents('.hy-editor-modal').css('align-items','flex-start');
	}else{
		_this.parents('.hy-editor-modal').css('align-items','center');
	}
}
//点击删除
function del_now_video(obj){
	var _this = $(obj);
	var $ul = _this.parents('ul');
	_this.parents('li').remove();
	if($ul.parents('.hy-editor-modal-box').height()>=$(window).height()){
		$ul.parents('.hy-editor-modal').css('align-items','flex-start');
	}else{
		$ul.parents('.hy-editor-modal').css('align-items','center');
	}
}
//打开本地上传图片
function open_video_upload_dialog(obj){
	var $ul = $(obj).parent();
	//console.log($ul);
	var _this = window.tmp_editor;
	var accept="";
	if(_this.config.upload_video_suffix != undefined){
		for(var o in _this.config.upload_video_suffix){
			accept+='video/'+_this.config.upload_video_suffix[o]+',';
		}
		accept = accept.substr(0, accept.length - 1);
	}
	$video_add_li = $('.video-add-li');
	$file = $('<input multiple="multiple" style="filter: alpha(opacity=0);opacity: 0;visibility: hidden;display: none;" type="file" hidefocus="" name="photo[]" accept="'+accept+'">');
	$file.click();
	$file.change(function(){
		if($file[0].files.length>0){
			var isPreviewVideo=false;
			if (window.FileReader) {//兼容性测试
	            isPreviewVideo=true;
	        }
	        var videoType = /^video/; //判断上传的是否是图片
	        function previewVideo(file){
	        	var reader = new FileReader();
				//读取完成事件
		        reader.onload = function(e) {
		        	var tmp_id = _this.getTmpId();
		        	
		        	var videoHtml = '<span id="'+tmp_id+'" class="hy-editor-upvideo-box"><img src="'+js_path+'video-uping.png" style="width:100%;height:100%"><span class="hy-editor-upvideo-box-c"></span><span class="hy-editor-upvideo-box-progress"></span><span class="hy-editor-upvideo-box-progress-text"></span></span>';
		        	$video_add_li.before('<li>'+videoHtml+'</li>');
		        	
	            	var $span = $('#'+tmp_id);
	            	//console.log($span);
	            	var $span_text = $span.children('.hy-editor-upvideo-box-progress-text');
	            	var $span_progress = $span.children('.hy-editor-upvideo-box-progress');

	            	if(_this.config.upload_video_maxsize<file.size){

	            		$span.prepend('<img onclick="del_now_video(this)" title="移除图片" class="video-list-remove" src="'+js_path+'close.png">');
			        	$span_text.text('文件大小超出系统限制');
			        	return;
	            	}

	            	var fd = new FormData();
			        fd.append(_this.config.upload_video_input_name, file);

			        if(_this.config.upload_video_argv != undefined){
			        	for(var o in _this.config.upload_video_argv){
			        		fd.append(o, _this.config.upload_video_argv[o]);
			        	}
			        }

			        var xhr = new XMLHttpRequest();
			        xhr.upload.addEventListener("progress", function(evt){
			        	if (evt.lengthComputable) {
				        	var percentComplete = Math.round(evt.loaded * 100 / evt.total);
				        	$span_progress.css('width',percentComplete.toString()+ '%')  ;
				        	$span_text.text('上传进度：'+percentComplete.toString()+ '%');
				        }
				        else{
				        	$span_text.text('进度获取失败');
				        }
			        }, false);
			        xhr.addEventListener("load", function(evt){
			        	//console.log(evt);
			        	if(evt.target.status==200){
			        		var json = eval("("+evt.target.response+")");

				           	if(json.error){
				           
				           		$video=$('<video class="upload-video-item" src="'+json.file_path+'" controls="controls" >您的浏览器不支持 video 标签。</video>');
				           		$span.replaceWith($video);
				           		//console.log($img);
				           		$video.before('<img onclick="del_now_video(this)" title="移除图片" class="video-list-remove" src="'+js_path+'close.png">');
				            }
				            else{
				            	//$span.prepend('<img onclick="del_now_video(this)" title="移除图片" class="video-list-remove" src="'+js_path+'close.png">');
				            	$span_text.text(json.info);
				            }
					        
					        _this.setTextareaVal();
			        	}else{

			        	}
			        }, false);
			        xhr.addEventListener("error", function(evt){
			        	console.log(evt);
			        	$span.prepend('<img onclick="del_now_video(this)" title="移除图片" class="video-list-remove" src="'+js_path+'close.png">');
			        	$span_text.text('传输时被中断');
			        }, false);
			        xhr.addEventListener("abort", function(evt){
			        	console.log(evt);
			        	$span.prepend('<img onclick="del_now_video(this)" title="移除图片" class="video-list-remove" src="'+js_path+'close.png">');
			        	$span_text.text('手动中断上传');
			        }, false);
			        xhr.onreadystatechange=function(){
			        	if (xhr.readyState==4){// 4 = "loaded"
							if (xhr.status==200){// 200 = OK
							}
							else{
								console.log(xhr);
								$span.prepend('<img onclick="del_now_video(this)" title="移除图片" class="video-list-remove" src="'+js_path+'close.png">');
								$span_text.text('上传失败返回：'+xhr.statusText+' '+xhr.status);
							}
						}
			        };
			        xhr.open("POST", _this.config.upload_video_path);
			        xhr.send(fd);
	            
		        };
		        reader.readAsDataURL(file);
	        }

			for(var i=0; i < $file[0].files.length;i++){
				var file = $file[0].files[i];
				//是否是图片
		        if (!videoType.test(file.type)) {
		            alert("请选择视频文件！");
		            return;
		        }
		        previewVideo(file);
			}
		}
		
	});
}
HY_editor_addBtn({
	name:'video',
	type:'modal',
	tooltip:'插入视频',
	command:'',
	iconClass:'hy-icon hy-icon-video',
	modal:{
		title:'插入视频',
		body:'<form onsubmit="return false;">'+
		'<div id="hy-editor-video-div">'+
		  '<ul class="hy-editor-tabs">'+
		    '<li onclick="touch_video_tab(this)" data-id="tab-video-local" class="active"><a href="javascript:;">上传本地视频</a></li>'+
		    '<li onclick="touch_video_tab(this)" data-id="tab-video-url"><a href="javascript:;">网络视频地址</a></li>'+
		  '</ul>'+
		  '<div class="tab-content">'+
		    '<div id="tab-video-local" class="hy-editor-tab-pane active" id="home">'+
		    	'<ul class="video-list-ul clearfix" id="editor-video-list1">'+
		    		'<li onclick="open_video_upload_dialog(this)" class="video-add-li"><img src="'+js_path+'video-add.png"></li>'+
		    	'</ul>'+
		    '</div>'+
		    '<div id="tab-video-url" class="hy-editor-tab-pane" id="profile">'+
			    '<div class="form-group">'+
			    	'<label for="">网络视频地址</label>'+
			    	'<input id="editor-video-text" type="text" class="editor-text" placeholder="填入视频地址">'+
			    '</div>'+
			    '<div class="form-group">'+
			    	'<button type="button" class="hy-editor-modal-btn" onclick="editor_add_video(this)">添加</button>'+
			    '</div>'+
			    '<ul class="video-list-ul clearfix" id="editor-video-list2">'+
			    	
			    	
			    '</ul>'+
		    '</div>'+
		  '</div>'+
		'</div>'+
		'</form>'+
		'<script>'+
			    
		'</script>'+
		'<style>'+
'.video-list-ul{}.video-list-ul video{width:200px;height:100px;max-height: 100%;max-width: 100%;}.video-list-ul>li{width:200px;height:100px;margin-bottom:5px;border: 1px solid #CCC;float:left;margin-right:5px;list-style: none;position: relative;}.video-list-remove{position: absolute;width: 25px!important;height: 25px!important;right: 0;    cursor: pointer;    z-index: 1;}'+
'.video-add-li{cursor: pointer;background: #fbfbfb;display: flex;justify-content: center;align-items: center;width: 100px!important;height: 100px;}.video-add-li img{width:30px;height:30px}'+
'.hy-editor-upvideo-box{position:relative;display:inline-block;width:200px;height:100px}'+
'.hy-editor-upvideo-box video{width:200px;height:100px}'+
'.hy-editor-upvideo-box-c{width:200px;height:100px;position:absolute;left:0;top:0;opacity:.8;border:solid 1px #b5b5b5;background-color:#fff}'+
'.hy-editor-upvideo-box-progress{position:absolute;left:0;bottom:0;background-color:#2e8b57;height:2px;width:0%}'+
'.hy-editor-upvideo-box-progress-text{position:absolute;left:1px;bottom:2px;right:1px;text-align:center;background:#eaeaea;opacity:.8;font-size:12px;color:#2e8b57}'+
		'</style>'
		,
		onshow:function(_this,$modal) {
			window.tmp_editor=_this;
			window.tmp_modal = $modal;
			$('body').css('overflow-y','hidden');
			
		},
		onok:function(_this){
			$('#hy-editor-video-div>.tab-content .upload-video-item').each(function(){
				var src = $(this).attr('src');
				_this.execCommand('insertHTML',false,'<p><video src="'+src+'" controls="controls" >您的浏览器不支持 video 标签。</video></p><p><br></p>');
			});
			$('#editor-video-list1>li,#editor-video-list2>li').each(function(){
				if(!$(this).hasClass('video-add-li'))
					$(this).remove();
			});
			$('body').css('overflow-y','auto');
		},
		onclose:function(_this){
			$('body').css('overflow-y','auto');
		},
		btn:{
			close:'取消',
			ok:'插入视频',
		}
	},

});