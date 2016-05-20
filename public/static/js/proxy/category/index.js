$(function(){
    init_tree("categoryList");
    $(".function-button").click(function(){
        var tree = $("#categories").fancytree("getTree");
        var source = tree.toDict(true);
        addCategory2Market(source);
    });
});

function init_tree(url){
    $("#categories").fancytree({
        extensions: ["table", "dnd"],
        checkbox: true,
        strings:{
            loading:''
        },
        icon:false,
        imagePath:'http://www.iframe.com/static/images/',
        source: {
            url:url
        },
        table: {
            nodeColumnIdx: 0
        },
        dnd: {
            focusOnClick: true,
            preventVoidMoves: true,
            preventRecursiveMoves: true,
            preventvoidmoves:true,// Prevent dropping nodes 'before self', etc.
            preventrecursivemoves:true,// Prevent dropping nodes on own descendants
            autoExpandMS: 400,
            dragStart: function(node, data) { return true;},
            dragEnter: function(node, data) {return true;},
            dragDrop: function(node, data) {
                if('over' !== data.hitMode){
                    data.otherNode.moveTo(node, data.hitMode);
                }}
        },
    });
}

function addCategory2Market(source){
    $.ajax({
        url:"categoryList",
        type:'POST',
        dataType:'JSON',
        data:{
            source:source
        },
        success:function(data,err){
            if(data.code==0){
                form_success(data.data);
            }
            else{
                form_error(data.data)
            }
        },
        error:function(err){
            form_error()
        }
    });
}