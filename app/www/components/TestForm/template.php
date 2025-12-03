<?php
requireComponent(rootPath('components', 'FileInput'));
?>

<form 
  id="<?= $id ?>" 
  action="/api/upload.php" 
  method="post" 
  enctype="multipart/form-data">
  Select image to upload:
  <?= FileInput('fileToUpload', FileType::Image); ?>
  <br />
  <input type="submit" value="Upload Image" name="submit" />
</form>
 