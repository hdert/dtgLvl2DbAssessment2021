<?php
include "connect.php";
include "header.php";
printHeader("CGS | Search", 2);
function isValid($array_item_name, $array)
{
    if (array_key_exists($array_item_name, $array)) {
        if ($array[$array_item_name] != '') {
            return 1;
        };
    };
    return 0;
};
$query = '';
$price = '';
if (isValid('query', $_GET)) {
    $query = "value='".$_GET['query']."'";
};
if (isValid('price', $_GET)) {
    $price = "value='".$_GET['price']."'";
};
$lessThan = 'checked';
$equals = '';
$greaterThan = '';
if (isValid('priceSort', $_GET)) {
    switch ($_GET['priceSort']) {
    case "equals":
        $equals = "checked";
        break;
    case "greaterThan":
        $greaterThan = "checked";
        break;
    };
};
   print '
    <div class="container-fluid col-8 mt-5 pt-5">
      <form action="search.php" method="get" class="d-flex flex-wrap">
        <input class="form-control form-control-lg col-12"
        type="search" name="query" placeholder="Search"
        aria-label="Search" '.$query.'>
        <div class="input-group input-group-sm mt-3">
          <span class="input-group-text">$</span>
          <input class="form-control form-control-sm col-4"
          type="number" name="price" min="1.00"
          max="9999.00" aria-label="price" placeholder="1.00" '.$price.'>
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
        <button class="btn btn-outline-primary col-4 mx-auto mt-3"
        type="submit">Search</button>
      </form>
    </div>
    <div class="container align-items-stretch mt-3">
      <div class="row align-items-stretch">';
      include "printResults.php";

      $statementPlaceholders = [];

      $statement = "SELECT title, series, price, sku, description,
      date, SUBSTRING(date, 1, POSITION('-' IN date) - 1) AS year,
      volume, number,
      category, image, manufacturers.publisher, manufacturers.website
      FROM products
      INNER JOIN manufacturers
      ON products.publisher = manufacturers.id
      WHERE 1=1";

if (isValid('query', $_GET)) {
        $statement .= " AND (title LIKE ?
        OR series LIKE ?
        OR sku LIKE ?
        OR description LIKE ?
        OR (SUBSTRING(date, 1, POSITION('-' IN date) - 1) LIKE ?)
        OR volume LIKE ?
        OR number LIKE ?
        OR category LIKE ?
        OR manufacturers.publisher LIKE ?
        OR manufacturers.website LIKE ?)";
        $wq = "%".$_GET['query']."%";
        $q = $_GET['query'];
        array_push(
            $statementPlaceholders, $wq, $wq,
            $q, $wq, $q, $wq, $q, $wq, $wq, $w
        );
};

if (isValid('price', $_GET)) {
    $statement .= " AND price ";
    if (isValid('priceSort', $_GET)) {
        switch ($_GET['priceSort']) {
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
    array_push($statementPlaceholders, $_GET['price']);
};

      $statement .= " ORDER BY title ASC, number ASC";

if (empty($statementPlaceholders)) {
    $query = $pdo->query($statement);
} else {
    $query = $pdo->prepare($statement);
    $query->execute($statementPlaceholders);
}

      $results = $query->fetchAll();
      printResults($results);

      $query = null;
      $pdo = null;
?>
      </div>
    </div>

  <?php include "footer.php"; ?>