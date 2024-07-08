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

    // Initialize the cart array in the session if it doesn't exist
    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
    }

    // Check if the form to add to cart was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $product_id = $_POST['product_id'];
      $product_name = $_POST['product_name'];
      $product_price = $_POST['product_price'];
      $product_image = $_POST['product_image'];
      $quantity = $_POST['quantity'];

      // Adding products to the cart
      $_SESSION['cart'][$product_id] = [
          'name' => $product_name,
          'price' => $product_price,
          'quantity' => $quantity,
          'image' => $product_image,
      ];
    }


    /* Deleting the items in the cart. Check if the form was submitted*/
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Get the product key from the form submission
      $product_key = $_POST['product_key'];
      
      // Removing items from the cart
      if (isset($_SESSION['cart'][$product_key])) {
          unset($_SESSION['cart'][$product_key]);
      }

      header('Location: cart.php');
      exit();
    }

    // Calculating the total price of the products
    $subtotal = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
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
          <div class="col-md-12 mb-0"><a href="home.php">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Cart</strong></div>
        </div>
      </div>
    </div>

    <div class="site-section">
        <div class="container">
            <div class="row mb-5">
                <form class="col-md-12" method="post">
                    <div class="site-blocks-table">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="product-thumbnail">Image</th>
                                <th class="product-name">Product</th>
                                <th class="product-price">Price</th>
                                <th class="product-quantity">Quantity</th>
                                <th class="product-total">Total</th>
                                <th class="product-remove">Remove</th>
                            </tr>
                            </thead>
                            <tbody>
                              <?php
                                    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                                        foreach ($_SESSION['cart'] as $key => $item) {
                                            $total_price = $item['price'] * $item['quantity'];
                                            echo "<tr id='product-$key'>
                                                <td class='product-thumbnail'>
                                                    <img src='image/{$item['image']}' alt='Image' class='img-fluid'>
                                                </td>
                                                <td class='product-name'>
                                                    <h2 class='h5 text-black'>{$item['name']}</h2>
                                                </td>
                                                <td>KES. {$item['price']}</td>
                                                <td>
                                                    <div class='input-group mb-3' style='max-width: 120px;'>
                                                        <div class='input-group-prepend'>
                                                            <button class='btn btn-outline-primary js-btn-minus' type='button'>&minus;</button>
                                                        </div>
                                                        <input type='text' class='form-control text-center' value='{$item['quantity']}' placeholder='' aria-label='Example text with button addon' aria-describedby='button-addon1'>
                                                        <div class='input-group-append'>
                                                            <button class='btn btn-outline-primary js-btn-plus' type='button'>&plus;</button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>KES. $total_price</td>
                                                <td>
                                                    <form action='cart.php' method='post' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to remove this item?\");'>
                                                        <input type='hidden' name='product_key' value='$key'>
                                                        <button type='submit' class='btn btn-primary btn-sm'>X</button>
                                                    </form>
                                                </td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>Your cart is empty.</td></tr>";
                                    }
                              ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>


        <div class="row">
          <div class="col-md-6">
            <div class="row mb-5">
              <div class="col-md-6 mb-3 mb-md-0">
                <button class="btn btn-primary btn-sm btn-block">Update Cart</button>
              </div>
              <div class="col-md-6">
                <a href="shop.php"><button class="btn btn-outline-primary btn-sm btn-block">Continue Shopping</button></a>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label class="text-black h4" for="coupon">Coupon</label>
                <p>Enter your coupon code if you have one.</p>
              </div>
              <div class="col-md-8 mb-3 mb-md-0">
                <input type="text" class="form-control py-3" id="coupon" placeholder="Coupon Code">
              </div>
              <div class="col-md-4">
                <button class="btn btn-primary btn-sm">Apply Coupon</button>
              </div>
            </div>
          </div>
          <div class="col-md-6 pl-5">
            <div class="row justify-content-end">
              <div class="col-md-7">
                    <div class="row">
                        <div class="col-md-12 text-right border-bottom mb-5">
                            <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <span class="text-black">Subtotal</span>
                        </div>
                        <div class="col-md-6 text-right">
                            <strong class="text-black">KES <?php echo number_format($subtotal, 2); ?></strong>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <span class="text-black">Total</span>
                        </div>
                        <div class="col-md-6 text-right">
                            <strong class="text-black">KES <?php echo number_format($subtotal, 2); ?></strong>
                        </div>
                    </div>
                </div>
             
            

                <div class="row">
                  <div class="col-md-12">
                    <button class="btn btn-primary btn-lg py-3 btn-block" onclick="window.location='checkout.php'">Proceed To Checkout</button>
                  </div>
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
          var userBox = document.getElementById("user-box");
          if (userBox.style.display === "none") {
              userBox.style.display = "block";
          } else {
              userBox.style.display = "none";
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