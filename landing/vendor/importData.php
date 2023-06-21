<?php

// Load the database configuration file
require_once __DIR__ . "../../database.php";

// Include PhpSpreadsheet library autoloader
require_once  'vendor/autoload.php';

if(isset($_POST['importSubmit'])){
    // Allowed mime types 
    $excelMimes = array('text/xls', 'text/xlsx', 'application/excel', 'application/vnd.msexcel', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
     
    // Validate whether selected file is a Excel file 
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $excelMimes)){ 
         
        // If the file is uploaded 
        if(is_uploaded_file($_FILES['file']['tmp_name'])){ 
            $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx(); 
            $spreadsheet = $reader->load($_FILES['file']['tmp_name']); 
            $worksheet = $spreadsheet->getActiveSheet();  
            $worksheet_arr = $worksheet->toArray(); 
 
            // Remove header row 
            unset($worksheet_arr[0]); 
 
            foreach($worksheet_arr as $row){ 
                $id_jadwal = $row[0]; 
                $id_mhs = $row[1]; 
                $tanggal = $row[2]; 
                $jam_masuk = $row[3]; 
                $jam_keluar = $row[4];
                $ruangan = $row[5]; 

 
                // Check whether member already exists in the database with the same email 
                $prevQuery = "SELECT id_jadwal FROM jadwal WHERE id_mhs = '".$id_mhs."'"; 
                $prevResult = $conn->query($prevQuery); 
                 
                if($prevResult->num_rows > 0){ 
                    // Update member data in the database 
                    $conn->query("UPDATE jadwal SET id_mhs = '".$id_mhs."', tanggal = '".$tanggal."', jam_masuk = '".$jam_masuk."', jam_keluar = '".$jam_keluar."', ruangan = '".$ruangan."' WHERE id_jadwal = '".$id_jadwal."'"); 
                }else{ 
                    // Insert member data in the database 
                    $conn->query("INSERT INTO jadwal (id_jadwal, id_mhs, tanggal, jam_masuk, jam_keluar, ruangan) VALUES ('".$id_jadwal."', '".$id_mhs."', '".$tanggal."', '".$jam_masuk."', '".$jam_keluar."', '".$ruangan."')"); 
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
header("Location: admin/header.php".$qstring); 
?>