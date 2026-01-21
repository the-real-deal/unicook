<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/reviews.php";
require_once "lib/users.php";
require_once "lib/core/db.php";
require_once "lib/auth.php";

function Review(
    string $reviewId, 
    string $userId, 
    string $username, 
    int $rating, 
    string $body, 
    DateTime $date, 
    bool $isDeletable = false,
    string $header = "h3",
) {
?>
<article data-reviewid="<?= $reviewId ?>" class="review p-3">
    <div class="d-flex justify-content-between">
        <div class="d-flex align-items-start flex-column mb-2"> 
            <<?= $header ?>><?= $username ?></<?= $header ?>>
            <a href="/profile/?userId=<?= $userId ?>">
                Go to profile
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                </svg>
            </a>
        </div>
        
    <div class="d-flex flex-row align-items-start justify-content-end">
        <div class="d-flex align-items-center gap-1">
            <span>(<?= $rating?>)</span>
            <?php
                $rating = max(1, min(5 , $rating));
                for ($i = 0; $i < $rating; $i++) {
                ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" stroke="currentColor" class="bi bi-star-fill flex-shrink-0" viewBox="-1 -1 18 18">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                </svg>
            <?php 
                }
            ?>
            <?php                 
                for ($i = 0; $i < 5-($rating); $i++) {
                ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="transparent" stroke="oklch(0.36 0 0)" class="bi bi-star-fill flex-shrink-0" viewBox="-1 -1 18 18">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                </svg>
            <?php 
                }
            ?>
        </div>
    </div>
    </div>
    <time datetime="<?=$date->format('Y-m-d') ?>"><?= $date->format('d/m/Y') ?></time>
    <p><?= $body?></p>
    <div class="d-flex justify-content-end">
        <?php if ($isDeletable) { ?>
            <button class="gap-1 d-flex align-items-center" type="button">
                Delete
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3 flex-shrink-0" viewBox="0 0 16 16">
                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                </svg>
            </button>
        <?php } ?>
    </div>
</article>
<?php } ?>