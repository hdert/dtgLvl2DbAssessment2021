<?php
require "header.php";
// print the header and the required active attributes in the right place
// in the navbar.
printHeader("CGS | Home", 0);
?>
<!-- setup the table headers and whatnot -->
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
// set and fetch the default query.
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

// loop through every result printing them in a table format
foreach ($results as $record) {
    // check if the image exists on the file system
    // if not, replace the image with a placeholder.
    if (file_exists("images/".$record['image'])) {
        $image = $record['image'];
    } else {
        $image = "placeholderImage.jpg";
    };
    // check if the website exists, if not, just print the publisher
    // if yes, print an anchor tag around the publisher name with
    // a link to the publisher website
    $website = '<p>'.$record['publisher'].'</p>';
    if ($record['website'] != '') {
        $website = '<a href="'.$record['website'].'"
        target="_blank">'.$record['publisher'].'</a>';
    };
    // print the information in a table format whilst trying to look
    // as little confusing as possible, hence the multiline print.
    print "<tr>
    <th scope='row'><p>".$record['title']."</p><br></th>
    <td><p>".$record['series']."</p><br></td>
    <td><p>$".$record['price']."</p><br></td>
    <td><p>".$record['sku']."</p><br></td>
    <td><p>".$record['description']."</p><br></td>
    <td><p>".$record['date']."</p><br></td>
    <td><p>".$record['volume']."</p><br></td>
    <td><p>".$record['number']."</p><br></td>
    <td><p>".$record['category']."</p><br></td>
    <td>".$website."<br></td>
    <td><img class='w-100' src='images/" . $image . "'
    alt='".$record['title']."'><br></td>";
}
// remove vars for security
$query = null;
$pdo = null;
?>
<!-- close the tables and print the footer. -->
      </tbody>
    </table>

    <?php require "footer.php"; ?>