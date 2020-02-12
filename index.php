<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

//we are going to use session variables so we need to enable sessions
session_start();

function whatIsHappening() {
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
}

//your products with their price.

$products0 = [
    ['name' => 'Cola', 'price' => 2],
    ['name' => 'Fanta', 'price' => 2],
    ['name' => 'Sprite', 'price' => 2],
    ['name' => 'Ice-tea', 'price' => 3],
];
$products1 = [
    ['name' => 'Club vegan cheese', 'price' => 3.20],
    ['name' => 'Club vegan Cheese', 'price' => 3],
    ['name' => 'Club vegan Cheese & Ham', 'price' => 4],
    ['name' => 'Club vegan Chicken', 'price' => 4],
    ['name' => 'Club vegan Salmon', 'price' => 5]
];

$products = [$products0, $products1];

$totalValue = 0;

require 'form-view.php';