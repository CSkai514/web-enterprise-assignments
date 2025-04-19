<?php
session_start();
include 'db_connect.php';
include 'alert_message_fuction.php';
include 'required_login.php'; 
    $role = $_SESSION['role'];
    $userName = $_SESSION['name'];
    $sql = "SELECT MONTH(created_at) AS month, COUNT(*) AS num_contributions 
        FROM articles 
        GROUP BY MONTH(created_at) 
        ORDER BY MONTH(created_at)";
    $stmt = $pdo_connect->prepare($sql);
    $stmt->execute();
    $submission_trends = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($submission_trends)) {
        $months = [];
        $submission_counts = [];
    } else {
        foreach ($submission_trends as $row) {
            $month = $row['month'];
            $num_contributions = $row['num_contributions'];

            $months[] = $month;
            $submission_counts[] = $num_contributions;
        }
        $months_json = json_encode($months);
        $submission_counts_json = json_encode($submission_counts);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        
    <aside class="sidebar">
    <p class="side-menu-title">Side menu</p>

    <a href="#"><p>Dashboard</p></a>

    <?php if ($role === 'student' || $role === 'admin'): ?>
        <a href="magazineSubmit.php"><p>Add New Magazine</p></a>
    <?php endif; ?>

    <?php if ($role === 'coordinator' || $role === 'admin' || $role === 'manager'): ?>
        <a href="coordinator_maganize_settings.php"><p>Add Closure Date</p></a>
    <?php endif; ?>

    <a href="maganize_view.php"><p>View maganize Data</p></a> <!-- Optional: visible to all -->
</aside>
        <header class="topbar">
        
            <div class="avatar">Avatar</div><br>
            <span style="padding-left: 20px;">Role: <?= ucfirst($_SESSION['role']) ?></span>
            <span style="padding-left: 20px;">Name: <?= ucfirst($userName) ?></span>
            <a href="logout.php" style="padding-left: 20px;">Logout</a>
        </header>
        <main class="content">
        <div id="submissionTrends" style="width: 100%; height: 400px;"></div>

            <!-- <div class="cards">
                <div class="card"></div>
                <div class="card"></div>
                <div class="card"></div>
            </div> -->
        </main>
    </div>

<script src="https://cdn.jsdelivr.net/npm/echarts@5.0.0/dist/echarts.min.js"></script>

<script>
    var submissionTrends = echarts.init(document.getElementById('submissionTrends'));
    var months = <?php echo $months_json; ?>;
    var submissionCounts = <?php echo $submission_counts_json; ?>;
    var submissionTrends = echarts.init(document.getElementById('submissionTrends'));
var option2 = {
    title: {
        text: months.length > 0 ? 'Submission Trends Over Time' : 'No Data Available'
    },
    tooltip: {},
    xAxis: {
        type: 'category',
        data: months.length > 0 ? months.map(month => {
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            return monthNames[month - 1];
        }) : ['No data']  // Show placeholder if empty
    },
    yAxis: {
        type: 'value'
    },
    series: [{
        data: submissionCounts.length > 0 ? submissionCounts : [0], 
        type: 'bar'
    }]
};

submissionTrends.setOption(option2);
</script>

</body>
</html>
