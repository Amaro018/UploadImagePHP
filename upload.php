<?php

if (isset($_POST['submit'])) {
   
    $host = 'localhost';
    $user = 'root';  
    $password = '';  
    $database = 'uploadimagetest'; //change this to your database name
    $port = 3307; // change this to you mysql port

    $conn = new mysqli($host, $user, $password, $database, $port); 

    if ($conn->connect_error) {
        echo "<script>Swal.fire('Error', 'Connection failed: " . $conn->connect_error . "', 'error');</script>";
        die();
    }

    $uploadDir = './uploads/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);  
    }

 
    $fileName = basename($_FILES['image']['name']);
    $targetFilePath = $uploadDir . $fileName;  
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

    if (in_array(strtolower($fileType), $allowedTypes)) {

        if (file_exists($targetFilePath)) {
            echo "<script>Swal.fire('Oops!', 'Sorry, file already exists.', 'warning');</script>";
        } else {
          
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
              
                $stmt = $conn->prepare("INSERT INTO images (file_path) VALUES (?)");
                $stmt->bind_param("s", $targetFilePath);  
                $stmt->execute();

                echo "<script>alert('Image uploaded successfully.');</script>";
                echo "<script>window.location.href = 'index.php';</script>";
            } else {
                echo "<script>Swal.fire('Error', 'Error uploading file.', 'error');</script>";
            }
        }
    } else {
        echo "<script>Swal.fire('Invalid File', 'Only JPG, JPEG, PNG, and GIF files are allowed.', 'error');</script>";
    }


    $conn->close();
}
?>
