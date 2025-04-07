<?php
session_start();
include 'librarycdn.php';
$pdo = require 'db_connect.php';

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    echo "
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            Swal.fire({
                icon: 'error',
                title: 'Access Denied',
                text: 'You must be logged in to access this page.',
                confirmButtonColor: '#ffa64d'
            }).then(() => {
                window.location.href = 'login.php';
            });
        });
    </script>";
    exit();
}

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'coordinator' && $_SESSION['role'] !== 'admin')) {
    echo "
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            Swal.fire({
                icon: 'error',
                title: 'Restricted Access',
                text: 'You do not have permission to access this page.',
                confirmButtonColor: '#ffa64d'
            }).then(() => {
                window.location.href = 'home.php';
            });
        });
    </script>";
    exit();
}

$settings = null;
if (isset($_POST['save_settings'])) {
    $open = $_POST['open_date'];
    $close = $_POST['close_date'];

    try {
        if (empty($open) || empty($close)) {
            throw new Exception('Open date or close date cannot be empty.');
        }
        $datafromDatabase = $pdo->query("SELECT open_date, close_date FROM magazine_closure_settings LIMIT 1");
        $settings = $datafromDatabase->fetch(PDO::FETCH_ASSOC);

        if ($settings === false) {
            $sql = "INSERT INTO magazine_closure_settings (open_date, close_date) VALUES (?, ?)";
            $datafromDatabase = $pdo->prepare($sql);
            if ($datafromDatabase->execute([$open, $close])) {
                echo "<script>
                window.addEventListener('DOMContentLoaded', (event) => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Settings Saved',
                        text: 'Submission dates saved successfully.',
                        confirmButtonColor: '#ffa64d'
                    }).then(() => {
                        window.location.href = 'coordinator_dashboard.php'; // Redirect after success
                    });
                });
                </script>";
            } else {
                echo "<script>
                window.addEventListener('DOMContentLoaded', (event) => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: 'Unable to save new settings.',
                        confirmButtonColor: '#ffa64d'
                    });
                });
                </script>";
            }
        } else {
            if ($open === $settings['open_date'] && $close === $settings['close_date']) {
                echo "<script>
                    window.addEventListener('DOMContentLoaded', (event) => {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });

                        Toast.fire({
                            icon: 'info',
                            title: 'No changes made to the submission dates.'
                        });
                    });
                </script>";
            } else {
                $sql = "UPDATE magazine_closure_settings SET open_date = ?, close_date = ?";
                $datafromDatabase = $pdo->prepare($sql);
                if ($datafromDatabase->execute([$open, $close])) {
                    echo "<script>
                                window.addEventListener('DOMContentLoaded', (event) => {
                                    Swal.fire({
                                        title: 'Submission dates updated successfully.',
                                        icon: 'success',
                                        confirmButtonText: 'Confirm',
                                        draggable: true
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = 'home.php'; // Redirect to home.php
                                        }
                                    });
                                });
                        </script>";
                } else {
                    echo "<script>
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });

                        Toast.fire({
                            icon: 'error',
                            title: 'Unable to update submission dates.'
                        });
                    </script>";
                }
            }
        }
    } catch (Exception $e) {
        echo "<script>
        window.addEventListener('DOMContentLoaded', (event) => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '" . $e->getMessage() . "',
                confirmButtonColor: '#ffa64d'
            });
        });
        </script>";
    }
} else {
    $stmt = $pdo->query("SELECT open_date, close_date FROM magazine_closure_settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Coordinator Settings</title>
    <style>
        :root{
            --mainTheme: #ffebcc;
            --subTheme: #ffcc80;
            --contrastTheme: #ffa64d;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: var(--mainTheme);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .settings-container {
            width: 400px;
            padding: 20px;
            background: var(--contrastTheme);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: white;
        }

        .settings-container h2 {
            text-align: center;
        }

        .settings-container input,
        .settings-container button {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ffffff;
            border-radius: 5px;
        }

        button {
            background-color: var(--subTheme);
            color: black;
            font-size: 18px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="settings-container">
        <h2>Submission Settings</h2>
        <form method="POST">
            <label>Open Date:</label>
            <input type="date" name="open_date" required value="<?= $settings['open_date'] ?? '' ?>">

            <label>Closure Date:</label>
            <input type="date" name="close_date" required value="<?= $settings['close_date'] ?? '' ?>">

            <button type="submit" name="save_settings">Save Settings</button>
        </form>
        <a href="home.php">
            <button type="button" class="cancel-button">Cancel</button>
        </a>
    </div>
</body>
</html>
