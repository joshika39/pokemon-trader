<?php

require("db/stores.php");

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
</header>
<div id="content">
    <div id="details">
        <div class="image clr-electric">
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