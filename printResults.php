<?php
  function print_number($number) {
    if ($number != '') {
      return " #$number";
    };
  };
  function print_results($results) {
    if ($results) {
      $iterator = 0;
      $cardsPerRow = 4;
      $column = 12 / $cardsPerRow;
      foreach ($results as $record) {
        if ($iterator % $cardsPerRow == 0) {
          print "</div><div class='row align-items-stretch'>";
        };
        if (file_exists("images/".$record['image'])) {
          $image = $record['image'];
        } else {
          $image = "placeholderImage.jpg";
        };
        print "<a type='button' data-bs-toggle='modal' class='col-".$column." my-3' data-bs-target='#modal".$record['sku']."'>
        <div class='card h-100'>"
        ."<img class='img-fluid' src='images/".$image."' alt='".$record['title']."'>"
        ."<hr class='bg-secondary my-0'><div class='card-body d-flex align-items-center'>"
        ."<h5 class='card-title text-center flex-grow-1'>".
        $record['title'].print_number($record['number'])."</h5>"
        ."</div></div></a>";
        $iterator++;
        $volume = '';
        if ($record['volume'] != '') {
          $volume = "<p>Volume: ".$record['volume']."</p>";
        }
        $number = '';
        if ($record['number'] != '') {
          $number = "<p>Release Number: ".$record['number']."</p>";
        }

        print '<div class="modal fade" tabindex="-1" id="modal'.$record['sku'].'"
        aria-labelledby="modal'.$record['sku'].'Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable
        modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal'.$record['sku'].'Label">'.$record['title'].print_number($record['number']).'</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-8 d-flex justify-content-between pe-0">
                    <div class="me-3">
                      <p>Manufacturer: '.$record['publisher'].'</p>
                      <p>Manufacturer Website: '.$record['website'].'</p>
                      <p>SKU: '.$record['sku'].'</p>
                      '.$volume.'
                      <p>Series: '.$record['series'].'</p>
                      '.$number.'
                      <p>Release Date: '.$record['date'].'</p>
                      <p>Price: $'.$record['price'].'</p>
                      <p>Category: '.$record['category'].'</p>
                      <p>Description: '.$record['description'].'</p>
                    </div>
                    <div class="vr"></div>
                  </div>
                  <div class="col-4 ps-0">
                    <img class="img-fluid" src="images/'.$image.'" alt="'.$record['title'].'">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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