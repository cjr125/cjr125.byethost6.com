<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
    include '/classes/PHPExcel/IOFactory.php';

    $user = $_COOKIE["user"];
    $tableName = "";
    $inputFileName = "";
    if ($_POST["hf_download"] == "download") {
        // Instantiate a new PHPExcel object 
        $objPHPExcel = new PHPExcel();  
        // Set the active Excel worksheet to sheet 0 
        $objPHPExcel->setActiveSheetIndex(0);  
        // Initialise the Excel row number 
        $rowCount = 1;  
        DBLogin("a8823305_audio");
        $result = DBQuery("SELECT * from ".$tableName);
        //start of printing column names as names of MySQL fields  
        $column = 'A';
        for ($i = 1; $i < mysql_num_fields($result); $i++)  
        {
            $objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, mysql_field_name($result,$i));
            $column++;
        }
        //end of adding column names  

        //start while loop to get data  
        $rowCount = 2;  
        while($row = mysql_fetch_row($result))  
        {  
            $column = 'A';
            for($j=1; $j<mysql_num_fields($result);$j++)  
            {  
                if(!isset($row[$j]))  
                    $value = NULL;  
                elseif ($row[$j] != "")  
                    $value = strip_tags($row[$j]);  
                else  
                    $value = "";  

                $objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, $value);
                $column++;
            }  
            $rowCount++;
        } 

        DBC();
        // Redirect output to a client’s web browser (Excel5) 
        header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$tableName.'".xls"'); 
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
        $objWriter->save('php://output');
    }
    else if ($_POST["hf_download"] == "" && $_FILES["file"]["error"] > 0) {
        echo "Error: " . $_FILES["file"]["error"] . "<br>";
    } else if (sizeof($_FILES["file"]) > 0 && strpos($_FILES["file"]["name"], ".xls") != -1) {
        $inputFileName = $_FILES["file"]["name"];
        echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' using IOFactory to identify the format<br />';
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

        echo '<hr />';

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        var_dump($sheetData);
    }
    if (isset($_POST["btn_upload"]) && sizeof($_FILES["file"]) > 0 && strpos($_FILES["file"]["name"], ".xls") != -1) {
        DBLogin("a8823305_audio");
        $SQL = "CREATE TABLE ".$workbookTitle;
        $inputFileName = $_FILES["file"]["name"];
        echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' using IOFactory to identify the format<br />';
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);  
        // Set the active Excel worksheet to sheet 0 
        $objPHPExcel->setActiveSheetIndex(0);  
        // Initialise the Excel row number 
        $rowCount = 1;
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();
        $colNames = "";
        //  Loop through each row of the worksheet in turn
        for ($row = 1; $row <= $highestRow; $row++){
            //  Read a row of data into an array
            $rowData = $sheetData->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                            NULL,
                                            TRUE,
                                            FALSE);
            if ($row == 1) {
                $colNames .= "(";
                foreach ($rowData as $cell) {
                    $colNames .= $cell.",";
                }
                $colNames = substr($colNames,0,strlen($colNames)-1).")";
                $SQL .= $colNames;
            } else {
                $SQL .= "INSERT INTO ".$tableName." ".$colNames." (";
                foreach ($rowData as $cell) {
                    $SQL .= $cell.",";
                }
                $SQL .= substr($SQL,0,strlen($SQL)-1).")";
            }
        }
        DBQuery($SQL);
        DBC();
    }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#btn_download").on("click", function() {
            $("#form1").submit();
        });
        $("#btn_upload").on("click", function() {
            $("#form1").submit();
        });
    });
</script>
</head>
<body>
<?php
    if (logged_in()) {
        echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
        include 'menu_bar.php';
        if ($error != "") {
          echo "<span class='error'>".$error."</span><br>\n";
        }
        echo "<form id='form1' name='form1' action='excel_db.php' method='post'>\n";
        echo "<table id='tbl_exceldb'>\n";
        echo "<tr><th>download</th><th>upload</th></tr>\n";
        echo "<tr><td><input type='button' id='btn_download' name='btn_download' value='Download' /></td>\n";
        echo "<td><label for='file'>Filename:</label><input type='file' name='file' id='file'><input type='button' id='btn_upload' name='btn_upload' value='Upload' /></td></tr>\n";
        echo "</table>\n";
        echo "</form>\n";
    } else {
        header("Location: cms.php");
    }
?>
</body>
</html>