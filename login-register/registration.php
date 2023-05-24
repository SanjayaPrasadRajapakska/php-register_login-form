<?php
session_start();
if (isset($_SESSION["user"])) {
    header("location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registeration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <div class="container justify-content-center">
        <?php
        //print_r($_POST);print_r($_POST):input check kirimata bawitha karai

        use PgSql\Result;

        if (isset($_POST["submit"])) { //submit:btn name
            $FullName = $_POST["fullname"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $repeat_password = $_POST["repeat_password"];

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $errors = array();


            if (empty($FullName) or empty($email) or empty($password) or empty($repeat_password)) {
                array_push($errors, "All fields are requered");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not validate");
            }
            if (strlen($password) < 8) {
                array_push($errors, "Password must be at least acharactes long");
            }
            if ($password !== $repeat_password) {
                array_push($errors, "Password does not match");
            }


            require_once "database.php";
            $sql = "SELECT * FroM users WHERE email='$email'"; //email dekin register wima welakwima
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount > 0) {
                array_push($errors, "Email already exists!");
            }




            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                require_once "database.php";
                $sql = "INSERT INTO users(full_name,email,password) VALUES (?,?,?)"; //full_name,email,password:mysql adi table data name
                $stmt = mysqli_stmt_init($conn);
                $preparestmt = mysqli_stmt_prepare($stmt, $sql);

                if ($preparestmt) {
                    mysqli_stmt_bind_param($stmt, "sss", $FullName, $email, $passwordHash);
                    mysqli_stmt_execute($stmt);
                    echo "<div class='alert alert-success'>You are registered successfully</div>";
                }
            }
        }
        ?>
        <form action="registration.php" method="post">
            <div class="form-group">
                <input type="text" name="fullname" placeholder="Full Name" class="form-control">
            </div>

            <div class="form-group">
                <input type="email" name="email" placeholder="Email" class="form-control">
            </div>

            <div class="form-group">
                <input type="text" name="password" placeholder="Password" class="form-control">
            </div>

            <div class="form-group">
                <input type="text" name="repeat_password" placeholder="Repeat_password" class="form-control">
            </div>

            <div class="form-group mb-0">
                <input type="submit" name="submit" value="Register" class="btn btn-primary">
            </div>
        </form>
        <div><p>Already Registered<a href="login.php"> Login here</a></p></div>
    </div>


</body>

</html>