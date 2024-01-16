<?php

require("db/stores.php");
session_start();

$isLoggedIn = isset($_SESSION["user"]);

if(!$isLoggedIn){
    header("Location: login.php");
    exit();
}

$cards = new CardsStorage();
$id = $_GET["id"];
$card = $cards->findById($id);
$type = $card["type"];
$hp = $card["hp"];
$attack = $card["attack"];
$name = $card["name"];
$defense = $card["defense"];
$image = $card["image"];
$description = $card["description"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | <?php echo $name ?></title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/details.css">
</head>

<body>
<header>
    <h1><a href="index.php">IKémon</a> > <?php echo $name ?></h1>
    <ul>
        <?php if ($isLoggedIn) { ?>
            <li><a class="btn outline" href="profile.php"><?php echo $_SESSION["user"] ?></a></li>
            <li><a class="btn" href="logout.php">Kijelentkezés</a></li>
        <?php } else { ?>
            <li><a class="btn" href="login.php">Bejelentkezés</a></li>
            <li><a class="btn" href="register.php">Regisztráció</a></li>
        <?php } ?>
    </ul>
</header>
<div id="content">
    <div id="details">
        <div class="image clr-<?php echo $type ?>">
            <img src="<?php echo $image ?>" alt="">
        </div>
        <div class="info">
            <div class="description"><?php echo $description ?></div>
            <span class="card-type"><span class="icon">🏷</span> Type: <?php echo $type ?></span>
            <div class="attributes">
                <div class="card-hp"><span class="icon">❤</span> Health: <?php echo $hp ?></div>
                <div class="card-attack"><span class="icon">⚔</span> Attack: <?php echo $attack ?></div>
                <div class="card-defense"><span class="icon">🛡</span> Defense: <?php echo $defense ?></div>
            </div>
        </div>
    </div>
</div>
<footer>
    <p>IKémon | ELTE IK Webprogramozás</p>
</footer>
</body>
</html>