<?php

require "db/stores.php";
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$users = new UserStorage();
$cards = new CardsStorage();

$user = $users->findOne(["username" => $_SESSION["user"]]);

if (!$user) {
    header("Location: login.php");
    exit();
}

$isAdmin = $user["username"] == "admin";

if (!$isAdmin) {
    $cardsIds = $user["cards"];
    $cards = new CardsStorage();
// convert card ids to card objects
    $userCards = array_map(function ($id) use ($cards) {
        return $cards->findOne(["id" => $id]);
    }, $cardsIds);
}

$errors = [];

if (isset($_POST["name"])) {
    $name = $_POST["name"];
    $hp = $_POST["hp"];
    $attack = $_POST["attack"];
    $defense = $_POST["defense"];
    $type = $_POST["type"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $image = $_POST["image"];


    if (!$name || !$hp || !$attack || !$defense || !$type || !$description || !$price || !$image) {
        $errors[] = "Minden mezot kotelezo kitolteni!";
    } else if (!is_numeric($hp) || !is_numeric($attack) || !is_numeric($defense) || !is_numeric($price)) {
        $errors[] = "A HP, Attack, Defense es a Price mezok szamokat kell tartalmazzanak!";
    } else {
        $cards = new CardsStorage();
        $cards->add([
            "name" => $name,
            "hp" => $hp,
            "attack" => $attack,
            "defense" => $defense,
            "type" => $type,
            "description" => $description,
            "price" => $price,
            "image" => $image
        ]);
    }
}

if (isset($_POST["id"])) {
    $card = $cards->findById($_POST["id"]);

    if (!$card) {
        $errors[] = "Nincs ilyen kártya!";
    } else {
        $user["balance"] += $card["price"] * 0.9;
        // Remove the card from the user
        $user["cards"] = array_filter($user["cards"], function ($id) use ($card) {
            return $id != $card["id"];
        });
        $users->update($user["id"], $user);
        $card["owner"] = "";
        $cards->update($card["id"], $card);
        header("Location: profile.php");
    }
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
    <?php foreach ($errors as $error) { ?>
        <div class="error"><?php echo $error ?></div>
    <?php } ?>
    <ul>
        <li><a class="btn" href="logout.php">Kijelentkezés</a></li>
    </ul>
</header>
<div id="content">
    <div id="profile">
        <?php if ($isAdmin){ ?>
        <h2>Adminisztrátori felület</h2>
        <h3>Uj kartya letrehozasa</h3>

        <?php if (count($errors) > 0) { ?>
            <div class="errors">
                <?php foreach ($errors as $error) { ?>
                    <div class="error"><?php echo $error ?></div>
                <?php } ?>
            </div>
        <?php } ?>

        <form action="" method="post">
            <label for="name">Nev</label>
            <input type="text" name="name" id="name">
            <label for="hp">HP</label>
            <input type="number" name="hp" id="hp">
            <label for="attack">Attack</label>
            <input type="number" name="attack" id="attack">
            <label for="defense">Defense</label>
            <input type="number" name="defense" id="defense">
            <label for="type">Type</label>
            <input type="text" name="type" id="type">
            <label for="description">Description</label>
            <input type="text" name="description" id="description">
            <label for="price">Price</label>
            <input type="number" name="price" id="price">
            <label for="image">Image</label>
            <input type="text" name="image" id="image">
            <input type="submit" value="Letrehoz">
            <?php } else { ?>
                <h2>Felhasználói adatok</h2>
                <div class="details">
                    <div class="username"><span class="icon">👤</span> Felhasználónév: <?php echo $user["username"] ?>
                    </div>
                    <div class="name"><span class="icon">👨‍🦱</span> Név: <?php echo $user["name"] ?></div>
                    <div class="email"><span class="icon">📧</span> Email: <?php echo $user["email"] ?></div>
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
                        <div class="image clr-<?php echo $type ?>">
                            <img src="<?php echo $image ?>" alt="">
                        </div>
                        <div class="details">
                            <h2><a href="details.php?id=<?php echo $id ?>"><?php echo $name ?></a></h2>
                            <span class="card-type"><span class="icon">🏷</span> <?php echo $type ?></span>
                            <span class="attributes">
                        <span class="card-hp"><span class="icon">❤</span> <?php echo $hp ?></span>
                        <span class="card-attack"><span class="icon">⚔</span> <?php echo $attack ?></span>
                        <span class="card-defense"><span class="icon">🛡</span> <?php echo $defense ?></span>
                    </span>
                        </div>
                        <form class="buy resell" action="" method="post">
                            <input type="hidden" name="id" value="<?php echo $id ?>">
                            <button type="submit">
                            <span class="card-price"><span
                                        class="icon">💰</span> <?php echo($price * 0.9) ?> 🔻</span>
                            </button>
                        </form>
                    </div>
                <?php } ?>
            <?php } ?>
    </div>
</div>
