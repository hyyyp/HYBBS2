var js = document.scripts;
var js_path = js[js.length - 1].src.substring(0, js[js.length - 1].src.lastIndexOf("/") + 1);
//插入网络视频
function editor_add_audio(obj){
	if($('#editor-audio-text').val() == '')
		return;
	$remove_img = $('<img onclick="del_now_audio(this)" title="移除图片" class="audio-list-remove" src="'+js_path+'close.png">');
	$li = $('<li><audio class="upload-audio-item" src="'+$('#editor-audio-text').val()+'" controls="controls">您的浏览器不支持 audio 标签。</audio></li>');
	$li.append($remove_img);
	$('#editor-audio-text').val('');
	$('#editor-audio-list2').append($li);
	if($(obj).parents('.hy-editor-modal-box').height()>=$(window).height()){
		$(obj).parents('.hy-editor-modal').css('align-items','flex-start');
	}else{
		$(obj).parents('.hy-editor-modal').css('align-items','center');
	}

}
//点击tab
function touch_audio_tab(obj){
	var _this = $(obj);
	 $("#hy-editor-audio-div>.hy-editor-tabs>li,#hy-editor-audio-div .hy-editor-tab-pane").removeClass("active"); 
	 $("#"+_this.data("id")).addClass("active");
	 _this.addClass("active");

	if(_this.parents('.hy-editor-modal-box').height()>=$(window).height()){
		_this.parents('.hy-editor-modal').css('align-items','flex-start');
	}else{
		_this.parents('.hy-editor-modal').css('align-items','center');
	}
}
//点击删除
function del_now_audio(obj){
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
function open_audio_upload_dialog(obj){
	var $ul = $(obj).parent();
	//console.log($ul);
	var _this = window.tmp_editor;
	var accept="";
	if(_this.config.upload_audio_suffix != undefined){
		for(var o in _this.config.upload_audio_suffix){
			accept+='audio/'+_this.config.upload_audio_suffix[o]+',';
		}
		accept = accept.substr(0, accept.length - 1);
	}
	$audio_add_li = $('.audio-add-li');
	$file = $('<input multiple="multiple" style="filter: alpha(opacity=0);opacity: 0;visibility: hidden;display: none;" type="file" hidefocus="" name="photo[]" accept="'+accept+'">');
	$file.click();
	$file.change(function(){
		if($file[0].files.length>0){
			var isPreviewaudio=false;
			if (window.FileReader) {//兼容性测试
	            isPreviewaudio=true;
	        }
	        var audioType = /^audio/; //判断上传的是否是图片
	        function previewaudio(file){
	        	var reader = new FileReader();
				//读取完成事件
		        reader.onload = function(e) {
		        	var tmp_id = _this.getTmpId();
		        	
		        	var audioHtml = '<span id="'+tmp_id+'" class="hy-editor-upaudio-box"><img src="'+js_path+'audio-uping.png" style="width:100%;height:100%"><span class="hy-editor-upaudio-box-c"></span><span class="hy-editor-upaudio-box-progress"></span><span class="hy-editor-upaudio-box-progress-text"></span></span>';
		        	$audio_add_li.before('<li>'+audioHtml+'</li>');
		        	
	            	var $span = $('#'+tmp_id);
	            	//console.log($span);
	            	var $span_text = $span.children('.hy-editor-upaudio-box-progress-text');
	            	var $span_progress = $span.children('.hy-editor-upaudio-box-progress');

	            	if(_this.config.upload_audio_maxsize<file.size){

	            		$span.prepend('<img onclick="del_now_audio(this)" title="移除图片" class="audio-list-remove" src="'+js_path+'close.png">');
			        	$span_text.text('文件大小超出系统限制');
			        	return;
	            	}

	            	var fd = new FormData();
			        fd.append(_this.config.upload_audio_input_name, file);

			        if(_this.config.upload_audio_argv != undefined){
			        	for(var o in _this.config.upload_audio_argv){
			        		fd.append(o, _this.config.upload_audio_argv[o]);
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
				           
				           		$audio=$('<audio class="upload-audio-item" src="'+json.file_path+'" controls="controls" >您的浏览器不支持 audio 标签。</audio>');
				           		$span.replaceWith($audio);
				           		//console.log($img);
				           		$audio.before('<img onclick="del_now_audio(this)" title="移除图片" class="audio-list-remove" src="'+js_path+'close.png">');
				            }
				            else{
				            	//$span.prepend('<img onclick="del_now_audio(this)" title="移除图片" class="audio-list-remove" src="'+js_path+'close.png">');
				            	$span_text.text(json.info);
				            }
					        
					        _this.setTextareaVal();
			        	}else{

			        	}
			        }, false);
			        xhr.addEventListener("error", function(evt){
			        	console.log(evt);
			        	$span.prepend('<img onclick="del_now_audio(this)" title="移除图片" class="audio-list-remove" src="'+js_path+'close.png">');
			        	$span_text.text('传输时被中断');
			        }, false);
			        xhr.addEventListener("abort", function(evt){
			        	console.log(evt);
			        	$span.prepend('<img onclick="del_now_audio(this)" title="移除图片" class="audio-list-remove" src="'+js_path+'close.png">');
			        	$span_text.text('手动中断上传');
			        }, false);
			        xhr.onreadystatechange=function(){
			        	if (xhr.readyState==4){// 4 = "loaded"
							if (xhr.status==200){// 200 = OK
							}
							else{
								console.log(xhr);
								$span.prepend('<img onclick="del_now_audio(this)" title="移除图片" class="audio-list-remove" src="'+js_path+'close.png">');
								$span_text.text('上传失败返回：'+xhr.statusText+' '+xhr.status);
							}
						}
			        };
			        xhr.open("POST", _this.config.upload_audio_path);
			        xhr.send(fd);
	            
		        };
		        reader.readAsDataURL(file);
	        }

			for(var i=0; i < $file[0].files.length;i++){
				var file = $file[0].files[i];
				//是否是图片
		        if (!audioType.test(file.type)) {
		            alert("请选择视频文件！");
		            return;
		        }
		        previewaudio(file);
			}
		}
		
	});
}
HY_editor_addBtn({
	name:'audio',
	type:'modal',
	tooltip:'插入音频',
	command:'',
	iconClass:'hy-icon hy-icon-music',
	modal:{
		title:'插入音频',
		body:'<form onsubmit="return false;">'+
		'<div id="hy-editor-audio-div">'+
		  '<ul class="hy-editor-tabs">'+
		    '<li onclick="touch_audio_tab(this)" data-id="tab-audio-local" class="active"><a href="javascript:;">上传本地音频</a></li>'+
		    '<li onclick="touch_audio_tab(this)" data-id="tab-audio-url"><a href="javascript:;">网络音频地址</a></li>'+
		  '</ul>'+
		  '<div class="tab-content">'+
		    '<div id="tab-audio-local" class="hy-editor-tab-pane active" id="home">'+
		    	'<ul class="audio-list-ul clearfix" id="editor-audio-list1">'+
		    		'<li onclick="open_audio_upload_dialog(this)" class="audio-add-li"><img src="'+js_path+'audio-add.png"></li>'+
		    	'</ul>'+
		    '</div>'+
		    '<div id="tab-audio-url" class="hy-editor-tab-pane" id="profile">'+
			    '<div class="form-group">'+
			    	'<label for="">网络音频地址</label>'+
			    	'<input id="editor-audio-text" type="text" class="editor-text" placeholder="填入音频地址">'+
			    '</div>'+
			    '<div class="form-group">'+
			    	'<button type="button" class="hy-editor-modal-btn" onclick="editor_add_audio(this)">添加</button>'+
			    '</div>'+
			    '<ul class="audio-list-ul clearfix" id="editor-audio-list2">'+
			    	
			    	
			    '</ul>'+
		    '</div>'+
		  '</div>'+
		'</div>'+
		'</form>'+
		'<script>'+
			    
		'</script>'+
		'<style>'+
'.audio-list-ul{}.audio-list-ul audio{width:200px;height:100px;max-height: 100%;max-width: 100%;}.audio-list-ul>li{width:200px;height:100px;margin-bottom:5px;border: 1px solid #CCC;float:left;margin-right:5px;list-style: none;position: relative;}.audio-list-remove{position: absolute;width: 25px!important;height: 25px!important;right: 0;    cursor: pointer;    z-index: 1;}'+
'.audio-add-li{cursor: pointer;background: #fbfbfb;display: flex;justify-content: center;align-items: center;width: 100px!important;height: 100px;}.audio-add-li img{width:30px;height:30px}'+
'.hy-editor-upaudio-box{position:relative;display:inline-block;width:200px;height:100px}'+
'.hy-editor-upaudio-box audio{width:200px;height:100px}'+
'.hy-editor-upaudio-box-c{width:200px;height:100px;position:absolute;left:0;top:0;opacity:.8;border:solid 1px #b5b5b5;background-color:#fff}'+
'.hy-editor-upaudio-box-progress{position:absolute;left:0;bottom:0;background-color:#2e8b57;height:2px;width:0%}'+
'.hy-editor-upaudio-box-progress-text{position:absolute;left:1px;bottom:2px;right:1px;text-align:center;background:#eaeaea;opacity:.8;font-size:12px;color:#2e8b57}'+
		'</style>'
		,
		onshow:function(_this,$modal) {
			window.tmp_editor=_this;
			window.tmp_modal = $modal;
			$('body').css('overflow-y','hidden');
			
		},
		onok:function(_this){
			$('#hy-editor-audio-div>.tab-content .upload-audio-item').each(function(){
				var src = $(this).attr('src');
				_this.execCommand('insertHTML',false,'<p><audio src="'+src+'" controls="controls" >您的浏览器不支持 audio 标签。</audio></p><p><br></p>');
			});
			$('#editor-audio-list1>li,#editor-audio-list2>li').each(function(){
				if(!$(this).hasClass('audio-add-li'))
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