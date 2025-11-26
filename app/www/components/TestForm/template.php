<?php
// TODO: separate file input component
$acceptString = arrayString(array_map(fn($mime) => $mime->value, UploadType::Image->allowedMimes()));
?>

<form action="testupload.php" method="post" enctype="multipart/form-data">
  Select image to upload:
  <input type="file" name="fileToUpload" id="fileToUpload" accept="<?= $acceptString ?>" />
  <input type="submit" value="Upload Image" name="submit" />
</form>
 