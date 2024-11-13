<?php

$host = 'localhost';
$user = 'root'; 
$password = 'amaro@071318'; 
$database = 'uploadimagetest';  


$conn = new mysqli($host, $user, $password, $database, 3307); 

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT file_path FROM images";
$result = $conn->query($query);


$images = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $images[] = $row['file_path'];
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Image Uploader</title>
    <style>
        .image-gallery {
            display: flex;
            flex-wrap: wrap;
        }
        .image-gallery img {
            max-width: 200px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <h1>Image Uploader</h1>
    <p>Upload an image to the server.</p>

    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="image" accept="image/*" required>
        <button type="submit" name="submit">Upload Image</button>
    </form>

    <h2>Uploaded Images:</h2>
    <div class="image-gallery">
        <?php
        // Display images
        if (!empty($images)) {
            foreach ($images as $image) {
                echo "<div><img src='{$image}' alt='Uploaded Image'></div>";
            }
        } else {
            echo "<p>No images uploaded yet.</p>";
        }
        ?>
    </div>
</body>
</html>
