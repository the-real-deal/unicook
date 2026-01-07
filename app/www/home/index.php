<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php";
require_once "components/PageOpening.php";
require_once "components/HTMLHeader.php";
require_once "components/FileInput.php";

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
    <br/>
    <pre><code id="uploadedImageMetadata">No file uploaded</code></pre>
    <img id="uploadedImage" alt="uploaded image" />
    <script type="module" src="main.js"></script>
</body>
