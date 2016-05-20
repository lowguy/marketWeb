/**
 * Created by LegendFox on 2016/2/24.
 */
$(function(){
    init_fancytree();
});
function init_fancytree(){
    $(".categories-table").fancytree({
        extensions: ["table"],
        strings:{loading:''},
        icon:false,
        debugLevel:0,
        minExpandLevel:1,
        activeVisible:false,
        autoCollapse:false,
        source: {url:"categoryTree"},
        table: {nodeColumnIdx: 1},
        renderColumns: function(event, data) {
            var node = data.node,
                $tdList = $(node.tr).find(">td");
            var html = '<a href="edit?id='+ node['data']['data-id'] +'" class="function-button">编辑</a>';
            $tdList.eq(2).html(html);
        }
    });
}