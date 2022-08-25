<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap-5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/styles.css?v=<?php echo time(); ?>">
    <link rel="icon" href="../images/logo/shops.png">
    <title>Inventory</title>

    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sweetalert.min.js"></script>
</head>
<body>
    <?php
        if(!isset($_SESSION['updateSuccessful'])){
        }else{
            if ($_SESSION['updateSuccessful'] == true){
                ?>
                    <script>
                        swal({
                            icon: "success",
                            title: "Price Updated Successfully",
                        }).then((value) => {
                            $('#inputItemCode').focus();
                        });
                    </script>
                <?php
                $_SESSION['updateSuccessful'] = false;
            }
        }
    ?>
    <div class="inv-con">
        <div class="top-con">
            <div class="top-left-con">
                <a href="./home.php" class="btn">
                    <svg viewBox="0 0 448 512">
                        <path d="M447.1 256C447.1 273.7 433.7 288 416 288H109.3l105.4 105.4c12.5 12.5 12.5 32.75 0 45.25C208.4 444.9 200.2 448 192 448s-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25l160-160c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25L109.3 224H416C433.7 224 447.1 238.3 447.1 256z"/>
                    </svg>
                    <span>BACK</span>
                </a>
            </div>

            <div class="top-right-con">
                <div class="search-con">
                    <input type="text" class="form-control" id="searchItem" placeholder="Search...">
                </div>
            </div>
        </div>
        <div class="content-con">
            <div class="tbl-con">
                <table>
                    <thead>
                        <tr>
                            <th>Item Code</th>
                            <th>Description</th>
                            <th>Retail Price (₱)</th>
                            <th style="width: 250px;">Wholesale Price (₱)</th>
                            <th>Stock</th>
                            <th>Category</th>
                        </tr>
                    </thead>
                    <tbody id="itemTable">
                        <?php
                            $queryItems = "SELECT `item_code`, `itemnb_name`, `itemnb_retail_price`, `itemnb_wholesale_price`, `itemnb_stock`, `itemnb_category` FROM `item_no_barcode` UNION SELECT `item_code`, `item_name`, `item_retail_price`, `item_wholesale_price`, `item_stock`, `item_category` FROM `item_with_barcode` ORDER BY itemnb_name ASC";
                            $resultItems = mysqli_query($con, $queryItems);
                            if(mysqli_num_rows($resultItems) > 0){
                                while($rowItems = mysqli_fetch_assoc($resultItems)){
                                    ?>
                                        <tr>
                                            <td><?php echo $rowItems['item_code']; ?></td>
                                            <td><?php echo $rowItems['itemnb_name']; ?></td>
                                            <td><?php echo number_format($rowItems['itemnb_retail_price'],2); ?><button class="btn-retail-edit" data-item-code="<?php echo $rowItems['item_code']; ?>"><svg height="20px" viewBox="0 0 512 512"><path d="M421.7 220.3L188.5 453.4L154.6 419.5L158.1 416H112C103.2 416 96 408.8 96 400V353.9L92.51 357.4C87.78 362.2 84.31 368 82.42 374.4L59.44 452.6L137.6 429.6C143.1 427.7 149.8 424.2 154.6 419.5L188.5 453.4C178.1 463.8 165.2 471.5 151.1 475.6L30.77 511C22.35 513.5 13.24 511.2 7.03 504.1C.8198 498.8-1.502 489.7 .976 481.2L36.37 360.9C40.53 346.8 48.16 333.9 58.57 323.5L291.7 90.34L421.7 220.3zM492.7 58.75C517.7 83.74 517.7 124.3 492.7 149.3L444.3 197.7L314.3 67.72L362.7 19.32C387.7-5.678 428.3-5.678 453.3 19.32L492.7 58.75z"/></svg></button></td>
                                            <td style="width: 250px;"><?php echo number_format($rowItems['itemnb_wholesale_price'],2); ?><button class="btn-wholesale-edit" data-item-code="<?php echo $rowItems['item_code']; ?>"><svg height="20px" viewBox="0 0 512 512"><path d="M421.7 220.3L188.5 453.4L154.6 419.5L158.1 416H112C103.2 416 96 408.8 96 400V353.9L92.51 357.4C87.78 362.2 84.31 368 82.42 374.4L59.44 452.6L137.6 429.6C143.1 427.7 149.8 424.2 154.6 419.5L188.5 453.4C178.1 463.8 165.2 471.5 151.1 475.6L30.77 511C22.35 513.5 13.24 511.2 7.03 504.1C.8198 498.8-1.502 489.7 .976 481.2L36.37 360.9C40.53 346.8 48.16 333.9 58.57 323.5L291.7 90.34L421.7 220.3zM492.7 58.75C517.7 83.74 517.7 124.3 492.7 149.3L444.3 197.7L314.3 67.72L362.7 19.32C387.7-5.678 428.3-5.678 453.3 19.32L492.7 58.75z"/></svg></button></td>
                                            <td><?php echo $rowItems['itemnb_stock']; ?></td>
                                            <td><?php echo $rowItems['itemnb_category']; ?></td>
                                        </tr>
                                    <?php
                                }
                            }else{
                                ?>
                                    <tr>
                                        <td colspan="5">NO RECORD</td>
                                    </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $("#searchItem").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#itemTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            var itemCode;
            $('.btn-retail-edit').click(function(){
                itemCode = $(this).data('item-code');
                
                swal({
                    title: "Update Retail Price",
                    closeOnClickOutside: false,
                    content: {
                        element: "input",
                        attributes: {
                            id: "updateRetailPrice",
                            type: "number",
                            min: "1",
                            max: "999999",
                        },
                    },
                    buttons:{
                        submit: {
                            text: "Submit",
                            value: 'sbmtQty',
                            visible: true,
                            className: "sbmtRetailPrice",
                            closeModal: true,
                        },
                        cancel: {
                            text: "Cancel",
                            value: null,
                            visible: true,
                            className: "cncl",
                            closeModal: true,
                        },
                    },
                });
            });

            jQuery(document).on( "click", ".sbmtRetailPrice", function(){
                if($('#updateRetailPrice').val() != ""){
                    if($('#updateRetailPrice').val() < 0){
                        swal({
                            icon: "error",
                            title: "Invalid Price",
                        })
                    }else{
                        var newPrice = $('#updateRetailPrice').val();
                        window.location.href = "./change-price.php?itemCode="+itemCode+"&newRetailPrice="+newPrice;
                    }
                }
            });

            jQuery(document).on( "keyup", "#updateRetailPrice", function(ep){
                var epKey = ep.which || ep.keyCode;
                
                if(epKey == 13){
                    if($('#updateRetailPrice').val() != ""){
                        if($('#updateRetailPrice').val() < 0){
                            swal({
                                icon: "error",
                                title: "Invalid Price",
                            })
                        }else{
                            var newPrice = $('#updateRetailPrice').val();
                            window.location.href = "./change-price.php?itemCode="+itemCode+"&newRetailPrice="+newPrice;
                        }
                    }
                }
            });










            $('.btn-wholesale-edit').click(function(){
                itemCode = $(this).data('item-code');
                
                swal({
                    title: "Update Wholesale Price",
                    closeOnClickOutside: false,
                    content: {
                        element: "input",
                        attributes: {
                            id: "updateWholesalePrice",
                            type: "number",
                            min: "1",
                            max: "999999",
                        },
                    },
                    buttons:{
                        submit: {
                            text: "Submit",
                            value: 'sbmtQty',
                            visible: true,
                            className: "sbmtWholesalePrice",
                            closeModal: true,
                        },
                        cancel: {
                            text: "Cancel",
                            value: null,
                            visible: true,
                            className: "cncl",
                            closeModal: true,
                        },
                    },
                });
            });

            jQuery(document).on( "click", ".sbmtWholesalePrice", function(){
                if($('#updateWholesalePrice').val() != ""){
                    if($('#updateWholesalePrice').val() < 0){
                        swal({
                            icon: "error",
                            title: "Invalid Price",
                        })
                    }else{
                        var newPrice = $('#updateWholesalePrice').val();
                        window.location.href = "./change-price.php?itemCode="+itemCode+"&newWholesalePrice="+newPrice;
                    }
                }
            });

            jQuery(document).on( "keyup", "#updateWholesalePrice", function(ep){
                var epKey = ep.which || ep.keyCode;
                
                if(epKey == 13){
                    if($('#updateWholesalePrice').val() != ""){
                        if($('#updateWholesalePrice').val() < 0){
                            swal({
                                icon: "error",
                                title: "Invalid Price",
                            })
                        }else{
                            var newPrice = $('#updateWholesalePrice').val();
                            window.location.href = "./change-price.php?itemCode="+itemCode+"&newWholesalePrice="+newPrice;
                        }
                    }
                }
            });
        });


    </script>
    
</body>
</html>