<?php
session_start();
include 'config.php';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if (!empty($email) && !empty($password)) {
        // Prepared statement untuk mencegah SQL Injection
        $stmt = $conn->prepare("SELECT * FROM user WHERE email_address = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Jika email ditemukan
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verifikasi password
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id_user'];
                $_SESSION['role'] = $row['role'];

                // Redirect berdasarkan role
                if ($row['role'] === "admin") {
                    header("Location: dashboard.php");
                } else {
                    header("Location: dashboard_user.php");
                }
                exit();
            } else {
                $error = "Invalid email or password";
            }
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="my-login.css" />
</head>

<body class="my-login-page">
    <nav>
        <ul class="nav-flex-row">
            <li class="nav-item"><a href="index.php">Home</a></li>
            <li class="nav-item"><a href="reservation.php">Reservation</a></li>
            <li class="nav-item"><a href="menu.php">Menu</a></li>
            <li class="nav-item"><a href="login.php">Log in</a></li>
        </ul>
    </nav>
    <section class="section-intro">
        <div class="container h-100">
            <div class="row justify-content-md-center h-100">
                <div class="card-wrapper">
                    <div class="brand">
                        <img src="img/logo.jpg" alt="logo" />
                    </div>
                    <div class="card fat">
                        <div class="card-body">
                            <h4 class="card-title">Login</h4>
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger"><?= $error ?></div>
                            <?php endif; ?>
                            <form method="POST" class="my-login-validation" novalidate="">
                                <div class="form-group">
                                    <label for="email">E-Mail Address</label>
                                    <input
                                        id="email"
                                        type="email"
                                        class="form-control"
                                        name="email"
                                        required
                                        autofocus />
                                    <div class="invalid-feedback">Email is invalid</div>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input
                                        id="password"
                                        type="password"
                                        class="form-control"
                                        name="password"
                                        required data-eye />
                                    <div class="invalid-feedback">Password is required</div>
                                    <div class="custom-checkbox custom-control">
                                                                                <input
                                                                                        type="checkbox"
                                                                                        name="remember"
                                                                                        id="remember"
                                                                                        class="custom-control-input" />
                                                                                <label for="remember" class="custom-control-label">Remember Me</label>
                                                                            </div>
                                </div>
                                <div class="form-group m-0">
                                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                                </div>
                                <div class="mt-4 text-center">
                                    Don't have an account? <a href="register.php">Create One</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="footer">
                        Copyright &copy; 2024 &mdash; Restaurantes &copy; All rights reserved
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="js/my-login.js"></script>
</body>

</html>