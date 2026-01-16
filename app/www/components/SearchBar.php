<?php 
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";

function SearchBar(string $id, int $width = 75){
// <?php if($center){echo "justify-content-center";}?\>
?>

<div class=" d-flex align-items-center mb-5 w-100">
    <label for="search-<?= $id?>" hidden>Text input for search purpose</label>
    <input id="search-<?= $id?>" type="search" placeholder="Search Recipes..." class="me-2 w-<?= $width?>" />
    <label for="btn-<?= $id?>" hidden>Button to start the search</label>
    <input id="btn-<?= $id?>" type="submit" class="px-4 w-auto" value="Search" />
</div>

<?php } ?>