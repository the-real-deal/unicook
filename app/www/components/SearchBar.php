<?php 
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";

function SearchBar(string $id, string $value = ""){
?>

<div class=" d-flex align-items-center w-100 p-2">
    <img src="/assets/search.svg" alt="Search bar" class="mx-2">
    <label for="search-<?= $id?>" hidden>Text input for search purpose</label>
    <input id="search-<?= $id?>" name="query" type="search" placeholder="Search Recipes..." class="w-100"  value="<?= $value?>"/>
</div>
    

<?php } ?>