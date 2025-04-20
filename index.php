<?php

include 'db_connect.php';

$sql = "SELECT * FROM articles WHERE is_selected = 1";
$stmt = $pdo_connect->prepare($sql);
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($articles)) {
    $show_carousel = true;
} else {
    $show_carousel = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-YUe2LzesAfftltw+PEaao2tjU/QATaW/rOitAq67e0CT0Zi2VVRL0oC4+gAaeBKu" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="home-styles.css">
    <style>
      .card-deck {
    display: flex;
    flex-wrap: wrap; 
    justify-content: space-between; 
}

.card {
    width: calc(20% - 20px);
    margin-bottom: 20px;  
}

@media (max-width: 1200px) {
    .card {
        width: calc(25% - 20px); 
    }
}

@media (max-width: 992px) {
    .card {
        width: calc(33.33% - 20px); 
    }
}

@media (max-width: 768px) {
    .card {
        width: calc(50% - 20px); 
    }
}

@media (max-width: 576px) {
    .card {
        width: 100%;
    }
}
    </style>
</head>
<body>

<header class="topbar">
    <div class="logo">Logo</div>
    <nav class="nav-links">
        <a href="#">Home</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<section class="welcome">
    <h1>Welcome to Uni of XXX Magazine Collection Site</h1>
    <a href="#magazines"><button type="button" class="btn magazine-btn ">See our magazines</button></a>
</section>

<section class="past-magazines" id="magazines">
    <h2>Past Magazines</h2>

    <?php if ($show_carousel): ?>
        <!-- If no articles found, show the carousel -->
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="slide-img" src="images/magazine_cover1.jpg">
                    <img class="slide-img" src="images/magazine_cover2.jpg">
                    <img class="slide-img" src="images/magazine_cover3.jpg">
                </div>
                <div class="carousel-item">
                    <img class="slide-img" src="images/magazine_cover4.jpg">
                    <img class="slide-img" src="images/magazine_cover5.jpg">
                    <img class="slide-img" src="images/magazine_cover1.jpg">
                </div>
                <div class="carousel-item">
                    <img class="slide-img" src="images/magazine_cover2.jpg">
                    <img class="slide-img" src="images/magazine_cover3.jpg">
                    <img class="slide-img" src="images/magazine_cover4.jpg">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    <?php else: ?>
        <!-- If articles are found, show them as cards -->
        <div class="card-deck">
            <?php foreach ($articles as $article): ?>
                <div class="card">
                <img class="card-img-top" src="<?= $article['image_file'] ?>" alt="Magazine Image" style="cursor:pointer;" onclick="showImageModal('<?= $article['image_file'] ?>')">

                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($article['title']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($article['description']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
   function showImageModal(imageUrl) {
        Swal.fire({
            imageUrl: imageUrl,
            imageAlt: 'Preview',
            showCloseButton: true,
            showConfirmButton: false,
            width: 'auto',
            padding: '1em',
        });
    }
</script>
</body>
</html>
