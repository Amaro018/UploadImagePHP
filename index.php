<?php
// MySQL database configuration
$host = 'localhost';
$user = 'root';  // Your MySQL username
$password = 'amaro@071318';  // Your MySQL password
$database = 'uploadimagetest';  // The database you created

// Connect to MySQL database
$conn = new mysqli($host, $user, $password, $database, 3307); // Adjusted to default port 3306

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all image file paths from the database
$query = "SELECT file_path FROM images";
$result = $conn->query($query);

// Check if images exist
$images = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $images[] = $row['file_path'];
    }
}

// Close the database connection
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
