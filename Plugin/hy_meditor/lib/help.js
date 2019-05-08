//添加按钮
HY_editor_addBtn({
	name:'help',
	type:'modal',
	tooltip:'关于',
	command:'',
	iconClass:'hy-icon hy-icon-help',
	modal:{
		title:'关于本编辑器',
		body:'<form onsubmit="return false;">'+
		'<p>本编辑器来自于 hyphp.cn 开发<p>'+
		'</form>',
		onshow:function(_this) {
			
			
		},
		onok:function(_this){
			
		},
		onclose:function(_this){
			
		},
		btn:{
			close:'关闭',
			ok:'还是关闭',
		}
	},

});