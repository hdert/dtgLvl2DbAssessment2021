<?php
/** 
 * Returns the number with a prefixed '#' if it exists.
 * 
 * @param integer $number the number that gets checked for it's existence.
 * 
 * @return $number with a '#' prefix if it exists, other wise nothing
 */
function printNumber($number)
{
    if ($number != '') {
        return " #$number";
    };
};
/**
 * Prints the results in a custom format.
 * 
 * If there are no results it prints and error message to the user.
 * 
 * @param array $results the results of a sql query
 * 
 * @return nothing
 */
function printResults($results)
{
    // check if there are results, else print error message.
    if ($results) {
        // These three variables dictate how many columns of results there are
        $iterator = 0;
        $cardsPerRow = 4;
        $column = 12 / $cardsPerRow;
        // Loop through the results
        foreach ($results as $record) {
            // print the closing tag for the flex row, and start the next
            // one, when the division is clean, therefore the iterator
            // is a multiple of $cardsPerRow, therefore there are that
            // many cards per row
            if ($iterator % $cardsPerRow == 0) {
                print "</div><div class='row align-items-stretch'>";
            };
            // Check for the image on the file system, if it doesn't exist
            // replace it with a placeholder image
            // This was implemented in php incase the client wanted to add
            // any more products to the database in future, and then those
            // columns wouldn't have to be tested for valid images
            if (file_exists("images/".$record['image'])) {
                $image = $record['image'];
            } else {
                $image = "placeholderImage.jpg";
            };
            // print the cards that display the data like, image, title
            // and release number.
            print "<a role='button' data-bs-toggle='modal'
            class='col-".$column." my-3' data-bs-target='#modal".$record['sku']."'>
            <div class='card h-100'>"
            ."<img class='img-fluid' src='images/".$image."'
            alt='".$record['title']."'>"
            ."<hr class='bg-secondary my-0'>
            <div class='card-body d-flex align-items-center'>"
            ."<h5 class='card-title text-center flex-grow-1'>".
            $record['title'].printNumber($record['number'])."</h5>"
            ."</div></div></a>";
            $iterator++;
            // only print the volume number information line, in the modal, if the
            // volume number exists
            $volume = '';
            if ($record['volume'] != '') {
                $volume = "<p>Volume: ".$record['volume']."</p>";
            };
            // only print the release number information line, in the modal
            // if the release number exists.
            $number = '';
            if ($record['number'] != '') {
                $number = "<p>Release Number: ".$record['number']."</p>";
            };
            // If the publisher website doesn't exist, just print the publisher name
            // if the publisher website does exists, print a link to the website
            // around the publisher name.
            $publisher = '<p>Manufacturer: '.$record['publisher'].'</p>';
            if ($record['website'] != '') {
                $publisher = '<p>Manufacturer: <a href="'.$record['website'].'"
                target="_blank">'.$record['publisher'].'</a></p>';
            };
            // print the pop-over modal itself using the variables to substitute
            // conditional data points in.
            // the modal contains every piece of data in a row on a particular
            // product, and is therefore quite a log bit of code.
            print '<div class="modal fade" tabindex="-1" id="modal'.$record['sku'].'"
        aria-labelledby="modal'.$record['sku'].'Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable
        modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"
              id="modal'.$record['sku'].'Label">'.$record['title'].
              printNumber($record['number']).'</h5>
              <!-- The title and release number of the product, if it exists-->
              <!-- The first close dialog, users can also click or tap outside of
              the modal to dismiss it -->
              <button type="button" class="btn-close"
              data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="container-fluid">
                <div class="row">
                <!-- The rest of the data on the product -->
                  <div class="col-8 d-flex justify-content-between pe-0">
                    <div class="me-3">
                      '.$publisher.'
                      <p>SKU: '.$record['sku'].'</p>
                      '.$volume.'
                      <p>Series: '.$record['series'].'</p>
                      '.$number.'
                      <p>Release Date: '.$record['date'].'</p>
                      <p>Price: $'.$record['price'].'</p>
                      <p>Category: '.$record['category'].'</p>
                      <p>Description: '.$record['description'].'</p>
                    </div>
                    <div class="vr"></div> <!-- A vertical rule, like a
                    horizontal rule,but vertical-->
                  </div>
                  <div class="col-4 ps-0">
                  <!-- the product\'s image -->
                    <img class="img-fluid" src="images/'.$image.'"
                    alt="'.$record['title'].'">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
            <!-- the second close dialogue -->
              <button type="button" class="btn btn-secondary"
              data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>';
        };
    } else {
        print "There were no results for your query";
    };
};
?>