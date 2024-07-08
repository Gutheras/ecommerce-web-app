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
    //Adding the products to the database
    if (isset($_POST['add_product'])) {
        $product_name = mysqli_real_escape_string($conn, $_POST['name']);
        $product_price = mysqli_real_escape_string($conn, $_POST['price']);
        $product_detail = mysqli_real_escape_string($conn, $_POST['detail']);
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'image/'.$image;

        $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$product_name'") or die(
            'query failed');
        if(mysqli_num_rows($select_product_name)>0){
            $message[] = 'Product name already exists';
        }else{
            $insert_product = mysqli_query($conn, "INSERT INTO `products`(`name`, `price`, `product_detail`, `image`)
            VALUES ('$product_name','$product_price','$product_detail','$image')") or die('query failed');
            if ($insert_product) {
                if ($image_size > 2000000) {
                    $message[] = 'Image size is too large';
                }else{
                    move_uploaded_file($image_tmp_name , $image_folder);
                    $message[] = 'Product added successfully'; 
                }
            }
        }
    }

    //Deleting products from the database
    if (isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        $select_delete_image = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('
        query failed');
        $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
        unlink('image/'.$fetch_delete_image['image']);

        mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
        mysqli_query($conn, "DELETE FROM `cart` WHERE pid = '$delete_id'") or die('query failed');
        mysqli_query($conn, "DELETE FROM `wishlist` WHERE pid = '$delete_id'") or die('query failed');

        header('location:admin_product.php');
    }
     
    // Updating products
    if (isset($_POST['update_product'])) {
        $update_id = $_POST['update_id'];
        $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
        $update_price = mysqli_real_escape_string($conn, $_POST['update_price']);
        $update_detail = mysqli_real_escape_string($conn, $_POST['update_detail']);
        $update_image = $_FILES['update_image']['name'];
        $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
        $update_image_folder = 'image/'.$update_image;

    // Check if a new image was uploaded
    if(!empty($update_image)){
        // Upload new image and update all fields including image
        move_uploaded_file($update_image_tmp_name, $update_image_folder);
        $update_query = mysqli_query($conn, "UPDATE `products` SET `name`='$update_name', `price`='$update_price', `product_detail`='$update_detail', `image`='$update_image' WHERE id = '$update_id'") or die('query failed');
    } else {
        // Update fields without changing image
        $update_query = mysqli_query($conn, "UPDATE `products` SET `name`='$update_name', `price`='$update_price', `product_detail`='$update_detail' WHERE id = '$update_id'") or die('query failed');
    }

    if($update_query){
        header('location:admin_product.php');
    }
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
    
    <div class="line2">
        <section class="add-products form-container" style="margin-top: 17rem;">
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
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="input-field">
                    <label>Product name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="input-field">
                    <label>Product price</label>
                    <input type="text" name="price" required>
                </div>
                <div class="input-field">
                    <label>Product details</label>
                    <textarea name="detail" required></textarea>
                </div>
                <div class="input-field">
                    <label>Product image</label>
                    <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp" required>
                </div>
                <input type="submit" name="add_product" value="add_product" class="btn">
            </form>
        </section>
    </div>  
    <div class="line3"></div>
    <div class="line4"></div>
    <section class="show-products" style="margin-top: 30rem;">
        <div class="box-container" style="display: flex; flex-wrap: wrap;">
            <?php 
                $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
                if (mysqli_num_rows($select_products) > 0) {
                while($fetch_products = mysqli_fetch_assoc($select_products)){
            ?>
            <div class="product-box" style="width: 30%; margin: 1.5%; padding: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); text-align: center;">
                <img src="image/<?php echo $fetch_products['image']; ?>" style="width: 100%; height: auto;">
                <p>Price: Kes. <?php echo $fetch_products['price']; ?></p>
                <h4><?php echo $fetch_products['name']; ?></h4>
                <details><?php echo $fetch_products['product_detail']; ?></details>
                <a href="admin_product.php?edit=<?php echo $fetch_products['id']; ?>" class="edit" style="display: inline-block; margin-top: 10px; padding: 5px 10px; background-color: #007bff; color: #fff; border-radius: 5px;">Edit</a>
                <a href="admin_product.php?delete=<?php echo $fetch_products['id']; ?>" class="delete" style="display: inline-block; margin-top: 10px; padding: 5px 10px; background-color: #dc3545; color: #fff; border-radius: 5px;" onclick="return confirm('Want to delete this product?');">Delete</a>
            </div>
            <?php 
                    }
              } else {
                echo '
                    <div class="empty"n>
                     <p>No products added yet!</p> 
                    </div>
                    ';
            }    
             ?>
        </div>
    </section>
    <div class="line"></div>
    <section class="update-container">
        <?php 
            if (isset($_GET['edit'])) {
                $edit_id = $_GET['edit'];
                $edit_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$edit_id'") or die('query
                failed');
                if (mysqli_num_rows($edit_query)>0) {
                    while($fetch_edit = mysqli_fetch_assoc($edit_query)){

                        
        ?>
        <form method="POST" enctype="multipart/form-data">
            <img src="image/<?php echo $fetch_edit['image']; ?>">
            <input type="hidden" name="update_id" value="<?php echo $fetch_edit['id']; ?>">
            <input type="text" name="update_name" value="<?php echo $fetch_edit['name']; ?>">
            <input type="number" name="update_price" min="0" value="<?php echo $fetch_edit['price']; ?>">
            <textarea name="update_detail"><?php echo $fetch_edit['product_detail']; ?></textarea>
            <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png, image/webp">
            <input type="submit" name="update_product" value="Update" class="edit">
            <input type="reset" name="" value="Cancel" class="option-btn btn" id="close-form">
        </form>
        <?php
                    } 
                }
                echo "<script>document.querySelector('.update-container').style.display='block'</script>";
            }
        ?>
    </section>
    <script src="script.js"></script>
</body>
</html>