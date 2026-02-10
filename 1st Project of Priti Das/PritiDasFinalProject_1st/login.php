<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MediCare Hub – Login</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

  <header>
    <div class="header-container">
      <div class="logo">
        <img src="logo.png" alt="MediCare Hub Logo" />
      </div>
      <nav class="menu">
        <ul>
          <li><a href="#">Home</a></li>
          <li><a href="#">About</a></li>
          <li><a href="#">Services</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <div class="container">
    <div class="left-side">
      <img src="hospitalBG.jpg" alt="Hospital" />
    </div>

    <div class="right-side">
      <form class="login-form" method="post" action="loginConnection.php">
        <h2>Login</h2>
        <label for="username">Username or Email</label>
        <input type="text" id="username" name="username" required />

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />

        <button type="submit">Log In</button>

        <?php if (!empty($_GET['error'])): ?>
          <p style="color:#b00020; font-size:14px; margin-top:8px;">
            <?= htmlspecialchars($_GET['error']) ?>
          </p>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <footer>
    <p>MediCare Hub © 2025</p>
  </footer>

</body>
</html>
