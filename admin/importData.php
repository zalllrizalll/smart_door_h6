<?php

// Load the database configuration file
require_once __DIR__ . "../../database.php";

// Include PhpSpreadsheet library autoloader
require_once  'excelReader/excel_reader2.php';
require_once 'excelReader/SpreadsheetReader.php';

if(isset($_POST['importSubmit'])){
    // Allowed mime types 
    // $excelMimes = array('text/xls', 'text/xlsx', 'application/excel', 'application/vnd.msexcel', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    // If the file is uploaded
    if (is_uploaded_file($_FILES['excel']['tmp_name'])) {
        $fileName = $_FILES['excel']['name'];
        $fileExtension = explode('.', $fileName);
        $fileExtension = strtolower(end($fileExtension));
        # Change new file name
        $newFileName = date("Y.m.d"). " - ".date("h.i.sa"). "." .$fileExtension;
        $targetDirectory = "uploads/".$newFileName;
        move_uploaded_file($_FILES['excel']['tmp_name'], $targetDirectory);

        error_reporting(0);
        ini_set('display_errors', 0);

        // Read data SpreadSheet
        $reader = new SpreadsheetReader($targetDirectory);

        foreach ($reader as $key => $row) {
            $id_jadwal = $row[0];
            $id_mhs = $row[1];
            $tanggal = $row[2];
            $jam_masuk = $row[3];
            $jam_keluar = $row[4];
            $ruangan = $row[5];
            mysqli_query($conn,"INSERT INTO jadwal (id_jadwal, id_mhs, tanggal, jam_masuk, jam_keluar, ruangan) VALUES ('$id_jadwal', '$id_mhs', '$tanggal', '$jam_masuk', '$jam_keluar', '$ruangan')");
        }
        echo "
            <script>
            alert('Successfully Imported')
            document.location.href = '';
            </script>
            ";
    } else {
        echo "
            <script>
            alert('Failed Imported')
            </script>
            ";
    }
    // $path = $_SERVER['REQUEST_URI'];
    // $filename = basename($path);
    # $filename = substr(strrchr($path, "/"),

    // Validate whether selected file is a Excel file 
    // if (!empty($_FILES['excel']['name']) && in_array($_FILES['file']['type'], $excelMimes)) {
       
            
    
                // Check whether member already exists in the database with the same email
                // $prevQuery = "SELECT id_jadwal FROM jadwal WHERE id_mhs = '$id_mhs'";
                // $prevResult = $conn->query($prevQuery);
    
                // if ($prevResult->num_rows > 0) {
                //     // Update member data in the database
                //     $conn->query("UPDATE jadwal SET id_mhs = '$id_mhs', tanggal = '$tanggal', jam_masuk = '$jam_masuk', jam_keluar = '$jam_keluar', ruangan = '$ruangan' WHERE id_jadwal = '$id_jadwal'");
                // } else {
                //     // Insert member data in the database
                //     $conn->query("INSERT INTO jadwal (id_jadwal, id_mhs, tanggal, jam_masuk, jam_keluar, ruangan) VALUES ('$id_jadwal', '$id_mhs', '$tanggal', '$jam_masuk', '$jam_keluar', '$ruangan')");
                // }
            
    // } else {
    //     $qstring = '?status=invalid_file';
    // }  
}
// Redirect to the listing page 
// header("Location: data_jadwal.php".$qstring); 
?>