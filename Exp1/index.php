<!DOCTYPE html>
<html>
<head>
    <title>Image Upload and Display</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            display: flex;
        }
        .upload-section,
        .display-section {
            flex: 1;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-right: 20px; /* Spacing between sections */
        }
        .upload-section h2,
        .display-section h2,
        .display-section h3 {
            color: #333;
        }
        .upload-section input[type="file"] {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .upload-section input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            margin-top: 10px;
        }
        .upload-section input[type="submit"]:hover {
            background-color: #45a049;
        }
        .display-section img {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
            border-radius: 4px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .display-section a {
            color: #007bff;
            text-decoration: none;
            display: block;
            margin-bottom: 5px;
        }
        .display-section a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="upload-section">
            <h2>Upload Image</h2>
            <form action="" method="post" enctype="multipart/form-data">
                Select image to upload:
                <input type="file" name="fileToUpload" id="fileToUpload">
                <input type="submit" value="Upload Image" name="submit">
            </form>

            <?php
    $targetDirectory = "uploads/";
    $uploadOk = 1;
    
    if(isset($_POST["submit"])) {
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0777, true); 
        }

        if (!empty($_FILES["fileToUpload"]["tmp_name"])) {
            $targetFile = $targetDirectory . basename($_FILES["fileToUpload"]["name"]);
            $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            if ($_FILES["fileToUpload"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
                    echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        } else {
            echo "Please select a file to upload.";
        }
    }
    ?>
        </div>
        <div class="display-section">
            <h2>View Image</h2>
            <?php
            if(isset($_GET['image'])) {
                $image = $_GET['image'];
                if (file_exists($targetDirectory . $image)) {
                    echo "<img src='uploads/$image' alt='Uploaded Image'>";
                } else {
                    echo "Image not found.";
                }
            }
            ?>
            <h3>Uploaded Images:</h3>
            <?php
            if (file_exists($targetDirectory)) {
                $images = scandir($targetDirectory);
                foreach($images as $image) {
                    if($image != '.' && $image != '..') {
                        echo "<a href='?image=$image'>$image</a><br>";
                    }
                }
            } else {
                echo "No images uploaded yet.";
            }
            ?>
        </div>
    </div>
</body>
</html>
