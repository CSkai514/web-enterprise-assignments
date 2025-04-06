<?php
session_start();
include 'librarycdn.php';
$pdo = include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo "
        <script> window.addEventListener('DOMContentLoaded', (event) => {
            Swal.fire({
                icon: 'error',
                title: 'Invalid details',
                text: 'Please fill in all fields.',
                confirmButtonColor: '#ffa64d'
            }).then(() => {
                window.location = 'login.php';
            });
        });
        </script>
        ";
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT id, email, password_hash, role,faculty_id FROM users WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['faculty_id'] = $user['faculty_id'];
                echo "<script>window.addEventListener('DOMContentLoaded', (event) => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Login successful!',
                            confirmButtonText:'Login',
                            confirmButtonColor: '#ffa64d'
                        }).then(() => {
                            window.location = 'home.php';
                        });
                    });
                       
                        </script>";
                $_SESSION['loggedIn'] = true;
                exit();
            } else {
                echo "
                        <script> window.addEventListener('DOMContentLoaded', (event) => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid details',
                                text: 'Incorrect Password.',
                                confirmButtonColor: '#ffa64d'
                            }).then(() => {
                                window.location = 'login.php';
                            });
                        });
                        </script>
                        ";
            }
        } else {
            echo "
                <script> window.addEventListener('DOMContentLoaded', (event) => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong',
                        text: 'User Not Found.',
                        confirmButtonColor: '#ffa64d'
                    }).then(() => {
                        window.location = 'login.php';
                    });
                });
                </script>
                ";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
