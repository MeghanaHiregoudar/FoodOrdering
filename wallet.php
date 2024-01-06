<?php include('header.php'); 
if(!isset($_SESSION['FOOD_USER_ID']))
{
    redirectPage(FRONT_SITE_PATH."shop");
}

//This is to add money to wallet from paytm
$wallet_error = '';
if(isset($_POST['add_money']))
{
    $amt = get_safe_value($_POST['amt']);
    if($amt > 0)
    {
        $ORDER_ID = "ORDS"."_".$_SESSION['FOOD_USER_ID']."_".rand(10000,99999999);
        $paytm = '<form method="post" action="pgRedirect.php" name="paymenform" style="display:none">
            <label>ORDER_ID::*</label>
            <input id="ORDER_ID" tabindex="1" maxlength="20" size="20" name="ORDER_ID" autocomplete="off" value="'.$ORDER_ID.'">
            <label>CUSTID ::*</label>
            <input id="CUST_ID" tabindex="2" maxlength="12" size="12" name="CUST_ID" autocomplete="off" value="'.$_SESSION['FOOD_USER_ID'].'">
            <label>INDUSTRY_TYPE_ID ::*</label>
            <input id="INDUSTRY_TYPE_ID" tabindex="4" maxlength="12" size="12" name="INDUSTRY_TYPE_ID" autocomplete="off" value="Retail">
            <label>Channel ::*</label>
            <input id="CHANNEL_ID" tabindex="4" maxlength="12" size="12" name="CHANNEL_ID" autocomplete="off" value="WEB">
            <label>txnAmount*</label>
            <input title="TXN_AMOUNT" tabindex="10" type="text" name="TXN_AMOUNT" value="'.$amt.'">
            <input value="CheckOut" type="submit"	onclick="">
            </form>
            <script type="text/javascript">
                document.paymenform.submit();
            </script>';
        echo $paytm;
    }
    else
    {
        $wallet_error = " Please Enter Valid Amount";
    }
    
}


?>

<div class="cart-main-area pt-95 pb-100">
    <div class="container">
        <div class="pb-20 add_money_wallet">
            <form method="POST" id="add_wallet_form">
                <div class="row ">
                    <div class="col-lg-4 col-md-4">
                        <div class="billing-info">
                            <label>Add Money To Wallet </label>
                            <input type="text"  name="amt"  placeholder="Add Money" required >
                            <div id="wallet_error" class="text-danger font-weight-bold"> <?php echo $wallet_error; ?></div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="billing-back-btn">
                            <div class="billing-btn">
                                <button type="submit" name="add_money">Add Money</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <h3 class="page-title">Wallet Statement</h3>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="table-content table-responsive">
					<table>
                        <thead>
                            <tr>
                                <th width="10%" >Sl No</th>
                                <th width="10%" >Date</th>
                                <th width="50%" > Narration</th>
                                <th width="10%" >Amount</th>
                                <th width="10%" >Credit/Debit</th>
                                <!--<th >Balance</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php $getWallet = getWallet($_SESSION['FOOD_USER_ID']);
                            $i = 1;
                            foreach($getWallet as $list) { ?>
                                <tr class="wallet_loop">
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo date('d-m-Y h:i:s',strtotime($list['added_on'])); ?></td>
                                    <td><?php echo $list['msg']; ?></td>
                                    <td><?php echo "₹ ".$list['amt']; ?></td>
                                    <td class="<?php echo $list['type'] ?>"><?php 
                                        if($list['type'] == 'in') { $type = 'Credit'; } else { $type = 'Debit'; }
                                        echo $type; ?>
                                    </td>
                                    <!--<td>-->
                                    <!--    <?php echo $balance; ?>-->
                                    <!--</td> -->
                                </tr>
                            <?php $i++; } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
include("footer.php");
?>



