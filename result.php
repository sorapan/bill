<?php

include 'catfish/ConvertNumber.php';

session_start();

if(!isset($_SESSION['json_unit'])){
    header('Location: index.php');
}

$array_unit = json_decode($_SESSION['json_unit'],true);
$array_name = json_decode($_SESSION['json_name'],true);
$array_ppu = json_decode($_SESSION['json_ppu'],true);
$array_price = json_decode($_SESSION['json_price'],true);

$count_tr = 19;


if(!isset($_SESSION['rl'] )){
    $_SESSION['rl'] = $_SESSION['refresh_Locker'];
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Print Result</title>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />

    <script src="jquery/jquery.js"></script>
    <script src="rb_plgin/rb_popup_plgin.js"></script>
    <script src="custom_plugin_1.js"></script>
    <script src="print_option_1.js"></script>


    <script language="JavaScript">
        $(function() {

            $.EditLastNumber(<?=$_SESSION["number_choi"]?>);

            $.GetLastNumber(" #last_number", <?=$_SESSION["number_choi"]?>, "1");
            $.GetLastNumber(" #last_alphabet", <?=$_SESSION["number_choi"]?>, "2");

            //TransferNumber(<?//=$_SESSION['rl']?>);
            <?php //$_SESSION['rl']="2" ?>

            $(" #print_button").click(function(){
                var confirmit =  confirm("Are you sure?");


                if(confirmit){

                    TransferNumber(0);
                    setTimeout(function(){
                        window.print();
                        window.location.href = "destroy.php";
                    },500);

                    return true;

                }else{

                    return false;

                }
            });

            $(" #back_button").click(function(){
                //TransferNumber(1);
                window.onbeforeunload = null;
                window.location.href = "destroy.php";
            });


            function TransferNumber(r){
                $.ajax({
                    type : 'POST',
                    url : 'CountAndWrite.php',
                    scriptCharset: 'UTF-8',
                    data : {
                        data:<?=$_SESSION["number_choi"]?> ,
                        refreshlocker:r
                    },
                    success: function(data){

                        $(" #counter_number").html("No."+data);

                    }
                });
            }

        });
    </script>

    <link rel="stylesheet" href="rb_plgin/rb_pop_plgin.css" type="text/css">
    <link rel="stylesheet" href="css/print_1.css" type="text/css">
    <link rel="stylesheet" href="css/print_option_1.css" type="text/css">
</head>
<body>

<div id="counter"><span id="counter_number"></span></div>

<div id="head_1"><img src="img/aa.png" id="logo"><span><?=$_SESSION['header_choi']?></span></div><br>

<div id="head_2">สงขลาเจริญการช่าง</div><br>
<div class="subhead_1">143/109 หมู่ที่ 8 ต.เขารูปช้าง อ.เมือง จ.สงขลา 90000</div>
<div class="subhead_1">โทร/แฟ๊กซ์ (074) 436353, 437377 081-2766620</div>
<div class="subhead_1">เลขประจำตัวผู้เสียภาษีอากร 3 90990012906 3</div><br>
<div class="subhead_2">วันที่........../........../25..........</div>

<div class="subhead_4">นามผู้ซื้อ.................................................................................................................................................................................
</div>
<div class="subhead_4">ที่อยู่.............................................................................................................................................................................................
</div>
<div class="subhead_4">..............................................................................................................................................................................................................................................
</div>

<br>
<div id="table_wrap">
<table id="table_print_1" border="1">
    <tr>
        <th id="unit_attri">จำนวน</th>
        <th id="name_attri">รายการ</th>
        <th id="ppu_attri">ราคาต่อหน่วย</th>
        <th id="price_attri">จำนวนเงิน</th>
    </tr>
        <?php
        foreach($array_unit as $key => $value){
            echo "<tr>",
                 "<td class='price_row'>",number_format($array_unit[$key]),"</td>",
                 "<td class='name'>",$array_name[$key],"</td>",
                 "<td class='price_row'>",number_format($array_ppu[$key], 2),"</td>",
                 "<td class='price_row'>",number_format($array_price[$key], 2),"</td>",
                 "</tr>";

            $count_tr-- ;
        }

        while($count_tr > 0){
            $count_tr-- ;

            echo "<tr class='fullfill_td'>",
            "<td> </td>",
            "<td> </td>",
            "<td> </td>",
            "<td> </td>",
            "</tr>";
        }


        if(is_numeric($_SESSION['vat_price'])){
            $vat_price = number_format($_SESSION['vat_price'], 2);
        }else{
            $vat_price = $_SESSION['vat_price'];
        }

        ?>


</table>
<table id="table_print_2" >
    <?php
    echo "<tr>",
        "<td class='char_num' >", new ConvertNumber($_SESSION['last_price']) ,"</td>",
        "<td ></td>",
        "<td class='empty_border_table'>รวมราคาทั้งหมด</td>",
        "<td class='result_td_1'>",number_format($_SESSION['all_price'], 2)," </td>",
        "</tr>";

    echo "<tr>",
        "<td ></td>",
        "<td ></td>",
        "<td class='empty_border_table'>จำนวนภาษีมูลค่าเพิ่ม 7%</td>",
        "<td class='result_td_2'>", $vat_price ," </td>",
        "</tr>";

    echo "<tr>",
    "<td ></td>",
    "<td ></td>",
    "<td class='empty_border_table'>จำนวนเงินรวมทั้งสิ้น</td>",
    "<td class='result_td_3'>",number_format($_SESSION['last_price'], 2)," </td>",
    "</tr>";

        //echo "<tr><td class='result_td'>",$_SESSION['all_price'],"</td></tr>";

    ?>
</table>
<br>
<div class="subhead_3">ลงชื่อ.............................................<?=$_SESSION['signature_choi']?></div>
</div>



<div id="bottom_box">

    <button id="btg">Show/Hide Option</button>

    <div id = "content">
        <button id="back_button"> ปฏิเสธหน้านี้ </button>
        <button id="print_button"> สั่งปริ้นท์ </button>
        <label for="last_number">เลขหน้าล่าสุด :</label>
        <input type="text" id="last_alphabet" >
        <input type="text" id="last_number" >
        <button id="edit_last_number"> แก้ไขเลขหน้า </button>
    </div>

</div>


</body>
</html>
