<?php


    require '../../db_conn.php';

    session_start();

    if(isset($_SESSION['database'])){
        $db = $_SESSION['database'];
        mysqli_select_db($conn, $db);

        if(isset($_POST['submit'])){
            
            $tb1 = $_POST['table1'];
            $tb2 = $_POST['table2'];
            $sql = compareTable($tb1,$tb2);

            //calculate time for comparison
            $msc = microtime(true);
            $result = mysqli_query($conn, $sql);
            $msc = (microtime(true)-$msc) * 1000;

            $header_info = mysqli_fetch_fields($result);

            if(!$result) {
                echo "some error";
            } else{
                echo "<p>time for comparison: $msc millisecond.</p>";
                echo "<table>";
                echo "<tr>";
                foreach ($header_info as $val) {
                    echo "<th>$val->name</th>";
                }
                echo "</tr>";

                // while($row = mysqli_fetch_assoc($result)) {
                //     echo "<tr>";
                //     foreach($row as $key=>$field) {
                //         echo "<td class='$num' value='$key'>$field</td>";
                //     }
                //     echo "</tr>";
                    
                // }

                while($row = mysqli_fetch_array($result,MYSQLI_NUM)) {
                    echo "<tr>";
                    foreach($row as $field) {
                        echo "<td class='$num' value=''>$field</td>";
                    }
                    echo "</tr>";
                    
                }

                echo "</table>";
            }

        }
    }

    function compareTable($tb1,$tb2) {
        // $sql = "select $tb1.* from $tb1 left join $tb2 on ifnull($tb1.name3,'1') = ifnull($tb2.name3,'1') where $tb2.id is null;";
        $sql = "select * from $tb1 tb1 where not exists (select 1 from $tb2 tb2 where tb1.name3 = tb2.name3);";
        // $sql = "select count(*) from $tb1 left join $tb2 on $tb1.name3 = $tb2.name3;";
        // $sql = "select count(*) from $tb1;";
        // $sql = "select * from $tb1 join $tb2 on ifnull($tb1.name3,'1') = ifnull($tb2.name3,'1');";
        // $sql = "select * from $tb1 where name3 = 'secondchange';";
        return $sql;
    }