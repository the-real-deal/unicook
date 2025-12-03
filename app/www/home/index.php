<?php
require_once '../bootstrap.php';

requireComponent(rootPath('components', 'PageOpening'));
requireComponent(rootPath('components', 'HTMLHeader'));
requireComponent(rootPath('components', 'TestForm'));

echo PageOpening('Home');
?>
<body>
    <?= HTMLHeader('Test title') ?>
    <?= TestForm('testForm') ?>
    <br />
    <pre>
        <code id="uploadedImageMetadata">No file uploaded</code>
    </pre>
    <img id="uploadedImage" alt="uploaded image" />
    <script src="main.js"></script>
</body>
