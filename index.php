<?php
session_start();

require("db/stores.php");

$cards = new CardsStorage();
$cards = $cards->findAll();

$isLoggedIn = isset($_SESSION["user"]);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Home</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>

<body>
<header>
    <h1><a href="index.php">IKémon</a> > Home</h1>
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
    <div id="card-list">
        <?php
        //
        foreach ($cards as $card) {
            $id = $card["id"];
            $name = $card["name"];
            $type = $card["type"];
            $hp = $card["hp"];
            $attack = $card["attack"];
            $defense = $card["defense"];
            $image = $card["image"];
            $description = $card["description"];
            $price = $card["price"];
            ?>
            <div class="pokemon-card">
                <div class="image clr-<?php echo $type ?>">
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
                <?php
                if($isLoggedIn) {
                ?>
                <div class="buy">
                    <span class="card-price"><span class="icon">💰</span> <?php echo $price ?></span>
                </div>
                <?php } ?>
            </div>

        <?php } ?>
    </div>
</div>
<footer>
    <p>IKémon | ELTE IK Webprogramozás</p>
</footer>
</body>

</html>