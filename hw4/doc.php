<!DOCTYPE html>
<html lang="en">

<head>
    <title>php db demo</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
        <h1>คำสั่งแต่งตั้ง | <a href='newdoc.php'><span class='glyphicon glyphicon-plus'></span></a></h1>
        <form action="#" method="post">
            <input type="text" name="kw" placeholder="เลขที่คำสั่งหรือชื่อคำสั่ง" value="">
            <input type="submit">
        </form>

        <?php
        require_once("dbconfig.php");

        // ตัวแปร $_POST เป็นตัวแปรอะเรย์ของ php ที่มีค่าของข้อมูลที่โพสมาจากฟอร์ม
        // ดึงค่าที่โพสจากฟอร์มตาม name ที่กำหนดในฟอร์มมากำหนดให้ตัวแปร $kw
        // ใส่ % เพือเตรียมใช้กับ LIKE
        @$kw = "%{$_POST['kw']}%";

        // เตรียมคำสั่ง SELECT ที่ถูกต้อง(ทดสอบให้แน่ใจ)
        // ถ้าต้องการแทนที่ค่าของตัวแปร ให้แทนที่ตัวแปรด้วยเครื่องหมาย ? 
        // concat() เป็นฟังก์ชั่นสำหรับต่อข้อความ
        $sql = "SELECT * 
                FROM documents 
                WHERE concat(doc_num , doc_title) Like ?
                ORDER BY doc_num";
        // Prepare query
        // Bind all variables to the prepared statement
        // Execute the statement
        // Get the mysqli result variable from the statement
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $kw );
        $stmt->execute();
        
        // Retrieves a result set from a prepared statement
        $result = $stmt->get_result();
        
        // num_rows เป็นจำนวนแถวที่ได้กลับคืนมา
        if ($result->num_rows == 0) {
            echo "Not found!";
        } else {
            echo "Found " . $result->num_rows . " record(s).";
            // สร้างตัวแปรเพื่อเก็บข้อความ html 
            $table = "<table class='table table-hover'>
                        <thead>                        
                            <th scope='col'>No</th>
                            <th scope='col'>Order Number</th>
                            <th scope='col'>Name</th>
                            <th scope='col'>Start Date</th>
                            <th scope='col'>End Date</th>
                            <th scope='col'>Status</th>
                            <th scope='col'>Document File Name</th>
                            <th scope='col'>Manage Appointment</th>
                            <th scope='col'>Manage Personnel</th>
                            </tr>
                        </thead>
                        <tbody>";
                        
            // 
            $i = 1; 

            // ดึงข้อมูลออกมาทีละแถว และกำหนดให้ตัวแปร row 
            while($row = $result->fetch_object()){ 
                $table.= "<tr>";
                $table.= "<td>" . $i++ . "</td>";
                $table.= "<td>$row->doc_num</td>";
                $table.= "<td>$row->doc_title</td>";
                $table.= "<td>$row->doc_start_date</td>";
                $table.= "<td>$row->doc_to_date</td>";
                $table.= "<td>$row->doc_status</td>";
                $table.= "<td>$row->doc_file_name</td>";
           

                $table.= "<td>";
                $table.= "<a href='editdoc.php?id=$row->id'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a>";
                $table.= " | ";
                $table.= "<a href='deletedoc.php?id=$row->id'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></a>";
                $table.= "</td>";
                
                

                $table.= "<td>";
                $table.= "<a href='personnel.php?id=$row->id'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a>";
              
                $table.= "</td>";
                $table.= "</tr>";
            }

            $table.= "</tbody>";
            $table.= "</table>";
            
            echo $table;
             
        }
        ?>
    </div>
</body>

</html>