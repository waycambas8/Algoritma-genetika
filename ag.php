<?php
class AG {
    var $TITIK = array();
    var $num_crommosom ; //jumlah kromosom awal yang dibangkitkan
    var $data = array();
    var $generation = 0; //generasi ke....
    var $max_generation = 25; //maksimal generasi
    var $crommosom = array(); //array kromosom sesuai $num_cromosom 
    var $success = false; //keadaan jika sudah ada sulosi terbaik
    var $debug = true; //menampilkan debug jika diset true;  
    var $fitness = array(); //nilai fitness setiap kromosom
    var $console = ""; //menyimpan proses algoritma 
          
    var $total_fitness = 0; //menyimpan total fitness untuk masing-masing kromosom
    var $probability  = array(); //menyimpan probabilitas fitness masing-masing kromosom
    var $com_pro = array(); //menyimpan fitness komulatif untuk masing masing kromosom
    var $rand = array(); //menyimpan bilangan rand())
    var $parent = array(); //menyimpan parent saat crossover
    
    var $best_fitness = 0; //menyimpan nilai fitness tertinggi
    var $best_cromossom = array(); //menyimpan kromosom dengan fitness tertinggi 
    
    var $crossover_rate = 75; //prosentase kromosom yang akan dipindah silang
    var $mutation_rate = 25; //prosentase kromosom yang akan dimutasi
    
    var $time_start; //menyimpan waktu mulai proses algotitma
    var $time_end; //menyimpan waktu selesai proses algoritma
    
    var $fitness_history = array();
    
    var $temp = array();
    
    var $titik_awal = '';
    
    /**
     * konstruktor ketiga pertama kali memanggil class AG
     * inputan waktu, ruang, dan kuliah 
     */
    function __construct($data, $titik_awal, $titik) {
        $this->TITIK = $titik;
        $this->data = $data;            
        $this->titik_awal = $titik_awal;
        foreach($data as $key => $val){
            $this->best_fitness+=array_sum($val);            
        }               
    }      
    /**
     * mulai memproses algoritma     
     */
    function generate(){
        global $db;        
        $this->time_start = microtime(true); //seting watu awal eksekusi        
        $this->generate_crommosom();        
        /**
         * proses algoritma akan diulang sampai memperoleh nilai 1
         * atau sudah mencapai maksimum generasi (sesuai yang diinputkan)
         */                        
        while(($this->generation < $this->max_generation) && $this->success == false){       
            $this->generation++;         
            $this->console.= "<h4>Generasi ke-$this->generation</h4>";
            $this->show_crommosom();
            $this->calculate_all_fitness();
            $this->show_fitness();
            
            //$this->success = true;
            
            if(!$this->success) { //jika fitness terbaik belum mencapai 0, dilanjutkan ke proses seleksi
                $this->get_com_pro();
                $this->selection();
                $this->show_crommosom();                   
                $this->calculate_all_fitness();         
                $this->show_fitness();
            }
            if(!$this->success) { //jika fitness terbaik belum mencapai 1, dilanjutkan ke proses crossover
                $this->crossover();
                $this->show_crommosom();                
                $this->calculate_all_fitness();
                $this->show_fitness(); 
            }
            if(!$this->success) { //jika fitness terbaik belum mencapai 1, dilanjutkan ke proses mutasi
                $this->mutation();
                $this->show_crommosom();
                $this->calculate_all_fitness();
                $this->show_fitness();
            }   
        }        
        //$this->save_result(); //menyimpan jadwal hasil AG
        
        $this->time_end = microtime(true); //seting waktu akhir eksekusi
        
        $time = $this->time_end - $this->time_start;
        
        /**
         * menampilan hasil algoritma
         */
        echo "<pre style='font-size:0.8em'>\r\nFITNESS TERBAIK: $this->best_fitness";
        echo "\r\nExecution Time: $time seconds";
        echo "\r\nMemory Usage: " . memory_get_usage() / 1024 . ' kilo bytes';
        echo "\r\nGENERASI: $this->generation";
        //echo "\r\nFitness: $this->generation";
        echo "\r\nCROMOSSOM/RUTE TERBAIK:  " . $this->print_cros($this->best_cromossom) . "</pre>"; 
        
        //menampilkan proses algoritma                             
        $this->get_debug();      
        return $this->best_cromossom;                             
    }
    
    /**
     * proses mutasi pada AG
     * mutasi dilakukan sesuai prosentase "Mutation Rate" yang diinputkan
     */
    function mutation(){
        $mutation = array();
        $this->console.= "<h5>Mutasi generasi ke-$this->generation</h5>";
        $gen_per_cro = count($this->data) - 1;
        $total_gen = count($this->crommosom) * $gen_per_cro;
        $total_mutation = ceil($this->mutation_rate / 100 * $total_gen);
        
        for($a = 1; $a <= $total_mutation; $a++) {
            $val = rand(1, $total_gen);
            
            $cro_index = ceil($val / $gen_per_cro) - 1;
            $gen_index1 = ( ($val -1)  % $gen_per_cro) + 1; 
            $gen_index2 = rand(0, $gen_per_cro - 1) + 1;
              
            $gen1 = $this->crommosom[$cro_index][$gen_index1];
            $gen2 = $this->crommosom[$cro_index][$gen_index2];
            
            
            $this->console.="rand($val): [$cro_index, $gen_index1 x $gen_index2] = ".implode(',', $this->kode_to_nama($this->crommosom[$cro_index]));
            
            $this->crommosom[$cro_index][$gen_index1] = $gen2;
            $this->crommosom[$cro_index][$gen_index2] = $gen1;
                        
            $this->console.=" = ". implode(',', $this->kode_to_nama($this->crommosom[$cro_index]))." \r\n";                          
        }
        return false;
    }
    
    /**
     * menghapus jadwal sebelumnya
     * menyimpan hasil jadwal yang baru
     */
    function save_result(){
        global $db;                
        $db->query("TRUNCATE tb_jadwal");
        foreach($this->best_cromossom as $key => $val){
            $db->query("INSERT INTO tb_jadwal VALUES (
                '".$this->kuliah[$val[kuliah]]->kode_kuliah."', 
                '".$this->ruang[$val[ruang]]->kode_ruang."', 
                '".$this->waktu[$val[waktu]]->kode_waktu."')");
        }
        //reset($this->best_cromossom);
    }
    
    /**
     * menampilkan semua kromosom 
     */
    function show_crommosom() { 
        $cros = $this->crommosom;
        
        $a = array();
        foreach ($cros as $key => $val) {
            $a[] =  "Cro $key: " . $this->print_cros($val);
        }
        
        $this->console.= implode(" \r\n", $a) . "\r\n";
    }
    
    function print_cros($cro){        
        return implode(', ', $this->kode_to_nama($cro));
    }
        
    /**
     * menghitung fitness pada semua kromosom
     */
    function calculate_all_fitness() {            
        foreach($this->crommosom as $key => $val) {                             
            $this->calculate_fitness($key);                         
        }
        
        //echo '<pre>'. print_r($this->data, 1) . '</pre>';
        
        $min = min($this->fitness);
                        
        $key = array_keys($this->fitness, min($this->fitness));
        $key = $key[0];
    
        $this->fitness_history[] = $min;
        
        if($min < $this->best_fitness){
            $this->best_fitness = $min;
            $this->best_cromossom = $this->crommosom[$key];  
        }
        $this->console.= "FITNES HISTORY: " . implode(",", $this->fitness_history) . "\r\n";
        
        if($this->is_stop()) // jika sudah optimal maka berhenti
            $this->success = true;              
    }
    
    /**
     * menghitung fitnes pada kromosom tertentu 
     */
    function calculate_fitness($key) {
        //echo '<pre>'. print_r($this->crommosom, 1) . '</pre>';
        
        
        $cro = (array)$this->crommosom[$key];
        $data = $this->data;
        
        $fitness = 0;
        for($a = 1; $a < count($cro); $a++){
            $fitness+=$data[$cro[$a-1]][$cro[$a]];  
            //echo $fitness.'<br />';          
        }
                        
        $this->fitness[$key] = $fitness;
        
        return $fitness;
    }
    
    function is_stop(){    
        $total = 10;
        
        if(count($this->fitness_history) < $total)
            return false;
            
        $this->fitness_history = array_values($this->fitness_history);
        
        unset($this->fitness_history[0]);
                
        $arr =  $this->fitness_history;
        
        if (count(array_unique($arr))==1) {
            return true;
        }
        return false;
    }
    
    /**
     * menampilkan nilai fitnes untuk masing-masing kromosom
     */
    function show_fitness(){
        foreach($this->fitness as $key => $val) {                                    
            $this->console.= "F[$key]: $val \r\n";                        
        }
        //reset($this->fitness);
        $this->get_total_fitness();
        $this->console.="Total F: ".$this->total_fitness ."\r\n"; 
    }
    
    /**
     * proses Crossover (pindah silang pada AG)
     */
    function crossover(){
        $this->console.= "<h5>Pindah silang generasi ke-$this->generation</h5>";
        $parent = array();
        
        //menentukan kromosom mana saja sebagai induks
        //jumlahnya berdasarkan crossover rate 
        
        $this->console.="Pertama kita bangkitkan bilangan acak R sebanyak jumlah populasi";
        foreach($this->crommosom as $key => $val) {
            $rnd = mt_rand() / mt_getrandmax();
            $this->console.="\nrand([$key]) : ".round($rnd, 3);
            if($rnd <= $this->crossover_rate / 100)
                $parent[] = $key;
        }        
        //reset($this->crommosom);
        
        //menampilkan parent/induk setiap pindah silang        
        foreach($parent as $key => $val) {
            //$this->console.="Parent[$key] : $val \r\n";
            //$this->console.="Ofspring[$val] : ";
        }
                
        $parent = $parent;
        $c = count($parent);
        
        
        //mulai proses pindah silang sesuai jumlah induk
        $this->temp['induk']= '';
        $this->temp['detail']= '';
        $this->temp['point']= '';
        if( $c > 1 ) {
            for($a = 0; $a < $c-1; $a++) {                                
                $new_cro[$parent[$a]] = $this->get_crossover( $parent[$a],  $parent[$a+1]);
            }    
            //$this->console.="Ofspring[".$parent[($c-1)]."] = chromosome[".$parent[($c-1)]."] x chromosome[$parent[0]] \r\n";
            $new_cro[$parent[$c-1]] = $this->get_crossover( $parent[$c-1],  $parent[0]);
            
            //menyimpan kromosom hasil pindah silang dan fitnessnya
            foreach($new_cro as $key => $val) {
                $this->crommosom[$key] = $val;
            }
        }         
        
        $this->console.="\nInduk crossover: \r\n" . $this->temp['induk'];              
        $this->console.="Point: \r\n" . $this->temp['point'];             
        $this->console.="Proses crossover: \r\n" . $this->temp['detail'];       
        $this->console.="Dengan demikian populasi chromosome setelah melalui proses crossover adalah: \r\n";             
    }
    
    //mencari nilai crossover dari dua induk
    function get_crossover($key1, $key2){
        
        $this->temp['induk'].="chro[$key1] x chro[$key2] \r\n";
        
        $cro1 = (array) $this->crommosom[$key1];
        $cro2 = (array) $this->crommosom[$key2];
        
        $jumlah_gen = count($cro1);
        $offspring = rand(1, $jumlah_gen - 2);
        
        foreach($cro1 as $key => $val){
            if($key <= $offspring)
                $new_cro[$key] = $val;             
        }
        
        foreach($cro2 as $key => $val){
            if(!in_array($val, $new_cro))
                $new_cro[] = $val;
        }
        $new_cro[] = $cro1[0];

        $this->temp['point'].="C[$key1] = $offspring \r\n";
        $this->temp['detail'].="Offspring[$key1] = chromosome[$key1] x chromosome[$key2] \r\n";
        $this->temp['detail'].='            = [' . implode(',', $this->kode_to_nama($cro1)) . '] x [' . implode(',', $this->kode_to_nama($cro2)) ."] \r\n";
        $this->temp['detail'].='            = [' . implode(',', $this->kode_to_nama($new_cro)) . "] \r\n";
        
        return $new_cro;        
    }
    
    function kode_to_nama($kode){
        $arr = array();
        foreach((array) $kode as $val){
            $arr[] = $this->TITIK[$val];
        }
        return $arr;
    }
    
    /**
     * menampilkan print out dari proses algoritma
     */
    function get_debug(){   
        if($this->debug)
            echo "<pre style='font-size:0.8em'>$this->console</pre>";
    }
    
    /**
     * membuat kromosom awal sesuai jumlah kromosom yang diinputkan
     */        
    function generate_crommosom() {
        $numb = 0;
        while($numb < $this->num_crommosom) { //diulang sesuai jumlah kromosom yang diinputkan
            $cro = $this->get_rand_crommosom();            
            $this->crommosom[] = $cro;       
            $this->fitness[] = 0;                                    
            $numb++;
        }                       
        //print_r($this->fitness);
    }         
    
    /**
     * membuat kromoson random(acak)
     */
    function get_rand_crommosom(){
        $arr = array($this->titik_awal);
        $x = $this->data;
        unset($x[$this->titik_awal]);
        $keys = array_keys($x);
        shuffle($keys);
        return array_merge($arr, $keys, $arr);                                                  
    }         
    
    /**
     * mencari garis 
     */
    function get_total_fitness(){
        $this->total_fitness = 0;
        //reset($this->fitness);
        foreach($this->fitness as $key => $val) {
            $this->total_fitness+=$val;
        }        
        return $this->total_fitness;
    }
    
    /**
     * mencari probabilitas untuk setiap fitness
     * rumusnya adalah  fitness / total fitness
     */     
    function get_probability(){
        $this->probability = array();
        
        $arr = array();
        foreach($this->fitness as $key => $val){
            $arr[$key] = $val==0 ? 0 : 1 / $val;
        }
        
        foreach($arr as $key => $val) {
            $x = (array_sum($arr)==0) ? 0 : $val / array_sum($arr);
            $this->probability[] = $x;
            //$this->console.="P[$key] : $x \r\n";
        }
        $this->console.="Total P: " . array_sum($this->probability) . "\r\n";        
        return $this->probability;
    }
    
    /**
     * mencari nilai probabilitas komulatif
     * 
     * */
    function get_com_pro(){
            
        $this->get_probability(); 
        
        $this->com_pro = array();
        $x = 0;
        foreach($this->probability as $key => $val) {
            $x+= $val;
            $this->com_pro[] = $x;
            $this->console.="PK[$key] : $x \r\n";
        }        
        //reset($this->probability);
        $this->com_pro;
    }
    
    /**
     * proses seleksi, memilih gen secara acak
     * dimana fitness yang besar mendapatkan kesempata yang lebih besar
     */
    function selection(){        
        $this->console.="<h5>Seleksi generasi ke-$this->generation</h5>";
        $this->get_rand();
        $new_cro = array();
        
        //print_r($this->rand);
        foreach ($this->rand as $key => $val) {
            $k = $this->choose_selection($val);
            $new_cro[$key] = $this->crommosom[$k];
            $this->fitness[$key] = $this->fitness[$k]; 
            $this->console.="K[$key] = rand(".round($val, 3).") = K[$k] \r\n";
        }  
        $this->crommosom = $new_cro;
    }
    
    /**
     * memilih berdasarkan bilangan random yang diinputkan
     * */
    function choose_selection($rand_numb = 0) {    
        foreach($this->com_pro as $key => $val) {
            if($rand_numb <= $val)
                return $key;
        }        
    }
    
    function get_rand(){
        $this->rand = array();
        //reset($this->fitness);
        foreach($this->fitness as $key => $val) {
            $r = mt_rand() / mt_getrandmax();
            $this->rand[] = $r;
            $this->console.="R[$key] : $r \r\n";
        }
    }                         
}

