<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script type="text/javascript">
            var btn_submit;
            function submit() {
                var inputs = ["tb_email"];
                for (i = 0; i < inputs.length; i++) {
                    input = document.createElement("input");
                    input.setAttribute("id", inputs[i]);
                    switch (inputs[i].substr(0, 2)) {
                        case "tb":
                            input.setAttribute("type", "text");
                            break;
                        default:
                            break;
                    }
                    document.forms[0].insertBefore(inputs[i], btn_submit);
                }
                document.forms[0].submit();
            }
            window.onload = function() {
                btn_submit = document.createElement("input");
                btn_submit.setAttribute("id", "btn_submit");
                btn_submit.setAttribute("value", "Submit");
                btn_submit.setAttribute("onclick", "javascript:submit();");
                document.forms[0].append(btn_submit);
            }
        </script>
    </head>
    <body>
        <form id="form1" action="#" method="post">
            <?php include "navigation.php"; ?>
            <?php include "footer.php"; ?>
        </form>
    </body>
</html>
