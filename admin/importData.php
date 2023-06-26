<?php

// Load the database configuration file
require_once __DIR__ . "../../database.php";

// Include PhpSpreadsheet library autoloader
require_once  'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

if(isset($_POST['importSubmit'])){
    // Allowed mime types 
    $excelMimes = array('text/xls', 'text/xlsx', 'application/excel', 'application/vnd.msexcel', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    // Validate whether selected file is a Excel file 
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $excelMimes)){ 
         
        // If the file is uploaded 
        if(is_uploaded_file($_FILES['file']['tmp_name'])){ 
            $reader = new Xlsx(); 
            $spreadsheet = $reader->load($_FILES['file']['tmp_name']); 
            $worksheet = $spreadsheet->getActiveSheet();  
            $worksheet_arr = $worksheet->toArray(); 
 
            // Remove header row 
            unset($worksheet_arr[0]); 
 
            foreach($worksheet_arr as $row){ 
                $id_jadwal = $row[0];  
                $tanggal = $row[1]; 
                $jam_masuk = $row[2]; 
                $jam_keluar = $row[3];
                $ruangan = $row[4];
                $nim = $row[5];
 
                // Check whether member already exists in the database with the same email 
                $prevQuery = "SELECT jadwal.id_jadwal, jadwal.tanggal, jadwal.jam_masuk, jadwal.jam_keluar, jadwal.ruangan, mahasiswa.nim FROM jadwal JOIN mahasiswa ON jadwal.nim = mahasiswa.nim WHERE mahasiswa.nim = '".$nim."'"; 
                $prevResult = $conn->query($prevQuery); 
                 
                if($prevResult->num_rows > 0){ 
                    // Update member data in the database 
                    $conn->query("UPDATE jadwal SET tanggal = '".$tanggal."', jam_masuk = '".$jam_masuk."', jam_keluar = '".$jam_keluar."', ruangan = '".$ruangan."', nim = '".$nim."' WHERE id_jadwal = '".$id_jadwal."'"); 
                }else{ 
                    // Insert member data in the database 
                    $conn->query("INSERT INTO jadwal (id_jadwal, tanggal, jam_masuk, jam_keluar, ruangan, nim) VALUES ('".$id_jadwal."', '".$tanggal."', '".$jam_masuk."', '".$jam_keluar."', '".$ruangan."', '".$nim."')"); 
                } 
            } 
            $qstring = '?status=succ'; 
        }else{ 
            $qstring = '?status=err'; 
        } 
    }else{ 
        $qstring = '?status=invalid_file'; 
    }    
} 
// Redirect to the listing page 
header("Location: data_jadwal.php".$qstring); 
?>