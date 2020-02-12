<?php

declare(strict_types=1);

//Catching initial load error
if (!(isset($_GET['food']))) {
    $_GET['food'] = 1;
}

if (!(isset($_COOKIE["totalOrder"]))){
    $_COOKIE["totalOrder"] = 0;
}

//Catching initial load error
if (isset($_POST['products'])) {
    $checkedBoxes = $_POST['products'];
} else {
    $checkedBoxes = [];
}

// Function to get the price of checked boxes in array
function getPrice(array $inputArray, array $checkBox): float
{
    $totalOrder = [];

    for ($i = 0; count($inputArray[$_GET['food']]) > $i; $i++) {
        if (isset($checkBox[$i])) {
            array_push($totalOrder, $inputArray[$_GET['food']][$i]['price']);
        }
    }
    return array_sum($totalOrder);
}

//  When the form is submitted, check for empty fields
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errorArray = [];
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $emailErr = "E-mail is required";
        $email = "";
        array_push($errorArray, $emailErr);
        $emailErrHTML = "<p class='alert alert-danger position-relative'> Email is required </p>";
    } else {
        $email = $_POST["email"];
        $_SESSION["email"] = $email;
        $emailErr = "";
        array_push($errorArray, $emailErr);
        $emailErrHTML = "<p class='alert alert-success position-relative'> Field is ok </p>";
    }

    if (empty($_POST["street"])) {
        $streetErr = "Street name is required";
        array_push($errorArray, $streetErr);
        $streetErrHTML = "<p class='alert alert-danger position-relative'> Street name is required </p>";

    } else {
        $street = ($_POST["street"]);
        $_SESSION["street"] = $street;
        $streetErr = "";
        array_push($errorArray, $streetErr);
        $streetErrHTML = "<p class='alert alert-success position-relative'> Field ok </p>";
    }

    if (empty($_POST["streetNumber"])) {
        $streetNumberErr = "Fill out a street number";
        array_push($errorArray, $streetNumberErr);
        $streetNumberErrHTML = "<p class='alert alert-danger position-relative'> Street number is required </p>";

    } else {
        if (ctype_digit($_POST["streetNumber"])) {
            $streetNumberErr = "";
            $streetNumber = ($_POST["streetNumber"]);
            $_SESSION["streetNumber"] = $streetNumberErr;
            array_push($errorArray, $emailErr);
            $streetNumberErrHTML = "<p class='alert alert-success position-relative'> Field ok </p>";
        } else {
            $streetNumberErr = "Please enter a valid street number";
            $_SESSION["streetNumber"] = "";
            array_push($errorArray, $streetNumberErr);
            $streetNumberErrHTML = "<p class='alert alert-danger position-relative'> Please enter a valid street number </p>";
        }
    }

    if (empty($_POST["city"])) {
        $cityErr = "Fill out a valid city.";
        array_push($errorArray, $cityErr);
        $cityErrHTML = "<p class='alert alert-danger position-relative'> Please enter a city </p>";

    } else {
        $city = ($_POST["city"]);
        $_SESSION["city"] = $city;
        $cityErr = "";
        array_push($errorArray, $cityErr);
        $cityErrHTML = "<p class='alert alert-success position-relative'> Field ok </p>";
    }
    if (empty($_POST["zipcode"])) {
        $zipcodeErr = "Fill out a valid zipcode.";
        array_push($errorArray, $zipcodeErr);
        $zipcodeErrHTML = "<p class='alert alert-danger position-relative'> Please enter a valid zipcode </p>";
    } else {
        $zipcode = ($_POST["zipcode"]);
        $_SESSION["zipcode"] = $zipcode;
        $zipcodeErr = "";
        array_push($errorArray, $zipcodeErr);
        $zipcodeErrHTML = "<p class='alert alert-success position-relative'> Field ok </p>";
    }
    if (empty($_POST['express'])) {
        $_GET['express'] = 0;
        $deliveryTime = "in 2 hours";
    } else {
        $express = true;
        $deliveryTime = "in 45 mins";
    }
    if (hasNoErrors($errorArray)) {
        echo "<p class='bg-success'> Your order will be delivered " . $deliveryTime . "</p>";
        $totalValue = getPrice($products, $checkedBoxes);
        $subject = 'Your delivery';
        $message = 'Thank you for your order. Please be ready to pay' . getPrice($products, $checkedBoxes) . 'The estimated delivery time will be ' . $deliveryTime;
        mail($email, $subject, $message);

        // Get the price of checked boxes with food
        if (!(isset($_COOKIE['totalOrder']))) {
            setcookie("totalOrder", strval(getPrice($products, $checkedBoxes)));
        } else {
            $totalPrice = $_COOKIE['totalOrder'] + getPrice($products, $checkedBoxes);
            setcookie("totalOrder", strval((getPrice($products, $checkedBoxes))+$_COOKIE['totalOrder']));
        }
    } else {

        echo "There are some errors, check the document again";
    }
} else {
    $_SESSION["email"] = "";
    $_SESSION["street"] = "";
    $_SESSION["streetNumber"] = "";
    $_SESSION["city"] = "";
    $_SESSION["zipcode"] = "";
}
function hasNoErrors(array $array): bool
{
    for ($i = 0; count($array) > $i; $i++) {
        if ($array[$i] !== "") {
            return false;
        }
    }
    return true;
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" type="text/css"
          rel="stylesheet"/>
    <title>Order food & drinks</title>
</head>
<body>
<div class="container">
    <h1>Order food in restaurant "the Personal VEGAN Processors"</h1>
    <nav>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link active" href="?food=1">Order food</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?food=0">Order drinks</a>
            </li>
        </ul>
    </nav>
    <form method="post">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="email">E-mail:</label>
                <input type="text" id="email" name="email" value="<?php echo $_SESSION['email'] ?>"
                       class="form-control"/>
                <?php echo isset($emailErrHTML) ? $emailErrHTML : ""; ?>
            </div>
            <div></div>
        </div>

        <fieldset>
            <legend>Address</legend>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="street">Street:</label>
                    <input type="text" name="street" id="street" class="form-control"
                           value="<?php echo $_SESSION['street'] ?>"><?php echo isset($streetErrHTML) ? $streetErrHTML : ""; ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="streetnumber">Street number:</label>
                    <input type="number" id="streetnumber" name="streetNumber"
                           value="<?php echo $_SESSION['streetNumber'] ?>" class="form-control">
                    <?php echo isset($streetNumberErrHTML) ? $streetNumberErrHTML : ""; ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" class="form-control"
                           value="<?php echo $_SESSION['city'] ?>">
                    <?php echo isset($cityErrHTML) ? $cityErrHTML : ""; ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="zipcode">Zipcode</label>
                    <input type="number" id="zipcode" name="zipcode" class="form-control"
                           value="<?php echo $_SESSION['zipcode'] ?>">
                    <?php echo isset($zipcodeErrHTML) ? $zipcodeErrHTML : ""; ?>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Products</legend>
            <?php foreach ($products[$_GET['food']] AS $i => $product): ?>
                <label>
                    <input type="checkbox" value="1" name="products[<?php echo $i ?>]"/> <?php echo $product['name'] ?>
                    -
                    &euro; <?php echo number_format($product['price'], 2) ?></label><br/>
            <?php endforeach; ?>
        </fieldset>
        <p>Do you wish to order express?</p> <input type="checkbox" value="1" name="express"><label
            for="express">yes</label>
        <button type="submit" class="btn btn-primary" name="submit">Order!</button>
    </form>

    <footer>You already ordered <strong>&euro; <?php echo isset($totalPrice) ? $totalPrice : "0"; ?></strong> in food and drinks.
    </footer>
</div>

<style>
    footer {
        text-align: center;
    }
</style>
</body>
</html>