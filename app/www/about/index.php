<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "components/PageHead.php";
require_once "components/Navbar.php";
require_once "components/Footer.php";
require_once "components/Chat.php";
require_once "components/ErrorNotification.php";
require_once "lib/auth.php";
    
$recipesCount = floorPlus(145);
$studentsCount = floorPlus(298);
$universitiesCount = floorPlus(400);
$averageRating = floorPlus(86);

function floorPlus($var) {
    if ($var >100) {
        $var = (floor($var / 100) * 100);
        return strval($var)."+";
    }
    return strval($var);
}

$db = Database::connectDefault();
$login = LoginSession::autoLogin($db);


?>

<!DOCTYPE html>
<html lang="en">
<?= PageHead("About", [ "style.css", "/css/components/chat.css" ]) ?>
<body id="about-page">
    <?php 
    ErrorNotification();
    Navbar($login);
    Chat();
    ?>
    <!-- ABOUT PAGE-->
    <main>
        <!-- HERO SECTION -->
        <div class="py-5" id="hero-section">
            <div class="container text-center">
                <div class="d-inline-flex align-items-center mb-4 gap-2 py-2 px-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path
                            d="M17 21a1 1 0 0 0 1-1v-5.35c0-.457.316-.844.727-1.041a4 4 0 0 0-2.134-7.589 5 5 0 0 0-9.186 0 4 4 0 0 0-2.134 7.588c.411.198.727.585.727 1.041V20a1 1 0 0 0 1 1Z" />
                        <path d="M6 17h12"></path>
                    </svg>
                    <span>About Unicook</span>
                </div>

                <h1 class="mb-3">Helping Students Cook with Confidence</h1>
                <p class="lead mx-auto">
                    We believe every student deserves to eat well without breaking the bank or spending hours in the
                    kitchen.
                </p>

                <div class="ratio ratio-16x9 mt-4 mx-auto">
                    <img src="/assets/homepage.png"
                        class="object-fit-cover shadow" alt="Students cooking together">
                </div>

            </div>
        </div>

        <!-- MISSION SECTION -->
        <section class="py-5" id="mission-section">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Left column -->
                    <div class="col-md-6">

                        <div class="d-inline-flex align-items-center px-3 py-2 mb-3 gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-target w-4 h-4 " aria-hidden="true">
                                <circle cx="12" cy="12" r="10"></circle>
                                <circle cx="12" cy="12" r="6"></circle>
                                <circle cx="12" cy="12" r="2"></circle>
                            </svg>
                            <span>Our Mission</span>
                        </div>

                        <h2 class="mb-3">Making Home Cooking Accessible for Every Student</h2>

                        <p class="mb-3">
                            Living away from home for the first time can be overwhelming, especially when it comes to
                            cooking.
                            Fast food and takeout might seem easier, but they're expensive and not always healthy.
                        </p>

                        <p class="mb-3">
                            Unicook was born from the simple idea that cooking doesn't have to be complicated or
                            expensive.
                            With the right recipes and a little guidance, anyone can make delicious, nutritious meals.
                        </p>

                        <p>
                            Our goal is to empower students to take control of their nutrition, save money, and discover
                            the
                            joy
                            of cooking — one simple recipe at a time.
                        </p>
                    </div>

                    <!-- Right column -->
                    <div class="col-md-6">
                        <div class="p-4">

                            <h3 class="mb-4">What Makes Us Different</h3>

                            <div class="d-flex gap-3 mb-3">
                                <div class="p-3 flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-book-open w-5 h-5"
                                        aria-hidden="true">
                                        <path d="M12 7v14"></path>
                                        <path
                                            d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="mb-1">Student-Tested Recipes</h4>
                                    <p class=" mb-0">Tested in real dorm kitchens with limited equipment.</p>
                                </div>
                            </div>

                            <div class="d-flex gap-3 mb-3">
                                <div class="p-3 flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-sparkles w-5 h-5 "
                                        aria-hidden="true">
                                        <path
                                            d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z">
                                        </path>
                                        <path d="M20 2v4"></path>
                                        <path d="M22 4h-4"></path>
                                        <circle cx="4" cy="20" r="2"></circle>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="mb-1">No Fancy Ingredients</h4>
                                    <p class=" mb-0">Only simple, affordable ingredients available anywhere.</p>
                                </div>
                            </div>

                            <div class="d-flex gap-3">
                                <div class="p-3 flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-users w-5 h-5" aria-hidden="true">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                        <path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="mb-1">Supportive Community</h4>
                                    <p class=" mb-0">Thousands of students sharing tips and recipes.</p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- VALUES SECTION -->
        <section class="py-5">
            <div class="container ">

                <div class="text-center mb-5">
                    <h2 class="mb-2">Our Values</h2>
                    <p>What drives us every day</p>
                </div>

                <div class="row gx-3">

                    <!-- Student First -->
                    <div class="col-md-4 d-flex py-2">
                        <div class="value-card p-4 text-center flex-grow-1">
                            <div class="p-3 d-inline-block mb-3">
                                <!-- Heart icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-heart w-8 h-8" aria-hidden="true">
                                    <path
                                        d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5">
                                    </path>
                                </svg>
                            </div>
                            <h3>Student-First</h3>
                            <p>
                                Every recipe is designed with college students in mind — simple, affordable, and
                                achievable.
                            </p>
                        </div>
                    </div>

                    <!-- Budget Friendly -->
                    <div class="col-md-4 d-flex py-2">
                        <div class="value-card p-4 text-center flex-grow-1">
                            <div class="p-3 d-inline-block mb-3">
                                <!-- Dollar -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-dollar-sign w-8 h-8"
                                    aria-hidden="true">
                                    <line x1="12" x2="12" y1="2" y2="22"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                            </div>
                            <h3>Budget-Friendly</h3>
                            <p>
                                We know money is tight. We use affordable ingredients found anywhere.
                            </p>
                        </div>
                    </div>

                    <!-- Time Saving -->
                    <div class="col-md-4 d-flex py-2">
                        <div class="value-card p-4 text-center flex-grow-1 ">
                            <div class="p-3 d-inline-block mb-3">
                                <!-- Clock -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-clock w-8 h-8" aria-hidden="true">
                                    <path d="M12 6v6l4 2"></path>
                                    <circle cx="12" cy="12" r="10"></circle>
                                </svg>
                            </div>
                            <h3>Time-Saving</h3>
                            <p>
                                Most recipes take 30 minutes or less. We don't want to waste your time.
                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- TEAM SECTION -->
        <section class="py-5">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="fw-bold mb-2">Meet the Team</h2>
                    <p>Students who understand the struggle</p>
                </div>

                <div class="row g-4">

                    <!-- Spita -->
                    <div class="team-user col-md-4 text-center ">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <img class="object-fit-cover" src="https://ui-avatars.com/api/?name=Ludovico+Spitaleri" alt="">
                        </div>
                        <h3 class="mb-1">Ludovico Spitaleri</h3>
                        <p class="text-success ">Founder & Developer</p>
                        <p>
                            Powered by pasta, cannoli, and very questionable portions.
                        </p>
                    </div>

                    <!-- Gio -->
                    <div class="team-user col-md-4 text-center">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <img class="object-fit-cover" src="https://ui-avatars.com/api/?name=Gioele+Foschi" alt="">
                        </div>
                        <h3 class="mb-1">Gioele Foschi</h3>
                        <p class="text-success ">Founder & Developer</p>
                        <p>
                            Meal prep is my cardio, protein shakes are my fuel, spreadsheets are my therapy.
                        </p>
                    </div>

                    <!-- Tonno -->
                    <div class="team-user col-md-4 text-center">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <img class="object-fit-cover" src="https://ui-avatars.com/api/?name=Matteo+Tonelli" alt="">
                        </div>
                        <h3 class="mb-1">Matteo Tonelli</h3>
                        <p class="text-success ">Founder & Developer</p>
                        <p>
                            Codes with the calm of the trulli and the crunch of tarallini.
                        </p>
                    </div>

                </div>

            </div>
        </section>

        <!-- STATS SECTION -->
        <section class="pt-3 pb-5" id="stats-section">
            <div class="container text-center">
                <h2>Why Us?</h2>
            </div>
            <div class="container">


                <div class="row text-center g-4">

                    <div class="col-6 col-md-3">
                        <span><?= $recipesCount ?></span>
                        <p>Recipes</p>
                    </div>

                    <div class="col-6 col-md-3">
                        <span><?= $studentsCount ?></span>
                        <p>Students</p>
                    </div>

                    <div class="col-6 col-md-3">
                        <span><?= $universitiesCount ?></span>
                        <p>Universities</p>
                    </div>

                    <div class="col-6 col-md-3">
                        <span><?= $averageRating ?></span>
                        <p>Average Rating</p>
                    </div>

                </div>

            </div>
        </section>

        <!-- CTA SECTION -->
        <section class="py-5">
            <div class="container">
                <div class="p-5 text-center" id="section-ending">
                    <h2 class="mb-3">Ready to Start Your Cooking Journey?</h2>
                    <p class="lead mb-4 w-75 mx-auto">
                        Join our community of student cooks and discover how easy and fun cooking can be.
                    </p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="/recipes" class="px-4 py-2">
                            Browse Recipes
                        </a>
                        <a href="/" class="px-4 py-2">
                            Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?= Footer() ?>
    <script type="module" src="/js/bootstrap.js"></script>
</body>
</html>