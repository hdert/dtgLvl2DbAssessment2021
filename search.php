<?php
require "header.php";
// print the header with a custom title and tell the header which
// page this is so that it can set it as the active page in the navbar.
printHeader("CGS | Search", 2);
// Set a function that checks if an array item exists and isn't blank.
function isValid($array_item_name, $array)
{
    if (array_key_exists($array_item_name, $array)) {
        if ($array[$array_item_name] != '') {
            return 1;
        };
    };
    return 0;
};
// check if the query and price fields are valid, if they are, set variables to their
// values so that the values entered by the user are there after they press search.
$query = '';
$price = '';
if (isValid('query', $_POST)) {
    $query = "value='".$_POST['query']."'";
};
if (isValid('price', $_POST)) {
    $price = "value='".$_POST['price']."'";
};
// Set the checked radio button, this checks if priceSort exists and sets the radio
// button to that for continuity between searches, if not it sets it to the first
// option.
$lessThan = '';
$equals = '';
$greaterThan = '';
if (isValid('priceSort', $_POST)) {
    switch ($_POST['priceSort']) {
    case "equals":
        $equals = "checked";
        break;
    case "greaterThan":
        $greaterThan = "checked";
        break;
    default:
        $lessThan = "checked";
        break;
    };
} else {
    $lessThan = "checked";
}
// This does the same thing as above but with the category select field in the form.
$all = '';
$comics = '';
$boardgames = '';
if (isValid('productType', $_POST)) {
    switch ($_POST['productType']) {
    case "comics":
        $comics = "checked";
        break;
    case "boardgames":
        $boardgames = "checked";
        break;
    default:
        $all = "checked";
        break;
    };
} else {
    $all = "checked";
}
// print out the search form with substituted values for continuity.
// This also prints the opening tags for the container that contains the results.
   print '
    <div class="container-fluid col-8 mt-5 pt-5">
      <form action="search.php" method="post" class="d-flex flex-wrap">
        <input class="form-control form-control-lg col-12"
        type="search" name="query" placeholder="Search"
        aria-label="Search" '.$query.'>
        <div class="input-group input-group-sm mt-3">
          <span class="input-group-text">$</span>
          <input class="form-control form-control-sm col-4"
          type="number" name="price" min="0.50"
          max="9999.00" step="0.50" aria-label="price" placeholder="1.00" '.$price.'>
          <input type="radio" class="btn-check"
          name="priceSort" id="lessThan" value="lessThan" '.$lessThan.'>
          <label class="btn btn-primary" for="lessThan">Less Than</label>
          <input type="radio" class="btn-check"
          name="priceSort" id="equals" value="equals" '.$equals.'>
          <label class="btn btn-primary" for="equals">Equals</label>
          <input type="radio" class="btn-check"
          name="priceSort" id="greaterThan" value="greaterThan" '.$greaterThan.'>
          <label class="btn btn-primary" for="greaterThan">Greater Than</label>
        </div>
        <div class="input-group input-group-sm mt-3 d-flex justify-content-center">
            <input type="radio" class="btn-check"
            name="productType" id="all" value="" '.$all.'>
            <label class="btn btn-primary rounded-start"
            for="all">All Products</label>
            <input type="radio" class="btn-check"
            name="productType" id="comics" value="comics" '.$comics.'>
            <label class="btn btn-primary" for="comics">Comics</label>
            <input type="radio" class="btn-check"
            name="productType" id="boardgames" value="boardgames" '.$boardgames.'>
            <label class="btn btn-primary" for="boardgames">Boardgames</label>
        </div>
        <button class="btn btn-outline-primary col-4 mx-auto mt-3"
        type="submit">Search</button>
      </form>
    </div>
    <div class="container align-items-stretch mt-3">
      <div class="row align-items-stretch">';
// require printResults.php for the custom result printing
require "printResults.php";

// set statementPlaceholders to blank so that it can be added to later by
// array_push().
$statementPlaceholders = [];

// set the base statement to be added to later by the various
// if statements and case statements.
// notice that at the end of the statement it has WHERE 1=1
// this is to prevent guessing about which 'addon' statement is first
// so I can just start every 'addon' statement with AND condition = ?…
$statement = "SELECT title, series, price, sku, description,
date, SUBSTRING(date, 1, POSITION('-' IN date) - 1) AS year,
volume, number,
category, image, manufacturers.publisher, manufacturers.website
FROM products
INNER JOIN manufacturers
ON products.publisher = manufacturers.id
WHERE 1=1";

// This checks if the user put a search option in, if they have, it 
// adds the below addon statement to the main query.
// It also pushes the required placeholders into the aforementioned
// $statementPlaceholders.
if (isValid('query', $_POST)) {
        $statement .= " AND (title LIKE ?
        OR series LIKE ?
        OR sku LIKE ?
        OR description LIKE ?
        OR (SUBSTRING(date, 1, POSITION('-' IN date) - 1) LIKE ?)
        OR volume LIKE ?
        OR number LIKE ?
        OR category LIKE ?
        OR manufacturers.publisher LIKE ?)";
        $wq = "%".$_POST['query']."%";
        $q = $_POST['query'];
        array_push(
            $statementPlaceholders, $wq, $wq,
            $q, $wq, $q, $wq, $q, $wq, $wq
        );
};

// This checks if the user specified a price, it then ascertains which
// sorting method the user chose, by default less than.
// and then it add the below statement to the query as well as the
// required placeholders to $statementPlaceholders.
if (isValid('price', $_POST)) {
    $statement .= " AND price ";
    if (isValid('priceSort', $_POST)) {
        switch ($_POST['priceSort']) {
        case "lessThan":
            $statement .= "< ?";
            break;
        case "equals":
            $statement .= "= ?";
            break;
        case "greaterThan":
            $statement .= "> ?";
            break;
        default:
            $statement .= "< ?";
            break;
        };
    } else {
        $statement .= "< ?";
    };
    array_push($statementPlaceholders, $_POST['price']);
};

// This does a similar job to the above but for filtering by category.
if (isValid('productType', $_POST)) {
    $statement .= " AND category = ";
    switch ($_POST['productType']) {
    case "comics":
        $statement .= "'comics'";
        break;
    case "boardgames":
        $statement .= "'boardgames'";
        break;
    default:
        $statement .= "'comics'";
        break;
    };
};

// This is the final, mandatory, addon to the statement.
// This could be extended to offer the user a way to order the results differently.
// However, that was not a feature high on the user priority list.
$statement .= " ORDER BY title ASC, number ASC";

// This inserts the placeholders into the query, if the placeholders exist
// It is possible that the user chose fields that didn't require any placeholders,
// therefore this check is necessary.
if (empty($statementPlaceholders)) {
    $query = $pdo->query($statement);
} else {
    $query = $pdo->prepare($statement);
    $query->execute($statementPlaceholders);
}

// I then fetch all results and pass them to the custom results printing function.
$results = $query->fetchAll();
printResults($results);

// This is for security.
$query = null;
$pdo = null;
?>
<!-- Close off the tags opened before, and import the footer -->
      </div>
    </div>

  <?php require "footer.php"; ?>