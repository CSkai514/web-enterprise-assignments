<?php
session_start();
include 'librarycdn.php'; 
include 'required_login.php'; 
$pdo = require 'db_connect.php';

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];
$user_faculty_id = $_SESSION['faculty_id'];

if ($user_role === 'student') {
    $query = $pdo->prepare("SELECT a.*, u.email AS author_name, f.name AS faculty_name, ac.comment
                            FROM articles a
                            LEFT JOIN users u ON a.user_id = u.id
                            LEFT JOIN faculties f ON a.faculty_id = f.id
                            LEFT JOIN article_comments ac ON a.id = ac.article_id
                            WHERE a.user_id = ?
                            ORDER BY a.created_at DESC");
    $query->execute([$user_id]);

} else if ($user_role === 'coordinator') {
    $stmt = $pdo->prepare("SELECT faculty_id FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $faculty_id = $stmt->fetchColumn();

    $query = $pdo->prepare("SELECT a.*, u.email AS author_name, f.name AS faculty_name, ac.comment
                            FROM articles a
                            LEFT JOIN users u ON a.user_id = u.id
                            LEFT JOIN faculties f ON a.faculty_id = f.id
                            LEFT JOIN article_comments ac ON a.id = ac.article_id AND ac.coordinator_id = ?
                            WHERE a.faculty_id = ?
                            ORDER BY a.created_at DESC");
    $query->execute([$user_id, $faculty_id]);

} else if ($user_role === 'manager' || $user_role === 'admin') {
    $query = $pdo->prepare("SELECT a.*, u.email AS author_name, f.name AS faculty_name, ac.comment
                            FROM articles a
                            LEFT JOIN users u ON a.user_id = u.id
                            LEFT JOIN faculties f ON a.faculty_id = f.id
                            LEFT JOIN article_comments ac ON a.id = ac.article_id AND ac.coordinator_id = ?
                            ORDER BY a.created_at DESC");
    $query->execute([$user_id]);
}

$maganizes = $query->fetchAll(PDO::FETCH_ASSOC);

$closureQuery = $pdo->query("SELECT final_closure_date FROM magazine_closure_settings ORDER BY id DESC LIMIT 1");
$closureResult = $closureQuery->fetch(PDO::FETCH_ASSOC);
$finalClosureDate = $closureResult['final_closure_date'];
$currentDate = date('Y-m-d');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Submitted Magazine</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fdf6e3;
            padding: 40px;
        }
        h1 {
            text-align: center;
            color: #ff8c00;
        }
        table.dataTable {
            width: 100%;
            border-collapse: collapse;
        }
        table.dataTable th, table.dataTable td {
            text-align: center;
            padding: 10px;
        }
        .preview-img {
            width: 80px;
            height: auto;
            object-fit: cover;
            border-radius: 4px;
        }
        .download-link {
            color: #ff8c00;
            /* text-decoration: none; */
        }
        .btn{
            margin: 5px;
        }
    </style>
</head>
<body>
<div style="text-align: left; margin: 20px;">
        <a href="home.php" style="font-size: 20px; text-decoration: none; color: #ff8c00;">
            <i class="bi bi-arrow-left-circle"></i> Back
        </a>
    </div>
<h1>Submitted Magazine</h1>

<table id="articleTable" class="display">
    <thead>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Faculty</th>
            <th>Image</th>
            <th>Word File</th>
            <th>Submitted At</th>
            <th>Comments</th>
            <th>Is selected</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php 
foreach ($maganizes as $maganize): ?>
<tr>
    <td><?= htmlspecialchars($maganize['title']) ?></td>
    <td><?= htmlspecialchars($maganize['author_name']) ?></td>
    <td><?= htmlspecialchars($maganize['faculty_name']) ?></td>
    <td>
        <img src="<?= $maganize['image_file'] ?>" alt="Image" width="100" style="cursor:pointer;" onclick="showImageModal('<?= $maganize['image_file'] ?>')">
    </td>
    <td>
        <i class="bi bi-file-earmark-word-fill"></i> <?= basename($maganize['word_file']) ?><br>
        <a href="<?= $maganize['word_file'] ?>" download class="download-link">Download</a>
    </td>
    <td><?= date('d M Y, h:i A', strtotime($maganize['created_at'])) ?></td>
    <td><?= htmlspecialchars($maganize['comment'] ?? 'No comment Yet') ?></td>
    <td>
    <?php if (!$maganize['is_selected']) {
        echo "<span class='badge bg-danger'>Not Selected</span>";
    }
    else{
        echo "<span class='badge bg-success'>Selected</span>";
    }
        
    ?>
    </td>
    <td>
        <?php
        $role = $_SESSION['role'];
        $userEmail = $_SESSION['email'];
        $userFaculty = $_SESSION['faculty_id'];
        $canEdit = strtotime($currentDate) <= strtotime($finalClosureDate);

        if ($role === 'student' && $maganize['faculty_id'] === $userFaculty ) {
            echo "<a href='view_maganize.php?id={$maganize['id']}' class='btn btn-sm btn-info'>View</a>";
        }  
        if ($maganize['is_selected'] != 1) {
            if ($canEdit) {
                echo "<a href='edit_maganize.php?id={$maganize['id']}' class='btn btn-sm btn-warning'>Edit</a> ";
            } else {
                echo "<button class='btn btn-sm btn-danger' disabled>Editing Closed</button> ";
            }
            echo "<a href='#' class='btn btn-sm btn-danger btn-delete' data-id='{$maganize['id']}'>Delete</a>";
        } 

        if (($role === 'coordinator' && $maganize['faculty_id'] === $userFaculty)|| $role === 'admin') {
            echo "<a href='view_maganize.php?id={$maganize['id']}' class='btn btn-sm btn-info'>View</a> ";
            echo "<a href='#' class='btn btn-sm btn-primary' onclick='commentAction({$maganize['id']})'>Comment</a> ";
            
            if (!$maganize['is_selected']) {
                echo "<form method='POST' action='select_maganize_fuction.php'>
                        <input type='hidden' name='select_id' value='{$maganize['id']}'>
                        <button type='submit' name='action' value='select' class='btn btn-sm btn-success'>Select</button>
                      </form>";
            } else {
                echo "<form method='POST' action='select_maganize_fuction.php'>
                        <input type='hidden' name='select_id' value='{$maganize['id']}'>
                        <br>
                        <button type='submit' name='action' value='deselect' class='btn btn-sm btn-warning'>Deselect</button>
                      </form>";
                // echo "<span class='badge bg-success'>Selected</span>";
            }
        }

        elseif ($role === 'manager'|| $role === 'admin' ) {
            echo "<a href='view_maganize.php?id={$maganize['id']}' class='btn btn-sm btn-secondary'>View</a>";
        }


        elseif ($role === 'admin') {
            echo "<a href='edit_maganize.php?id={$maganize['id']}' class='btn btn-sm btn-warning'>Edit</a> ";
            echo "<a href='delete_func.php?id={$maganize['id']}' class='btn btn-sm btn-danger'>Delete</a>";
        }

        elseif ($role === 'guest'  && $maganize['is_selected'] || $role === 'admin') {
            echo "<a href='view_maganize.php?id={$maganize['id']}' class='btn btn-sm btn-info'>View</a>";
        }
        ?>
    </td>
</tr>
<?php endforeach; ?>
    </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#articleTable').DataTable({
            pageLength: 10
        });

    });
</script>
<script>
    function showImageModal(imageUrl) {
        Swal.fire({
            imageUrl: imageUrl,
            imageAlt: 'Preview',
            showCloseButton: true,
            showConfirmButton: false,
            width: 'auto',
            padding: '1em',
        });
    }
</script>
<script>
    function commentAction(articleId) {
        Swal.fire({
            title: 'Enter your comment',
            input: 'textarea',
            inputPlaceholder: 'Write your comment...',
            showCancelButton: true,
            confirmButtonText: 'Save Comment',
            cancelButtonText: 'Cancel',
            preConfirm: (comment) => {
                if (comment) {
                    $.ajax({
                        url: 'comment_function.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            article_id: articleId,
                            comment: comment
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText); 
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to save the comment. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Comment cannot be empty!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
        });
    }
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to undo this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `delete_func.php?delete_id=${id}`;
                }
            });
        });
    });
});
</script>
</body>
</html>
