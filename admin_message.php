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
        
        mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('query failed');

        header('location:admin_message.php');
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
        <section class="message-container">
            <h1 class="title">Unread message</h1>
            <div class="box-container" style="display: flex; flex-wrap: wrap;">
                <?php 
                    $select_message = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
                    if (mysqli_num_rows($select_message) > 0) {
                        while($fetch_message = mysqli_fetch_assoc($select_message)){
                ?>
                <div class="message-box" style="width: 23%; margin: 1%; padding: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                    <p>User id: <span><?php echo $fetch_message['id']; ?></span></p>
                    <p>Name: <span><?php echo $fetch_message['name']; ?></span></p>
                    <p>Number: <span><?php echo $fetch_message['number']; ?></span></p>
                    <p>Email: <span><?php echo $fetch_message['email']; ?></span></p>
                    <p><?php echo $fetch_message['message']; ?></p>
                    <a href="admin_message.php?delete=<?php echo $fetch_message['id']; ?>" onclick="return confirm('Delete this message?');">Delete</a>
                </div>
    

                <?php 
                        }
                    }else{
                        echo '
                            <div class="empty">
                                <p>No messages sent yet!</p>
                            </div>
                        ';
                    }
                    
                ?>
            </div>
        </section> 
        <div class="line"></div>
        <script src="script.js"></script>

</body>
</html>