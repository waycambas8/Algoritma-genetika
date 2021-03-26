<div class="page-header">
    <h1>Tempat</h1>
</div>
<div id="map" style="height: 500px;"></div>
<script>
function tampilDekat(){
    getCurLocation();
    
    map_dekat = new google.maps.Map(document.getElementById('map'), {
        zoom: <?=get_option('default_zoom')?>,
        center: {
            lat : default_lat, 
            lng : default_lng
        }
    });
       
    var data =  <?=json_encode($db->get_results("SELECT * FROM tb_tempat"))?>;
    $.each(data, function(k, v){
        var pos = {
            lat : parseFloat(v.lat),
            lng : parseFloat(v.lng)
        };
        var contentString = '<h3>'  + v.nama_tempat + '</h3>' + 
            '<p align="center"><a href="?m=tempat_detail&ID=' + v.id_tempat + '" class="link_detail btn btn-primary">Lihat Detail</a>';
        var infowindow = new google.maps.InfoWindow({
            content: contentString
        });
        var marker = new google.maps.Marker({
            position: pos,
            map: map_dekat,
            animation: google.maps.Animation.DROP
        });         
        marker.addListener('click', function() {
            infowindow.open(map_dekat, marker);
        });
    });    
}  

$(function(){
    tampilDekat();
})
</script>