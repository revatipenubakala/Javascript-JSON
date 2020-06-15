<?php 
    require_once('db.php');


    $sql = 'SELECT *
            FROM Profile as a
            WHERE a.profile_id = :xyz';

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'xyz' => $_GET['profile_id']
    ]);

    if ($stmt->rowCount() == 0) 
    {
        $_SESSION['error'] = 'Bad value for profile_id';
        
        header('Location: index.php');
        return;
    }






    ////check profile_id exist or not
    $sql = 'SELECT a.* , b.*
            FROM Profile as a
            LEFT JOIN Position as b
            ON a.profile_id = b.profile_id
            WHERE a.profile_id = :xyz';
    
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'xyz' => $_GET['profile_id']
    ]);


    $i = 0;
    while( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) 
    {        
        $firstName =  htmlentities($row['first_name']);
        $lastName =  htmlentities($row['last_name']);
        $email =  htmlentities($row['email']);
        $headline =  htmlentities($row['headline']);
        $summary =  htmlentities($row['summary']);

        if ( isset($row['rank']) ) 
        {
            $checkPos = true;
        }
        else 
        {
            $checkPos = false ;
        }
        $i++;

        if ($i == 1) 
        {
            break;
        } 
        else 
        {
            continue;
        }
        
    }
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


            <!-- printing Position -->
            <?php 

                if($checkPos)
                {
                    $sql = 'SELECT a.* , b.*
                        FROM Profile as a
                        LEFT JOIN Position as b
                        ON a.profile_id = b.profile_id
                        WHERE a.profile_id = :xyz';

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        'xyz' => $_GET['profile_id']
                    ]);                    
            ?>

                    <p>Position:<br>
            <?php 
                    echo "<ul>";
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                    {                
            ?>  
                        <li>
                            <?= $row['year']; ?> : <?= $row['description']; ?>
                        </li>
                       
            <?php 
                    }
                    echo "</ul>";
                }
            ?> 
            <a href="index.php">Done</a>
        </div>

















