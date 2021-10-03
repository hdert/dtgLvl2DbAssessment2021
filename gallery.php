<?php
require "header.php";
// print the header with a custom title, and the required active
// attribute in the navbar.
printHeader("CGS | Gallery", 1);
?>
<!-- opening tags for the results that are going to be printed -->
    <div class="container align-items-stretch mt-5 pt-3">
      <div class="row align-items-stretch">
<?php
// query the database with the default query.
$query = $pdo->query(
    "SELECT title, series, price, sku, description,
date, volume, number, category, image,
manufacturers.publisher, manufacturers.website
FROM products
INNER JOIN manufacturers
ON products.publisher = manufacturers.id 
ORDER BY title ASC, number ASC"
);

// fetch results and pass them to the custom result printing function.
$results = $query->fetchAll();

require "printResults.php";
printResults($results);      

// set the variables to null for security.
$query = null;
$pdo = null;
?>
<!-- Close the results container and print the footer. -->
      </div>
    </div>

  <?php require "footer.php"; ?>