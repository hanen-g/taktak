<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if(empty($_SESSION["adm_id"])) {
    header('location:index.php');
} else {
    // Get statistics for the report
    $total_orders = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as total FROM users_orders"))['total'];
    $delivered_orders = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as total FROM users_orders WHERE status='closed'"))['total'];
    $pending_orders = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as total FROM users_orders WHERE status='' OR 'null'"))['total'];
    $in_progress = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as total FROM users_orders WHERE status='in process'"))['total'];
    
    // Get top delivery men
    $top_delivery = mysqli_query($db, "SELECT l.username, COUNT(o.o_id) as deliveries 
                                     FROM livreur l 
                                     LEFT JOIN users_orders o ON l.liv_id = o.liv_id AND o.status='closed' 
                                     GROUP BY l.liv_id 
                                     ORDER BY deliveries DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delivery Reports</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="fix-header">
<div id="main-wrapper">

        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">

                <div class="navbar-header">
                    <a class="navbar-brand" href="dashboard.php">

                        <span><img src="images/icn.png" alt="homepage" class="dark-logo" /></span>
                    </a>
                </div>

                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto mt-md-0">
                    </ul>

                    



                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="images/bookingSystem/user-icn.jpeg" alt="user" class="profile-pic" /></a>
                        <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                            <ul class="dropdown-user">
                                <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                            </ul>
                        </div>
                    </li>
                    </ul>
                </div>
            </nav>
        </div>

        <div class="left-sidebar">

            <div class="scroll-sidebar">
                

                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li class="nav-label">Home</li>
                        <li> <a href="dashboard.php"><i class="fa fa-tachometer"></i><span>Dashboard</span></a>
                        </li>
                        <li class="nav-label">Log</li>
                        <li> <a href="all_users.php"> <span><i class="fa fa-user f-s-20 "></i></span><span>Users</span></a></li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-archive f-s-20 color-warning"></i><span class="hide-menu">Restaurant</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_restaurant.php">All Restaurant</a></li>
                                <li><a href="add_category.php">Add Category</a></li>
                                <li><a href="add_restaurant.php">Add Restaurant</a></li>

                            </ul>
                        </li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-cutlery" aria-hidden="true"></i><span class="hide-menu">Menu</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_menu.php">All Menues</a></li>
                                <li><a href="add_menu.php">Add Menu</a></li>

                                

                            </ul>
                        </li>
                        <li> <a href="all_orders.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span>Orders</span></a></li>
                         <li> <a href="delivery_reports.php"><i class="fa fa-bar-chart"></i><span>Delivery Reports</span></a></li>

                    </ul>
                </nav>

            </div>

        </div>
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline-primary">
                        <div class="card-header">
                            <h4 class="m-b-0 text-white">Delivery Performance Reports</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card p-30">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-shopping-cart f-s-40"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php echo $total_orders; ?></h2>
                                                <p class="m-b-0">Total Orders</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card p-30">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-check f-s-40"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php echo $delivered_orders; ?></h2>
                                                <p class="m-b-0">Delivered Orders</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card p-30">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-hourglass-half f-s-40"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php echo $pending_orders; ?></h2>
                                                <p class="m-b-0">Pending Orders</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card p-30">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-truck f-s-40"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php echo $in_progress; ?></h2>
                                                <p class="m-b-0">In Progress</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="m-b-0 text-white">Delivery Status Distribution</h4>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="statusChart" height="250"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="m-b-0 text-white">Top Delivery Men</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Delivery Man</th>
                                                            <th>Completed Deliveries</th>
                                                            <th>Performance</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while($row = mysqli_fetch_assoc($top_delivery)): ?>
                                                            <tr>
                                                                <td><?php echo $row['username']; ?></td>
                                                                <td><?php echo $row['deliveries']; ?></td>
                                                                <td>
                                                                    <?php 
                                                                    $percentage = $total_orders > 0 ? round(($row['deliveries'] / $total_orders) * 100) : 0;
                                                                    echo '<div class="progress">
                                                                            <div class="progress-bar" role="progressbar" style="width: '.$percentage.'%" aria-valuenow="'.$percentage.'" aria-valuemin="0" aria-valuemax="100"></div>
                                                                          </div>';
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php endwhile; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "include/footer.php"; ?>
    <script>
        // Delivery Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Delivered', 'Pending', 'In Progress', 'Rejected'],
                datasets: [{
                    data: [
                        <?php echo $delivered_orders; ?>,
                        <?php echo $pending_orders; ?>,
                        <?php echo $in_progress; ?>,
                        <?php echo mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as total FROM users_orders WHERE status='rejected'"))['total']; ?>
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#ffc107',
                        '#17a2b8',
                        '#dc3545'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
</body>
</html>
<?php } ?>