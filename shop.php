<?php
session_start();
include("php/connection.php");
include("php/functions.php");

// Verificăm autentificarea utilizatorului
$user_data = check_login($con);

// Verificăm dacă utilizatorul a apăsat butonul de adăugare în coș
if (isset($_POST['adauga'])) {
  $nume = $_POST['nume'];
  $pret = $_POST['pret'];
  $img = $_POST['img']; 
  
  adauga_in_cos($con, $nume, $img);
}


?>


<!DOCTYPE html>
<php lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/navbar.css?<?php echo time(); ?>" />
    <link rel="stylesheet" href="css/index.css?<?php echo time(); ?>" />
    <link rel="stylesheet" href="css/footer.css?<?php echo time(); ?>" />
    <link rel="stylesheet" href="css/shop.css?<?php echo time(); ?>" />
    <link rel="icon" href="imagini/logo.png" />
    <link
      rel="stylesheet"
      media="screen and (max-width: 1080px)"
      href="css/mobile.css?<?php echo time(); ?>"
    />
    <title>Shop - Electronix Heaven</title>
  </head>

  <body>
    <nav class="navbar">
      <div class="container">
        <div class="logo">
          <a href="index.php"
            ><img src="imagini/logo.png"
          /></a>
        </div>
        <div class="meniu">
          <ul class="meniu-dpd">
            <li><a href="index.php">Acasa</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="about.php">Despre</a></li>
            <?php
              if(!is_admin_or_seller($con, $user_data['id'])) { ?>
                <li><a href="cos.php"><img src="imagini/shopping-cart3.png" alt=""></a></li>
              <?php } ?>

          
            <?php 
                if ($user_data && !is_admin_or_seller($con, $user_data['id'])) { ?>
                    <li id="buton-cont"><a href="account.php"><img src="imagini/user.png"></a></li>
                    <li><a href="logout.php">Log Out</a></li>
                <?php } else if(!is_admin_or_seller($con, $user_data['id'])) { ?>
                    <li><a href="login.php">Log In</a></li>
                    <li><a href="register.php ">Register</a></li>
                <?php } ?>  
                
                <?php 
                if ($user_data && is_admin_or_seller($con, $user_data['id'])) {
                    echo '<div class="hamburger2">
                    <div class="middle-bar2">
                      <div class="top-bar2"></div>
                      <div class="bottom-bar2"></div>
                    </div>
                    <ul class="hidden meniu-admin">
                    <li class="li-menu"><a href="add_product.php">Adauga produs <img id="plus" src="imagini/plus.png" alt=""></a></li>
                    <li class="li-menu"><a href="order_history.php">Order History <img id="istorie" src="imagini/history.png" alt=""></a></li>
                    </ul>
                  </div>'; 
                }
                ?>
          </ul>

          <!-- <div class="hamburger">
            <div class="middle-bar">
              <div class="top-bar"></div>
              <div class="bottom-bar"></div>
            </div>
          </div> -->
        </div>  
      </div>
    </nav>

    <div id="bar">
      <div class="container bar-content">
        <div class="bar-text">
          <h2>Products</h2>
        </div>
      </div>
    </div>

    <div class="linie"></div>

    <div id="products">
        <div class="container">
        <?php 
        $select_produs = mysqli_query($con, "SELECT * FROM products ORDER BY id ASC") or die('query failed');
        if (mysqli_num_rows($select_produs) > 0) {
            while ($fetch_produs = mysqli_fetch_assoc($select_produs)) {
                $product_id = $fetch_produs['id'];
                $seller_id = $fetch_produs['seller_id']; // Obții seller_id-ul produsului

                // Obții numele vânzătorului asociat cu acest produs
                $seller_query = mysqli_query($con, "SELECT user_name FROM users WHERE id = $seller_id");
                $seller_data = mysqli_fetch_assoc($seller_query);
                $seller_name = $seller_data['user_name'];
                ?>
                  <div class="box">
                      <div class="box-content">
                          <div class="img-container">
                                <?php
                                $product_id = $fetch_produs['id'];
                                echo '<img src="imagini/' . $fetch_produs['img'] . '" alt="Image" class="image-fit">';
                              ?>
                          </div>
                          <div class="descriere"><?php echo $fetch_produs['descriere']; ?></div>
                          
                          <div class="name">
                          <p><?php echo $fetch_produs['nume']; ?></p>
                          </div>
                          <div class="pret"><?php echo $fetch_produs['pret']; ?> lei</div>
                          
                          <div class="vandut-de">Vândut de: <?php echo $seller_name == "admin" ? "ElectronixHeaven" : $seller_name; ?></div> 
                          <form method="POST" action="">
                              <input type="hidden" name="nume" value="<?php echo $fetch_produs['nume']; ?>">
                              <input type="hidden" name="pret" value="<?php echo $fetch_produs['pret']; ?>">
                              <input type="hidden" name="img" value="<?php echo $product_id; ?>">
                              
                              <?php 
                                if ($fetch_produs['quantity'] > 0) {
                                  echo '<input type="submit" name="adauga" value="Adauga in cos" class="hero-btn">';
                                } else {
                                  echo '<div class="out-of-stock">Out of stock</div>';
                                }
                              ?>
                              <!-- <input type="submit" name="adauga" value="Adauga in cos" class="hero-btn"> -->
                          </form>                  
                          <?php 
                            if ($user_data && is_admin_or_seller($con, $user_data['id'])) {
                              // Add the quantity div here
                          ?>
                            <div class="quantity-container">
                              <p class="quantity-text">In stock: <?php echo $fetch_produs['quantity']; ?></p>
                            </div>
                          <?php } ?>                        
                                             
                          <div class="view-reviews">
                            <?php 
                              echo "<a href='product_reviews.php?id={$fetch_produs['id']}'>Check Reviews</a>";
                            ?>
                          </div>
                      </div>
                  </div>
                  <?php
              }
          }
          ?>
        </div>
    </div>

    <div class="linie"></div>

    <footer id="footer">
      <div class="footer-content">
        <div class="social-media">
          <a href="https://www.instagram.com/andreixbolos"
            ><img src="imagini/instagram-logo (1).png"
          /></a>
          <a href="https://www.linkedin.com/in/andrei-bolo%C8%99-408ab1254/"
            ><img src="imagini/linkedin.png"
          /></a>
          <a href="https://github.com/andreixdbolos"
            ><img src="imagini/github-sign.png"
          /></a>
        </div>
      </div>
    </footer>

    <script src="dpd.js"></script>
  </body>
</html>
