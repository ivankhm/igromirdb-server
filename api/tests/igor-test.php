
<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">

        <title>IGOR</title>



    </head>

    <style>

        #myTable {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd;
            font-size: 18px;
        }

        #myTable th, #myTable td {
            text-align: left;
            padding: 12px;
        }

        #myTable tr {
            border-bottom: 1px solid #ddd;
        }

        #myTable tr.header, #myTable tr:hover {
            background-color: #f1f1f1;
        }

    </style>
    <body>
    <table id="myTable">
        <?php
        // 0 2
        $mass = array( array(0, 1), array(2, 3));
        $row_count = 2;
        $column_count = 2;
            for ($i = 0; $i < $column_count; $i++){
                echo "<tr>";
                for ($j = 0; $j < $row_count; $j++) {
                    echo "<td>".$mass[$i][$j]. "</td>";
                }
                echo "</tr>";
            }

        ?>

    </table>


    </body>

</html>