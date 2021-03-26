<table class="table table-bordered table-hover table-striped">
<thead>
    <tr>
        <th>Kelompok</th>
        <th>Kode</th>
        <th>Nama Titik</th>
        <th>Lat</th>
        <th>Lng</th>
    </tr>
</thead>
<?php
$q = esc_field($_GET['q']);
$rows = $db->get_results("SELECT * 
    FROM tb_titik t LEFT JOIN tb_kelompok k ON k.kode_kelompok=t.kode_kelompok 
    WHERE nama_titik LIKE '%$q%' 
    ORDER BY t.kode_kelompok DESC, kode_titik");
$no=0;
foreach($rows as $row):?>
<tr>
    <td><?=$row->nama_kelompok?></td>
    <td><?=$row->kode_titik ?></td>
    <td><?=$row->nama_titik?></td>
    <td><?=$row->lat?></td>
    <td><?=$row->lng?></td>
</tr>
<?php endforeach;?>
</table>