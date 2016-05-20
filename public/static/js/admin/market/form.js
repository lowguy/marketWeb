var map;
var global_is_edit = false;
var global_boundary;//BMap.Polygon对象
var global_free_area;//BMap.Polygon对象
var global_marker;//BMap.Marker对象
var global_center;//BMap.Point对象
var global_label;//BMap.Label对象

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

function points_to_str(points){
    var arr = [];
    for(var i = 0; i < points.length; i++){
        arr.push(points[i].lng + ',' + points[i].lat)
    }

    return arr.join(';');
}


function show_boundary(boundaries){

    var points = str_to_points(boundaries);
    global_boundary.setPath(points);
    map.setViewport(points);

    show_free_area();
}



/**
 * 免费配送区被拖拽时更新免费配送区的覆盖范围
 * @param point, BMap.Point对象
 */
function on_center_change(point){
    var points = [];
    var offset_x = point.lng - global_center.lng;
    var offset_y = point.lat - global_center.lat;
    global_center = point;
    var old = global_free_area.getPath();
    for(var i = 0; i < old.length; i++){

        points.push(new BMap.Point(old[i].lng + offset_x, old[i].lat + offset_y));
    }

    global_free_area.setPath(points);

}

function default_free_area(){
    var points = [];
    var center = global_boundary.getBounds().getCenter();
    var x = center.lng;
    var y = center.lat;
    var offset = 0.01;
    points.push(new BMap.Point(x + offset, y + offset));
    points.push(new BMap.Point(x + offset, y - offset));
    points.push(new BMap.Point(x - offset, y - offset));
    points.push(new BMap.Point(x - offset, y + offset));
    points.push(points[0]);


    global_free_area.setPath(points);
}

function all_free_area(){
    global_free_area.setPath(global_boundary.getPath());
}

function show_free_area(){

    var points = [];

    var path = $('input[name="area"]').val();

    if(!path){
        default_free_area();
    }
    else{
        global_free_area.setPath(str_to_points(path))
    }

    global_free_area.enableEditing();
}

function init_select(){
    var cities = get_cities();

    var html = '';

    for(var i = 0; i < cities.length; i++){
        html += '<option value="' + cities[i] + '">' + cities[i] + '</option>'
    }

    $('select[name="city"]').append(html);


    $('select[name="city"]').change(function(){
       var city = $(this).val();
       var districts = get_districts(city);
       $('select[name="district"]').find('option[value!=""]').remove();
        var html = '';
        for(var i = 0; i < districts.length; i++){
            html += '<option value="' + districts[i] + '">' + districts[i] + '</option>'
        }
        $('select[name="district"]').append(html);
    });

    if(global_is_edit){
        var city = $('select[name="city"]').attr('data-value');
        $('select[name="city"]').find('option[value="' + city + '"]').attr('selected', true);
        $('select[name="city"]').trigger('change');
        var district = $('select[name="district"]').attr('data-value');
        $('select[name="district"]').find('option[value="' + district + '"]').attr('selected', true);
    }
}

function init_map(){


    map = new BMap.Map('map', {enableMapClick:false,enableAutoResize:false});
    map.centerAndZoom('西安市', 12);
    map.enableScrollWheelZoom(true);

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

    global_marker.addEventListener('dragging', function(event){
        on_center_change(event.point);
    });

    global_marker.addEventListener('dragstart', function(event){
        global_center = event.point;
        global_dragging = true;
    });

    global_marker.addEventListener('dragend', function(event){
        global_center = event.point;
        global_dragging = false;
    });

    global_marker.enableDragging();

    //global_marker.setAnimation(BMAP_ANIMATION_BOUNCE)

    global_free_area.addEventListener('lineupdate', function(){

        if(!global_dragging){
            global_marker.setPosition(global_free_area.getBounds().getCenter());
        }
    });

    var menu = new BMap.ContextMenu();


    menu.addItem(new BMap.MenuItem('全城免费',all_free_area.bind(global_free_area)));
    menu.addItem(new BMap.MenuItem('局部免费',default_free_area.bind(global_free_area)));

    global_free_area.addContextMenu(menu);
}


function addMarket(city, district, area,boundaries){
    $(document).ajaxStart(function(){
        ajax_doing();
    });

    $(document).ajaxStop(function(){
        ajax_finished();
    });

    $.ajax({
        url:location.href,
        type:'POST',
        dataType:'JSON',
        data:{
            city:city,
            district:district,
            area:area,
            boundaries:boundaries
        },

        success:function(data,err){
            if(data.code == 0){
                form_success('添加成功');
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

function editMarket(market_id,area){
    $(document).ajaxStart(function(){
        ajax_doing();
    });

    $(document).ajaxStop(function(){
        ajax_finished();
    });

    $.ajax({
        url:'/admin/market/edit',
        type:'POST',
        dataType:'JSON',
        data:{
            market_id:market_id,
            area:area
        },

        success:function(data,err){
            if(data.code == 0){
                form_success('更新成功');
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

function ajax_doing(){
    $('#js_submit').attr('disabled', true);

}

function ajax_finished(){
    $('#js_submit').attr('disabled', false);
}

function init_validator(){
    $('.market-form').validate({
        errorElement:'div',
        errorClass:'help-block',
        focusInvalid:false,
        ignore:'.validate-ignore',
        highlight : function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        success : function(label) {
            label.closest('.form-group').removeClass('has-error');
            label.remove();
        },
        errorPlacement : function(error, element) {
            element.parent('div').append(error);
        },
        rules:{
            city:{
                required:true
            },
            district:{
                required:true
            },
            area:{
                required:true
            }
        },

        messages:{
            city:{
                required:'请选择城市'
            },
            district:{
                required:'请选择区/县'
            },
            area:{
                required:'请选择配送区域'
            }
        },

        submitHandler:function(form){
            var city = $('select[name="city"]').val();
            var district = $('select[name="district"]').val();
            var area = $('input[name="area"]').val();
            var market_id = $('input[name="market_id"]').val();
            var boundaries = $('input[name="boundaries"]').val();
            if(!global_is_edit){
                addMarket(city, district, area,boundaries);
            }
            else{
                editMarket(market_id,area);
            }
        }
    });
}

function open_map(){
    if($("select[name='city'],select[name='district']").valid()){
        var path = global_boundary.getPath();

        if(path.length == 0){

            var area = $('select[name="city"]').val() + $('select[name="district"]').val();
            var boundary = new BMap.Boundary();
            boundary.get(area, function(result){

                if(result.boundaries.length){
                    var boundaries = result.boundaries.join(';');
                    $('input[name="boundaries"]').val(boundaries);
                    $('.modal').modal('toggle');
                    setTimeout(function(){
                        show_boundary(boundaries);
                    }, 500);
                }

            });
        }
        else{
            $('.modal').modal('toggle');
        }

    }
}

function get_center(points){
    var x = 0;
    var y = 0;

    for(var i = 0; i < points.length; i++){
        x += points[i].lng;
        y += points[i].lat;
    }

    x = x / points.length;
    y = y / points.length;

    return new BMap.Point(x,y);
}

$(function(){
    if(location.pathname == '/admin/market/edit'){
        global_is_edit = true;
    }

    init_select();

    init_validator();

    init_map();

    $("#select_area").click(function(){
        open_map();
    });

    $("select[name='city'],select[name='district']").change(function(){

        global_boundary.setPath([]);

    });

    $("#js_area_confirm").click(function(){
        var points = global_free_area.getPath();
        $("input[name='area']").val(points_to_str(points));
        $('.modal').modal('toggle');
    });

    $("#js_submit").click(function(){
       $('.market-form').submit();
    });
});