<?php
require "header.php";
printHeader("CGS | Search", 2);
$query = '';
if (array_key_exists('query', $_POST)) {
    if ($_POST['query'] != '') {
        $query = "value='".$_POST['query']."'";
    };
};
$price = '';
if (array_key_exists('price', $_POST)) {
    if ($_POST['price'] != '') {
        $price = "value='".$_POST['price']."'";
    };
};
$lessThan = 'checked';
$equals = '';
$greaterThan = '';
if (array_key_exists('priceSort', $_POST)) {
    if ($_POST['priceSort'] != '') {
        switch ($_POST['priceSort']) {
        case "equals":
            $equals = "checked";
            break;
        case "greaterThan":
            $greaterThan = "checked";
            break;
        };
    };
};
    print '
    <div class="container-fluid col-8 mt-5 pt-5">
      <form action="search.old.php" method="post" class="d-flex flex-wrap">
        <input class="form-control form-control-lg col-12" type="search"
        name="query" placeholder="Search" aria-label="Search" '.$query.'>
        <div class="input-group input-group-sm mt-3">
          <span class="input-group-text">$</span>
          <input class="form-control form-control-sm col-4" type="number"
          name="price" min="1.00" max="9999.00" aria-label="price"
          placeholder="1.00" '.$price.'>
          <input type="radio" class="btn-check" name="priceSort"
          id="lessThan" value="lessThan" autocomplete="off" '.$lessThan.'>
          <label class="btn btn-primary" for="lessThan">Less Than</label>
          <input type="radio" class="btn-check" name="priceSort" id="equals"
          value="equals" autocomplete="off" '.$equals.'>
          <label class="btn btn-primary" for="equals">Equals</label>
          <input type="radio" class="btn-check" name="priceSort"
          id="greaterThan" value="greaterThan" autocomplete="off" '.$greaterThan.'>
          <label class="btn btn-primary" for="greaterThan">Greater Than</label>
        </div>
        <button class="btn btn-outline-primary col-4 mx-auto mt-3"
        type="submit">Search</button>
      </form>
    </div>
    <div class="container align-items-stretch mt-3">
      <div class="row align-items-stretch">';
    // Check if the search_query exists, if it does, check if it's blank
        // if it's blank or null, set $search_query to null
        // if it does have a value, set $search_query to that value
    // call priceFieldIsValid()
        // if return is 0, check if $search_query is null
            // if null, select and fetch all entries and call printResults($results)
            // if not null, select and fetch all entries with
            // $search_query in any field and call printResults($results)
        // if return is 1,
        // call priceSortHandler($search_query, $_POST['price'], $_POST['priceSort'])
        // if return is 2, print the given error message

    // def priceSortHandler($search_query, $price, $priceSort)
        // if $search_query is null
            // select and fetch everything that is less than,
            // equal to, or greater than $price dependent on $priceSort
            // call printResults($results)
        // else
            // select and fetch everything with a price that is
            // less than, equal to, or greater than $price dependent on
            // $priceSort that also has any field that fits $search_query
            // call printResults($results)

    // def priceFieldIsValid():
        // If the price field doesn't exist, return 0, null
        // If the price field does exist, check if it's blank
            // If the price field is blank, return 0, null
            // If the price field holds a valid number, return 1, null
            // If the price field doesn't hold a valid number,
            // return 2, "The price field isn't valid"

    // def printResults($results):
        // if there are results:
            // print the results
        // else print "There were no results for your query"

      require "printResults.php";

function priceFieldIsValid()
{
    if (array_key_exists('price', $_POST)) {
        $price = $_POST['price'];
        if ($price == '') {
            return array(0, null);
        } else {
            return array(1, null);
        };
    } else {
        return array(0, null);
    };
};

function priceSortHandler($pdo, $search_query, $price, $priceSort)
{
    if ($search_query) {
        if ($priceSort == 'lessThan') {
            $query = $pdo->prepare(
                "SELECT title, series, price, sku, description, date, volume,
            number, category, image,
            manufacturers.publisher, manufacturers.website
            FROM products
            INNER JOIN manufacturers
            ON products.publisher = manufacturers.id
            WHERE price < ? AND
            (title LIKE ?
            OR series LIKE ?
            OR sku LIKE ?
            OR description LIKE ?
            OR date LIKE ?
            OR volume LIKE ?
            OR number LIKE ?
            OR category LIKE ?
            OR manufacturers.publisher LIKE ?
            OR manufacturers.website LIKE ?)
            ORDER BY title ASC"
            );
        } elseif ($priceSort == 'equals') {
            $query = $pdo->prepare(
                "SELECT title, series, price, sku, description, date, volume,
            number, category, image,
            manufacturers.publisher, manufacturers.website
            FROM products
            INNER JOIN manufacturers
            ON products.publisher = manufacturers.id
            WHERE price = ? AND
            (title LIKE ?
            OR series LIKE ?
            OR sku LIKE ?
            OR description LIKE ?
            OR date LIKE ?
            OR volume LIKE ?
            OR number LIKE ?
            OR category LIKE ?
            OR manufacturers.publisher LIKE ?
            OR manufacturers.website LIKE ?)
            ORDER BY title ASC"
            );
        } else {
            $query = $pdo->prepare(
                "SELECT title, series, price, sku, description, date, volume,
            number, category, image,
            manufacturers.publisher, manufacturers.website
            FROM products
            INNER JOIN manufacturers
            ON products.publisher = manufacturers.id
            WHERE price > ? AND
            (title LIKE ?
            OR series LIKE ?
            OR sku LIKE ?
            OR description LIKE ?
            OR date LIKE ?
            OR volume LIKE ?
            OR number LIKE ?
            OR category LIKE ?
            OR manufacturers.publisher LIKE ?
            OR manufacturers.website LIKE ?)
            ORDER BY title ASC"
            );
        };
        $query->execute(
            [$price, $search_query, $search_query, $search_query,
            $search_query, $search_query, $search_query, $search_query,
            $search_query, $search_query, $search_query]
        );
    } else {
        if ($priceSort == 'lessThan') {
            $query = $pdo->prepare(
                "SELECT title, number, image
            FROM products
            WHERE price < ?
            ORDER BY title ASC"
            );
        } elseif ($priceSort == 'equals') {
            $query = $pdo->prepare(
                "SELECT title, number, image
            FROM products
            WHERE price = ?
            ORDER BY title ASC"
            );
        } else {
            $query = $pdo->prepare(
                "SELECT title, number, image
            FROM products
            WHERE price > ?
            ORDER BY title ASC"
            );
        };
        $query->execute([$price]);
    };
    $results = $query->fetchAll();
    printResults($results);
};

if (array_key_exists('query', $_POST)) {
    if ($_POST['query'] == '') {
          $search_query = null;
    } else {
        $search_query = "%".$_POST['query']."%";
    };
} else {
    $search_query = null;
};

list($result, $errorMessage) = priceFieldIsValid();
switch ($result) {
case 0:
    if ($search_query) {
        $query = $pdo->prepare(
            "SELECT title, series, sku, description, date,
            volume, number, category, image,
            manufacturers.publisher, manufacturers.website
            FROM products
            INNER JOIN manufacturers
            ON products.publisher = manufacturers.id
            WHERE title LIKE ?
            OR series LIKE ?
            OR sku LIKE ?
            OR description LIKE ?
            OR date LIKE ?
            OR volume LIKE ?
            OR number LIKE ?
            OR category LIKE ?
            OR manufacturers.publisher LIKE ?
            OR manufacturers.website LIKE ?
            ORDER BY title ASC"
        );
        $query->execute(
            [$search_query, $search_query, $search_query,
            $search_query, $search_query, $search_query, $search_query,
            $search_query, $search_query, $search_query]
        );
    } else {
        $query = $pdo->query(
            "SELECT title, number, image
        FROM products
        ORDER BY title ASC"
        );
    }
    $results = $query->fetchAll();
    printResults($results);
    break;
case 1:
    priceSortHandler($pdo, $search_query, $_POST['price'], $_POST['priceSort']);
    break;
case 2:
    print $errorMessage;
    break;
};

      $query = null;
      $pdo = null;
?>
    </div>
  </div>

  <?php require "footer.php" ?>