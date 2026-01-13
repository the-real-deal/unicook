<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";

function CategoryCard(string $id, string $title, int $nRecipes, string $path) {
?>
<li class="col-6 col-md-4 col-lg-2 category-card py-1">
                    <!-- href="http://www.google.it" -->
    <a href="#" class="my-1">
        <div class="d-flex justify-content-center align-items-center">
            <img src="/assets/<?php echo $path?>" alt="" class="w-50">
        </div>
        <?php echo $title?>
        <p><?php echo $nRecipes?> Recipes</p>
    </a>
</li>
<?php } ?>