<?php
require_once "../bootstrap.php";
require_once rootPath("components", "PageOpening.php");
require_once rootPath("components", "HTMLHeader.php");
require_once rootPath("components", "FileInput.php");

PageOpening("Home");
?>
<body>
    <?= HTMLHeader("Hello, World") ?>
    <form
        id="testForm"
        action="/api/files/upload.php"
        method="post"
        enctype="multipart/form-data">
        Select image to upload:
        <?= FileInput("fileToUpload", FileType::Image) ?>
        <br />
        <input type="submit" value="Upload Image" />
    </form>
    <br />
    <pre><code id="uploadedImageMetadata">No file uploaded</code></pre>
    <img id="uploadedImage" alt="uploaded image" />
    <script src="main.js"></script>
</body>
