<div class="page-header">
    <h1>Tambah Kelompok</h1>
</div>
<?php if($_POST) include'aksi.php'?>
<form method="post">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Kode <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="kode" value="<?=set_value('kode', kode_oto('kode_kelompok', 'tb_kelompok', 'K', 2))?>"/>
            </div>
            <div class="form-group">
                <label>Nama Kelompok <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="nama" value="<?=set_value('nama')?>" id="nama"/>
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
                <a class="btn btn-danger" href="?m=kelompok"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
            </div>
        </div>
    </div>
</form>