<?php
include "connect.php";
include "header.php";
print_header("CGS | Gallery", 1);
?>
    <div class="container align-items-stretch mt-5 pt-3">
      <div class="row align-items-stretch">
        <?php
          $query = $pdo->query("SELECT title, series, price, sku, description,
          date, volume, number, category, image,
          manufacturers.publisher, manufacturers.website
          FROM products
          INNER JOIN manufacturers
          ON products.publisher = manufacturers.id 
          ORDER BY title ASC, number ASC");

          $results = $query->fetchAll();

          include "printResults.php";
          print_results($results);      

          $query = null;
          $pdo = null;
        ?>
      </div>
    </div>

  <?php include "footer.php"; ?>