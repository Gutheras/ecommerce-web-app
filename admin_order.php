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
   

    //Deleting products from database
    if (isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        
        mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
        $message[]='User removed successfully';
        header('location:admin_order.php');
    }

    //Updating payment status

    if (isset($_POST['update_order'])) {
        $order_id = $_POST['order_id'];
        $update_payment = $_POST['update_payment'];

        mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_id'") or die('query failed');
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

    <div class="line4"></div>
        <section class="message-container" style="margin-top: 10rem;">
        <?php
        if (isset($message)) {
            foreach ($message as $msg) {
                echo '
                <div class="message">
                    <span>' . $msg . '</span>
                    <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                </div>

                ';
            }
        }
        ?>
        <div class="line4"></div>
        <section class="order-container">
                <h1 class="title">Total orders placed</h1>
                <div class="box-container" style="display: flex; flex-wrap: wrap; justify-content: space-between;">
                    <?php 
                        $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
                        if (mysqli_num_rows($select_orders) > 0) {
                            while($fetch_orders = mysqli_fetch_assoc($select_orders)){
                    ?>
                    <div class="order-box" style="width: 23%; margin: 1%; padding: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); flex: 1 1 23%;">
                        <p>User id: <span><?php echo $fetch_orders['user_id']; ?></span></p>
                        <p>User name: <span><?php echo $fetch_orders['name']; ?></span></p>
                        <p>Placed on: <span><?php echo $fetch_orders['placed_on']; ?></span></p>
                        <p>Number: <span><?php echo $fetch_orders['number']; ?></span></p>
                        <p>Address: <span><?php echo $fetch_orders['address']; ?></span></p>
                        <p>Email: <span><?php echo $fetch_orders['email']; ?></span></p>          
                        <p>Place: <span><?php echo $fetch_orders['place']; ?></span></p>
                        <p>Apartment: <span><?php echo $fetch_orders['apartment']; ?></span></p>
                        <p>City: <span><?php echo $fetch_orders['city']; ?></span></p>       
                        <p>Total product: <span><?php echo $fetch_orders['total_products']; ?></span></p>
                        <p>Products: <span><?php echo $fetch_orders['product']; ?></span></p>
                        <p>Total price: <span><?php echo $fetch_orders['total_price']; ?></span></p>
                        <p>Method: <span><?php echo $fetch_orders['method']; ?></span></p>
                        <p>Order Notes: <span><?php echo $fetch_orders['notes']; ?></span></p> 
                        <form method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                            <select name="update_payment">
                                <option disabled selected><?php echo $fetch_orders['payment_status']; ?></option>
                                <option value="pending">Pending</option>
                                <option value="complete">Complete</option>
                            </select>
                            <input type="submit" name="update_order" value="Update payment" class="btn" style="margin-top: 10px; padding: 5px 10px; background-color: #088178; color: #fff; border: none; border-radius: 5px;">
                            <a href="admin_order.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('Delete this order?');" class="btn" style="display: inline-block; margin-top: 10px; padding: 5px 10px; background-color: #dc3545; color: #fff; border-radius: 5px;">Delete</a>
                        </form>
                    </div>
                    <?php
                            }
                        } else {
                            echo '
                                <div class="empty" style="width: 100%; text-align: center; padding: 20px;">
                                    <p>No order placed yet!</p>
                                </div>';
                        }
                    ?>
                </div>
        </section>
 

        <div class="line"></div>
        <script src="script.js"></script>

</body>
</html>