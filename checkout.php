<?php 

    include 'connection.php';
    session_start();
    $user_id = $_SESSION['user_name'];

    if (isset($_POST['logout'])) {
        session_destroy();
        header('location:landing.php');
    }

    // Calculating the number of items in the cart
    $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; 

    // Checking if the place order button is pressed
    if (isset($_POST['place_order'])) {
      $user_id = $_SESSION['user_id']; 
      $name = $_POST['name'];
      $place = $_POST['place'];
      $number = $_POST['phone'];
      $email = $_POST['email'];
      $apartment = $_POST['apartment'];
      $city = $_POST['city_county'];
      $method = $_POST['payment_method'];
      $address = $_POST['postal_zip'];
      $notes = $_POST['order_notes'];
  
    // Gathering cart details
    $products = '';
    $total_products = 0;
    $total_price = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $products .= $item['name'] . ' (x' . $item['quantity'] . '), ';
            $total_products += $item['quantity'];
            $total_price += $item['price'] * $item['quantity'];
        }
        $products = rtrim($products, ', ');
    }

    $placed_on = date('Y-m-d H:i:s');
    $payment_status = 'pending';

    // Inserting order details into the database
    $insert_order = "INSERT INTO `orders` (user_id, name, place, number, email, apartment, city, method, address, product, total_products, total_price, placed_on, payment_status, notes) 
      VALUES ('$user_id', '$name', '$place', '$number', '$email', '$apartment', '$city', '$method', '$address', '$products', '$total_products', '$total_price', '$placed_on', '$payment_status', '$notes')";
    mysqli_query($conn, $insert_order) or die('Query failed: ' . mysqli_error($conn));

    // Clearing the cart
    unset($_SESSION['cart']);

    header('Location: thankyou.php');
    exit();
  }
?>
<style>
  <?php include 'style.css'; ?>
</style>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mukta:300,400,700"> 
    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">



    <link rel="stylesheet" href="css/style.css">
    
  </head>
  <body>
  
  <div class="site-wrap">
    <!---Header--->
    <header class="site-navbar" role="banner">
        <div class="site-navbar-top">
          <div class="container">
            <div class="row align-items-center">

              <div class="col-6 col-md-4 order-2 order-md-1 site-search-icon text-left">
                <form action="" class="site-block-top-search">
                  <span class="icon icon-search2"></span>
                  <input type="text" class="form-control border-0" placeholder="Search">
                </form>
              </div>

              <div class="col-12 mb-3 mb-md-  0 col-md-4 order-1 order-md-2 text-center">
                <div class="site-logo">
                  <a href="home.php" class="js-logo-clone">G & S TEES</a>
                </div>
              </div>

              <div class="col-6 col-md-4 order-3 order-md-3 text-right">
                <div class="site-top-icons">
                  <ul>
                      <li>
                          <a href="#" onclick="toggleUserBox()">
                              <span class="icon icon-person" id="user-btn"></span>
                          </a>
                      </li>
                      <li><a href="#"><span class="icon icon-heart-o"></span></a></li>
                      <li>
                        <a href="cart.php" class="site-cart">
                          <span class="icon icon-shopping_cart"></span>
                          <span class="count"><?php echo $cart_count; ?></span>
                        </a>
                      </li>  
                      <li class="d-inline-block d-md-none ml-md-0">
                          <a href="#" class="site-menu-toggle js-menu-toggle">
                              <span class="icon-menu"></span>
                          </a>
                      </li>
                  </ul>
                </div>

                <div class="user-box" id="user-box" style="display: none; ">
                    <p>Username : <span><?php echo $_SESSION['user_name']; ?></span></p>
                    <p>Email : <span><?php echo $_SESSION['user_email']; ?></span></p>
                    <form method="post">
                        <button type="submit" class="logout-btn" name="logout">Log Out</button>
                    </form>
                </div>
  
                
              </div>

            </div>
          </div>
        </div> 
        <nav class="site-navigation text-right text-md-center" role="navigation">
          <div class="container">
            <ul class="site-menu js-clone-nav d-none d-md-block">
              <li><a href="home.php">Home</a></li>
              <li><a href="shop.php">Shop</a></li>
              <li><a href="about.php">About</a></li>     
              <li><a href="contact.php">Contact</a></li>
            </ul>
          </div>
        </nav>
    </header>

      <div class="bg-light py-3">
        <div class="container">
          <div class="row">
            <div class="col-md-12 mb-0"><a href="home.php">Home</a> <span class="mx-2 mb-0">/</span> <a href="cart.php">Cart</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Checkout</strong></div>
          </div>
        </div>
      </div>

      <!--Billing details and order--->
      <div class="site-section">
          <div class="container">
              <div class="row">
                  <div class="col-md-6 mb-2 mb-md-0">
                      <h2 class="h3 mb-3 text-black">Billing Details</h2>
                      <div class="p-3 p-lg-5 border">
                          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                              <div class="form-group">
                                  <label for="c_place" class="text-black">Place </label>
                                  <select id="c_place" name="place" class="form-control">
                                      <option value="B.A.T">Free delivery in this places</option> 
                                      <option value="B.A.T">B.A.T</option>    
                                      <option value="Kiganjo">Kiganjo</option>    
                                      <option value="M.K.U">M.K.U</option>    
                                      <option value="Makongeni">Makongeni</option>    
                                      <option value="Kamenu">Kamenu</option>    
                                      <option value="Runda">Runda</option>    
                                      <option value="Community">Community</option>       
                                  </select>
                              </div>
                              <div class="form-group row">
                                  <div class="col-md-12">
                                      <label for="c_name" class="text-black">Names </label>
                                      <input type="text" class="form-control" id="c_name" name="name" required>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <input type="text" class="form-control" name="apartment" placeholder="Apartment, suite, unit etc. (optional)">
                              </div>
                              <div class="form-group row">
                                  <div class="col-md-6">
                                      <label for="c_city_county" class="text-black">City / County </label>
                                      <input type="text" class="form-control" id="c_city_county" name="city_county" required>
                                  </div>
                                  <div class="col-md-6">
                                      <label for="c_postal_zip" class="text-black">Postal / Zip </label>
                                      <input type="text" class="form-control" id="c_postal_zip" name="postal_zip" required>
                                  </div>
                              </div>
                              <div class="form-group row mb-5">
                                  <div class="col-md-6">
                                      <label for="c_email_address" class="text-black">Email Address </label>
                                      <input type="email" class="form-control" id="c_email_address" name="email" required>
                                  </div>
                                  <div class="col-md-6">
                                      <label for="c_phone" class="text-black">Phone </label>
                                      <input type="text" class="form-control" id="c_phone" name="phone" required>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <label for="c_order_notes" class="text-black">Order Notes</label>
                                  <textarea name="order_notes" id="c_order_notes" cols="30" rows="5" class="form-control" placeholder="Write your notes here..." required></textarea>
                              </div>
                              <div class="form-group">
                                  <label for="payment_method" class="text-black">Payment Method</label>
                                  <select id="payment_method" name="payment_method" class="form-control" required>
                                      <option value="M-PESA">M-PESA</option>
                                      <option value="Airtel Money">Airtel Money</option>
                                      <option value="Bank">Bank</option>
                                  </select>
                              </div>
                              <div class="form-group">
                                  <button type="submit" name="place_order" class="btn btn-primary btn-lg py-3 btn-block">Place Order</button>
                              </div>
                          </form>
                      </div>
                  </div>

                  <div class="col-md-6">
                      <div class="row mb-5">
                          <div class="col-md-12">
                              <h2 class="h3 mb-3 text-black">Coupon Code</h2>
                              <div class="p-3 p-lg-5 border">
                                  <label for="c_code" class="text-black mb-3">Enter your coupon code if you have one</label>
                                  <div class="input-group w-75">
                                      <input type="text" class="form-control" id="c_code" placeholder="Coupon Code" aria-label="Coupon Code" aria-describedby="button-addon2">
                                      <div class="input-group-append">
                                          <button class="btn btn-primary btn-sm" type="button" id="button-addon2">Apply</button>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      
                      <div class="row mb-5">
                          <div class="col-md-12">
                              <h2 class="h3 mb-3 text-black">Your Order</h2>
                              <div class="p-3 p-lg-5 border">
                                  <table class="table site-block-order-table mb-5">
                                      <thead>
                                          <th>Product</th>
                                          <th>Total</th>
                                      </thead>
                                      <tbody>
                                      <?php
                                          $cart_total = 0;
                                          if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                                              foreach ($_SESSION['cart'] as $item) {
                                                  $product_name = htmlspecialchars($item['name']);
                                                  $product_price = $item['price'];
                                                  $product_quantity = $item['quantity'];
                                                  $total_price = $product_price * $product_quantity;
                                                  $cart_total += $total_price;
                                                  echo "<tr>
                                                          <td>{$product_name} <strong class='mx-2'>x</strong> {$product_quantity}</td>
                                                          <td>KES. {$total_price}</td>
                                                        </tr>";
                                              }
                                          }
                                      ?>
                                      <tr>
                                          <td class="text-black font-weight-bold"><strong>Cart Subtotal</strong></td>
                                          <td class="text-black">KES. <?php echo $cart_total; ?></td>
                                      </tr>
                                      <tr>
                                          <td class="text-black font-weight-bold"><strong>Order Total</strong></td>
                                          <td class="text-black font-weight-bold">KES. <?php echo $cart_total; ?></td>
                                      </tr>
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <!--Footer section-->
      <footer class="site-footer border-top">
        <div class="container">
          <div class="row">
            <div class="col-lg-6 mb-5 mb-lg-0">
              <div class="row">
                <div class="col-md-12">
                  <h3 class="footer-heading mb-4">Navigations</h3>
                </div>
                <div class="col-md-6 col-lg-4">
                  <ul class="list-unstyled">
                    <li><a href="home.php">Home</a></li>
                    <li><a href="shop.php">Shop</a></li>
                    <li><a href="">Order</a></li>
                    <li><a href="about.php">About</a></li>
                  </ul>
                </div>
                <div class="col-md-6 col-lg-4">
                  <ul class="list-unstyled">
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="cart.php">Cart</a></li>
                    <li><a href="#">Wishlist</a></li>
                  </ul>
                </div>
                <div class="col-md-6 col-lg-4">
                  <ul class="list-unstyled">
                    <li><a href="#">Shipping Information</a></li>
                    <li><a href="#">Customer Reviews</a></li>
                    <li><a href="#">FAQ</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
              <h3 class="footer-heading mb-4">Promo</h3>
              <a href="shop.php" class="block-6">
                <img src="img/products/f4.jpg" alt="Image placeholder" class="img-fluid rounded mb-4">
                <h3 class="font-weight-light  mb-0">Finding Your Perfect Tee</h3>
                <p>Promo from July 13 &mdash; 29, 2024</p>
              </a>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="block-5 mb-5">
                <h3 class="footer-heading mb-4">Contact Info</h3>
                <ul class="list-unstyled">
                  <li class="address">Kiambu, Thika, Makongeni</li>
                  <li class="phone"><a href="#">+254748116823</a></li>
                  <li class="email"><a href="mailto:gamalielgachanga58@gmail.com">gamalielgachanga58@gmail.com</a></li>
                </ul>
              </div>

              <div class="block-7">
                <form action="#" method="post">
                  <label for="email_subscribe" class="footer-heading">Subscribe to our newsletter</label>
                  <div class="form-group">
                    <input type="text" class="form-control py-4" id="email_subscribe" placeholder="Email">
                    <input type="submit" class="btn btn-sm btn-primary" value="Send">
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="row pt-5 mt-5 text-center">
            <div class="col-md-12">
              <p>
            
              Copyright &copy;<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script>document.write(new Date().getFullYear());</script> All rights reserved G & S TEES | made by Gutheras 
    
              </p>
            </div>
            
          </div>
        </div>
      </footer>
  </div>

  <!--Start of Tawk.to Script-->
  <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/6687c17aeaf3bd8d4d185e47/1i214nk9i';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
  </script>

  <script>
    function toggleUserBox() {
      var userBox = document.getElementById('user-box');
      if (userBox.style.display === 'none' || userBox.style.display === '') {
        userBox.style.display = 'block';
      } else {
        userBox.style.display = 'none';
      }
    }
  </script>
  
  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>

  <script src="js/main.js"></script>
    
  </body>
</html>