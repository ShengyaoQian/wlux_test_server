<html>
    <head>
        <style type="text/css">
        p, h2 { text-align: center; margin-top: 1em; }
        </style>
    </head>
    <body>
        <?php
            // todo - add error handling
            $file = "sess" . $_GET["wlux_session"] . "cond" . $_GET["wlux_condition"] . ".txt"
        ?>
        <h2>WebLabUX Survey</h2>
        <p>Thank you for taking part in our survey!!</p>
        <p>Click <a href="/server/study_results/<?=$file?>">here</a> to view the data collected.</p>
    </body>
</html>
