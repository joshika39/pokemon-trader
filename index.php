<?php
session_start();

require("db/stores.php");

$cards = new CardsStorage();
$cardsList = $cards->findAll();

$isLoggedIn = isset($_SESSION["user"]);

if ($isLoggedIn) {
    $users = new UserStorage();
    $user = $users->findOne(["username" => $_SESSION["user"]]);
}

$errors = [];

$filter = "";

if (isset($_GET["filter"])) {
    $filter = $_GET["filter"];
    if ($filter == "") {
        $cardsList = $cards->findAll();
    } else {
        $cardsList = $cards->findAll(["type" => $filter]);
    }
}

if (isset($_POST["id"])) {
    if ($isLoggedIn) {
        $users = new UserStorage();
        $user = $users->findOne(["username" => $_SESSION["user"]]);
    } else {
        header("Location: login.php");
        exit();
    }

    $id = $_POST["id"];
    echo $id;
    $card = $cards->findById($id);

    if (!$card) {
        $errors[] = "Nincs ilyen kártya!";
    } else if ($user["balance"] < $card["price"]) {
        $errors[] = "Nincs elég pénzed!";
    } // reject if the user already have 5 cards
    else if (count($user["cards"]) >= 5) {
        $errors[] = "Nem vehetsz több kártyát! (max 5)";
    } else {
        $user["balance"] -= $card["price"];
        $user["cards"][] = $card["id"];
        $users->update($user["id"], $user);
        $_SESSION["user"] = $user["username"];
        $card["owner"] = $user["id"];
        $cards->update($card["id"], $card);
        header("Location: index.php?filter=$filter");
    }
}

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

    <?php if ($isLoggedIn) { ?>
        <div class="balance"><span class="icon">💰</span> Egyenleg: <?php echo $user["balance"] ?> IKM</div>

        <?php foreach ($errors as $error) { ?>
            <div class="error"><?php echo $error ?></div>
        <?php } ?>
    <?php } ?>

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
    <form class="filter-search" action="" method="get">
        <label for="filter">Keresés típus alapján:</label>
        <input type="text" name="filter" id="filter">
        <button type="submit">Keresés</button>
    </form>
    <div id="card-list">
        <?php
        foreach ($cardsList as $card) {
            $id = $card["id"];
            $sold = $card["owner"] != "";

            $own = false;

            if ($isLoggedIn && $card["owner"] == $user["id"]) {
                $own = true;
            }

            $buttonStyle = "";

            if ($sold && !$own) {
                $buttonStyle = "sold";
            } else if ($own) {
                $buttonStyle = "own";
            }

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
                    <h2><a href="details.php?id=<?php echo $id ?>"><?php echo $name ?></a></h2>
                    <span class="card-type"><span class="icon">🏷</span> <?php echo $type ?></span>
                    <span class="attributes">
                        <span class="card-hp"><span class="icon">❤</span> <?php echo $hp ?></span>
                        <span class="card-attack"><span class="icon">⚔</span> <?php echo $attack ?></span>
                        <span class="card-defense"><span class="icon">🛡</span> <?php echo $defense ?></span>
                    </span>
                </div>
                <?php
                if ($isLoggedIn) {
                    ?>

                    <form class="buy <?php echo $buttonStyle ?>" action="" method="post">
                        <input type="hidden" name="id" value="<?php echo $id ?>">
                        <button type="submit" <?php if ($sold) echo "disabled" ?>>
                            <?php if ($own) { ?>
                                <span class="card-price"><span class="icon">✅</span></span>
                            <?php } else if($sold) { ?>
                                <span class="card-price"><span class="icon">⛔</span></span>
                            <?php } else { ?>
                                <span class="card-price"><span class="icon">💰</span> <?php echo $price ?></span>
                            <?php } ?>
                        </button>
                    </form>

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