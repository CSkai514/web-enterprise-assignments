<?php
if (isset($_SESSION['show_alert_global']) && $_SESSION['show_alert_global']) {
    $alert_messages_fromOtherpage = $_SESSION['alert_message'];
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                itle: '" . addslashes($alert_messages_fromOtherpage) . "',
                showConfirmButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'home.php'; // Redirect to home.php after confirming
                }
            });
        });
    </script>";
    unset($_SESSION['show_alert_global']);
}

if (isset($_SESSION['error_message'])) {
    $error_messages_fromOtherPage = $_SESSION['error_message'];
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: " . json_encode($error_messages_fromOtherPage) . ",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>";
    unset($_SESSION['error_message']);
}
?>