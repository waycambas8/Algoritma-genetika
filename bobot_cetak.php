<h1>Bobot Titik dalam Kilometer</h1>
<?php    
                    
$rows = $db->get_results("SELECT * FROM tb_bobot
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
        <?=number_format($v, 2)?> km
    </td>
    <?php $b++; endforeach;?>
</tr>
<?php $a++; endforeach;?>
</tbody>
</table>

<h1>Bobot Titik dalam Rupiah</h1>
<?php    
                    
$rows = $db->get_results("SELECT * FROM tb_bobot
    WHERE 
        ID1 IN(SELECT kode_titik FROM tb_titik WHERE kode_kelompok='$_GET[kode_kelompok]') AND
        ID2 IN(SELECT kode_titik FROM tb_titik WHERE kode_kelompok='$_GET[kode_kelompok]') 
    ORDER BY ID1, ID2");

$data = array();        
foreach($rows as $row){
    $data[$row->ID1][$row->ID2]  = $row->bobot;    
    $ID[$row->ID1][$row->ID2] = $row->ID;
}
$cost_per_kilo = get_option('cost_per_kilo');
?>
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
    <td>Rp <?=number_format($v*$cost_per_kilo)?></td>
    <?php $b++; endforeach;?>
</tr>
<?php $a++; endforeach;?>
</tbody>
</table>