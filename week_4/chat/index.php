<?php 
    session_start();

    if (isset($_POST['reset'])) 
    {
        $_SESSION['chats'] = [];

        header("Location: index.php");
        return;
    }

    if (isset($_POST['message'])) 
    {
        if (!isset($_SESSION['chats'])) 
        {
            $_SESSION['chats'] = [];
        }

        $_SESSION['chats'][] = [ $_POST['message'], date(DATE_RFC2822)  ];

        header("Location: index.php");
        return;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sunzid</title>

    <link rel="stylesheet" 
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
        integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
        crossorigin="anonymous">

    <link rel="stylesheet" 
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
        integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
        crossorigin="anonymous">

    <script
    src="https://code.jquery.com/jquery-3.2.1.js"
    integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
    crossorigin="anonymous"></script>
</head>
<body>
    <h1> Chat </h1>

    <form action="index.php" method="post">
        <p>
            <input type="text" name="message"  size="60" id="">
            <input type="submit" value="Chat" >
            <input type="submit" name="reset" value="Reset" >        
        </p>
    </form>

    <div id="chatcontent">
        <img src="spinner.gif" alt="Loading..." >
    </div>

    <script>


            function updateMsg() 
            {
                console.log('requesting json');
                
                $.ajax({
                    url: "chatlist.php",
                    cache: false,

                    success: function(data) {
                        console.log('json received');
                        console.log(data);
                    
                        $('#chatcontent').empty();

                        for (var i = 0; i < data.length; i++) 
                        {
                            entry = data[i];

                            $('#chatcontent').append(
                                "<p>" + entry[0] + "<br/>&nbsp;&nbsp;"+ entry[1] + "</p>\n"
                            );    
                        }
                        setTimeout('updateMsg()', 4000);
                        
                    }
                });            
            }

        $(document).ready(function(){
            console.log('startup complete');

            updateMsg();
            
        });

    </script>

</body>
</html>