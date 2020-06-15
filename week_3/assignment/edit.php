<?php
    require_once('db.php');
    session_start();
    if ( !isset($_SESSION['name']) ) 
    {
        die("Not logged in");
        
    }
    //// after form submission
    if (    
            isset($_POST['first_name']) 
            &&  isset($_POST['last_name']) 
            &&  isset($_POST['email']) 
            &&  isset($_POST['headline']) 
            &&  isset($_POST['summary']) 
    
    )    
    {
        if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 
                || strlen($_POST['headline']) < 1 
                || strlen($_POST['summary']) < 1 
        ) 
        {
            $_SESSION['error'] = "All fields are required";

            header("Location: edit.php?profile_id=" . $_POST["profile_id"]);
            return;            

        }
        elseif ( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)   ) 
        {
            $_SESSION['error'] = 'Email address must contain @';

            header("Location: edit.php?profile_id=" . $_POST["profile_id"]);
            return;            
        }
        else 
        {
            // echo "<pre>";
            // print_r($_POST);
            // die();
            $sql = "UPDATE Profile SET 
                    user_id = :a,
                    first_name = :fn,
                    last_name = :ln,
                    email = :em,
                    headline = :he,
                    summary = :su

                    WHERE profile_id = :tt";


            // die($_POST['profile_id']);

            $stmt = $pdo->prepare($sql);

            $stmt->execute(array(
                    ':a' => $_SESSION['user_id'],
                    ':fn' => $_POST['first_name'],
                    ':ln' => $_POST['last_name'],
                    ':em' => $_POST['email'],
                    ':he' => $_POST['headline'],
                    ':su' => $_POST['summary'],
                    ':tt' =>  $_POST['profile_id']
                )
            );


            $_SESSION['success'] = "Profile updated";
            header("Location: index.php");
            return;
            
        }

    } 


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
        <h1 style="text-align:center; margin-bottom: 50px"> 
                Editing Profile for <?= $_SESSION['name'] ?>
        </h1>
        <?php
            // Note triple not equals and think how badly double
            // not equals would work here...
            if ( isset($_SESSION['error'] ) ) 
            {
                // Look closely at the use of single and double quotes
                echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
                unset( $_SESSION['error'] );
            }
            // else 
            // {
            //     echo "<pre>";
            //     print_r($_SESSION);
            // }

        ?>
        <form method="post">
            <input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>">

            <label>First Name:
                <input type="text" name="first_name" size="60" class="form-control" value="<?= $firstName ?>"/>
            </label><br>

            <label>Last Name:
                <input type="text" name="last_name" value="<?= $lastName ?>" size="60"  class="form-control"/>
            </label><br>

            <label>Email:
                <input type="text" name="email" value="<?= $email ?>" class="form-control"/>
            </label><br>

            <label>Headline:
                <input type="text" name="headline" value="<?= $headline ?>" class="form-control"/>
            </label><br>

            <label>Summary:
                <textarea name="summary"  class="form-control" rows="8" cols="80"><?= $summary ?></textarea>                    
            </label><br>
            <br><br>


            <input type="submit" value="Update" name="Save" class="btn btn-success">
            <a href="index.php" class="btn btn-warning">Cancel</a>
        </form>
    
    </div>