<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarker Sunzid</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <form id="target">
        <input type="text" name="one" value="Hello There" style="vertical-align: middle" />

        <img src="spinner.gif" 
            id="spinner" height="25"
            style="vertical-align: middle; display: none;"
        >
    </form>
    <div id="result">

    </div>

    <script>
        $('#target').change(function(event) {
            $('#spinner').show();

            var form = $('#target');
            var txt = form.find('input[name="one"]').val();

            // console.log(txt);

            $.post('autoecho.php', { 'val': txt }, 
                function(data) {
                    console.log(data);
                    $('#result').empty().append(data);
                    $('#spinner').hide();    


                    data = {
                        'one': 'two', 'three': 4,
                        'five': ['six', 'seven'],
                        'eight': { 'nine': 10, 'ten': 11 }
                    }

                    // alert(data.five[0]);                
                }            
            ).fail(
                function() {
                    $('#target').css('background-color', 'red');
                    alert('Whoops');
                }
            );
            
        });
    </script>

</body>
</html>