<?php
require_once'functions.php';
/** LOGIN */ 
if ($act=='login'){
    $user = esc_field($_POST['user']);
    $pass = esc_field($_POST['pass']);
    
    $row = $db->get_row("SELECT * FROM tb_admin WHERE user='$user' AND pass='$pass'");
    if($row){
        $_SESSION['login'] = $row->user;
        redirect_js("index.php");
    } else{
        print_msg("Salah kombinasi username dan password.");
    }          
}  else if ($mod=='password'){
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    $pass3 = $_POST['pass3'];
    
    $row = $db->get_row("SELECT * FROM tb_admin WHERE user='$_SESSION[login]' AND pass='$pass1'");        
    
    if($pass1=='' || $pass2=='' || $pass3=='')
        print_msg('Field bertanda * harus diisi.');
    elseif(!$row)
        print_msg('Password lama salah.');
    elseif( $pass2 != $pass3 )
        print_msg('Password baru dan konfirmasi password baru tidak sama.');
    else{        
        $db->query("UPDATE tb_admin SET pass='$pass2' WHERE user='$_SESSION[login]'");                    
        print_msg('Password berhasil diubah.', 'success');
    }
} elseif($act=='logout'){
    unset($_SESSION['login']);
    header("location:login.php");
}

/** titik */    
if($mod=='kelompok_tambah'){
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    
    if($kode=='' || $nama=='')
        print_msg("Field bertanda * tidak boleh kosong!");
    elseif($db->get_results("SELECT * FROM tb_kelompok WHERE kode_kelompok='$kode'"))
        print_msg("Kode sudah ada!");
    else{
        $db->query("INSERT INTO tb_kelompok (kode_kelompok, nama_kelompok) 
            VALUES ('$kode', '$nama')");  
        redirect_js("index.php?m=kelompok");
    }                    
} else if($mod=='kelompok_ubah'){
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    
    if($kode=='' || $nama=='')
        print_msg("Field bertanda * tidak boleh kosong!");
    else{
        $db->query("UPDATE tb_kelompok SET nama_kelompok='$nama' WHERE kode_kelompok='$_GET[ID]'");
        redirect_js("index.php?m=kelompok");
    }    
} else if ($act=='kelompok_hapus'){
    $db->query("DELETE FROM tb_kelompok WHERE kode_kelompok='$_GET[ID]'");
    header("location:index.php?m=kelompok");
} 

/** BOBOT */
elseif($mod=='bobot'){
    foreach($_POST['bobot'] as $key => $val){
        foreach($val as $k => $v){
            $db->query("UPDATE tb_bobot SET bobot='$v' WHERE (ID1='$key' AND ID2='$k')");
        }
    }                
    print_msg('Data tersimpan', 'success');
} else if($mod=='alternatif_ubah'){
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $keterangan = $_POST['keterangan'];
    if($kode=='' || $nama=='')
        print_msg("Field yang bertanda * tidak boleh kosong!");
    else{
        $db->query("UPDATE tb_alternatif SET nama_alternatif='$nama', keterangan='$keterangan' WHERE kode_alternatif='$_GET[ID]'");
        redirect_js("index.php?m=alternatif");
    }
} else if ($act=='alternatif_hapus'){
    $db->query("DELETE FROM tb_alternatif WHERE kode_alternatif='$_GET[ID]'");
    $db->query("DELETE FROM tb_rel_alternatif WHERE kode_alternatif='$_GET[ID]'");
    header("location:index.php?m=alternatif");
} 

/** TITIK */    
if($mod=='titik_tambah'){
    $kode_kelompok = $_POST['kode_kelompok'];
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    
    if($kode_kelompok=='' || $kode=='' || $nama=='' || $lat=='' || $lng=='')
        print_msg("Field bertanda * tidak boleh kosong!");
    elseif($db->get_results("SELECT * FROM tb_titik WHERE kode_titik='$kode'"))
        print_msg("Kode sudah ada!");
    else{
        $db->query("INSERT INTO tb_titik (kode_kelompok, kode_titik, nama_titik, lat, lng) 
            VALUES ('$kode_kelompok', '$kode', '$nama', '$lat', '$lng')");            
        $db->query("INSERT INTO tb_bobot(ID1, ID2, bobot) SELECT kode_titik, '$kode', 0  FROM tb_titik");           
        $db->query("INSERT INTO tb_bobot(ID1, ID2, bobot) SELECT '$kode', kode_titik, 0  FROM tb_titik WHERE kode_titik<>'$kode'");           
        redirect_js("index.php?m=titik");
    }                    
} else if($mod=='titik_ubah'){
    $kode_kelompok = $_POST['kode_kelompok'];
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    
    if($kode_kelompok=='' || $kode=='' || $nama=='' || $lat=='' || $lng=='')
        print_msg("Field bertanda * tidak boleh kosong!");
    else{
        $db->query("UPDATE tb_titik SET kode_kelompok='$kode_kelompok', nama_titik='$nama', lat='$lat', lng='$lng' WHERE kode_titik='$_GET[ID]'");
        redirect_js("index.php?m=titik");
    }    
} else if ($act=='titik_hapus'){
    $db->query("DELETE FROM tb_titik WHERE kode_titik='$_GET[ID]'");
    $db->query("DELETE FROM tb_bobot WHERE ID1='$_GET[ID]' OR ID2='$_GET[ID]'");
    header("location:index.php?m=titik");
} 
    
/** RELASI ALTERNATIF */ 
else if ($act=='rel_alternatif_ubah'){
    foreach($_POST as $key => $value){
        $ID = str_replace('ID-', '', $key);
        $db->query("UPDATE tb_rel_alternatif SET nilai='$value' WHERE ID='$ID'");
    }
    header("location:index.php?m=rel_alternatif");
}    
elseif($mod=='pengaturan'){
    foreach($_POST['options'] as $key => $val){
        update_option($key, $val);
    }
    print_msg('Pengaturan tersimpan!', 'success');
}           
?>
