<?php
require "connect.php";
require "header.php";
printHeader("CGS | Gallery", 1);
?>
    <div class="container align-items-stretch mt-5 pt-3">
      <div class="row align-items-stretch">
        <?php
        $query = $pdo->query(
            "SELECT title, series, price, sku, description,
date, volume, number, category, image,
manufacturers.publisher, manufacturers.website
FROM products
INNER JOIN manufacturers
ON products.publisher = manufacturers.id 
ORDER BY title ASC, number ASC"
        );

          $results = $query->fetchAll();

          require "printResults.php";
          printResults($results);      

          $query = null;
          $pdo = null;
        ?>
      </div>
    </div>

  <?php require "footer.php"; ?>