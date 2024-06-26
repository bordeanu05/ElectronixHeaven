<?php
session_start();
include("php/connection.php");
include("php/functions.php");

$user_data = check_login($con);
if (!isset($_SESSION['redirected']) && !isset($_SESSION['logged_in'])) {
  $_SESSION['redirected'] = true;
  header("Location: login.php");
}

if (isset($_SESSION['loggedIn'])) {
  $loggedIn = true;
} else {
  $loggedIn = false;
}

if (isset($_POST['login'])) {

  $username = $_POST['username'];
  $password = $_POST['password'];

  if ($username === 'admin' && $password === 'password') {
    $_SESSION['loggedIn'] = true;
    header('Location: index.php');
    exit();
  } else {
  }
}

if (isset($_POST['logout'])) {
  unset($_SESSION['loggedIn']);

  header('Location: index.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/navbar.css?<?php echo time(); ?>" />
    <link rel="stylesheet" href="css/index.css?<?php echo time(); ?>" />
    <link rel="stylesheet" href="css/footer.css?<?php echo time(); ?>" />
    <link rel="stylesheet" href="css/about.css?<?php echo time(); ?>" />
    <link rel="icon" href="imagini/logo.png" />
    <link
    rel="stylesheet"
    media="screen and (max-width: 1080px)"
    href="css/mobile.css?<?php echo time(); ?>"
  />
    <title>Despre - Electronix Heaven</title>
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
          <ul class="hidden meniu-dpd">
            <li><a href="index.php">Acasa</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="about.php">Despre</a></li>
            <?php
              if(!is_admin_or_seller($con, $user_data['id'])) { ?>
                <li><a href="cos.php"><img src="imagini/shopping-cart3.png" alt=""></a></li>
              <?php } ?>
            <?php 
                if ($user_data) { ?>
                    <li id="buton-cont"><a href="account.php"><img src="imagini/user.png"></a></li>
                    <li><a href="logout.php">Log Out</a></li>
                <?php } else { ?>
                    <li><a href="login.php">Log In</a></li>
                    <li><a href="register.php ">Register</a></li>
                <?php } ?>  
            
          </ul>
            <!-- <li><a href="login.php">Login</a></li> -->
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

    <div id="about">
      <div class="container about-content">
        <div class="about-text">
          <h2>Despre noi</h2>
          <p>
            • Bine ai venit la magazinul nostru online de electronice, Electronix Heaven! Suntem o echipă pasionată de tehnologie și gadgeturi, dedicată să-ți aducă cele mai recente și inovatoare produse electronice. Ne propunem să fim destinația ta preferată atunci când vine vorba de achiziționarea celor mai avansate dispozitive și accesorii electronice.
<br><br>
• La magazinul nostru, găsești o gamă largă de produse electronice de la branduri de top din industrie. Indiferent dacă îți dorești un smartphone de ultimă generație, o tabletă performantă, un laptop puternic sau chiar cele mai noi gadgeturi inteligente, suntem aici să-ți satisfacem toate preferințele și nevoile.
</p>

          </div>
        </div>
      </div>
</div>

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
    <script src="navbar.js"></script>
  </body>
</html>
