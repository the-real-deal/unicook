<?php
require_once '../bootstrap.php';

requireComponent(rootPath('components', 'PageOpening'));
requireComponent(rootPath('components', 'HTMLHeader'));
requireComponent(rootPath('components', 'FileInput'));

echo PageOpening('Home');
?>
<body>
    <?= HTMLHeader('Test title') ?>
    <form 
        id="testForm" 
        action="/api/files/upload.php" 
        method="post" 
        enctype="multipart/form-data">
        Select image to upload:
        <?= FileInput('fileToUpload', FileType::Image); ?>
        <br />
        <input type="submit" value="Upload Image" />
    </form>
    <br />
    <pre><code id="uploadedImageMetadata">No file uploaded</code></pre>
    <img id="uploadedImage" alt="uploaded image" />
    <script src="main.js"></script>
</body>
