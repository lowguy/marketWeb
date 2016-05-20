/**
 * Created by Administrator on 2016/3/1 0001.
 */
var map;
var global_boundary;//BMap.Polygon对象
var global_free_area;//BMap.Polygon对象
var global_marker;//BMap.Marker对象

var global_dragging = false;

function str_to_points(boundaries){
    var arr = boundaries.split(';');
    var result = [];
    for(var i = 0; i < arr.length; i++){
        var tmp = arr[i].split(',');
        var x = parseFloat(tmp[0]);
        var y = parseFloat(tmp[1]);
        var point = new BMap.Point(x, y);
        result.push(point);
    }
    return result;
}

function show_boundary(boundaries){
    var points = str_to_points(boundaries);
    global_boundary.setPath(points);
    map.setViewport(points);
    show_free_area();
}




function show_free_area(){
    var path = $('input[name="area"]').val();
    global_free_area.setPath(str_to_points(path))
}


function init_map(){
    map = new BMap.Map('map', {enableMapClick:false,enableAutoResize:false});
    map.centerAndZoom('西安市', 12);
    map.enableScrollWheelZoom(true);
    map.disableDragging();
    global_boundary = new BMap.Polygon();
    global_boundary.setStrokeColor('#FF0000');
    global_boundary.setFillColor('');
    global_boundary.setStrokeWeight(3);
    global_boundary.setStrokeStyle('dashed');

    global_free_area = new BMap.Polygon();
    global_free_area.setStrokeColor('#00FF00');
    global_free_area.setFillColor('#00FF00');
    global_free_area.setStrokeWeight(1);

    map.addOverlay(global_boundary);
    map.addOverlay(global_free_area);

    global_marker = new BMap.Marker(new BMap.Point(0,0));

    map.addOverlay(global_marker);

    open_map();
}


function open_map(){
    var area = $('input[name="city"]').val() + $('input[name="district"]').val();
    var boundary = new BMap.Boundary();
    boundary.get(area, function(result){
        if(result.boundaries.length){
            var boundaries = result.boundaries.join(';');
            setTimeout(function(){
                show_boundary(boundaries);
            }, 100);
        }
    });
    if($("input[name='address']").val()){
        mark($("input[name='address']").val());
    }

}
function mark(title){
    var myGeo = new BMap.Geocoder();
    myGeo.getPoint(title, function(point){
        if (point) {
            map.centerAndZoom(point, 12);
            $("input[name='lng']").val(point.lng);
            $("input[name='lat']").val(point.lat);
            map.addOverlay(new BMap.Marker(point));
        }
    }, "西安市");
}
$(function(){
    init_map();
    $("input[name='address']").on('keyup',function(){
        mark($(this).val());
    });

});
