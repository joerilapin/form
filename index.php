<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);

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

//Validate email
//validate address
$emailErr = "";
$streetErr="";
$cityErr="";
$zipErr="";
$numberErr="";
//variables
$email= "";
$street="";
$city="";
$number="";
$zipNumber="";

$products=[];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Check if email field is empty
    //POST sends data
    if (empty($_POST["email"])) {
        $emailErr = "Please fill in email";
    } else {
        $email = test_input($_POST["email"]);
        //Check if this email format is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    if (empty($_POST["street"])) {
        $streetErr = "Please fill in address";

    }
//uit W3schools een soort test op input
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;

    }
}
//showing food or drinks

//isset — Determine if a variable is declared and is different
// than NULL
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET['food'])) {
            $showfood = htmlspecialchars($_GET['food']);

            if ($showfood) {
                $products = [
                    ['name' => 'Club Ham', 'price' => 3.20],
                    ['name' => 'Club Cheese', 'price' => 3],
                    ['name' => 'Club Cheese & Ham', 'price' => 4],
                    ['name' => 'Club Chicken', 'price' => 4],
                    ['name' => 'Club Salmon', 'price' => 5]
                ];

            } else {


                $products = [
                    ['name' => 'Cola', 'price' => 2],
                    ['name' => 'Fanta', 'price' => 2],
                    ['name' => 'Sprite', 'price' => 2],
                    ['name' => 'Ice-tea', 'price' => 3],
                ];
            }

        } else {
            $products = [
                ['name' => 'Club Ham', 'price' => 3.20],
                ['name' => 'Club Cheese', 'price' => 3],
                ['name' => 'Club Cheese & Ham', 'price' => 4],
                ['name' => 'Club Chicken', 'price' => 4],
                ['name' => 'Club Salmon', 'price' => 5]
            ];
        }
    }


    $totalValue = 0;

    require 'form-view.php';



