<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";

function Review(string $id, string $username, int $rating, DateTime $date, string $text) {
?>
<article class="review p-3">
    <div class="d-flex justify-content-between">
        <h3><?= $username ?></h3>
        <div>
            <span>(<?= $rating?>)</span>
            <?php
                $rating = min(5 ,$rating);
                for ($i = 0; $i < $rating; $i++) {
                ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" stroke="currentColor" class="bi bi-star-fill" viewBox="-1 -1 18 18">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                </svg>
            <?php 
                }
            ?>
            <?php                 
                for ($i = 0; $i < 5-$rating; $i++) {
                ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="transparent" stroke="oklch(0.36 0 0)" class="bi bi-star-fill" viewBox="-1 -1 18 18">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                </svg>
            <?php 
                }
            ?>
        </div>
    </div>
    <time datetime="<?= $date->format('Y-m-d') ?>"><?= $date->format('d/m/Y') ?></time>
    <p><?= $text?></p>
</article>
<?php } ?>