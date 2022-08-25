<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $wData;
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap-5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/styles.css?v=<?php echo time(); ?>">
    <link rel="icon" href="../images/logo/<?php echo $_SESSION['logo']; ?>">
    <link rel="stylesheet" href="../styles/jquery.dataTables.css">
    <title>Inventory</title>

    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src="../js/chart.min.js"></script>
    <script src="../js/jquery.dataTables.js"></script>

</head>
<body onload="navF()">

    <?php
        require_once('./nav.php');

        if(!isset($_SESSION['UpdateStockSuccess'])){
        }else{
            if ($_SESSION['UpdateStockSuccess'] == true){
                ?>
                    <script>
                        swal({
                            icon: "success",
                            title: "Stock Successfully Updated!",
                        }).then((value) => {
                            $('#itemSearch').focus();
                        });
                    </script>
                <?php
                $_SESSION['UpdateStockSuccess'] = false;
            }
        }

        if(!isset($_SESSION['addItemSuccess'])){
        }else{
            if ($_SESSION['addItemSuccess'] == true){
                ?>
                    <script>
                        swal({
                            icon: "success",
                            title: "Item Added Successfully!",
                        }).then((value) => {
                            $('#itemSearch').focus();
                        });
                    </script>
                <?php
                $_SESSION['addItemSuccess'] = false;
            }
        }

        if(!isset($_SESSION['editItemSuccess'])){
        }else{
            if ($_SESSION['editItemSuccess'] == true){
                ?>
                    <script>
                        swal({
                            icon: "success",
                            title: "Item Updated Successfully!",
                        }).then((value) => {
                            $('#itemSearch').focus();
                        });
                    </script>
                <?php
                $_SESSION['editItemSuccess'] = false;
            }
        }

        if(!isset($_SESSION['RemoveSuccess'])){
        }else{
            if ($_SESSION['RemoveSuccess'] == true){
                ?>
                    <script>
                        swal({
                            icon: "success",
                            title: "Item Deleted Successfully!",
                        }).then((value) => {
                            $('#itemSearch').focus();
                        });
                    </script>
                <?php
                $_SESSION['RemoveSuccess'] = false;
            }
        }

        if(!isset($_SESSION['importSuccess'])){
        }else{
            if ($_SESSION['importSuccess'] == true){
                ?>
                    <script>
                        swal({
                            icon: "success",
                            title: "Import Has Been Successfully Finished!",
                        }).then((value) => {
                            $('#itemSearch').focus();
                        });
                    </script>
                <?php
                $_SESSION['importSuccess'] = false;
            }
        }
    ?>

    <div id="admin-inventory-con">
        <div class="top-con">
            <div class="title-con">
                INVENTORY
                <div class="menu-con">
                    <div class="option-con">
                        <button class="btnImport">
                            <div class="svg-con">
                                <img src="../images/other/csv-import.png" alt="">
                            </div>
                            <span>IMPORT</span>
                        </button>
                        <button class="btnExport">
                            <div class="svg-con">
                                <img src="../images/other/csv-export.png" alt="">
                            </div>
                            <span>EXPORT</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="control-con">
                <!-- <div class="left-con">
                    <input type="text" placeholder="Search..." id="itemSearch" class="form-control" autofocus>
                    <span>Category: </span>
                    <select id="itemFilter" class="form-select">
                        <option value="All" selected>All</option> -->
                        <?php
                            // Query and Display all categories
                            // $queryCat = "SELECT * FROM `category`";
                            // $resultCat = mysqli_query($con, $queryCat);
                            // if(mysqli_num_rows($resultCat) > 0){
                            //     while($rowCat = mysqli_fetch_assoc($resultCat)){
                                    ?>
                                        <!-- <option value="<?php //echo $rowCat['cat_name']; ?>"><?php //echo ucwords($rowCat['cat_name']); ?></option> -->
                                    <?php
                            //     }
                            // }
                        ?>
                    <!-- </select>
                </div> -->
                <div class="right-con">
                    <input type="button" class="btn btn-primary" id="addBtn" value="+ ADD">
                </div>
            </div>
        </div>
        <div class="content-con">
            <div class="table-con">
                <div class="loading">SYNCING DATA...</div>
                <table id="itemTable" class="display" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Item Code</th>
                            <th>Item Description</th>
                            <th>Retail Price(₱)</th>
                            <th>Wholesale Price(₱)</th>
                            <th>Stock</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Date Updated</th>
                            <th>Updated By</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php
                            // Query and Display all Items
                            $queryItems = "SELECT `item_code`, `itemnb_name`, `itemnb_retail_price`, `itemnb_wholesale_price`, `itemnb_stock`, `itemnb_category`, `itemnb_suppplier`, `date_updated`, `updated_by`, `itemnb_remarks`,`itemnb_img` FROM `item_no_barcode` UNION SELECT `item_code`, `item_name`, `item_retail_price`, `item_wholesale_price`, `item_stock`, `item_category`, `item_supplier`, `date_updated`, `updated_by`, `item_remarks`, `item_id` FROM `item_with_barcode` ORDER BY `itemnb_stock` ASC";
                            $resultItems = mysqli_query($con, $queryItems);
                            if(mysqli_num_rows($resultItems) > 0){
                                $wData = true;
                                while($rowItems = mysqli_fetch_assoc($resultItems)){
                                    ?>
                                        <tr>
                                            <td>
                                                <button class="inv-btn-plus" id="<?php echo $rowItems['item_code']; ?>" data-code="<?php echo $rowItems['item_code']; ?>" data-name="<?php echo $rowItems['itemnb_name']; ?>" data-stock="<?php echo $rowItems['itemnb_stock']; ?>" data-supplier="<?php echo $rowItems['itemnb_suppplier']; ?>">
                                                    <svg viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" stroke="none"><path d="M2143 5105 c-87 -19 -176 -64 -254 -128 -77 -64 -132 -143 -168 -241 l-26 -71 -5 -570 c-5 -524 -6 -573 -23 -598 -42 -64 -16 -61 -627 -68 -544 -5 -556 -5 -625 -28 -206 -67 -359 -232 -401 -430 -20 -96 -20 -718 1 -816 42 -200 197 -371 395 -437 63 -21 83 -22 624 -26 607 -4 596 -3 633 -59 16 -25 18 -75 23 -588 6 -550 6 -561 29 -630 67 -206 232 -359 430 -401 96 -20 718 -20 816 1 200 42 372 197 437 395 21 63 22 86 27 637 l6 571 25 27 c14 15 34 32 45 38 14 9 184 14 585 17 542 5 567 6 620 26 221 84 352 227 395 431 20 95 21 718 1 813 -38 178 -153 324 -313 397 -123 57 -115 56 -725 62 l-566 6 -27 25 c-15 14 -32 34 -38 45 -9 14 -14 184 -17 585 -5 542 -6 567 -26 620 -84 221 -227 352 -431 395 -94 20 -728 20 -820 0z"/></g></svg>
                                                </button>
                                                <button class="inv-btn-edit" id="<?php echo $rowItems['item_code']; ?>" data-code="<?php echo $rowItems['item_code']; ?>" data-name="<?php echo $rowItems['itemnb_name']; ?>" data-rprice="<?php echo $rowItems['itemnb_retail_price']; ?>" data-wprice="<?php echo $rowItems['itemnb_wholesale_price']; ?>" data-stock="<?php echo $rowItems['itemnb_stock']; ?>" data-category="<?php echo $rowItems['itemnb_category']; ?>" data-supplier="<?php echo $rowItems['itemnb_suppplier']; ?>" data-img="<?php echo $rowItems['itemnb_img']; ?>">
                                                    <svg viewBox="0 0 512 512"><path d="M421.7 220.3L188.5 453.4L154.6 419.5L158.1 416H112C103.2 416 96 408.8 96 400V353.9L92.51 357.4C87.78 362.2 84.31 368 82.42 374.4L59.44 452.6L137.6 429.6C143.1 427.7 149.8 424.2 154.6 419.5L188.5 453.4C178.1 463.8 165.2 471.5 151.1 475.6L30.77 511C22.35 513.5 13.24 511.2 7.03 504.1C.8198 498.8-1.502 489.7 .976 481.2L36.37 360.9C40.53 346.8 48.16 333.9 58.57 323.5L291.7 90.34L421.7 220.3zM492.7 58.75C517.7 83.74 517.7 124.3 492.7 149.3L444.3 197.7L314.3 67.72L362.7 19.32C387.7-5.678 428.3-5.678 453.3 19.32L492.7 58.75z"/></svg>
                                                </button>
                                                <button class="inv-btn-delete" id="<?php echo $rowItems['item_code']; ?>" data-code="<?php echo $rowItems['item_code']; ?>" data-name="<?php echo $rowItems['itemnb_name']; ?>">
                                                    <svg viewBox="0 0 448 512"><path d="M135.2 17.69C140.6 6.848 151.7 0 163.8 0H284.2C296.3 0 307.4 6.848 312.8 17.69L320 32H416C433.7 32 448 46.33 448 64C448 81.67 433.7 96 416 96H32C14.33 96 0 81.67 0 64C0 46.33 14.33 32 32 32H128L135.2 17.69zM31.1 128H416V448C416 483.3 387.3 512 352 512H95.1C60.65 512 31.1 483.3 31.1 448V128zM111.1 208V432C111.1 440.8 119.2 448 127.1 448C136.8 448 143.1 440.8 143.1 432V208C143.1 199.2 136.8 192 127.1 192C119.2 192 111.1 199.2 111.1 208zM207.1 208V432C207.1 440.8 215.2 448 223.1 448C232.8 448 240 440.8 240 432V208C240 199.2 232.8 192 223.1 192C215.2 192 207.1 199.2 207.1 208zM304 208V432C304 440.8 311.2 448 320 448C328.8 448 336 440.8 336 432V208C336 199.2 328.8 192 320 192C311.2 192 304 199.2 304 208z"/></svg>
                                                </button>
                                            </td>
                                            <td><?php echo $rowItems['item_code']; ?></td>
                                            <td><?php echo $rowItems['itemnb_name']; ?></td>
                                            <td><?php echo number_format($rowItems['itemnb_retail_price'],2); ?></td>
                                            <td><?php echo number_format($rowItems['itemnb_wholesale_price'],2); ?></td>
                                            <td><?php echo $rowItems['itemnb_stock']; ?></td>
                                            <td><?php echo $rowItems['itemnb_category']; ?></td>
                                            <td><?php echo ucwords($rowItems['itemnb_suppplier']); ?></td>
                                            <td><?php echo $rowItems['date_updated']; ?></td>
                                            <td><?php echo ucwords($rowItems['updated_by']); ?></td>
                                            <td><?php echo $rowItems['itemnb_remarks']; ?></td>
                                        </tr>
                                    <?php
                                }
                            }else{
                                $wData = false;
                                ?> <tr><td colspan="11"><h1>NO RECORD</h1></td></tr> <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL FOR ADDING STOCK -->
    <div class="modal-update-stock visually-hidden">
        <div class="modal-inner-con">
            <div class="modal-title-con">
                <h1>ADD STOCK</h1>
            </div>
            <div class="modal-content-con">
                <div class="modal-details-con">
                    <div class="row">
                        <div class="row-col col-4">
                            <h1>ITEM NAME:</h1>
                        </div>
                        <div class="row-col col-8">
                            <h1 class="modal-item-name"></h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="row-col col-4">
                            <h1>CURRENT STOCK:</h1>
                        </div>
                        <div class="row-col col-8">
                            <h1 class="modal-current-stock"></h1>
                        </div>
                    </div>
                </div>
                <div class="form-con">
                    <form action="inventory-update.php" method="POST">
                        <input type="hidden" id="addStockItemCode" name="addStockItemCode" value="">
                        <input type="hidden" id="addStockItemName" name="addStockItemName" value="">
                        <input type="hidden" id="addStockItemSup" name="addStockItemSup" value="">
                        <div class="row">
                            <div class="row-col col-4">
                                <h1>STOCK:</h1>
                            </div>
                            <div class="row-col col-8">
                                <input type="number" id="inputAddStock" name="stockValue" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="row-col col-4">
                                <h1>DR NUMBER:</h1>
                            </div>
                            <div class="row-col col-8">
                                <input type="text" id="inputDRNum" name="drNum" class="form-control" value="<?php if(isset($_SESSION['drNum'])){ echo $_SESSION['drNum']; } ?>" required autocomplete="off">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="btn-con">
                                    <input type="submit" class="btn btn-primary" value="UPDATE STOCK">
                                </div>
                            </div>
                            <div class="col-6">
                                <input type="button" class="btnCancel btn btn-secondary" value="CANCEL">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL FOR ADD AND EDIT ITEMS -->
    <div class="modal-edit-item visually-hidden">
        <div class="modal-inner-con">
            <div class="modal-title-con">
                <h1 id="titleAE"></h1>
            </div>
            <form class="form-con" id="formAE" method="POST" action="inventory-add.php" enctype="multipart/form-data">
                <div class="radio-con row">
                    <div class="radioInput col-6">
                        <input class="form-check-input" type="radio" name="barcode" value="with-barcode" id="wB" checked>
                        <label class="form-check-label" for="wB">With Barcode</label>
                    </div>
                    <div class="radioInput col-6">
                        <input class="form-check-input" type="radio" name="barcode" value="without-barcode" id="woB">
                        <label class="form-check-label" for="woB">Without Barcode</label>
                    </div>
                </div>
                <div class="rowItemCode row">
                    <div class="col-5">
                        <h1>ITEM CODE:</h1>
                    </div>
                    <div class="col-7">
                        <input type="hidden" id="aeItemIdwb" name="aeItemIdwb" val="">
                        <input type="number" id="aeItemCode" name="aeItemCode" class="form-control" required autocomplete="off">
                    </div>
                </div>
                <div class="rowImage row visually-hidden">
                    <div class="col-5">
                        <h1>ITEM IMAGE:</h1>
                    </div>
                    <div class="col-7">
                        <input type="hidden" id="aeItemIdwob" name="aeItemIdwob" val="">
                        <input type="file" id="aeItemImage" name="aeItemImage" class="form-control" accept="image/*" tabindex="-1">
                    </div>
                </div>
                <div class="row">
                    <div class="col-5">
                        <h1>ITEM DESCRIPTION:</h1>
                    </div>
                    <div class="col-7">
                        <input type="text" id="aeItemDesc" name="aeItemDesc" class="form-control" required autocomplete="off">
                    </div>
                </div>
                <div class="row">
                    <div class="col-5">
                        <h1>RETAIL PRICE(₱):</h1>
                    </div>
                    <div class="col-7">
                        <input type="number" step="0.01" id="aeItemRP" name="aeItemRP" class="form-control" required autocomplete="off">
                    </div>
                </div>
                <div class="row">
                    <div class="col-5">
                        <h1>WHOLESALE PRICE(₱):</h1>
                    </div>
                    <div class="col-7">
                        <input type="number" step="0.01" id="aeItemWP" name="aeItemWP" class="form-control" required autocomplete="off">
                    </div>
                </div>
                <!-- <div class="rowStock row">
                    <div class="col-5">
                        <h1>STOCK:</h1>
                    </div>
                    <div class="col-7">
                        <input type="number" step="1" id="aeItemStock" name="aeItemStock" class="form-control" required autocomplete="off">
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-5">
                        <h1>CATEGORY:</h1>
                    </div>
                    <div class="col-7">
                        <select class="form-select" id="aeItemCat" name="aeItemCat" required>
                            <option hidden> - - - Select Category - - - </option>
                            <?php
                                // Query and Display all categories
                                $queryCat = "SELECT * FROM `category`";
                                $resultCat = mysqli_query($con, $queryCat);
                                if(mysqli_num_rows($resultCat) > 0){
                                    while($rowCat = mysqli_fetch_assoc($resultCat)){
                                        ?>
                                            <option value="<?php echo $rowCat['cat_name']; ?>"><?php echo ucwords($rowCat['cat_name']); ?></option>
                                        <?php
                                    }
                                }
                            ?>
                        </select>
                        <!-- <input type="text" id="aeItemCat" name="aeItemCat" class="form-control" required autocomplete="off"> -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-5">
                        <h1>SUPPLIER:</h1>
                    </div>
                    <div class="col-7">
                        <select class="form-select" id="aeItemSup" name="aeItemSup" required>
                            <option hidden> - - - Select Supplier - - - </option>
                            <?php
                                // Query and Display all categories
                                $querySup = "SELECT * FROM `supplier`";
                                $resultsup = mysqli_query($con, $querySup);
                                if(mysqli_num_rows($resultsup) > 0){
                                    while($rowSup = mysqli_fetch_assoc($resultsup)){
                                        ?>
                                            <option value="<?php echo $rowSup['sup_name']; ?>"><?php echo ucwords($rowSup['sup_name']); ?></option>
                                        <?php
                                    }
                                }
                            ?>
                        </select>




                        <!-- <input type="text" id="aeItemSup" name="aeItemSup" class="form-control" required autocomplete="off"> -->
                    </div>
                </div>
                <div class="rowRemarks row">
                    <div class="col-5">
                        <h1>REMARKS:</h1>
                    </div>
                    <div class="col-7">
                        <input type="text" id="aeItemRemark" name="aeItemRemark" class="form-control" required autocomplete="off">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="btn-con">
                            <input type="submit" name="submit" class="btnSave btn btn-primary" value="SAVE">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="btn-con">
                            <input type="button" class="btnCancel btn btn-secondary" value="CANCEL">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL FOR DELETING ITEM -->
    <div class="modal-delete-item visually-hidden">
        <div class="modal-inner-con">
            <div class="modal-title-con">
                <h1 style="color: #dc3545">DELETE ITEM?</h1>
                <p>Are you sure you want to delete this item?</p>
            </div>
            <div class="content-con">
                <div class="row">
                    <div class="col-4">
                        <h1>Item Name:</h1>
                    </div>
                    <div class="col-8">
                        <h1 class="modal-item-name"></h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="btn-con">
                            <input type="button" class="btnDelete btn btn-danger" value="DELETE">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="btn-con">
                            <input type="button" class="btnCancel btn btn-secondary" value="CANCEL">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL FOR IMPORTING ITEMS -->
    <div class="modal-import visually-hidden">
        <div class="modal-inner-con">
                <div class="modal-title-con">
                    <h1>IMPORT</h1>
                </div>
                <form class="content-con" method="POST" action="./inventory-import.php" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-5">
                            <h1>Browse your computer:</h1>
                        </div>
                        <div class="col-7">
                            <input type="file" id="importFile" name="importFile" class="form-control"  accept="Text/csv" tabindex="-1" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="btn-con">
                                <input type="submit" class="btnImport btn btn-primary" value="IMPORT">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="btn-con">
                                <input type="button" class="btnCancel btn btn-secondary" value="CANCEL">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>

        function navF(){
            $('.inventory').addClass('active');
            $('.inventory').addClass('disabled');
        }

        $(document).ready(function(){

            $('.loading').addClass('visually-hidden');

            let height = (screen.height - 460);
            const wData = <?php echo json_encode($wData); ?>;
            console.log(wData);

            if(wData == true){
                $('#itemTable').DataTable({
                    scrollY: height+'px',
                    scrollX: true,
                    "pageLength": 50,
                    // scrollCollapse: true,
                });
            }

            // Filter Table Row on Keyup
            $("#itemSearch").on("keyup", function() {
                $("#itemFilter").val('All');
                var value = $(this).val().toLowerCase();
                $("#tableBody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            // Filter Table Row on Change
            $("#itemFilter").on("change", function() {
                $("#itemSearch").val('');
                if($("#itemFilter").val() == 'All'){
                    var value = "";
                    $("#tableBody tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                }else{
                    var value = $(this).val().toLowerCase();
                    $("#tableBody tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                }
            });
            
            //Keypress shortcut
            $('#admin-inventory-con').on('keydown', function(e){
                var upKey = e.which || e.keyCode;
                console.log(upKey);
                
                if(upKey == 107){
                    var rowCount = $('#itemTable tbody tr:visible').length;
                    if(rowCount == 1){
                        $("#itemTable").find("tbody tr:visible td button:first").click();
                    }
                    e.preventDefault();
                }
            });
            
            $(document).on('keydown', function(de){
                var upKey = de.which || de.keyCode;
                if(upKey == 33){
                    $('#addBtn').click();
                }else if(upKey == 27){
                    $('.btnCancel').click();
                }
            });

            // Update Stock (PLUS BUTTON)
            jQuery(document).on( "click", ".inv-btn-plus", function(){
                var addItemName = $(this).data('name');
                var addCurStock = $(this).data('stock');
                var addItemCode = $(this).data('code');
                var addItemSup = $(this).data('supplier');
                $('.modal-update-stock').removeClass('visually-hidden');
                $('#inputAddStock').val("");
                $('#itemSearch').val("");
                $('.modal-item-name').html(addItemName);
                $('.modal-current-stock').html(addCurStock);
                $('#addStockItemCode').val(addItemCode);
                $('#addStockItemName').val(addItemName);
                $('#addStockItemSup').val(addItemSup);
                $('#inputAddStock').focus();
            });

            // Add Button
            $('#addBtn').click(function(){
                $('.modal-edit-item').removeClass('visually-hidden');
                $('.rowRemarks').addClass('visually-hidden');
                $('.radio-con').removeClass('visually-hidden');
                $('.rowItemCode').removeClass('visually-hidden');
                $('.rowImage').addClass('visually-hidden');
                $('#aeItemCode').attr('required', true);
                $('#aeItemRemark').attr('required', false);
                $('#aeItemCode').focus();
                $('#titleAE').html("ADD");
            });

            // Cancel Button
            $('.btnCancel').click(function(){
                $('#formAE')[0].reset();
                $('.modal-update-stock').addClass('visually-hidden');
                $('.modal-edit-item').addClass('visually-hidden');
                $('.modal-delete-item').addClass('visually-hidden');
                $('.modal-import').addClass('visually-hidden');
                $('#itemSearch').focus();
            });

            // Esc Keypress to close Modal
            $('.modal-update-stock').on('keydown', function(em){
                if(em.keyCode == 27){
                    $('.btnCancel').click();
                }
            });

            // With or Without Barcode Radio Button
            $("input[name='barcode']").on('change',function(){
                if ($("#wB").prop("checked")) {
                    $('.rowItemCode').removeClass('visually-hidden');
                    $('.rowImage').addClass('visually-hidden');
                    $('#aeItemCode').attr('required', true);
                }

                if ($("#woB").prop("checked")) {
                    $('#aeItemCode').attr('required', false);
                    $('.rowItemCode').addClass('visually-hidden');
                    $('.rowImage').removeClass('visually-hidden');
                }
            });

            // Save Button for ADD
            $('.btnSave').click(function(es){
                if($('#titleAE').html() == 'ADD'){
                    if($("#wB").prop("checked")) {
                        $('#itemTable tbody tr').find("td:nth-child(2)").each(function(){
                            var exItemCode =  $(this).html();
                            if ($('#aeItemCode').val() == exItemCode){
                                swal({
                                    icon: "error",
                                    title: "Item Code Already Exist!",
                                }).then((value) => {
                                    $('#aeItemCode').focus();
                                });
                                es.preventDefault();
                                return false;
                            }
                        });
                    }
                }else if($('#titleAE').html() == 'EDIT'){
                    if(barEdit == 'wB'){
                        if($('#aeItemCode').val() != editItemCode){
                            $('#itemTable tbody tr').find("td:nth-child(2)").each(function(){
                                var exItemCode =  $(this).html();
                                if($('#aeItemCode').val() == exItemCode){
                                    swal({
                                        icon: "error",
                                        title: "Item Code Already Exist!",
                                    }).then((value) => {
                                        $('#aeItemCode').focus();
                                    });
                                    es.preventDefault();
                                    return false;
                                }
                            });
                        }
                    }
                }

            });

            // Edit Button
            var barEdit, editItemCode;
            jQuery(document).on( "click", ".inv-btn-edit", function(){
                var editItemImg = $(this).data('img');
                editItemCode = $(this).data('code');
                var editItemName = $(this).data('name');
                var editItemRP = $(this).data('rprice');
                var editItemWP = $(this).data('wprice');
                var editItemStock = $(this).data('stock');
                var editItemCat = $(this).data('category');
                var editItemSup = $(this).data('supplier');

                $('#formAE').attr('action', './inventory-edit.php');

                $('.modal-edit-item').removeClass('visually-hidden');
                $('#titleAE').html("EDIT");
                $('.rowRemarks').removeClass('visually-hidden');
                // $('.rowStock').removeClass('visually-hidden');
                // $('#aeItemStock').attr('required', true);
                // $('#aeItemStock').attr('tabindex', true);

                if($.isNumeric(editItemImg)){
                    barEdit = 'wB';
                    $("#wB").prop("checked", true);
                    $('.rowItemCode').removeClass('visually-hidden');
                    $('.rowImage').addClass('visually-hidden');
                    $('#aeItemCode').attr('required', true);
                    $('#aeItemCode').val(editItemCode);
                    $('#aeItemCode').focus();
                }else{
                    barEdit = 'woB';
                    $("#woB").prop("checked", true);
                    $('#aeItemCode').attr('required', false);
                    $('.rowItemCode').addClass('visually-hidden');
                    $('.rowImage').removeClass('visually-hidden');
                    $('#aeItemDesc').focus();
                }
                $('.radio-con').addClass('visually-hidden');
                $('#aeItemIdwb').val(editItemImg);
                $('#aeItemIdwob').val(editItemCode);
                $('#aeItemDesc').val(editItemName);
                $('#aeItemRP').val(editItemRP);
                $('#aeItemWP').val(editItemWP);
                $('#aeItemStock').val(editItemStock);
                $('#aeItemCat').val(editItemCat.toLowerCase()).change();
                $('#aeItemSup').val(editItemSup);
            });

            // Delete Button
            var delId;
            jQuery(document).on( "click", ".inv-btn-delete", function(){
                $('.modal-delete-item').removeClass('visually-hidden');
                $('.modal-item-name').html($(this).data('name'));
                delId = $(this).data('code');
                console.log(delId);
            });
            // Delete Confirm Button
            $('.btnDelete').click(function(){
                window.location.href = "./inventory-remove.php?delId="+delId;
            });

            $('.btnImport').click(function(){
                $('.modal-import').removeClass('visually-hidden');
            });

            $('.btnExport').click(function(){
                myWindow = window.open("./inventory-export.php");
                // setTimeout(close, 50);
            });

            // function close(){
            //     // myWindow.close();
            // }
        });

    </script>
    
</body>
</html>