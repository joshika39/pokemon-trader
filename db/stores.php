<?php

require("lib/Storage.php");

class UserStorage extends Storage {
    public function __construct()
    {
        parent::__construct(new JsonIO("data/users.json"));
    }
}

class CardsStorage extends Storage {
    public function __construct()
    {
        parent::__construct(new JsonIO("data/cards.json"));
    }
}