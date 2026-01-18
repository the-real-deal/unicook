<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";

function Navbar(LoginSession|false $login) {
?>
<nav class="navbar navbar-expand-lg fixed-top px-3 py-2">
    <div class="container-fluid d-flex justify-content-between">
        <div class="container-fluid d-flex justify-content-between">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/home">
                <div class="d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="none" stroke="white"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path
                            d="M17 21a1 1 0 0 0 1-1v-5.35c0-.457.316-.844.727-1.041a4 4 0 0 0-2.134-7.589 5 5 0 0 0-9.186 0 4 4 0 0 0-2.134 7.588c.411.198.727.585.727 1.041V20a1 1 0 0 0 1 1Z" />
                        <path d="M6 17h12"></path>
                    </svg>
                </div>
                <span>Unicook</span>
            </a>

            <button id="svgToggle" class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                    width="35" height="35" x="0" y="0" viewBox="0 0 32 32" 
                    xml:space="preserve" class="">
                    <g
                        transform="matrix(-3.061616997868383e-16,1,1,3.061616997868383e-16,-0.4992274045944214,0.4992274045944214)">
                        <path
                            d="M19.147 2.501c-.253-.029-.495.181-.524.443L17.892 9.5h-.665l-.73-6.556a.5.5 0 0 0-.994 0L14.772 9.5h-.665l-.731-6.556c-.029-.263-.258-.475-.524-.443A.5.5 0 0 0 12.38 3v9.91c0 .877.718 1.59 1.601 1.59h.43l-.789 13.468a2.355 2.355 0 0 0 .637 1.78 2.39 2.39 0 0 0 3.483 0 2.356 2.356 0 0 0 .637-1.778L17.59 14.5h.43c.883 0 1.601-.713 1.601-1.59V3a.501.501 0 0 0-.474-.499zm-1.766 25.528v.003c.025.386-.105.752-.368 1.031-.527.563-1.498.563-2.025 0a1.358 1.358 0 0 1-.368-1.034l.792-13.529h1.176zM18.62 12.91c0 .325-.27.59-.601.59H13.98a.597.597 0 0 1-.601-.59V10.5h1.84a.5.5 0 0 0 .497-.444L16 7.514l.283 2.542a.5.5 0 0 0 .497.444h1.84zM8.906 4.157a1.676 1.676 0 0 0-.505-1.176 1.639 1.639 0 0 0-1.19-.481 1.683 1.683 0 0 0-1.563 1.133L1.614 15.328a2.065 2.065 0 0 0 .121 1.631c.268.511.722.877 1.281 1.032l2.747.746-.483 9.265c-.031.66.206 1.286.665 1.764a2.406 2.406 0 0 0 1.725.734h.021a2.358 2.358 0 0 0 1.717-.764 2.387 2.387 0 0 0 .634-1.794l-1.027-13.96zm-.232 24.9a1.368 1.368 0 0 1-.994.443 1.403 1.403 0 0 1-1.014-.428 1.372 1.372 0 0 1-.388-1.021l.505-9.666c.013-.234-.22-.47-.447-.531l-3.055-.827a1.06 1.06 0 0 1-.722-1.372L6.595 3.956a.672.672 0 0 1 .632-.456h.009c.172 0 .337.068.464.194a.68.68 0 0 1 .206.476l.108 9.855 1.028 13.986a1.39 1.39 0 0 1-.368 1.046zM30.5 8.45c0-3.281-2.019-5.95-4.5-5.95s-4.5 2.669-4.5 5.95c0 2.518 1.156 4.677 2.883 5.528l-.772 13.944a2.37 2.37 0 0 0 .65 1.78c.455.481 1.072.747 1.738.747s1.283-.266 1.738-.747a2.366 2.366 0 0 0 .65-1.78l-.772-13.945c1.736-.863 2.885-3.017 2.885-5.527zm-3.487 20.566c-.531.561-1.494.561-2.025 0a1.385 1.385 0 0 1-.378-1.038l.755-13.637c.412.08.859.08 1.271 0l.755 13.637c.021.389-.114.757-.378 1.038zm-.111-15.795a2.373 2.373 0 0 1-1.366.134 2.311 2.311 0 0 1-.458-.143c-1.517-.577-2.578-2.535-2.578-4.762 0-2.729 1.57-4.95 3.5-4.95s3.5 2.221 3.5 4.95c0 2.227-1.061 4.185-2.598 4.771z"
                            fill="#000000" opacity="1" data-original="#000000" class="" />
                    </g>
                </svg>

            </button>
        </div>


        <!-- MENU -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <div class="navbar-nav d-flex">
                <ul class="navbar-nav d-flex gap-2 my-2 mx-1">
                    <li class="nav-item"><a class="nav-link px-3 py-2" href="/home">Home</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-2" href="/recipes">Recipes</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-2" href="/about">About</a></li>
                    <?php 
                    if ($login !== false) { 
                    ?>
                        <li class="nav-item">
                            <a class="d-flex align-items-center nav-link px-3 py-2" href="/login">
                                Create
                            </a>    
                        </li>
                    <?php } ?>
                </ul>
                <?php  
                if ($login === false) { 
                ?>
                <a id="login"class="d-flex align-items-center nav-link px-3 py-2 my-2 mx-1 " href="/login">
                    <svg class="flex-shrink-0 me-2" xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Login
                </a>
                <?php } else { ?>
                <a class="d-flex align-items-center nav-link px-3 py-2 my-2 mx-1 " href="/profile/?id=<?= $login->user->id ?>">
                    <?= $login->user->username ?>
                    <?php if($login->user->avatarId){ ?>
                    <img class="ms-2" src="/api/users/image/content.php?userId=<?= $login->user->id ?>">
                    <?php } else { ?>
                    <img class="ms-2" src="https://ui-avatars.com/api/?name=<?= $login->user->username ?>">
                    <?php } ?>
                </a>
                <?php }?>
            </div>

        </div>

    </div>
</nav>
<?php } ?>