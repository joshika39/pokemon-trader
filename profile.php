<?php

require "db/stores.php";
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$users = new UserStorage();

$user = $users->findOne(["username" => $_SESSION["user"]]);

if (!$user) {
    header("Location: login.php");
    exit();
}

$isAdmin = $user["username"] == "admin";

if(!$isAdmin){
    $cardsIds = $user["cards"];
    $cards = new CardsStorage();
// convert card ids to card objects
    $userCards = array_map(function ($id) use ($cards) {
        return $cards->findOne(["id" => $id]);
    }, $cardsIds);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IKémon | Profil</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>
<body>
<header>
    <h1><a href="index.php">IKémon</a> > Profil</h1>
    <nav>
        <ul>
            <li><a href="logout.php">Kijelentkezés</a></li>
        </ul>
    </nav>
</header>
<div id="content">
    <div id="profile">
        <?php if($isAdmin){ ?>
            <h2>Adminisztrátori felület</h2>
        <?php } else { ?>
        <h2>Felhasználói adatok</h2>
        <div class="details">
            <div class="username"><span class="icon">👤</span> Felhasználónév: <?php echo $user["username"] ?></div>
            <div class="name"><span class="icon">👨‍🦱</span> Név: <?php echo $user["name"] ?></div>
            <div class="balance"><span class="icon">💰</span> Egyenleg: <?php echo $user["balance"] ?> IKM</div>
        </div>
        <h2>IKémonok</h2>
        <?php
            foreach ($userCards as $card) {
                $id = $card["id"];
                $hp = $card["hp"];
                $attack = $card["attack"];
                $name = $card["name"];
                $defense = $card["defense"];
                $image = $card["image"];
                $type = $card["type"];
                $description = $card["description"];
                $price = $card["price"];
        ?>
                <div class="pokemon-card">
                    <div class="image clr-electric">
                        <img src="<?php echo $image ?>" alt="">
                    </div>
                    <div class="details">
                        <h2><a href="details.php?id=<?php echo $id?>"><?php echo $name ?></a></h2>
                        <span class="card-type"><span class="icon">🏷</span> <?php echo $type ?></span>
                        <span class="attributes">
                        <span class="card-hp"><span class="icon">❤</span> <?php echo $hp ?></span>
                        <span class="card-attack"><span class="icon">⚔</span> <?php echo $attack ?></span>
                        <span class="card-defense"><span class="icon">🛡</span> <?php echo $defense ?></span>
                    </span>
                    </div>
                    <div class="buy">
                        <span class="card-price"><span class="icon">💰</span> <?php echo $price ?></span>
                    </div>
                </div>
        <?php } ?>
        <?php } ?>
    </div>
</div>
