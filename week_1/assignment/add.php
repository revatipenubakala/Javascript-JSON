<?php
    session_start();
    if ( !isset($_SESSION['name']) ) 
    {
        die("Not logged in");
        
    }
    
    require_once('db.php');
    

    // If the user requested logout go back to index.php
    if ( isset($_POST['cancel']) ) 
    {

        header('Location: index.php');
        return;
    }

    //// after form submit
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

            header("Location: add.php");
            return;            

        }
        elseif ( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)   ) 
        {
            $_SESSION['error'] = 'Email address must contain @';

            header("Location: add.php");
            return;            
        }

        else 
        {
            // echo "<pre>";
            // print_r($_POST);
            // die();

            $stmt = $pdo->prepare('INSERT INTO Profile
                (user_id, first_name, last_name, email, headline, summary)
                VALUES ( :uid, :fn, :ln, :em, :he, :su)');

            $stmt->execute(array(
                ':uid' => $_SESSION['user_id'],
                ':fn' => $_POST['first_name'],
                ':ln' => $_POST['last_name'],
                ':em' => $_POST['email'],
                ':he' => $_POST['headline'],
                ':su' => $_POST['summary'])
            );


            $_SESSION['success'] = "Profile added";
            header("Location: index.php");
            return;
            
        }

    } 
    
        
    


?>


<!DOCTYPE html>
    <html>
    <head>
        <title>Sarker Sunzid Mahmud</title>

        <?php require_once "bootstrap.php"; ?>

    </head>

        <body>
            <div class="container">
                <h1>Adding Profile for <?= $_SESSION['name'] ?> </h1>
                <br>
                <?php
                    // Note triple not equals and think how badly double
                    // not equals would work here...
                    if ( isset($_SESSION['error'] ) ) 
                    {
                        // Look closely at the use of single and double quotes
                        echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
                        unset( $_SESSION['error'] );
                    }
                ?>

                <!-- <p style="color: green;">Record inserted</p> -->
                <form method="post">
                    <label>First Name:
                        <input type="text" name="first_name" size="60" class="form-control"/>
                    </label><br>

                    <label>Last Name:
                        <input type="text" name="last_name" size="60"  class="form-control"/>
                    </label><br>

                    <label>Email:
                        <input type="text" name="email" class="form-control"/>
                    </label><br>

                    <label>Headline:
                        <input type="text" name="headline" class="form-control"/>
                    </label><br>

                    <label>Summary:
                        <textarea name="summary" class="form-control" rows="8" cols="80"></textarea>                    
                    </label><br>
                    <br><br>

                    <input type="submit" value="Add" class="btn btn-success"> 
                    <input type="submit" name="cancel" value="Cancel" class="btn btn-warning">
                </form>


                    

            </div>
        </body>
</html>
