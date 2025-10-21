<?php
include("../connection/connect.php");
session_start();
error_reporting(0);

// Redirect if not logged in
if (empty($_SESSION['liv_id'])) {
    header("Location: login_livreur.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Delivery Men Panel - Delivery Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="fix-header">
    <!-- Preloader -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>

    <!-- Main Wrapper -->
    <div id="main-wrapper">
        <!-- Header -->
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <div class="navbar-header">
                    <a class="navbar-brand" href="dashboard_livreur.php">
                        <img src="images/icn.png" alt="homepage" class="dark-logo" />
                    </a>
                </div>
                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto mt-md-0"></ul>
                    <ul class="navbar-nav my-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted" href="#" data-toggle="dropdown">
                                <img src="images/bookingSystem/livreur-icn.jpeg" alt="user" class="profile-pic" />
                            </a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <a class="dropdown-item" href="logout.php"><i class="fa fa-power-off"></i> Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- Sidebar -->
        <div class="left-sidebar">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li class="nav-label">Home</li>
                        <li><a href="dashboard_livreur.php"><i class="fa fa-tachometer"></i> Dashboard</a></li>
                        <li class="nav-label">Delivery Management</li>
                        <li><a href="all_orders.php"><i class="fa fa-list-alt"></i> Orders Tracking</a></li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Page Wrapper -->
        <div class="page-wrapper">
            <div class="container-fluid pt-3">
                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <div class="card-header">
                            <h4 class="text-white">Delivery Management Dashboard</h4>
                        </div>

                        <!-- Stats Row 1 -->
                        <div class="row">
                            <?php
                            $cards = [
                                ['icon' => 'fa-motorcycle', 'label' => 'Delivery Men', 'query' => "SELECT * FROM livreur"],
                                ['icon' => 'fa-shopping-cart', 'label' => 'Total Orders', 'query' => "SELECT * FROM users_orders"],
                                ['icon' => 'fa-check', 'label' => 'Delivered Orders', 'query' => "SELECT * FROM users_orders WHERE status='closed'"]
                            ];

                            foreach ($cards as $card) {
                                $result = mysqli_query($db, $card['query']);
                                $count = mysqli_num_rows($result);
                                echo <<<HTML
                                <div class="col-md-4">
                                    <div class="card p-30">
                                        <div class="media">
                                            <div class="media-left media-middle">
                                                <span><i class="fa {$card['icon']} f-s-40"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2>{$count}</h2>
                                                <p class="m-b-0">{$card['label']}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                HTML;
                            }
                            ?>
                        </div>

                        <!-- Stats Row 2 -->
                        <div class="row">
                            <?php
                            $cards2 = [
                                ['icon' => 'fa-clock-o', 'label' => 'Orders In Delivery', 'query' => "SELECT * FROM users_orders WHERE status='in process'"],
                                ['icon' => 'fa-hourglass-half', 'label' => 'Pending Orders', 'query' => "SELECT * FROM users_orders WHERE status IS NULL"]
                            ];

                            foreach ($cards2 as $card) {
                                $result = mysqli_query($db, $card['query']);
                                $count = mysqli_num_rows($result);
                                echo <<<HTML
                                <div class="col-md-6">
                                    <div class="card p-30">
                                        <div class="media">
                                            <div class="media-left media-middle">
                                                <span><i class="fa {$card['icon']} f-s-40"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2>{$count}</h2>
                                                <p class="m-b-0">{$card['label']}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                HTML;
                            }
                            ?>
                        </div>

                        <!-- Recent Deliveries -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="text-white">Recent Delivery Activities</h4>
                                    </div>
                                    <div class="card-body">
                                        <?php
                                        $recent_query = "
                                            SELECT o.*, u.f_name, u.l_name, l.nom_complet AS delivery_man
                                            FROM users_orders o
                                            LEFT JOIN users u ON o.u_id = u.u_id
                                            LEFT JOIN livreur l ON o.liv_id = l.liv_id
                                            ORDER BY o.date DESC LIMIT 5";
                                        $recent_result = mysqli_query($db, $recent_query);

                                        if (mysqli_num_rows($recent_result) > 0) {
                                            echo '<div class="table-responsive"><table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Order ID</th>
                                                            <th>Customer</th>
                                                            <th>Items</th>
                                                            <th>Status</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>';
                                            while ($row = mysqli_fetch_assoc($recent_result)) {
    $status_value = strtolower(trim($row['status'])); // Normalize the status value

    $status = match($status_value) {
        '', 'null'        => '<span class="label label-warning">Pending</span>',
        'in process'      => '<span class="label label-primary">In Delivery</span>',
        'closed'          => '<span class="label label-success">Delivered</span>',
        'rejected'        => '<span class="label label-danger">Rejected</span>',
        default           => '<span class="label label-default">' . htmlspecialchars($row['status']) . '</span>'
    };
   

                                                echo '<tr>
                                                        <td>' . $row['o_id'] . '</td>
                                                        <td>' . htmlspecialchars($row['f_name']) . ' ' . htmlspecialchars($row['l_name']) . '</td>
                                                        <td>' . htmlspecialchars($row['title']) . ' (x' . $row['quantity'] . ')</td>
                                                        <td>' . $status . '</td>
                                                        <td>' . date('d/m/Y H:i', strtotime($row['date'])) . '</td>
                                                    </tr>';
                                            }
                                            echo '</tbody></table></div>';
                                        } else {
                                            echo '<p>No recent delivery activities found.</p>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include "include/footer.php"; ?>
    </div>

    <!-- Scripts -->
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
</body>
</html>
