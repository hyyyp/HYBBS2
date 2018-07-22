UM.registerUI('test', function( name) {
    //注册按钮执行时的command命令，使用命令默认就会带有回退操作
    var me = this;
    //创建一个button
    var $btn = $.eduibutton({
        icon : name,
        //按钮的名字
        name: name,
        //点击时执行的命令
        click : function(){
            me.execCommand(name);
            //alert('xxx');
        },
        title: name || ''
    });
    this.addListener('selectionchange',function(){
        var state = this.queryCommandState(name);
        $btn.edui().disabled(state == -1).active(state == 1)
    });
    
    //因为你是添加button,所以需要返回这个button
    return $btn;
});