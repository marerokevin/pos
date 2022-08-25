<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $allItemWB = "SELECT `item_code`, `itemnb_name`, `itemnb_retail_price`, `itemnb_wholesale_price`, `itemnb_stock`, `itemnb_category`, `itemnb_suppplier`, `date_updated`, `updated_by`, `itemnb_remarks`,`itemnb_img` FROM `item_no_barcode` UNION SELECT `item_code`, `item_name`, `item_retail_price`, `item_wholesale_price`, `item_stock`, `item_category`, `item_supplier`, `date_updated`, `updated_by`, `item_remarks`, `item_id` FROM `item_with_barcode`";
    $resultItemWB = mysqli_query($con, $allItemWB);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/table2csv.min.js"></script>
</head>
<body>
    <div style="width: 100vw; height: 100vh; overflow: hidden;">
        <h3>Please Wait...</h3><br>
        <h3>Exporting Inventory...</h3>
    </div>

    <table id="invTable">
        <thead>
            <tr>
                <th>item_code</th>
                <th>item_name</th>
                <th>item_retail_price</th>
                <th>item_stock</th>
                <th>item_category</th>
                <th>item_supplier</th>
                <th>item_img</th>
                <th>item_wholesale_price</th>
            </tr>
        </thead>
        <tbody>
            <?php
                while($rowItems = mysqli_fetch_assoc($resultItemWB)){
                    ?>
                        <tr>
                            <td><?php echo $rowItems['item_code']; ?></td>
                            <td><?php echo $rowItems['itemnb_name']; ?></td>
                            <td><?php echo $rowItems['itemnb_retail_price']; ?></td>
                            <td><?php echo $rowItems['itemnb_stock']; ?></td>
                            <td><?php echo $rowItems['itemnb_category']; ?></td>
                            <td><?php echo $rowItems['itemnb_suppplier']; ?></td>
                            <td><?php echo $rowItems['itemnb_img']; ?></td>
                            <td><?php echo $rowItems['itemnb_wholesale_price']; ?></td>
                        </tr>
                    <?php
                }
            ?>
        </tbody>
    </table>

    <script>
        var option = {
            "filename":"inventory.csv"
        }

        function invExp(){
            console.log('text');
            $("#invTable").first().table2csv(option);
        }

        $(document).ready(function(){
            invExp();
        });
    </script>
</body>
</html>