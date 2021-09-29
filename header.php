<?php
function printHeader($title, $activePage)
{
    include "connect.php";
    $home = '';
    $gallery = '';
    $search = '';
    switch ($activePage) {
    case 0:
        $home = ' active" aria-current="page';
        break;
    case 1:
        $gallery = ' active" aria-current="page';
        break;
    case 2:
        $search = ' active" aria-current="page';
        break;
    }
    print '<!DOCTYPE html>
<html lang="en">
  <head>
    <title>'.$title.'</title>
    <meta name="robots" content="noindex, nofollow" />
    <meta name="charset" content="UTF-8" />
    <!-- Tells the crawler what type of page this page is-->
    <meta property="og:type" content="website" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Download bootstrap CSS -->
    <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity=
    "sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU"
    crossorigin="anonymous">
  </head>
  <body>
    <!-- Navbar that is consistent between all pages -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Comics & Games Store</a>';
        print '<div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <a class="nav-link'.$home.'" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link'.$gallery.'" href="gallery.php">Gallery</a>
            </li>
            <li class="nav-item">
              <a class="nav-link'.$search.'" href="search.php">Search</a>
            </li>
          </ul>
        </div>
        <div class="d-flex">';
    if ($activePage != 2) {
            print '
          <form action="search.php" method="get" class="d-flex me-2">
            <input class="form-control me-2" type="search" name="query"
            placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-primary text-nowrap"
            type="submit">Advanced Search</button>
          </form>';
    };
          print '<button class="navbar-toggler" type="button"
          data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false"
          aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
      </div>
    </nav>';
}
?>