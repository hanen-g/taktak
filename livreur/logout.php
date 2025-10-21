                
                <?php
session_start();
session_destroy();
$url = 'login_livreur.php';
header('Location: ' . $url);

?>
                