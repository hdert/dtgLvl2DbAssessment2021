<?php
require "connect.php";
require "header.php";
printHeader("CGS | Home", 0);
?>
    <table class="table mt-5 pt-3">
      <thead>
        <tr>
          <th scope="col">Title</th>
          <th scope="col">Series</th>
          <th scope="col">Price</th>
          <th scope="col">SKU</th>
          <th scope="col">Description</th>
          <th scope="col">Date</th>
          <th scope="col">Vol.</th>
          <th scope="col">#</th>
          <th scope="col">Type</th>
          <th scope="col">Manufacturer</th>
          <th scope="col">Website</th>
          <th scope="col">Image</th>
        </tr>
      </thead>
      <tbody>
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

        foreach ($results as $record) {
            if (file_exists("images/".$record['image'])) {
                $image = $record['image'];
            } else {
                $image = "placeholderImage.jpg";
            };
            print "<tr>
          <th scope='row'>" . "<p>" . $record['title'] . "</p><br>" . "</th>
          <td>" . "<p>" . $record['series'] . "</p><br>" . "</td>
          <td>" . "<p>$" . $record['price'] . "</p><br>" . "</td>
          <td>" . "<p>" . $record['sku'] . "</p><br>" . "</td>
          <td>" . "<p>" . $record['description'] . "</p><br>" . "</td>
          <td>" . "<p>" . $record['date'] . "</p><br>" . "</td>
          <td>" . "<p>" . $record['volume'] . "</p><br>" . "</td>
          <td>" . "<p>" . $record['number'] . "</p><br>" . "</td>
          <td>" . "<p>" . $record['category'] . "</p><br>" . "</td>
          <td>" . "<p>" . $record['publisher'] . "</p><br>" . "</td>
          <td>" . "<p>" . $record['website'] . "</p><br>" . "</td>
          <td>" . "<img class='w-100' src='images/" . $image . "'
          alt='".$record['title']."'><br>" . "</td>";
        }
        $query = null;
        $pdo = null;
        ?>
      </tbody>
    </table>

    <?php require "footer.php"; ?>