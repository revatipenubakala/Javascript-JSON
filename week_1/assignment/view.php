<?php 
    require_once('db.php');



    ////check profile_id exist or not
    $sql = 'SELECT * FROM Profile WHERE profile_id = :xyz';    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'xyz' => $_GET['profile_id']
    ]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row === false) 
    {
        $_SESSION['error'] = 'Bad value for profile_id';
        
        header('Location: index.php');
        return;
    }


    $firstName =  htmlentities($row['first_name']);
    $lastName =  htmlentities($row['last_name']);
    $email =  htmlentities($row['email']);
    $headline =  htmlentities($row['headline']);
    $summary =  htmlentities($row['summary']);

?>






<!-- if exist -->
<!DOCTYPE html>
<html>
<head>
<title>Sarker Sunzid Mahmud</title>

<?php require_once "bootstrap.php"; ?>

</head>
    <body>
        <div class="container">
            <h1>Profile information</h1>

            <p>First Name:
            <?= $firstName ?></p>

            <p>Last Name:
            <?= $lastName ?></p>

            <p>Email:
            <?= $email ?></p>

            <p>Headline:<br>
            <?= $headline ?></p>
            
            <p>Summary:<br>
            <?= $summary ?></p><p>
            </p>

            <a href="index.php">Done</a>
        </div>

















