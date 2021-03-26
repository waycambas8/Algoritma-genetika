<div class="page-header">
    <h1>Perhitungan AG</h1>
</div>
<style>
      #right-panel {
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }

      #right-panel select, #right-panel input {
        font-size: 15px;
      }

      #right-panel select {
        width: 100%;
      }

      #right-panel i {
        font-size: 12px;
      }
      
      #map{
        height: 500px;
        float: left;
        width: 63%;
      }
      #right-panel {
        float: right;
        width: 34%;
        height: 500px;
        overflow: auto;
      }
</style>

<?php
$success = true;
$a = 1;
$b = 1;
$c = 75;
$d = 25;
$titik_tujuan = $_GET['titik_tujuan'];

if(isset($_GET['num_kromosom'])) {
    $num_kromosom = $_GET['num_kromosom'];
    if($num_kromosom<$a || $num_kromosom>500) {
        print_msg("Masukkan jumlah kromosom dari $a sampai 500");
        $success = false;
    }   
    
    $max_generation = $_GET['max_generation'];
    if($max_generation<$b || $max_generation>1000) {
        print_msg("Masukkan maksimal generasi dari $b sampai 1000");
        $success = false;
    } 
    
    $crossover_rate = $_GET['crossover_rate'];
    if($crossover_rate<1 || $crossover_rate>100) {
        print_msg("Masukkan dari 1 sampai 100");
        $success = false;
    } 
    
    $mutation_rate = $_GET['mutation_rate'];
    if($mutation_rate<1 || $mutation_rate>100) {
        print_msg("Masukkan dari 1 sampai 100");
        $success = false;
    } 
    
    if(count((array)$titik_tujuan)<1 || (count((array)$titik_tujuan)==1 && $_GET['titik_awal']==$titik_tujuan[0])){
        print_msg('Pilih minimal 1 titik tujuan yang berbeda dari titik awal!');
        $success = false;
    }
} else {
    $num_kromosom = $a;
    $max_generation = $b;
    $crossover_rate = $c;
    $mutation_rate = $d;
}
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <form class="form-inline">    
            <input type="hidden" name="m" value="hitung" />
            <div class="form-group">
                <select class="form-control" name="kode_kelompok" onchange="this.form.submit()">
                    <option value="">Pilih kelompok</option>
                    <?=get_kelompok_option(set_value('kode_kelompok'))?>
                </select>
            </div>
        </form>
    </div>
    <?php if($_GET['kode_kelompok']):?>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <form action="?">
                    <input type="hidden" name="m" value="hitung" />
                    <input type="hidden" name="kode_kelompok" value="<?=$_GET['kode_kelompok']?>" />
                    <div class="form-group">
                        <label>Titik Awal</label>
                        <select class="form-control" name="titik_awal">
                            <?=get_titik_option(set_value('titik_awal'), $_GET['kode_kelompok'])?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Titik Tujuan</label>
                        <select class="form-control s2" name="titik_tujuan[]" multiple="true">
                            <?=get_titik_option(set_value('titik_tujuan'), $_GET['kode_kelompok'])?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Kromosom Dibangkitkan</label>
                        <input class="form-control" type="text" name="num_kromosom" value="<?=set_value('num_kromosom', 10)?>" />
                        <p class="help-block">Masukkan antara <?=$a?>-100</p>
                    </div>
                    <div class="form-group">
                        <label>Maksimal Generasi</label>
                        <input class="form-control" type="text" name="max_generation" value="<?=set_value('max_generation', 50)?>" />
                        <p class="help-block">Masukkan antara <?=$b?>-100</p>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="debug" <?=(isset($_GET['debug'])) ? 'checked' : ''?> name="debug" /> Tampilkan proses algoritma
                        </label>
                    </div>
                    <a class="btn btn-info" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                      Opsi Lain
                    </a>
                    <div class="collapse" id="collapseExample">
                    <hr />                
                        <div class="form-group">
                            <label>Crossover Rate</label>
                            <input class="form-control" type="text" name="crossover_rate" value="<?=$crossover_rate?>" />
                            <p class="help-block">Masukkan antara 1-100</p>
                        </div>   
                        <div class="form-group">
                            <label>Mutation Rate</label>
                            <input class="form-control" type="text" name="mutation_rate" value="<?=$mutation_rate?>" />
                            <p class="help-block">Masukkan antara 1-100</p>
                        </div>
                    </div>                                    
                    <button class="btn btn-primary">Generate</button> 
                </form>
            </div>
        </div>
    </div>
    <?php endif?>
</div>    

<div class="panel panel-primary  <?=$success && isset($_GET['num_kromosom']) ? '' : 'hidden'?>">
    <div class="panel-heading">
        <h3  class="panel-title">Hasil TSP</h3>
    </div>
    <div class="panel-body">
        <div class="row">    
            <div class="col-md-10">  
                <p class="rute"></p>      
                <div>
                    <div id="map" class="thumbnail"></div>
                    <div id="right-panel" class="small">
                      <p style="font-weight: bold;" class="text-danger">
                        Total Jarak: <span id="total"></span> km<br />
                        Total Biaya: Rp <span id="biaya"></span>
                      </p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">  
                <p class="keterangan"></p>      
            </div>
        </div>
    </div>
</div>
<?php
include 'ag.php';

if($success && isset($_GET['num_kromosom'])):
    echo '<hr />';        
    $titik_tujuan[] = $_GET['titik_awal'];
    $titik_tujuan = array_unique($titik_tujuan);
    $rows =  $db->get_results("SELECT * 
        FROM tb_bobot 
        WHERE 
            ID1 IN(SELECT kode_titik FROM tb_titik WHERE kode_kelompok='$_GET[kode_kelompok]') AND
            ID2 IN(SELECT kode_titik FROM tb_titik WHERE kode_kelompok='$_GET[kode_kelompok]') AND
            ID1 IN ('".implode("','", $titik_tujuan)."') AND
            ID2 IN ('".implode("','", $titik_tujuan)."')
        ORDER BY ID1, ID2");
    $arr_data = array();
    foreach($rows as $row){
        $arr_data[$row->ID1][$row->ID2] = $row->bobot;
    }
    
    $ag = new AG($arr_data, $_GET['titik_awal'], $TITIK);
    $ag->num_crommosom = $num_kromosom;
    $ag->max_generation = $max_generation;
    $ag->debug = $_GET['debug'];
        
    $ag->crossover_rate = $crossover_rate;
    $arr = $ag->generate();
    
    $origin = array(
        'lat' => $POINTS[$arr[0]]->lat * 1,
        'lng' => $POINTS[$arr[0]]->lng * 1,
    );
    $detination = array(
        'lat' => $POINTS[$arr[count($arr)-1]]->lat * 1,
        'lng' => $POINTS[$arr[count($arr)-1]]->lng * 1,
    );
    
    $waypoint = array();
    for($a = 1; $a < count($arr) - 1; $a++){
        $waypoint[] = array(
            'location'=> array(
                'lat' => $POINTS[$arr[$a]]->lat * 1,
                'lng' => $POINTS[$arr[$a]]->lng * 1,
            ),
            'stopover' => TRUE,
        );
    }
    
    $ket_str = "Keterangan: <br />";
    $ascii = 65;
    $arr_rute = array();
    foreach($arr as $key){
        $chr = chr($ascii++);
        $arr_rute[] = $chr;
        $ket_str.= "$chr = $TITIK[$key] <br />";
    }
    $arr_poly = array();
    foreach($arr as $key){
        
        $arr_poly[] = array(
            'lat' => $POINTS[$key]->lat * 1,
            'lng' => $POINTS[$key]->lng * 1,
        );
    }

?>
<script>
var titik  = <?=json_encode(array_values($arr_rute))?>;
var cost_per_kilo = <?=get_option('cost_per_kilo')?>;
function initMap() {
    var directionsService = new google.maps.DirectionsService;
    var directionsDisplay = new google.maps.DirectionsRenderer;
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 7,
      center: {
        lat : <?=get_option('default_lat')?>, 
        lng : <?=get_option('default_lng')?>
      }
    });
    directionsDisplay.setMap(map);
    directionsDisplay.setPanel(document.getElementById('right-panel'));
    calculateAndDisplayRoute(directionsService, directionsDisplay, map);    
}

function calculateAndDisplayRoute(directionsService, directionsDisplay, map) {
    directionsService.route({
      origin: <?=json_encode($origin)?>,
      destination: <?=json_encode($detination)?>,
      waypoints: <?=json_encode($waypoint)?>,
      optimizeWaypoints: false,
      travelMode: 'DRIVING'
    }, function(response, status) {
      if (status === 'OK') {        
        
        directionsDisplay.setDirections(response);                
                
        $('.rute').html('Rute: <?=implode(' - ', $arr)?>');             
        $('.keterangan').html('<?=$ket_str?>');
        
        //mencari total jarak
        var total = 0;
        var result = directionsDisplay.getDirections();
        var myroute = result.routes[0];
        for (var i = 0; i < myroute.legs.length; i++) {
          total += myroute.legs[i].distance.value;      
        }
        total = total / 1000;
        $('#total').html(total);
        var cost = Math.round(total * cost_per_kilo) ;
        $('#biaya').html(cost.toLocaleString()); 

                        
        var flightPlanCoordinates = <?=json_encode($arr_poly)?>;                
        var flightPath = new google.maps.Polyline({
          path: flightPlanCoordinates,
          geodesic: true,
          strokeColor: '#FF0000',
          strokeOpacity: 1.0,
          strokeWeight: 2
        });
        flightPath.setMap(map);
        
      } else {      
        window.alert('Directions request failed due to ' + status);
      }
    });
}

$(function(){
    initMap();
})
</script>
<?php endif?>
