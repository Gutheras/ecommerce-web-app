<?php 

    include 'connection.php';
    session_start();
    $admin_id = $_SESSION['admin_name'];


    if (!isset($admin_id)) {
        header('location:login.php');
    }

    if (isset($_POST['logout'])) {
        session_destroy();
        header('location:landing.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>admin panel</title>
</head>
<body>
    
    <?php include 'admin_header.php'; ?>
    <section class="dashboard" style="margin-top: 20rem;">
        <div class="container">
        <div class="row">
            <div class="col">
                <div class="dashboard-box" id="pendings-box">
                    <?php
                        $total_pendings = 0;
                        $select_pendings = mysqli_query($conn, "SELECT * FROM `orders` WHERE payment_status = 'pending'")
                            or die('query failed');
                        while ($fetch_pending = mysqli_fetch_assoc($select_pendings)) {
                            $total_pendings += $fetch_pending['total_price'];
                        }
                    ?>
                    <h3>Kes. <?php echo $total_pendings; ?>/-</h3>
                    <p>Total pendings</p>
                </div>
            </div>
            <div class="col">
                <div class="dashboard-box" id="completes-box">
                    <?php
                        $total_completes = 0;
                        $select_completes = mysqli_query($conn, "SELECT * FROM `orders` WHERE payment_status = 'complete'")
                            or die('query failed');
                        while ($fetch_completes = mysqli_fetch_assoc($select_completes)) {
                            $total_completes += $fetch_completes['total_price'];
                        }
                    ?>
                    <h3>Kes. <?php echo $total_completes; ?>/-</h3>
                    <p>Total completes</p>
                </div>
            </div>
            <div class="col">
                <div class="dashboard-box" id="orders-box">
                    <?php
                        $select_order = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
                        $num_of_order = mysqli_num_rows($select_order);
                    ?>
                    <a href="admin_order.php"><h3><?php echo $num_of_order; ?></h3></a>
                    <a href="admin_order.php"><p>Orders Placed</p></a>
                </div>
            </div>
            <div class="col">
                <div class="dashboard-box" id="products-box">
                    <?php
                        $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
                        $num_of_products = mysqli_num_rows($select_products);
                    ?>
                    <a href="admin_product.php"><h3><?php echo $num_of_products; ?></h3></a>
                    <a href="admin_product.php"><p>Products added</p></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="dashboard-box" id="users-box">
                    <?php
                        $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'") or die('query failed');
                        $num_of_users = mysqli_num_rows($select_users);
                    ?>
                    <a href="admin_user.php"><h3><?php echo $num_of_users; ?></h3></a>
                    <a href="admin_user.php"><p>Total normal users</p></a>
                </div>
            </div>
            <div class="col">
                <div class="dashboard-box" id="admins-box">
                    <?php
                        $select_admins = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'admin'") or die('query failed');
                        $num_of_admins = mysqli_num_rows($select_admins);
                    ?>
                    <h3><?php echo $num_of_admins; ?></h3>
                    <p>Total admins</p>
                </div>
            </div>
            <div class="col">
                <div class="dashboard-box" id="registered-users-box">
                    <?php
                        $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
                        $num_of_users = mysqli_num_rows($select_users);
                    ?>
                    <a href="admin_user.php"><h3><?php echo $num_of_users; ?></h3></a>
                    <a href="admin_user.php"><p>Total registered users</p></a>
                </div>
            </div>
            <div class="col">
                <div class="dashboard-box" id="messages-box">
                    <?php
                        $select_message = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
                        $num_of_message = mysqli_num_rows($select_message);
                    ?>
                    <a href="admin_message.php"><h3><?php echo $num_of_message; ?></h3></a>
                    <a href="admin_message.php"><p>New messages</p></a>
                </div>
            </div>
        </div>
        </div>
    </section>

   
    <script src="script.js"></script>
</body>
</html>