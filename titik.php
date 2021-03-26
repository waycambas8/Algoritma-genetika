<div class="page-header">
    <h1>Titik</h1>
</div>
<div class="panel panel-default">
    <div class="panel-heading">        
        <form class="form-inline">
            <input type="hidden" name="m" value="titik" />
            <div class="form-group">
                <input class="form-control" type="text" placeholder="Pencarian. . ." name="q" value="<?=$_GET['q']?>" />
            </div>
            <div class="form-group">
                <button class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span> Refresh</a>
            </div>
            <div class="form-group">
                <a class="btn btn-primary" href="?m=titik_tambah"><span class="glyphicon glyphicon-plus"></span> Tambah</a>
            </div>
            <div class="form-group">
                <a class="btn btn-default" href="cetak.php?m=titik" target="_blank"><span class="glyphicon glyphicon-plus"></span> Cetak</a>
            </div>
        </form>
    </div>
    <table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th>Kelompok</th>
            <th>Kode</th>
            <th>Nama Titik</th>
            <th>Lat</th>
            <th>Lng</th>
            <th>Aksi</th>
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
        <td>
            <a class="btn btn-xs btn-warning" href="?m=titik_ubah&ID=<?=$row->kode_titik?>"><span class="glyphicon glyphicon-edit"></span></a>
            <a class="btn btn-xs btn-danger" href="aksi.php?act=titik_hapus&ID=<?=$row->kode_titik?>" onclick="return confirm('Hapus data?')"><span class="glyphicon glyphicon-trash"></span></a>
        </td>
    </tr>
    <?php endforeach;?>
    </table>
</div>