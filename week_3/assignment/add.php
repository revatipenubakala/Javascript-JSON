<?php
    session_start();
    if ( !isset($_SESSION['name']) ) 
    {
        die("Not logged in");
        
    }
    
    require_once('db.php');
    require_once('mr_trait.php');
    

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
        /////from mr_trait.php
        $msg = validateProfile();

        if ( is_string($msg) ) 
        {
            $_SESSION['error'] = $msg;
            header("Location: add.php");
            return;
        }

        ////validate the position from mr_trait.php
        $msg = validatePos();

        if ( is_string($msg) ) 
        {
            $_SESSION['error'] = $msg;
            header("Location: add.php");
            return;
        }

        ////otherwise data is valid, so time to insert
        $stmt = $pdo->prepare('INSERT INTO Profile
            (user_id, first_name, last_name, email, headline, summary)
            VALUES ( :uid, :fn, :ln, :em, :he, :su)');

        $profInsrt = $stmt->execute(array(
            ':uid' => $_SESSION['user_id'],
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['last_name'],
            ':em' => $_POST['email'],
            ':he' => $_POST['headline'],
            ':su' => $_POST['summary'])
        );

        $profileId = $pdo->lastInsertId();

        ////insert into Postition
        $rank = 1;
        for($i=1; $i<=9; $i++) 
        {
            if ( ! isset($_POST['year'.$i]) ) continue;
            if ( ! isset($_POST['desc'.$i]) ) continue;

            $year = $_POST['year'.$i];
            $desc = $_POST['desc'.$i];

            $stmt = $pdo->prepare(
                'INSERT INTO Position (profile_id, rank, year, description)
                VALUES (:pid, :rank, :year, :desc)');

            $posInsrt = $stmt->execute([
                ':pid' => $profileId,
                ':rank' => $rank,
                ':year' => $year,
                ':desc' => $desc
            ]);

            $rank ++;
        
        }


        $_SESSION['success'] = "Profile added";
        header("Location: index.php");
        return;

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
                    </label><br><br>

                    <label>Last Name:
                        <input type="text" name="last_name" size="60"  class="form-control"/>
                    </label><br><br>

                    <label>Email:
                        <input type="text" name="email" class="form-control"/>
                    </label><br><br>

                    <label>Headline:
                        <input type="text" name="headline" class="form-control"/>
                    </label><br><br>

                    <label>Summary:
                        <textarea name="summary" class="form-control" rows="8" cols="80"></textarea>                    
                    </label><br><br>
                    



                    <label>
                    Position: <input type="submit" class="btn btn-primary"  id="addPos" value="+" >
                    </label><br><br>

                    <div id="position_fields">
                    </div><br><br>





                    <br><br>
                    <input type="submit" value="Add" class="btn btn-success"> 
                    <input type="submit" name="cancel" value="Cancel" class="btn btn-warning">
                </form>



                
                <script>
                    countPos = 0;

                    // http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
                    $(document).ready(function(){
                        window.console && console.log('Document ready called');
                        $('#addPos').click(function(event){
                            // http://api.jquery.com/event.preventdefault/
                            event.preventDefault();
                            if ( countPos >= 9 ) {
                                alert("Maximum of nine position entries exceeded");
                                return;
                            }
                            countPos++;
                            window.console && console.log("Adding position "+countPos);
                            $('#position_fields').append(
                                '<div id="position'+countPos+'"> \
                                    <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
                                    <input type="button" value="-" \
                                        onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
                                    <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
                                    <br><br> \
                                </div>');
                        });
                    });
                </script>

            </div>
        </body>
</html>
