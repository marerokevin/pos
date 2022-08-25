<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $subTotal = 0.00;
    $grandTotal = 0.00;
    $totalItems = 0.00;
    $totalQty = 0;
    $manualDisc = 0;
    $curStock = 0;
    $basePriceTotal = 0;

    $getWSDisc = "SELECT * FROM `admin_settings` WHERE `set_id` = 1";
    $resultWSDisc = mysqli_query($con, $getWSDisc);
    $rowDisc = mysqli_fetch_assoc($resultWSDisc);

    $manualDisc = ($rowDisc['discount'] / 100);

    if(!isset($_SESSION['endBuyer'])){
        $_SESSION['endBuyer'] = "RETAIL";
    }

    $ItemWB_name = array();
    $ItemWB_code = array();
    $getItemWB = "SELECT * FROM `item_with_barcode`";
    $resultItemWB = mysqli_query($con, $getItemWB);
    if(mysqli_num_rows($resultItemWB) > 0){
        while($rowItemWB = mysqli_fetch_assoc($resultItemWB)){
            array_push($ItemWB_name, $rowItemWB['item_name']);
            array_push($ItemWB_code, $rowItemWB['item_code']);
        }
    }

    if(isset($_SESSION['searchVal'])){
        $searchVal = $_SESSION['searchVal'];
    }else{
        $searchVal = "";
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../bootstrap-5.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="../styles/ACstyles.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../styles/styles.css?v=<?php echo time(); ?>">
        <link rel="icon" href="../images/logo/<?php echo $_SESSION['logo']; ?>">
        <title>POS</title>

        <script src="../js/jquery-3.6.0.min.js"></script>
        <script src="../bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
        <script src="../js/sweetalert.min.js"></script>
        <script crossorigin src="../js/react.production.min.js"></script>
        <script crossorigin src="../js/react-dom.production.min.js"></script>
        <script src="../js/jquery.autocomplete.min.js"></script>
    </head>
    <body id="nchome-body">

        <?php
            if(!isset($_SESSION['unregItemError'])){
            }else{
                if ($_SESSION['unregItemError'] == true){
                    ?>
                        <script>
                            swal({
                                icon: "error",
                                title: "Unregistered Item!",
                            }).then((value) => {
                                $('#inputItemCode').focus();
                            });
                        </script>
                    <?php
                    $_SESSION['unregItemError'] = false;
                }
            }

            if(!isset($_SESSION['errorStock'])){
            }else{
                if ($_SESSION['errorStock'] == true){
                    ?>
                        <script>
                            swal({
                                icon: "error",
                                title: "Out of Stock!",
                            }).then((value) => {
                                $('#inputItemCode').focus();
                            });
                        </script>
                    <?php
                    $_SESSION['errorStock'] = false;
                }
            }

            if(!isset($_SESSION['deleteAllSuccess'])){
            }else{
                if ($_SESSION['deleteAllSuccess'] == true){
                    ?>
                        <script>
                            swal({
                                icon: "success",
                                title: "Transaction has been cancel.",
                            }).then((value) => {
                                $('#inputItemCode').focus();
                            });
                        </script>
                    <?php
                    $_SESSION['deleteAllSuccess'] = false;
                }
            }

            if(!isset($_SESSION['removeSuccess'])){
            }else{
                if ($_SESSION['removeSuccess'] == true){
                    ?>
                        <script>
                            swal({
                                icon: "success",
                                title: "Item has been remove.",
                            }).then((value) => {
                                $('#inputItemCode').focus();
                            });
                        </script>
                    <?php
                    $_SESSION['removeSuccess'] = false;
                }
            }

            if(!isset($_SESSION['changeSuccess'])){
            }else{
                if ($_SESSION['changeSuccess'] == true){
                    ?>
                        <script>
                            swal({
                                icon: "success",
                                title: "End buyer has been change.",
                            }).then((value) => {
                                $('#inputItemCode').focus();
                            });
                        </script>
                    <?php
                    $_SESSION['changeSuccess'] = false;
                }
            }

            // if(!isset($_SESSION['TranComSuccess'])){
            // }else{
            //     if ($_SESSION['TranComSuccess'] == true){
            //         ?>
                         <script>
            //                 swal({
            //                     icon: "success",
            //                     title: "Transaction Completed",
            //                 }).then((value) => {
            //                     $('#inputItemCode').focus();
            //                 });
            //             </script>
                     <?php
            //         $_SESSION['TranComSuccess'] = false;
            //     }
            // }

            if(!isset($_SESSION['discUpdateSuccessful'])){
            }else{
                if ($_SESSION['discUpdateSuccessful'] == true){
                    ?>
                        <script>
                            swal({
                                icon: "success",
                                title: "Discount Updated",
                            }).then((value) => {
                                $('#inputItemCode').focus();
                            });
                        </script>
                    <?php
                    $_SESSION['discUpdateSuccessful'] = false;
                }
            }
        ?>

        <div class="nchome-container">
            <div class="left-con">
                <div class="left-top-con">
                    <div class="logo-con">
                        <div class="the-logo">
                            <img src="../images/logo/<?php echo $_SESSION['logo']; ?>" alt="">
                            <h1><?php echo $_SESSION['branch_name']; ?></h1>
                        </div>
                    </div>
                    <div class="search-con">
                        <input type="text" placeholder="Search..." class="form-control" id="searchItem">
                    </div>
                </div>
                <div class="left-mid-con">
                    <div class="left-nav-con">
                        <div class="top-title"><span>Category</span></div>
                        <div class="nav-content">
                            <div class="nav flex-column nav-pills me-3 btn-nav-con" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <button class="nav-link active" id="v-pills-all-tab" data-bs-toggle="pill" data-bs-target="#all" type="button" role="tab" aria-controls="v-pills-all" aria-selected="true">All</button>
                                <?php
                                    $queryCategoryNav = "SELECT * FROM `category`";
                                    $resultCategoryNav = mysqli_query($con, $queryCategoryNav);
                                    if(mysqli_num_rows($resultCategoryNav) > 0){
                                        while($rowCatNav = mysqli_fetch_assoc($resultCategoryNav)){
                                            ?>
                                                <button class="nav-link" id="v-pills-<?php echo str_replace(" ", "-", $rowCatNav['cat_name']); ?>-tab" data-bs-toggle="pill" data-bs-target="#<?php echo str_replace(" ", "-", $rowCatNav['cat_name']);  ?>" type="button" role="tab" aria-controls="v-pills-<?php echo str_replace(" ", "-", $rowCatNav['cat_name']);  ?>" aria-selected="false"><?php echo ucfirst($rowCatNav['cat_name']);  ?></button>
                                            <?php
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="down-arrow">
                            <button class="btnArrow">
                                <svg height="30px" viewBox="0 0 384 512">
                                    <path d="M192 384c-8.188 0-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L192 306.8l137.4-137.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-160 160C208.4 380.9 200.2 384 192 384z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="content-con">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="v-pills-all-tab">
                                <div class="con-con">
                                    <div class="item-con" id="all-con">
                                        <?php
                                            $queryAllNoBarcode = "SELECT * FROM `item_no_barcode` ORDER BY `itemnb_name` ASC";
                                            $resultAllNoBarcode = mysqli_query($con, $queryAllNoBarcode);
                                            if(mysqli_num_rows($resultAllNoBarcode) > 0){
                                                while($rowAllNoBarcode = mysqli_fetch_assoc($resultAllNoBarcode)){
                                                    if($_SESSION['endBuyer'] == "WHOLESALE"){
                                                        $itemPrice = $rowAllNoBarcode['itemnb_wholesale_price'];
                                                        $itemName = "(W)".$rowAllNoBarcode['itemnb_name'];
                                                    }else{
                                                        $itemPrice = $rowAllNoBarcode['itemnb_retail_price'];
                                                        $itemName = $rowAllNoBarcode['itemnb_name'];
                                                    }
                                                    $nbCurQty = $rowAllNoBarcode['itemnb_stock'];
                                                    $nbBasePrice = $rowAllNoBarcode['itemnb_wholesale_price'];

                                                    ?>
                                                        <!-- <a class="con-item" href="./nobarcode-add.php?itemId=<?php //echo $rowAllNoBarcode['item_code']; ?>&itemName=<?php //echo $itemName; ?>&itemPrice=<?php //echo $itemPrice; ?>&curQty=<?php //echo $nbCurQty; ?>&basePrice=<?php //echo $nbBasePrice; ?>">
                                                            <img src="<?php //echo $rowAllNoBarcode['itemnb_img']; ?>" alt="">
                                                            <p><?php //echo $rowAllNoBarcode['itemnb_name']; ?></p>
                                                        </a> -->

                                                        <button class="itemInCategory con-item" data-code="<?php echo $rowAllNoBarcode['item_code']; ?>" data-name="<?php echo $itemName; ?>" data-price="<?php echo $itemPrice; ?>" data-curqty="<?php echo $nbCurQty; ?>" data-baseprice="<?php echo $nbBasePrice; ?>">
                                                            <img src="<?php echo $rowAllNoBarcode['itemnb_img']; ?>" alt="">
                                                            <p><?php echo $rowAllNoBarcode['itemnb_name']; ?></p>
                                                        </button>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                                $queryCategoryCon = "SELECT * FROM `category`";
                                $resultCategoryCon = mysqli_query($con, $queryCategoryCon);
                                if(mysqli_num_rows($resultCategoryCon) > 0){
                                    while($rowCatCon = mysqli_fetch_assoc($resultCategoryCon)){
                                        ?>
                                            <div class="tab-pane fade" id="<?php echo str_replace(" ", "-", $rowCatCon['cat_name']); ?>" role="tabpanel" aria-labelledby="v-pills-<?php echo str_replace(" ", "-", $rowCatCon['cat_name']); ?>-tab">
                                                <div class="con-con">
                                                    <div class="item-con">
                                                        <?php
                                                            $thisCategory = ucwords($rowCatCon['cat_name']);
                                                            $catName = $rowCatCon['cat_name'];

                                                            $queryCatNoBarcode = "SELECT * FROM `item_no_barcode` WHERE `itemnb_category` = '$thisCategory' ORDER BY `itemnb_name` ASC";
                                                            $resultCatNoBarcode = mysqli_query($con, $queryCatNoBarcode);
                                                            if(mysqli_num_rows($resultCatNoBarcode) > 0){
                                                                while($rowCatNoBarcode = mysqli_fetch_assoc($resultCatNoBarcode)){
                                                                    if($_SESSION['endBuyer'] == "WHOLESALE"){
                                                                        $itemPrice = $rowCatNoBarcode['itemnb_wholesale_price'];
                                                                        $itemName = "(W)".$rowCatNoBarcode['itemnb_name'];
                                                                    }else{
                                                                        $itemPrice = $rowCatNoBarcode['itemnb_retail_price'];
                                                                        $itemName = $rowCatNoBarcode['itemnb_name'];
                                                                    }
                                                                    $nbCurQty = $rowCatNoBarcode['itemnb_stock'];
                                                                    $nbBasePrice = $rowCatNoBarcode['itemnb_wholesale_price'];

                                                                    ?>
                                                                        <a class="con-item" href="./nobarcode-add.php?itemId=<?php echo $rowCatNoBarcode['item_code']; ?>&itemName=<?php echo $itemName; ?>&itemPrice=<?php echo $itemPrice; ?>&curQty=<?php echo $nbCurQty; ?>&basePrice=<?php echo $nbBasePrice; ?>">
                                                                            <img src="<?php echo $rowCatNoBarcode['itemnb_img']; ?>" alt="">
                                                                            <p><?php echo $rowCatNoBarcode['itemnb_name']; ?></p>
                                                                        </a>
                                                                    <?php
                                                                }
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="left-bot-con">
                    <div class="bot-btn-con">
                        <div class="btn-con">
                            <button class="btn btn-secondary btnHome">
                                <p class="skey">HOME</p>
                                <p style="font-size: 14px;">CHANGE BUYER</p>
                            </button>
                            <button class="btn btn-secondary btnF4">
                                <p class="skey">F4</p>
                                <p>REMOVE LAST</p>
                            </button>
                            <button class="btn btn-secondary btnPlus">
                                <svg viewBox="0 0 448 512">
                                    <path d="M432 256c0 17.69-14.33 32.01-32 32.01H256v144c0 17.69-14.33 31.99-32 31.99s-32-14.3-32-31.99v-144H48c-17.67 0-32-14.32-32-32.01s14.33-31.99 32-31.99H192v-144c0-17.69 14.33-32.01 32-32.01s32 14.32 32 32.01v144h144C417.7 224 432 238.3 432 256z"/>
                                </svg>
                                <p>ADD QTY</p>
                            </button>
                            <button class="btn btn-secondary btnMinus">
                                <svg viewBox="0 0 448 512">
                                    <path d="M400 288h-352c-17.69 0-32-14.32-32-32.01s14.31-31.99 32-31.99h352c17.69 0 32 14.3 32 31.99S417.7 288 400 288z"/>
                                    </svg>
                                <p>MINUS QTY</p>
                            </button>
                            <button class="btn btn-secondary btnDot">
                                <svg width="10px" viewBox="0 0 512 512">
                                    <path d="M512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256z"/>
                                </svg>
                                <p>CUSTOM QTY</p>
                            </button>
                            <button class="btn btn-secondary btnDiscount">
                                <p class="skey">PgDn</p>
                                <p>DISCOUNT</p>
                            </button>
                            <button class="btn btn-secondary btnTran">
                                <svg viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet">
                                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" stroke="none">
                                    <path d="M975 4690 c-38 -16 -84 -67 -90 -101 -3 -19 -2 -914 3 -1989 9 -1848 11 -1958 28 -2009 24 -71 97 -151 166 -182 l53 -24 1460 -3 c803 -2 1479 0 1503 3 27 4 55 17 78 37 69 61 64 -86 64 1771 0 1847 2 1775 -59 1933 -92 236 -294 436 -525 520 -154 56 -112 54 -1438 53 -673 0 -1232 -4 -1243 -9z m2542 -339 c150 -52 261 -151 334 -296 73 -147 69 -44 69 -1777 l0 -1558 -1349 0 -1349 0 -6 233 c-3 127 -8 951 -11 1830 l-5 1597 1117 0 1118 0 82 -29z"/>
                                    <path d="M2398 4229 c-74 -11 -219 -59 -300 -100 -438 -221 -679 -669 -602 -1120 27 -157 112 -361 195 -468 19 -23 18 -23 -14 -45 -46 -31 -79 -100 -74 -154 6 -52 51 -105 105 -123 41 -13 388 -4 439 12 39 12 89 67 101 111 14 54 -6 396 -26 438 -24 50 -74 80 -134 80 -57 0 -96 -17 -131 -59 l-25 -30 -31 65 c-45 92 -62 164 -68 290 -11 222 52 397 199 550 122 127 230 190 388 225 213 47 412 5 595 -126 298 -213 395 -617 225 -944 -81 -156 -198 -268 -355 -341 -98 -46 -194 -70 -277 -70 -35 0 -80 -5 -101 -11 -117 -32 -160 -167 -80 -252 48 -50 72 -57 196 -57 254 0 508 109 698 299 477 477 397 1311 -160 1669 -124 79 -259 133 -400 157 -89 16 -269 18 -363 4z"/>
                                    <path d="M2505 3794 c-16 -8 -41 -25 -54 -37 -48 -44 -51 -66 -51 -374 0 -270 1 -289 21 -330 29 -60 388 -354 455 -373 118 -33 222 78 184 197 -17 51 -23 57 -192 200 l-148 124 0 237 c0 227 -1 240 -22 282 -17 33 -35 51 -68 68 -52 26 -84 28 -125 6z"/>
                                    <path d="M1682 1740 c-33 -15 -47 -29 -62 -62 -40 -88 -12 -184 62 -218 41 -19 72 -20 790 -20 504 0 756 3 775 11 61 23 113 95 113 156 0 15 -9 47 -20 71 -15 33 -29 47 -62 62 -41 19 -72 20 -798 20 -726 0 -757 -1 -798 -20z"/>
                                    <path d="M1682 1260 c-33 -15 -47 -29 -62 -62 -40 -88 -12 -184 62 -218 41 -19 70 -20 630 -20 391 0 596 4 615 11 61 23 113 95 113 156 0 15 -9 47 -20 71 -15 33 -29 47 -62 62 -41 19 -70 20 -638 20 -568 0 -597 -1 -638 -20z"/>
                                    </g>
                                </svg>
                                <p>TRANSACTION</p>
                            </button>
                            <button class="btn btn-secondary btnInventory">
                                <svg viewBox="0 0 640 512">
                                    <path d="M0 488V171.3C0 145.2 15.93 121.6 40.23 111.9L308.1 4.753C315.7 1.702 324.3 1.702 331.9 4.753L599.8 111.9C624.1 121.6 640 145.2 640 171.3V488C640 501.3 629.3 512 616 512H568C554.7 512 544 501.3 544 488V223.1C544 206.3 529.7 191.1 512 191.1H128C110.3 191.1 96 206.3 96 223.1V488C96 501.3 85.25 512 72 512H24C10.75 512 0 501.3 0 488zM152 512C138.7 512 128 501.3 128 488V432H512V488C512 501.3 501.3 512 488 512H152zM128 336H512V400H128V336zM128 224H512V304H128V224z"/>
                                </svg>
                                <p>INVENTORY</p>
                            </button>
                            <button class="btn btn-secondary btnDTR">
                            <svg viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet">

                                <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none">
                                    <path d="M2480 5104 c-42 -18 -86 -58 -108 -99 -15 -27 -17 -108 -22 -810 l-5 -780 -375 -5 c-340 -5 -379 -7 -403 -23 -32 -21 -64 -68 -72 -108 -16 -72 -10 -80 491 -610 261 -277 487 -510 502 -518 39 -21 113 -19 150 5 16 10 241 243 499 516 508 537 506 534 483 617 -11 43 -41 85 -73 104 -16 9 -124 13 -397 17 l-375 5 -5 780 c-5 702 -7 783 -22 810 -53 96 -172 140 -268 99z"/>
                                    <path d="M259 2115 c-108 -34 -204 -132 -239 -244 -19 -60 -20 -93 -20 -705 0 -607 1 -647 20 -720 28 -110 74 -190 155 -271 81 -81 161 -127 271 -155 76 -20 118 -20 2114 -20 1996 0 2038 0 2114 20 110 28 190 74 271 155 81 81 127 161 155 271 19 73 20 113 20 720 0 612 -1 645 -20 705 -36 117 -131 211 -245 245 -45 12 -114 14 -427 12 -361 -3 -375 -4 -421 -25 -57 -27 -140 -103 -172 -158 -13 -22 -70 -195 -126 -384 -113 -379 -123 -400 -214 -456 l-48 -30 -887 0 -887 0 -48 30 c-91 56 -101 77 -214 456 -56 189 -113 362 -126 384 -32 55 -115 131 -172 158 -47 21 -59 22 -428 24 -308 2 -389 -1 -426 -12z"/>
                                </g>
                                </svg>
                                <p>DTR</p>
                            </button>
                            <button class="btn btn-secondary btnLogout">
                                <svg viewBox="0 0 512 512">
                                    <path d="M96 480h64C177.7 480 192 465.7 192 448S177.7 416 160 416H96c-17.67 0-32-14.33-32-32V128c0-17.67 14.33-32 32-32h64C177.7 96 192 81.67 192 64S177.7 32 160 32H96C42.98 32 0 74.98 0 128v256C0 437 42.98 480 96 480zM504.8 238.5l-144.1-136c-6.975-6.578-17.2-8.375-26-4.594c-8.803 3.797-14.51 12.47-14.51 22.05l-.0918 72l-128-.001c-17.69 0-32.02 14.33-32.02 32v64c0 17.67 14.34 32 32.02 32l128 .001l.0918 71.1c0 9.578 5.707 18.25 14.51 22.05c8.803 3.781 19.03 1.984 26-4.594l144.1-136C514.4 264.4 514.4 247.6 504.8 238.5z"/>
                                </svg>
                                <p>LOG OUT</p>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-con">
                <div class="right-top-con">
                    <form class="item-code-con form" method="POST">
                        <label for="inputItemCode" class="align-middle ps-5 pe-2">Item:</label>
                        <input type="text" class="form-control shadow-sm" name="itemCode" id="inputItemCode" autofocus autocomplete="off">
                        <input type="submit" style="position: fixed; left: -100px;" name="itemCodeSubmit" id="codeSubmit" tabindex="-1">
                    </form>
                    <div class="end-buyer-con">
                        <h1 id="end-buyer"><?php echo $_SESSION['endBuyer']; ?></h1>
                    </div>
                </div>
                <div class="right-bottom-con">
                    <div class="scanned-item-table">
                        <table id="scanned-items">
                            <thead id="tableHead">
                                <tr>
                                    <th>Item Name</th>
                                    <th>Price (₱)</th>
                                    <th>Qty</th>
                                    <th>Total (₱)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(isset($_POST['itemCodeSubmit'])){
                                        $itemCode = $_REQUEST['itemCode'];
                                        $checkItem = "SELECT * FROM `item_with_barcode` WHERE `item_code` = '$itemCode'";
                                        $resultItem = mysqli_query($con, $checkItem);
                                        if(mysqli_num_rows($resultItem) > 0){
                                            $rowItem = mysqli_fetch_assoc($resultItem);
                                            $curQty = $rowItem['item_stock'];
                                            if($curQty <= 0){
                                                ?>
                                                    <script>
                                                        swal({
                                                            icon: "error",
                                                            title: "Out of Stock!",
                                                        }).then((value) => {
                                                            $('#inputItemCode').focus();
                                                        });
                                                    </script>
                                                <?php	
                                            }else{
                                                if($_SESSION['endBuyer'] == "WHOLESALE"){
                                                    $itemPrice = $rowItem['item_wholesale_price'];
                                                    $itemName = "(W)".$rowItem['item_name'];
                                                }else{
                                                    $itemPrice = $rowItem['item_retail_price'];
                                                    $itemName = $rowItem['item_name'];
                                                }

                                                $checkTemp = "SELECT * FROM `temp_item` WHERE `item_code` = '$itemCode'";
                                                $resultTemp = mysqli_query($con, $checkTemp);
                                                if(mysqli_num_rows($resultTemp) > 0){
                                                    while($rowTemp = mysqli_fetch_assoc($resultTemp)){
                                                        if($rowTemp['temp_quantity'] != $rowTemp['current_stock']){
                                                                $newQty = $rowTemp['temp_quantity'] + 1;
                                                        }else{
                                                            $newQty = $rowTemp['temp_quantity'];
                                                        }

                                                        $newTotal = $newQty * $rowTemp['temp_price'];
                                                        $newbasePrice = $newQty * $rowItem['item_wholesale_price'];
                                                        $tempPrice = $rowTemp['temp_price'];

                                                        $deleteTemp = "DELETE FROM `temp_item` WHERE `item_code` = '$itemCode'";
                                                        mysqli_query($con, $deleteTemp);
                                                        $updateTemp = "INSERT INTO `temp_item`(`temp_id`, `item_code`, `temp_quantity`, `temp_price`, `temp_name`, `temp_total`, `current_stock`, `temp_baseprice`) VALUES (null,'$itemCode','$newQty','$tempPrice','$itemName','$newTotal','$curQty','$newbasePrice')";
                                                        mysqli_query($con, $updateTemp);
                                                    }
                                                }else{
                                                    $basePrice = $rowItem['item_wholesale_price'];
                                                    $insertTemp = "INSERT INTO `temp_item`(`temp_id`, `item_code`, `temp_quantity`, `temp_price`, `temp_name`, `temp_total`, `current_stock`, `temp_baseprice`) VALUES (null,'$itemCode','1','$itemPrice','$itemName','$itemPrice','$curQty','$basePrice')";
                                                    mysqli_query($con, $insertTemp);
                                                }
                                            }
                                        }else{
                                            ?>
                                                <script>
                                                    swal({
                                                        icon: "error",
                                                        title: "Unregistered Item!",
                                                    }).then((value) => {
                                                        $('#inputItemCode').focus();
                                                    });
                                                </script>
                                            <?php
                                        }
                                    }

                                    $queryTempItems = "SELECT * FROM `temp_item` ORDER BY temp_id DESC";
                                    $resultTempItems = mysqli_query($con, $queryTempItems);
                                    if(mysqli_num_rows($resultTempItems) > 0){
                                        $c = 0;

                                        while($rowTempItems = mysqli_fetch_assoc($resultTempItems)){
                                            if($c == 0){
                                                $_SESSION['lastCode'] = $rowTempItems['item_code'];
                                                $_SESSION['lastQty'] = $rowTempItems['temp_quantity'];
                                                $_SESSION['curStock'] = $rowTempItems['current_stock'];
                                                $curStock = $rowTempItems['current_stock'];
                                                $c++;
                                            }

                                            ?>
                                                <tr>
                                                    <td><?php echo $rowTempItems['temp_name']; ?></td>
                                                    <td><?php echo number_format($rowTempItems['temp_price'],2); ?></td>
                                                    <td><?php echo $rowTempItems['temp_quantity']; ?></td>
                                                    <td><?php echo number_format($rowTempItems['temp_total'],2); ?></td>
                                                    <td><button class="btn btn-danger remTempItem" data-item-code="<?php echo $rowTempItems['item_code'] ?>">Remove</button></td>
                                                </tr>
                                            <?php
                                            $subTotal = $subTotal + $rowTempItems['temp_total'];
                                            $basePriceTotal = $basePriceTotal + $rowTempItems['temp_baseprice'];
                                            $totalQty = $totalQty + $rowTempItems['temp_quantity'];
                                            $totalItems++;
                                        }

                                        $_SESSION['totalItems'] = $totalItems;
                                        $_SESSION['totalBasePrice'] = $basePriceTotal;
                                        $_SESSION['subTotal'] = $subTotal;
                                        $_SESSION['totalQty'] = $totalQty;

                                        $grandTotal = $subTotal - ($subTotal * $manualDisc);
                                    }else{
                                        ?>
                                            <tr style="text-align: center;"><td colspan="5">NO RECORD</td></tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="total-con">
                        <div class="row1">
                            <div class="col1">
                                <span>Total Quantity</span>
                                <span><?php echo $totalQty; ?></span>
                            </div>
                            <div class="col2">
                                <span>Subtotal</span>
                                <span><span>₱ </span><?php echo number_format($subTotal, 2); ?></span>
                            </div>
                        </div>
                        <div class="srow">
                            <span>Discount</span>
                            <span><?php echo number_format(($manualDisc*100),2) ?>%</span>
                        </div>
                        <div class="srow">
                            <span>GRAND TOTAL</span>
                            <span id="grandTotal"><span>₱ </span><?php echo number_format(($grandTotal), 2); ?></span>
                        </div>
                    </div>
                    <div class="button-con">
                        <div class="payment-con">
                            <button href="#" class="btn btn-primary btnPayment" <?php if($totalQty == 0){ echo 'disabled'; } ?>>PAYMENT (F8)</button>
                        </div>
                        <div class="cancel-con">
                            <button class="btn btn-danger btnCancel">CANCEL (F9)</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">

            $(document).ready(function(){

                var arr_name = <?php echo json_encode($ItemWB_name); ?>;
                var arr_code = <?php echo json_encode($ItemWB_code); ?>;

                var searchValue = <?php echo json_encode($searchVal); ?>;
                $('#searchItem').val(searchValue);
                
                var value = $('#searchItem').val().toLowerCase();
                $("#all-con button").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });

                $('#inputItemCode').autocomplete({
                    lookup: arr_name
                });

                jQuery(document).on( "click", ".autocomplete-suggestion", function(){
                    var thisItem = $(this).text();
                    var ind = $.inArray(thisItem, arr_name);
                    $('#inputItemCode').val(arr_code[ind]);
                    $('#codeSubmit').click();
                });

                $('.btnTran').click(function(){
                    window.location.href = './transaction-history.php?dateFrom=<?php echo date('Y-m-d'); ?>&dateTo=<?php echo date("Y-m-d"); ?>&searchDate="Search"';
                });

                $('.itemInCategory').click(function(){
                    var searchVal = $('#searchItem').val();
                    var catItemCode = $(this).data('code');
                    var catItemName = $(this).data('name');
                    var catItemPrice = $(this).data('price');
                    var catItemQty = $(this).data('curqty');
                    var catItemBase = $(this).data('baseprice');

                    window.location.href = './nobarcode-add.php?itemId='+catItemCode+'&itemName='+catItemName+'&itemPrice='+catItemPrice+'&curQty='+catItemQty+'&basePrice='+catItemBase+'&searchVal='+searchVal;

                    
                });

                $('#inputItemCode').on('keydown', function(ice){
                    var iceKey = ice.which || ice.keyCode;

                    var regExp = /[a-zA-Z]/g;
                    var inputCode = $('#inputItemCode').val();

                    console.log(inputCode);

                    if(iceKey == 13){
                        var inputNgItemCode = $('#inputItemCode').val();
                        if(inputNgItemCode == ""){
                            ice.preventDefault();
                        }else if(regExp.test(inputCode)){
                            var ind = $.inArray(inputCode, arr_name);
                            $('#inputItemCode').val(arr_code[ind]);
                        }
                    }
                });

                $('.btnDTR').click(function(){
                    window.location.href = "./dtr.php";
                });

                $('.btnDiscount').click(function(){
                    $("#inputItemCode").val("");
                    
                    swal({
                        title: "Discount (%)",
                        closeOnClickOutside: false,
                        content: {
                            element: "input",
                            attributes: {
                                id: "perDisc",
                                type: "number",
                                min: "1",
                                max: "100",
                                step: "1",
                            },
                        },
                        buttons:{
                            submit: {
                                text: "Submit",
                                value: 'sbmtQty',
                                visible: true,
                                className: "sbmtDiscount",
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

                jQuery(document).on( "click", ".sbmtDiscount", function(){
                    if($('#perDisc').val() != ""){
                        var manDisc = $('#perDisc').val();
                        window.location.href = './change-manual-discount.php?manDisc='+manDisc;
                    }else{
                        $("#inputItemCode").focus();
                    }
                });

                jQuery(document).on( "keyup", "#perDisc", function(ep){
                    var epKey = ep.which || ep.keyCode;

                    if($('#perDisc').val() > 100){
                        $('#perDisc').val("100");
                    }
        
                    if(epKey == 13){
                        if($('#perDisc').val() != ""){
                            var manDisc = $('#perDisc').val();
                            window.location.href = './change-manual-discount.php?manDisc='+manDisc;
                        }   
                    }else if(epKey == 27){
                        $("#inputItemCode").focus();
                    }
                });

                $('.btnLogout').click(function(){
                    window.location.href = './logout.php';
                });

                $('.btnPayment').click(function(){
                    $("#inputItemCode").val("");
                    
                    swal({
                        title: "Amount Received",
                        closeOnClickOutside: false,
                        content: {
                            element: "input",
                            attributes: {
                                id: "amountRecieved",
                                type: "number",
                                min: "1",
                                max: "999999",
                                step: "1",
                            },
                        },
                        buttons:{
                            submit: {
                                text: "Submit",
                                value: 'sbmtQty',
                                visible: true,
                                className: "sbmtAmount",
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

                jQuery(document).on( "click", ".swal-modal", function(){
                    // window.location.href = "./reciept.php";

                });

                // jQuery(document).on( "click", ".sbmtAmount", function(){
                //     // window.location.href = "./reciept.php";
                // });

                jQuery(document).on( "click", ".sbmtAmount", function(){
                    if($('#amountRecieved').val() != ""){
                            var amRec = Number($('#amountRecieved').val()).toFixed(2);
                            var amPay = Number(<?php echo json_encode($grandTotal); ?>).toFixed(2);
                            var amChange = Number(amRec - amPay).toFixed(2);

                        if(amChange < 0){
                            swal({
                                icon: "error",
                                title: "Invalid Amount",
                            }).then((value) => {
                                $('#inputItemCode').focus();
                            });
                        }else{
                            swal({
                                icon: "info",
                                title: "Transaction Processing",
                                text: "\nAmount Payable:   ₱ "+amPay.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+ "\n\n" +"Amount Recieved:   ₱ "+amRec.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+ "\n\n" +"Change:   ₱ "+amChange.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','),
                            }).then((value) => {
                                window.location.href = "./reciept.php?amRec="+amRec;
                            });
                        }
                    }else{
                        // $("#inputItemCode").focus();
                        window.location.href = "./reciept.php";
                    }
                });

                // jQuery(document).on( "click", ".btnPayment", function(){
                //     if($('#amountRecieved').val() != ""){
                //             var amRec = Number($('#amountRecieved').val()).toFixed(2);
                //             var amPay = Number(<?php //echo json_encode($grandTotal); ?>).toFixed(2);
                //             var amChange = Number(amRec - amPay).toFixed(2);

                //         if(amChange < 0){
                //             swal({
                //                 icon: "error",
                //                 title: "Invalid Amount",
                //             }).then((value) => {
                //                 $('#inputItemCode').focus();
                //             });
                //         }else{
                //             swal({
                //                 icon: "info",
                //                 title: "Transaction Processing",
                //                 text: "\nAmount Payable:   ₱ "+amPay.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','),
                //             }).then((value) => {
                //                 window.location.href = "./reciept.php?amRec="+amRec;
                //             });
                //         }
                //     }else{
                //         $("#inputItemCode").focus();
                //         // window.location.href = "./reciept.php";
                //     }
                // });

                jQuery(document).on( "keyup", "#amountRecieved", function(ep){
                    var epKey = ep.which || ep.keyCode;
                    
                    if(epKey == 13){
                        if($('#amountRecieved').val() != ""){
                            var amRec = Number($('#amountRecieved').val()).toFixed(2);
                            var amPay = Number(<?php echo json_encode($grandTotal); ?>).toFixed(2);
                            var amChange = Number(amRec - amPay).toFixed(2);

                            if(amChange < 0){
                                swal({
                                    icon: "error",
                                    title: "Invalid Amount",
                                }).then((value) => {
                                    $('#inputItemCode').focus();
                                });
                            }else{
                                swal({
                                    icon: "info",
                                    title: "Transaction Processing",
                                    text: "\nAmount Payable:   ₱ "+amPay.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+ "\n\n" +"Amount Recieved:   ₱ "+amRec.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+ "\n\n" +"Change:   ₱ "+amChange.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','),
                                }).then((value) => {
                                    window.location.href = "./reciept.php?amRec="+amRec;
                                });
                            }
                        }else{
                            $("#inputItemCode").focus();
                            window.location.href = "./reciept.php";
                        }

                    }else if(epKey == 27){
                        $("#inputItemCode").focus();
                    }

                    if(event.key==='.'){event.preventDefault();}
                });

                $('.btnInventory').click(function(){
                    window.location.href = "./inventory.php";
                });

                $('.btnDot').click(function(){
                    $("#inputItemCode").val("");
                    
                    swal({
                        title: "QUANTITY",
                        closeOnClickOutside: false,
                        content: {
                            element: "input",
                            attributes: {
                                name: "manualQty",
                                id: "manualQty",
                                type: "number",
                                min: "1",
                                max: "999",
                                step: "1",
                            },
                        },
                        buttons:{
                            submit: {
                                text: "Submit",
                                value: 'sbmtQty',
                                visible: true,
                                className: "sbmtQty",
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

                $('.btnMinus').click(function(){
                    $("#inputItemCode").val("");
                    window.location.href = './dec-inc.php';
                });

                $('.btnPlus').click(function(){
                    $("#inputItemCode").val("");
                    window.location.href = './inc-qty.php';
                });

                $('.btnF4').click(function(){
                    $("#inputItemCode").val("");
                    
                    swal({
                        icon: "warning",
                        title: "REMOVE LAST ITEM",
                        text: "Are you sure you want to remove the last item?",
                        closeOnClickOutside: false,
                        dangerMode: true,
                        buttons:{
                            confirm: {
                                text: "YES",
                                visible: true,
                                className: "yesRemove",
                                closeModal: true,
                                attributes: {
                                    autofocus: true,
                                },
                            },
                            cancel: {
                                text: "NO",
                                visible: true,
                                className: "cncl",
                                closeModal: true,
                            },
                        },
                    })
                });

                $('.btnHome').click(function(){
                    $("#inputItemCode").val("");
                    var retailWholesale = $('#end-buyer').html();
                    var changeTo;
                    if(retailWholesale == "RETAIL"){
                        changeTo = "WHOLESALE";
                    }else if(retailWholesale == "WHOLESALE"){
                        changeTo = "RETAIL";
                    }
                    window.location.href = './change-end-buyer.php?endBuyer=' + changeTo;
                });

                $("#searchItem").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $("#all-con button").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });

                $('.btnCancel').click(function(){
                    $("#inputItemCode").val("");
                    
                    swal({
                        icon: "warning",
                        title: "CANCEL TRANSACTION",
                        text: "Are you sure you want to cancel the transactions?",
                        closeOnClickOutside: false,
                        dangerMode: true,
                        buttons:{
                            confirm: {
                                text: "YES",
                                visible: true,
                                className: "yesCancel",
                                closeModal: true,
                                attributes: {
                                    autofocus: true,
                                },
                            },
                            cancel: {
                                text: "NO",
                                visible: true,
                                className: "cncl",
                                closeModal: true,
                            },
                        },
                    })
                });

                $('#searchItem').on('keyup', function(){
                    $('#v-pills-all-tab').click();
                });

                $('.nav-link:not(:first)').click(function(){
                    $('#searchItem').val("");
                });

                $(document).on("keyup", function(e) {
                    var keyUp = e.which || e.keyCode;

			if(keyUp == 13){
				if($('#inputItemCode').val == ""){
					console.log('running');
					e.preventDefault();
				}
                    }else if(keyUp == 107){
                        $("#inputItemCode").val("");
                        window.location.href = './inc-qty.php';
                    }else if(keyUp == 109){
                        $("#inputItemCode").val("");
                        window.location.href = './dec-inc.php';
                    }else if(keyUp == 110){
                        $('.btnDot').click();
                    }else if(keyUp == 120){
                        $("#inputItemCode").val("");
                        
                        swal({
                            icon: "warning",
                            title: "CANCEL TRANSACTION",
                            text: "Are you sure you want to cancel the transactions?",
                            closeOnClickOutside: false,
                            dangerMode: true,
                            buttons:{
                                confirm: {
                                    text: "YES",
                                    visible: true,
                                    className: "yesCancel",
                                    closeModal: true,
                                    attributes: {
                                        autofocus: true,
                                    },
                                },
                                cancel: {
                                    text: "NO",
                                    visible: true,
                                    className: "cncl",
                                    closeModal: true,
                                },
                            },
                        })
                    }else if(keyUp == 115){
                        $("#inputItemCode").val("");
                        
                        swal({
                            icon: "warning",
                            title: "REMOVE LAST ITEM",
                            text: "Are you sure you want to remove the last item?",
                            closeOnClickOutside: false,
                            dangerMode: true,
                            buttons:{
                                confirm: {
                                    text: "YES",
                                    visible: true,
                                    className: "yesRemove",
                                    closeModal: true,
                                    attributes: {
                                        autofocus: true,
                                    },
                                },
                                cancel: {
                                    text: "NO",
                                    visible: true,
                                    className: "cncl",
                                    closeModal: true,
                                },
                            },
                        })
                    }else if(keyUp == 36){
                        e.preventDefault();
                        $("#inputItemCode").val("");
                        var retailWholesale = $('#end-buyer').html();
                        var changeTo;
                        if(retailWholesale == "RETAIL"){
                            changeTo = "WHOLESALE";
                        }else if(retailWholesale == "WHOLESALE"){
                            changeTo = "RETAIL";
                        }
                        window.location.href = './change-end-buyer.php?endBuyer=' + changeTo;
                    }else if(keyUp == 119){
                        if(<?php echo json_encode($totalQty); ?> != "0"){
                            $("#inputItemCode").val("");
                    
                            swal({
                                title: "Amount Received",
                                closeOnClickOutside: false,
                                content: {
                                    element: "input",
                                    attributes: {
                                        id: "amountRecieved",
                                        type: "number",
                                        min: "1",
                                        max: "999999",
                                        step: "1",
                                    },
                                },
                                buttons:{
                                    submit: {
                                        text: "Submit",
                                        value: 'sbmtQty',
                                        visible: true,
                                        className: "sbmtAmount",
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
                        }
                    }else if(keyUp == 34){
                        $("#inputItemCode").val("");
                        
                        swal({
                            title: "Discount (%)",
                            closeOnClickOutside: false,
                            content: {
                                element: "input",
                                attributes: {
                                    id: "perDisc",
                                    type: "number",
                                    min: "1",
                                    max: "100",
                                    step: "1",
                                },
                            },
                            buttons:{
                                submit: {
                                    text: "Submit",
                                    value: 'sbmtQty',
                                    visible: true,
                                    className: "sbmtDiscount",
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
                    }
                });

                $('.remTempItem').click(function(){
                    var thisItemCode = $(this).data('item-code');
                    swal({
                        title: "Delete Item",
                        text: "Are you sure you want to delete this item?",
                        icon: "warning",
                        closeOnClickOutside: false,
                        dangerMode: true,buttons:{
                                confirm: {
                                    text: "YES",
                                    visible: true,
                                    closeModal: true,
                                    attributes: {
                                        autofocus: true,
                                    },
                                },
                                cancel: {
                                    text: "NO",
                                    visible: true,
                                    className: "cncl",
                                    closeModal: true,
                                },
                            },
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            window.location.href = './remove-temp-item.php?tempCode=' + thisItemCode;
                        } else {
                            $("#inputItemCode").focus();
                        }
                    });
                });

                function sbtManualQty(){
                    var manualQty = $('#manualQty').val();
                    window.location.href = './manual-qty.php?mqty=' + manualQty;
                }

                function cancelTran(){
                    window.location.href = './del-temp-table.php';
                }


                function removeLastItem(){
                    window.location.href = './remove-last-item.php';
                }

                jQuery(document).on( "keyup", "#manualQty", function(em){

                    var curStock = <?php echo json_encode($curStock); ?>;
                    var inputQty = $('#manualQty').val();

                    if(inputQty > parseInt(curStock)){
                        $('#manualQty').val(parseInt(curStock));
                    }


                    var emKey = em.which || em.keyCode;
                    
                    if(emKey == 13){
                        if($('#manualQty').val() != ""){
                            sbtManualQty();
                        }else{
                            $("#inputItemCode").focus();
                        }

                    }else if(emKey == 27){
                        $("#inputItemCode").focus();
                    }

                    if(event.key==='.'){event.preventDefault();}
                });

                jQuery(document).on( "click", ".sbmtQty", function(){
                    if($('#manualQty').val() != ""){
                        sbtManualQty();
                    }else{
                        $("#inputItemCode").focus();
                    }
                });

                jQuery(document).on( "click", ".cncl", function(){
                    $("#inputItemCode").focus();
                });

                jQuery(document).on( "keyup", ".swal-modal", function(ec){
                    var ecKey = ec.which || ec.keyCode;
                    
                    if(ecKey == 27){
                        $("#inputItemCode").focus();
                    }
                });

                jQuery(document).on( "click", ".yesCancel", function(){
                    cancelTran();
                });

                jQuery(document).on( "click", ".yesRemove", function(){
                    removeLastItem();
                });

                const itemCount = <?php echo json_encode($totalItems); ?>;
                if(screen.height <= '1080'){
                    if(itemCount > 12){
                        $('#tableHead').css("width","calc(100% - 15px)");
                    }
                }
            });
        </script>
    </body>
</html>