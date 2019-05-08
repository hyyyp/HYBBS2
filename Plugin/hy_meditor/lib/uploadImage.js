
//添加按钮
HY_editor_addBtn({
	name:'image',
	type:'btn',
	tooltip:'上传图片',
	iconClass:'hy-icon hy-icon-image',
	onclick:function(_this){//点击按钮
		$file = $('<input multiple="multiple" style="filter: alpha(opacity=0);opacity: 0;visibility: hidden;display: none;" class="edui-image-file" type="file" hidefocus="" name="photo[]" accept="image/gif,image/jpeg,image/png,image/jpg,image/bmp">');

		//obj.$editor.append($file);
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
			        	
			        	var ImgHtml = '&nbsp;<span id="'+tmp_id+'" contenteditable="false" class="hy-editor-upimg-box"><img src="'+e.target.result+'"><span class="hy-editor-upimg-box-c"></span><span class="hy-editor-upimg-box-progress"></span><span class="hy-editor-upimg-box-progress-text"></span></span><br>&nbsp;';
			        	if(_this.execCommand('insertHTML',false,ImgHtml)){
			        		
				            
				            //setTimeout(function(){//防止浏览器效率跟不上
				            	
				            	var $span = $('#'+tmp_id);
				            	console.log($span);
				            	var $span_text = $span.children('.hy-editor-upimg-box-progress-text');
				            	var $span_progress = $span.children('.hy-editor-upimg-box-progress');

				            	var fd = new FormData();
						        fd.append("photo", file);
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
						        	console.log(evt);
						        	if(evt.target.status==200){
						        		var json = eval("("+evt.target.response+")");
						        		if(json.hasOwnProperty("msg") ){
								           if(json.success){
								           		$span.replaceWith('<img src="'+json.file_path+'">');
								            }
								            else{
								            	$span_text.text(json.msg);
								            }
								        }
								        _this.setTextareaVal();
						        	}else{

						        	}
						        }, false);
						        xhr.addEventListener("error", function(evt){
						        	console.log(evt);
						        	$span_text.text('传输时被中断');
						        }, false);
						        xhr.addEventListener("abort", function(evt){
						        	console.log(evt);
						        	$span_text.text('手动中断上传');
						        }, false);
						        xhr.onreadystatechange=function(){
						        	if (xhr.readyState==4){// 4 = "loaded"
										if (xhr.status==200){// 200 = OK
										}
										else{
											console.log(xhr);
											$span_text.text('上传失败返回：'+xhr.statusText+' '+xhr.status);
										}
									}
						        };
						        xhr.open("POST", _this.config.upload_image_path);
						        xhr.send(fd);
				            //},1000)

			        	}
			        };
			        reader.readAsDataURL(file);
		        }


				for(var i=0; i < $file[0].files.length;i++){

					var file = $file[0].files[i];
					//console.log(file.type);
					//是否是图片
			        if (!imageType.test(file.type)) {
			            alert("请选择图片！");
			            return;
			        }

			        previewImg(file);
			        
			        
				}
			}
			
		});
		
	}
});