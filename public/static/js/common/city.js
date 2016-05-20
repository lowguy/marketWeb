/**
 * Created by Monk on 2016/2/19.
 */


var global_cities = [
    {
        city:"西安市",
        districts:[
            '碑林区',
            '莲湖区',
            '阎良区'
        ]
    },
    {
    city:"渭南市",
        districts:[
            '富平县',
            '蒲城县'
        ]
    }
];

function get_districts(city){
    var result = [];
    for(var i=0; i < global_cities.length; i++){
        if(global_cities[i].city == city){
            result = global_cities[i].districts;
        }
    }

    return result;
}

function get_cities(){
    var result = [];
    for(var i=0; i < global_cities.length; i++){

            result.push( global_cities[i].city);

    }

    return result;
}