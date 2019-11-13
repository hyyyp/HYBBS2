//添加按钮
window.HY_editor_config.initFun.scale = function(_this){
	_this.$scale = $('<div class="hy-editor-upimg" unselectable="on">' +
        '<span class="hy-editor-upimg-0" ondragstart="return false" style="display:none"></span>' +
        '<span class="hy-editor-upimg-1" ondragstart="return false" style="display:none"></span>' +
        '<span class="hy-editor-upimg-2" ondragstart="return false" style="display:none"></span>' +
        '<span class="hy-editor-upimg-3" ondragstart="return false" style="display:none"></span>' +
        '<span class="hy-editor-upimg-4" ondragstart="return false"></span>' +
        '<span class="hy-editor-upimg-5" ondragstart="return false" style="display:none"></span>' +
        '<span class="hy-editor-upimg-6" ondragstart="return false"></span>' +
        '<span class="hy-editor-upimg-7" ondragstart="return false"></span>' +
        '</div>');
	var nowSize = {width: 0, height: 0};
	var startPos =  {x: 0, y: 0};
	var domMove,domUp=null;
	_this.$scale.children('span').mousedown(function(e){
		startPos.x = e.clientX;
		startPos.y = e.clientY;
		nowSize.width = _this.$scale.width();
		nowSize.height = _this.$scale.height();
		//console.log('开始：'+startPos.x);
        var Index=$(this)[0].className.slice(-1);
        $(document).bind('mousemove',domMove = function(event){
        	//console.log(event);
        	//console.log(event.clientX,event.pageX,event.screenX);
			
            var rect = [
                //[ width, height]
                [-1, -1],
                [0, -1], //1
                [1, -1],
                [-1, 0], //3
                [1, 0],
                [-1, 1], //5 #
                [0, 1], //6
                [1, 1]
            ];
            //console.log(rect[Index]);
            if(rect[Index][0] != 0){
            	tmp_width = nowSize.width + (  event.clientX - startPos.x);
            	window.HY_editor.$nowClickImg.css({
            		width:tmp_width,
            		height:nowSize.height
            	});
            	_this.$scale.css({
    				width:tmp_width
    			})

            }
            if(rect[Index][1] != 0){
            	tmp_height = nowSize.height + (  event.clientY - startPos.y);
            	
            	window.HY_editor.$nowClickImg.css({
            		height:tmp_height
            	})
            	_this.$scale.css({
    				height:tmp_height

    			})
            }

        });
        $(document).bind('mouseup',domUp = function(event){
        	//console.log('释放');
        	$(document).unbind('mousemove',domMove);
        	$(document).unbind('mouseup',domUp);
        });
		
	});
	

	_this.$editor.append(_this.$scale);
	
}
window.HY_editor_config.containerKeyup.scale = function(_this){
	//编辑器其他动作 隐藏
	_this.$scale.hide();
}

if(navigator.userAgent.indexOf("Firefox") == -1){
	window.HY_editor_config.containerClick.scale =function(_this,e){
		if(e.target.nodeName.toLowerCase() == 'img'){
			window.HY_editor.clickImg = true;
			window.HY_editor.$nowClickImg = $(e.target);
			var img_obj = window.HY_editor.$nowClickImg;
			var position = window.HY_editor.$nowClickImg.position();
			_this.$scale.css({
				left:position.left,
				top:position.top,
				width:img_obj.width(),
				height:img_obj.height(),

			}).show();
		}
		
	}
	window.HY_editor_config.updateEditorFun.scale = function(_this){
		//编辑器其他动作 隐藏
		if(window.HY_editor.clickImg!==true){
			_this.$scale.hide();
		}
		window.HY_editor.clickImg=false;
		_this.$container.children('span').each(function(){
			if($(this)[0].className.indexOf('edui') != -1)
				$(this).remove();
		})
			
		
	}
}

var js = document.scripts;
var js_path = js[js.length - 1].src.substring(0, js[js.length - 1].src.lastIndexOf("/") + 1);
if(js_path=='')
	js_path = '/Plugin/hy_meditor/lib/';
//插入网络图片
function editor_add_image(obj){
	if($('#editor-image-text').val() == '')
		return;
	$remove_img = $('<img onclick="del_now_image(this)" title="移除图片" class="image-list-remove" src="'+js_path+'close.png">');
	$li = $('<li><img class="upload-image-item" src="'+$('#editor-image-text').val()+'"></li>');
	$li.append($remove_img);
	$('#editor-image-text').val('');
	$('#editor-image-list2').append($li);
	if($(obj).parents('.hy-editor-modal-box').height()>=$(window).height()){
		$(obj).parents('.hy-editor-modal').css('align-items','flex-start');
	}else{
		$(obj).parents('.hy-editor-modal').css('align-items','center');
	}

}
//点击tab
function touchtab(obj){
	var _this = $(obj);
	 $("#hy-editor-image2-div>.hy-editor-tabs>li,#hy-editor-image2-div .hy-editor-tab-pane").removeClass("active"); 
	 $("#"+_this.data("id")).addClass("active");
	 _this.addClass("active");

	if(_this.parents('.hy-editor-modal-box').height()>=$(window).height()){
		_this.parents('.hy-editor-modal').css('align-items','flex-start');
	}else{
		_this.parents('.hy-editor-modal').css('align-items','center');
	}
}
//点击删除图片
function del_now_image(obj){
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
function open_upload_dialog(obj){
	var $ul = $(obj).parent();
	//console.log($ul);
	var _this = window.tmp_editor;
	var accept="";
	if(_this.config.upload_image_suffix != undefined){
		for(var o in _this.config.upload_image_suffix){
			accept+='image/'+_this.config.upload_image_suffix[o]+',';
		}
		accept = accept.substr(0, accept.length - 1);
		//console.log(accept);
	}
	$image_add_li = $('.image-add-li');
	$file = $('<input multiple="multiple" style="filter: alpha(opacity=0);opacity: 0;visibility: hidden;display: none;" class="edui-image-file" type="file" hidefocus="" name="photo[]" accept="'+accept+'">');
	$file.click();
	$file.change(function(){
		if($file[0].files.length>0){
			var isPreviewImg=false;
			if (window.FileReader) {//兼容性测试
	            isPreviewImg=true;
	        }
	        var imageType = /^image/; //判断上传的是否是图片
	        //console.log($file[0].files);
	        function previewImg(file){
	        	var reader = new FileReader();
				//读取完成事件
		        reader.onload = function(e) {
		        	var tmp_id = _this.getTmpId();
		        	
		        	var ImgHtml = '<span id="'+tmp_id+'" contenteditable="false" class="hy-editor-upimg-box"><img src="'+e.target.result+'"><span class="hy-editor-upimg-box-c"></span><span class="hy-editor-upimg-box-progress"></span><span class="hy-editor-upimg-box-progress-text"></span></span>';
		        	$image_add_li.before('<li>'+ImgHtml+'</li>');
		        	//if(_this.execCommand('insertHTML',false,ImgHtml)){
			            //setTimeout(function(){//防止浏览器效率跟不上
			            	var $span = $('#'+tmp_id);
			            	//console.log($span);
			            	var $span_text = $span.children('.hy-editor-upimg-box-progress-text');
			            	var $span_progress = $span.children('.hy-editor-upimg-box-progress');

			            	if(_this.config.upload_image_maxsize<file.size){

			            		$span.prepend('<img onclick="del_now_image(this)" title="移除图片" class="image-list-remove" src="'+js_path+'close.png">');
					        	$span_text.text('文件大小超出系统限制');
					        	return;
			            	}

			            	var fd = new FormData();
					        fd.append(_this.config.upload_image_input_name, file);

					        if(_this.config.upload_image_argv != undefined){
					        	for(var o in _this.config.upload_image_argv){
					        		fd.append(o, _this.config.upload_image_argv[o]);
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
					        		if(json.hasOwnProperty("msg") ){
							           if(json.success){
							           		$img=$('<img class="upload-image-item" _moz_resizing="false" src="'+json.file_path+'">');
							           		$span.replaceWith($img);
							           		//console.log($img);
							           		$img.before('<img onclick="del_now_image(this)" title="移除图片" class="image-list-remove" src="'+js_path+'close.png">');
							            }
							            else{
							            	$span.prepend('<img onclick="del_now_image(this)" title="移除图片" class="image-list-remove" src="'+js_path+'close.png">');
							            	$span_text.text(json.msg);
							            }
							        }
							        _this.setTextareaVal();
					        	}else{

					        	}
					        }, false);
					        xhr.addEventListener("error", function(evt){
					        	console.log(evt);
					        	$span.prepend('<img onclick="del_now_image(this)" title="移除图片" class="image-list-remove" src="'+js_path+'close.png">');
					        	$span_text.text('传输时被中断');
					        }, false);
					        xhr.addEventListener("abort", function(evt){
					        	console.log(evt);
					        	$span.prepend('<img onclick="del_now_image(this)" title="移除图片" class="image-list-remove" src="'+js_path+'close.png">');
					        	$span_text.text('手动中断上传');
					        }, false);
					        xhr.onreadystatechange=function(){
					        	if (xhr.readyState==4){// 4 = "loaded"
									if (xhr.status==200){// 200 = OK
									}
									else{
										console.log(xhr);
										$span.prepend('<img onclick="del_now_image(this)" title="移除图片" class="image-list-remove" src="'+js_path+'close.png">');
										$span_text.text('上传失败返回：'+xhr.statusText+' '+xhr.status);
									}
								}
					        };
					        xhr.open("POST", _this.config.upload_image_path);
					        xhr.send(fd);
			            //},1000)

		        	//}
		        };
		        reader.readAsDataURL(file);
	        }

			for(var i=0; i < $file[0].files.length;i++){
				var file = $file[0].files[i];
				//是否是图片
				//console.log(file);
		        if (!imageType.test(file.type)) {
		            alert("请选择图片！");
		            return;
		        }
		        previewImg(file);
			}
		}
		
	});
}
HY_editor_addBtn({
	name:'image2',
	type:'modal',
	tooltip:'插入图片',
	command:'',
	iconClass:'hy-icon hy-icon-image',
	modal:{
		title:'插入图片',
		body:'<form onsubmit="return false;">'+
		'<div id="hy-editor-image2-div">'+
		  '<ul class="hy-editor-tabs">'+
		    '<li onclick="touchtab(this)" data-id="tab-local" class="active"><a href="javascript:;">上传本地图片</a></li>'+
		    '<li onclick="touchtab(this)" data-id="tab-url"><a href="javascript:;">网络图片</a></li>'+
		  '</ul>'+
		  '<div class="tab-content">'+
		    '<div id="tab-local" class="hy-editor-tab-pane active" id="home">'+
		    	'<ul class="image-list-ul clearfix" id="editor-image-list1">'+
		    		'<li onclick="open_upload_dialog(this)" class="image-add-li"><img src="'+js_path+'image-add.png"></li>'+
		    	'</ul>'+
		    '</div>'+
		    '<div id="tab-url" class="hy-editor-tab-pane" id="profile">'+
			    '<div class="form-group">'+
			    	'<label for="">网络图片地址</label>'+
			    	'<input id="editor-image-text" type="text" class="editor-text" placeholder="填入图片地址">'+
			    '</div>'+
			    '<div class="form-group">'+
			    	'<button type="button" class="hy-editor-modal-btn" onclick="editor_add_image(this)">添加</button>'+
			    '</div>'+
			    '<ul class="image-list-ul clearfix" id="editor-image-list2">'+
			    	
			    	
			    '</ul>'+
		    '</div>'+
		  '</div>'+
		'</div>'+
		'</form>'+
		'<script>'+
			    
		'</script>'+
		'<style>'+
'.image-list-ul{}.image-list-ul img{width:100px;height:100px;max-height: 100%;max-width: 100%;}.image-list-ul>li{width:100px;height:100px;margin-bottom:5px;border: 1px solid #CCC;float:left;margin-right:5px;list-style: none;position: relative;}.image-list-remove{position: absolute;width: 25px!important;height: 25px!important;right: 0;    cursor: pointer;    z-index: 1;}'+
'.image-add-li{cursor: pointer;background: #fbfbfb;display: flex;justify-content: center;align-items: center;width: 100px;height: 100px;}.image-add-li img{width:30px;height:30px}'+
'.hy-editor-upimg{display:none;position:absolute;border:1px solid #000;cursor:hand}'+
'.hy-editor-upimg span{position:absolute;left:0;top:0;width:7px;height:7px;overflow:hidden;font-size:0;display:block;background-color:#fff;border:solid 1px #000}'+
'.hy-editor-upimg .hy-editor-upimg-0{cursor:nw-resize;top:0;margin-top:-4px;left:0;margin-left:-4px}'+
'.hy-editor-upimg .hy-editor-upimg-1{cursor:n-resize;top:0;margin-top:-4px;left:50%;margin-left:-4px}'+
'.hy-editor-upimg .hy-editor-upimg-2{cursor:ne-resize;top:0;margin-top:-4px;left:100%;margin-left:-3px}'+
'.hy-editor-upimg .hy-editor-upimg-3{cursor:w-resize;top:50%;margin-top:-4px;left:0;margin-left:-4px}'+
'.hy-editor-upimg .hy-editor-upimg-4{cursor:e-resize;top:50%;margin-top:-4px;left:100%;margin-left:-3px}'+
'.hy-editor-upimg .hy-editor-upimg-5{cursor:sw-resize;top:100%;margin-top:-3px;left:0;margin-left:-4px}'+
'.hy-editor-upimg .hy-editor-upimg-6{cursor:s-resize;top:100%;margin-top:-3px;left:50%;margin-left:-4px}'+
'.hy-editor-upimg .hy-editor-upimg-7{cursor:se-resize;top:100%;margin-top:-3px;left:100%;margin-left:-3px}'+
'.hy-editor-upimg-box{position:relative;display:inline-block;width:100px;height:100px}'+
'.hy-editor-upimg-box img{width:100px;height:100px}'+
'.hy-editor-upimg-box-c{width:100px;height:100px;position:absolute;left:0;top:0;opacity:.8;border:solid 1px #b5b5b5;background-color:#fff}'+
'.hy-editor-upimg-box-progress{position:absolute;left:0;bottom:0;background-color:#2e8b57;height:2px;width:0%}'+
'.hy-editor-upimg-box-progress-text{position:absolute;left:1px;bottom:2px;right:1px;text-align:center;background:#eaeaea;opacity:.8;font-size:12px;color:#2e8b57}'+
		'</style>'
		,
		onshow:function(_this,$modal) {
			window.tmp_editor=_this;
			window.tmp_modal = $modal;
			$('body').css('overflow-y','hidden');
			
		},
		onok:function(_this){
			$('#hy-editor-image2-div>.tab-content .upload-image-item').each(function(){
				var imgsrc = $(this).attr('src');
				_this.execCommand('insertHTML',false,'<img _moz_resizing="false" src="'+imgsrc+'">');
			});
			$('#editor-image-list1>li,#editor-image-list2>li').each(function(){
				if(!$(this).hasClass('image-add-li'))
					$(this).remove();
			});
			$('body').css('overflow-y','auto');
		},
		onclose:function(_this){
			$('body').css('overflow-y','auto');
		},
		btn:{
			close:'取消',
			ok:'插入图片',
		}
	},

});