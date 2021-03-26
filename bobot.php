<div class="page-header">
    <h1>Bobot Titik</h1>
</div>
<?php if($_POST) include 'aksi.php'; ?>

<form class="form-inline">    
    <input type="hidden" name="m" value="bobot" />
    <div class="form-group">
        <label>Kelompok </label>
        <select class="form-control" name="kode_kelompok" onchange="this.form.submit()">
            <option value="">Pilih kelompok</option>
            <?=get_kelompok_option(set_value('kode_kelompok'))?>
        </select>
    </div>
</form>
<?php if($_GET['kode_kelompok']):?>
<hr />
<form method="post">
    <div class="panel panel-default">
        <div class="panel-heading form-inline">                    
            <div class="form-group">
                <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</a>
            </div>
            <div class="form-group">
                <a class="btn btn-default" href="cetak.php?m=bobot&kode_kelompok=<?=$_GET['kode_kelompok']?>" target="_blank"><span class="glyphicon glyphicon-plus"></span> Cetak</a>
            </div>     
            <div class="form-group">
                <select class="form-control" name="titik1" id="start">                                       
                    <?=get_titik_option(set_value('titik1'), $_GET['kode_kelompok'])?>
                </select>
            </div>
            <div class="form-group">
                <select class="form-control" name="titik2" id="end">                                   
                    <?=get_titik_option(set_value('titik2'), $_GET['kode_kelompok'])?>
                </select>
            </div>
        </div>
        <div class="panel-body">           
            <div id="map" style="height: 300px;"></div>
        </div>
        <?php    
        $rows =  $db->get_results("SELECT * 
        FROM tb_bobot 
        WHERE 
            ID1 IN(SELECT kode_titik FROM tb_titik WHERE kode_kelompok='$_GET[kode_kelompok]') AND
            ID2 IN(SELECT kode_titik FROM tb_titik WHERE kode_kelompok='$_GET[kode_kelompok]') 
        ORDER BY ID1, ID2");
        
        $data = array();        
        foreach($rows as $row){
            $data[$row->ID1][$row->ID2]  = $row->bobot;    
            $ID[$row->ID1][$row->ID2] = $row->ID;
        }
        
        ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th class="nw">Nama Alternatif</th>
                    <?php foreach($data as $key => $val):?>
                    <th><?=$key?></th>
                    <?php endforeach?>
                </tr>
            </thead>
            <tbody>
            <?php
            $a = 1;
            foreach($data as $key => $val):?>
            <tr>
                <td><?=$key?></td>
                <td><?=$TITIK[$key]?></td>
                <?php  
                $b=1;
                foreach($val as $k => $v):?>
                <td>
                    
                    <input type="text" name="bobot[<?=$key?>][<?=$k?>]" class="form-control input-sm bobot_<?=$key?>_<?=$k?>" value="<?=$v?>" />
                                  
                </td>
                <?php $b++; endforeach;?>
            </tr>
            <?php $a++; endforeach;?>
            </tbody>
            </table>
        </div>
    </div>
</form>
<script>

function initMap() {
    var directionsService = new google.maps.DirectionsService;
    var directionsDisplay = new google.maps.DirectionsRenderer;
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 7,
      center: {
        lat : <?=set_value('lat', get_option('default_lat')) * 1?>, 
        lng : <?=set_value('lng', get_option('default_lng')) * 1?>
      }
    });
    directionsDisplay.setMap(map);

    var onChangeHandler = function() {
      calculateAndDisplayRoute(directionsService, directionsDisplay);
    };
    document.getElementById('start').addEventListener('change', onChangeHandler);
    document.getElementById('end').addEventListener('change', onChangeHandler);
}

function calculateAndDisplayRoute(directionsService, directionsDisplay) {
    directionsService.route({
      origin: {
        lat : parseFloat($('#start').find(':selected').data('lat')), 
        lng : parseFloat($('#start').find(':selected').data('lng')), 
      },
      destination: {
        lat : parseFloat($('#end').find(':selected').data('lat')), 
        lng : parseFloat($('#end').find(':selected').data('lng')), 
      },
      travelMode: 'DRIVING'
    }, function(response, status) {
      if (status === 'OK') {
        $('.bobot_' + $('#start').val() + '_' + $('#end').val()).val(response.routes[0].legs[0].distance.value / 1000);
        //$('.bobot_' + $('#end').val() + '_' + $('#start').val()).val(response.routes[0].legs[0].distance.value / 1000);

        directionsDisplay.setDirections(response);
      } else {      
        window.alert('Directions request failed due to ' + satus);
      }
    });
}

$(function(){
    initMap();
})
</script>
<?php endif?>