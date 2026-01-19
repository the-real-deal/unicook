<?php 
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";

function SearchBar(string $id, int $width = 75, string $value = "", bool $showButton=true){
?>

<div class=" d-flex align-items-center mb-5 w-100">
    <label for="search-<?= $id?>" hidden>Text input for search purpose</label>
    <input id="search-<?= $id?>" name="query" type="search" placeholder="Search Recipes..." class="me-2 w-<?= $width?>" value="<?= $value?>"/>
    <?php 
        if($showButton){

    ?>
    <label for="btn-<?= $id?>" hidden>Button to start the search</label>
    <input id="btn-<?= $id?>" type="submit" class="px-4 w-auto" value="Search" />
    <?php 
        }   
    ?>
</div>

<?php } ?>