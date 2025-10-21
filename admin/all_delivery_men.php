<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if(empty($_SESSION["adm_id"])) {
    header('location:index.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Delivery Men</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="fix-header">
    <?php include "include/header.php"; ?>

    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="col-lg-12">
                        <div class="card card-outline-primary">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white">All Delivery Men</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Username</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM livreur ORDER BY liv_id DESC";
                                            $result = mysqli_query($db, $sql);
                                            
                                            if(mysqli_num_rows($result) > 0) {
                                                while($row = mysqli_fetch_assoc($result)) {
                                                    echo '<tr>
                                                            <td>'.$row['liv_id'].'</td>
                                                            <td>'.$row['username'].'</td>
                                                            <td>';
                                                    echo $row['disponible'] ? 
                                                        '<span class="label label-success">Available</span>' : 
                                                        '<span class="label label-danger">On Delivery</span>';
                                                    echo '</td>
                                                            <td>
                                                                <a href="edit_delivery_man.php?id='.$row['liv_id'].'" class="btn btn-sm btn-primary">Edit</a>
                                                                <a href="delete_delivery.php?id='.$row['liv_id'].'" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>
                                                            </td>
                                                        </tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="4">No delivery men found</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <a href="add_delivery_man.php" class="btn btn-primary">Add New Delivery Man</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "include/footer.php"; ?>
</body>
</html>
<?php } ?>