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

    if (isset($_POST['submit-btn'])) {
      // Escaping user inputs for security
      $name = mysqli_real_escape_string($conn, $_POST['c_fname']);
      $email = mysqli_real_escape_string($conn, $_POST['c_email']);
      $number = mysqli_real_escape_string($conn, $_POST['number']);
      $message = mysqli_real_escape_string($conn, $_POST['c_message']);
  
      // Checking if the message already exists
      $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$message'") or die('query failed');
  
      if(mysqli_num_rows($select_message) > 0) {
          echo '<p style="color: solid black; font-size: 20px;  text-align: center;">Message already sent!</p>';
      } else {
          //Inserting the message into the database
          $insert_message = "INSERT INTO `message`(`name`, `email`, `number`, `message`) VALUES ('$name', '$email', '$number', '$message')";
          if(mysqli_query($conn, $insert_message)) {
              echo '<p style="color: black; font-size: 20px; text-align: center;">Message sent successfully!</p>';
          } else {
              echo 'Error: ' . mysqli_error($conn);
          }
      }
  }
  
  // Close connection
  mysqli_close($conn);
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
          <div class="col-md-12 mb-0"><a href="home.php">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Contact</strong></div>
        </div>
      </div>
    </div>  

    <!--Contact form section-->
    <div class="site-section">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h2 class="h3 mb-3 text-black">Get In Touch With Us</h2>
          </div>
          <div class="col-md-7">

            <form method="post">
              
              <div class="p-3 p-lg-5 border">
                <div class="form-group row">
                  <div class="col-md-6">
                    <label for="c_fname" class="text-black">Your Name</label>
                    <input type="text" class="form-control" id="c_fname" name="c_fname" placeholder="Full names" required>
                  </div>
                 
                </div>
                <div class="form-group row">
                  <div class="col-md-12">
                    <label for="c_email" class="text-black">Email</label>
                    <input type="email" class="form-control" id="c_email" name="c_email" placeholder="example@gmail.com" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-12">
                    <label for="c_subject" class="text-black">Number</label>
                    <input type="number" class="form-control" id="c_subject" name="number" placeholder="0748***823" required>
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-12">
                    <label for="c_message" class="text-black">Your Message</label>
                    <textarea name="c_message" id="c_message" cols="30" rows="7" class="form-control" placeholder="Write your message here" required></textarea>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-lg-12">
                    <input name="submit-btn" type="submit" class="btn btn-primary btn-lg btn-block" value="Send Message">
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="col-md-5 ml-auto">
            <div class="p-4 border mb-3">
              <span class="d-block text-primary h6 text-uppercase">Kiambu</span>
              <p class="mb-0">Thika, Makongeni</p>
            </div>
            <div class="p-4 border mb-3">
              <span class="d-block text-primary h6 text-uppercase">Nairobi</span>
              <p class="mb-0">Thika main stage, near Huduma Center</p>
            </div>
            <div class="p-4 border mb-3">
              <span class="d-block text-primary h6 text-uppercase">Nairobi</span>
              <p class="mb-0">Muthurwa</p>
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