<?php
require_once("dbconfig.php");

// ตรวจสอบว่ามีการ post มาจากฟอร์ม ถึงจะลบ
if ($_POST){
    // ดึงค่าที่โพสจากฟอร์มตาม name ที่กำหนดในฟอร์มมากำหนดให้ตัวแปร $id
    $id = $_POST['id'];


    
    // เตรียมคำสั่ง DELETE
    $sql = "DELETE 
            FROM staff 
            WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // redirect ไปยังหน้า actor.php
    header("location: Personnel.php");
} else {
    // ดึงค่าที่ส่งผ่านมาทาง query string มากำหนดให้ตัวแปร $id
    $id = $_GET['id'];
    $sql = "SELECT *
            FROM staff
            WHERE id = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_object();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Delete Personnel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
    <h1>Delete Personnel</h1>
    <form action="DeletePersonnel.php" method="post">
        
    <div class="form-group">
                <label for="fname">Employee ID</label>
                <input type="text" class="form-control" name="stf_code" id="stf_code" value="<?php echo $row->stf_code;?>">
            </div> 


            <div class="form-group">
                <label for="lname">Name - Surname</label>
                <input type="text" class="form-control" name="stf_name" id="stf_name" value="<?php echo $row->stf_name;?>">
            </div>
            

           


        
        <form action="Deletedocuments.php" method="post">
            <input type="hidden" name="id" value="<?php echo $row->id;?>">
            <input type="submit" value="Confirm delete" class="btn btn-danger">
            <button type="button" class="btn btn-warning" onClick="window.history.back()">Cancel Delete</button>
        </form>
</body>

</html>