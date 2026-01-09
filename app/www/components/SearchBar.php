<?php 
require_once "{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php";

function SearchBar(string $id, bool $center = false){
// <?php if($center){echo "justify-content-center";}?\>
?>

<div class=" d-flex align-items-center mb-5 w-100">
    <label for="search-<?php echo $id?>" hidden>Text input for search purpose</label>
    <input id="search-<?php echo $id?>" type="search" placeholder="Search Recipes..." class="me-2 w-75" />
    <label for="btn-<?php echo $id?>" hidden>Button to start the search</label>
    <input id="btn-<?php echo $id?>" type="submit" class="px-4 w-auto" value="Search" />
</div>

<?php }?>