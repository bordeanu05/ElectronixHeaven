<?php
include("php/connection.php");
include("php/functions.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
$user_data = check_login($con);

function getUserDataFromDatabase($userId) {
    global $con;

    $query = "SELECT * FROM users WHERE id = $userId";
    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }

    $userData = mysqli_fetch_assoc($result);

    // Close the result set, not the connection
    mysqli_free_result($result);

    return $userData;
}

// Example usage
$userId = $user_data['id'];
$userData = getUserDataFromDatabase($userId);

// Display user data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Account</title>
    <link rel="stylesheet" href="css/navbar.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="css/account-page.css?<?php echo time(); ?>"> <!-- You can link your styles here -->
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
      <div class="container">
        <div class="logo">
          <a href="index.php"
            ><img src="imagini/logo.png"
          /></a>
        </div>
        <div class="meniu">
          <ul class="hidden meniu-dpd">
            <li><a href="index.php">Acasa</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="about.php">Despre</a></li>
            <?php
              if(!is_admin_or_seller($con, $user_data['id'])) { ?>
                <li><a href="cos.php"><img src="imagini/shopping-cart3.png" alt=""></a></li>
              <?php } ?>
            
          </ul>

          <div class="hamburger">
            <div class="middle-bar">
              <div class="top-bar"></div>
              <div class="bottom-bar"></div>
            </div>
          </div>
        </div>
      </div>
    </nav>

<!-- User Account Section -->
<section class="account-section">
    <h2>User Account</h2>

    <?php
    // Check if the user is logged in (you may need more robust authentication)
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['user_id'])) {
        // Fetch and display user data
        $user_id = $_SESSION['user_id'];
        // You should replace the below with your database query logic
        $user_data = getUserDataFromDatabase($user_id);

        // Display user information
        echo '<div class="user-data">
            <p><strong>User ID:</strong> ' . $user_data['id'] . '</p>
            <p><strong>Username:</strong> ' . $user_data['user_name'] . '</p>
            <p><strong>Email:</strong> ' . $user_data['email'] . '</p>
        </div>';

        if ($user_data['account_type'] == 'seller') {
            // Fetch and display products sold by this seller
            $query_products = "SELECT * FROM products WHERE seller_id = $user_id";
            $result_products = mysqli_query($con, $query_products);
            echo '<h3>Products Sold by You</h3>';
            if ($result_products) {
                // Display products
                echo '<div id="tabel-comanda">
                    <table border="1">
                        <tr>
                            <th>Product Name</th>
                            <th>Description</th>
                            <!-- Add more columns as needed -->
                        </tr>';
                while ($product_data = mysqli_fetch_assoc($result_products)) {
                    echo '<tr>
                            <td>' . $product_data['nume'] . '</td>
                            <td>' . $product_data['descriere'] . '</td>
                            <!-- Add more cells for additional product information -->
                        </tr>';
                }
                echo '</table></div>';
                mysqli_free_result($result_products);
            } else {
                echo "Error fetching products sold by you: " . mysqli_error($con);
            }
        } else {
            // Display orders for non-seller users
            $query_orders = "SELECT * FROM orders WHERE user_id = $user_id";
            $result_orders = mysqli_query($con, $query_orders);
            echo '<h3>My Orders</h3>';
            if ($result_orders) {
                echo '<div id="tabel-comanda">
                    <table border="1">
                            <tr>
                                <th>Order Number</th>
                                <th>Total Price</th>
                                <th>Order Date</th>
                                <th>Name</th>
                                <th>Surname</th>
                                <th>Address</th>
                                <th>Phone Number</th>
                                <th>Email</th>
                                <th>Payment Method</th>
                            </tr>';

                while ($order_data = mysqli_fetch_assoc($result_orders)) {
                    echo '<tr>
                                <td>' . $order_data['order_number'] . '</td>
                                <td>' . $order_data['total_price'] . '</td>
                                <td>' . $order_data['order_date'] . '</td>
                                <td>' . $order_data['name'] . '</td>
                                <td>' . $order_data['surname'] . '</td>
                                <td>' . $order_data['address'] . '</td>
                                <td>' . $order_data['phone_number'] . '</td>
                                <td>' . $order_data['user_email'] . '</td>
                                <td>' . $order_data['payment_method'] . '</td>
                              </tr>';
                }

                echo '</table></div>';
                mysqli_free_result($result_orders);
            } else {
                echo "Error fetching user orders: " . mysqli_error($con);
            }
        }
        // Add more user information as needed
    } else {
        // Redirect to login page if not logged in
        header("Location: login.php");
        exit();
    }
    ?>

    <div id="log-out-btn">
        <div id="buton-log">
        <a href="logout.php">Logout</a>
        </div>
    </div><!-- Assuming you have a logout page -->
</section>

</body>
</html>
