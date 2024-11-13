<?php
// Check if the form is submitted
if (isset($_POST['submit'])) {
    // SQLite database configuration
    $dbPath = 'uploadimagetest.db';  // Path to the SQLite database

    try {
        // Create or open the SQLite database connection
        $conn = new PDO('sqlite:' . $dbPath);
        // Set error mode to exceptions for better error handling
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create table if it does not exist
        $createTableQuery = "CREATE TABLE IF NOT EXISTS images (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            file_path TEXT NOT NULL
        );";
        $conn->exec($createTableQuery); // Execute the table creation query

        // Folder where images will be uploaded
        $uploadDir = 'uploads/';

        // Ensure the upload directory exists, create it if not
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);  // Create directory if it doesn't exist
        }

        // Get the file details from the form submission
        $fileName = basename($_FILES['image']['name']);
        $targetFilePath = $uploadDir . $fileName;  // Full path where the file will be uploaded
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); // Get file extension

        // Allowed file types
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

        // Check if the uploaded file is of an allowed type
        if (in_array(strtolower($fileType), $allowedTypes)) {
            // Attempt to move the uploaded file to the server's upload directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                // Prepare an SQL query to insert the file path into the database
                $query = "INSERT INTO images (file_path) VALUES (:file_path)";
                $stmt = $conn->prepare($query);

                // Bind the file path to the parameter
                $stmt->bindParam(':file_path', $targetFilePath, PDO::PARAM_STR);

                // Execute the query and check if the file path was successfully inserted
                if ($stmt->execute()) {
                    // Redirect to the index page and pass the image filename as a query parameter
                    header("Location: index.html?image=" . urlencode($fileName));
                    exit();
                } else {
                    echo "Error saving image path in the database.";
                }
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
        }

        // Close the database connection
        $conn = null;

    } catch (PDOException $e) {
        // Catch any database-related errors
        echo "Error: " . $e->getMessage();
    }
}
?>
