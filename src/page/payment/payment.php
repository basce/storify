<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1.0,user-scalable=0">
    <meta name="robots" content="noindex, nofollow">
<?php include("page/component/meta.php"); ?>

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon"> 

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Varela+Round" rel="stylesheet">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="/assets/fonts/font-awesome.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/selectize.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/user.css">
    <script>
        window._startTime = new Date().getTime();

        window.getExecuteTime = function(str){
            var currentTime = new Date().getTime();
            console.log(str);
            console.log( currentTime - window._startTime);
        }
    </script>
    <script src="/assets/js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="/assets/js/popper.min.js"></script>
    <script type="text/javascript" src="/assets/bootstrap/js/bootstrap.min.js"></script>
    <!-- <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyBEDfNcQRmKQEyulDN8nGWjLYPm8s4YB58&libraries=places"></script> -->
    <!--<script type="text/javascript" src="http://maps.google.com/maps/api/js"></script>-->
    <script src="/assets/js/selectize.min.js"></script>
    <script src="/assets/js/masonry.pkgd.min.js"></script>
    <script src="/assets/js/icheck.min.js"></script>
    <script src="/assets/js/jquery.validate.min.js"></script>
    <script src="/assets/js/scrollreveal.js"></script>
    <script src="/assets/js/custom.js"></script>
    <script src="/assets/js/nc_custom.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
<?=get_option("custom_settings_header_js")?>
    <style type="text/css">
        .selectize-control.customselect{
            padding:0;
        }
        .selectize-control.customselect .selectize-input.items{
            box-shadow: none;
            border: none;
        }
        .StripeElement {
          box-sizing: border-box;

          height: 40px;

          padding: 10px 12px;

          border: 1px solid transparent;
          border-radius: 4px;
          background-color: white;

          box-shadow: 0 1px 3px 0 #e6ebf1;
          -webkit-transition: box-shadow 150ms ease;
          transition: box-shadow 150ms ease;
        }

        .StripeElement--focus {
          box-shadow: 0 1px 3px 0 #cfd7df;
        }

        .StripeElement--invalid {
          border-color: #fa755a;
        }

        .StripeElement--webkit-autofill {
          background-color: #fefde5 !important;
        }

        .ccard_container{
            margin-bottom:10px;
        }
        .ccard{
            background-color: white;
            display: inline-block;
            padding: 5px 15px;
            color: gray;
            font-size:1.1em;
        }

        .ccard.default{
            border: solid 1px blue;
        }

        .ccard i{
            padding-right:10px;
            color:black;
        }

        .ccard .date{
            padding-left:260px;
            padding-right:20px;
        }

        .ccard_container .setDefaultForm{
            display:inline-block;
        }
    </style>
</head>
<body>
    <div class="page sub-page">
        <header class="hero">
            <div class="hero-wrapper">
<?php 
$header_without_toggle_button = true;
include("page/component/header.php"); ?>
                <!--============ Page Title =========================================================================-->
                <div class="page-title">
                    <div class="container">
                        <h1>Payment</h1>
                        <h2>Stripe</h2>
                    </div>
                    <!--end container-->
                </div>
                <!--============ End Page Title =====================================================================-->
                <div class="background"></div>
                <!--end background-->
            </div>
        </header>
        <section class="content">
            <section class="block">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <?php include("page/user/leftnav.php"); ?>
                        </div>
                        <div class="col-md-9">
                            <div class="clearfix">
                                <p>Credit Cards</p>
                                <?php if(isset($_GET["error"])){
                                    ?>
                                    <div class="alert alert-danger">
                                      <?=$_GET["error"]?>
                                    </div>
                                    <?php
                                }?>
                            </div>
                            <div class="">
                                <?php
                                /*
                                //http_build_query
                                $state = dechex(mt_rand()*mt_rand());
                                $_SESSION["state"] = $state;
                                echo $state;
                                $params = array(
                                    "client_id"=>"ca_FeKnKcTlSsRVVOZS6wI7t6y7ej3ChEfH",
                                    "response_type"=>"code",
                                    "scope"=>"read_write",
                                    "state"=>$state,
                                    "redirect_uri"=>get_home_url()."/stripe_authorize"
                                );
                                ?>
                                <a href="https://connect.stripe.com/oauth/authorize?<?=http_build_query($params)?>" class="btn btn-primary">Connect</a>
                                <?php */ 

                                //updat to use business id

                                $stripe_id = \storify\stripe::getStripe_id($default_group_id, "pay");
                                if(!$stripe_id){
                                    //stripe_id customer not exist, create stripe customer
                                    $create_result = \storify\stripe::createCustomer();

                                    if(isset($create_result["id"]) && $create_result["id"]){

                                        $stripe_id = $create_result["id"];

                                        \storify\stripe::updateStripe_id($default_group_id, $create_result["type"], $stripe_id);

                                        //print_r("new customer :".$stripe_id);
                                    }
                                }

                                $stripe_customer = \storify\stripe::getStripeCustomer($default_group_id);
                                if($stripe_id){

                                    if(isset($_POST["stripeToken"])){
                                        $result = \storify\stripe::addCard($stripe_id, $_POST["stripeToken"]);
                                    }

                                    $cards = \storify\stripe::getAllCardsByStripeID($stripe_id);

                                    if($cards && sizeof($cards->data)){
                                        //with cards

                                        //print_r($cards->data);
                                        foreach($cards->data as $key=>$value){
                                            if($value["id"] == $stripe_customer->default_source){
                                                ?>
                                                <div class="ccard_container">
                                            <div class="ccard default"><i class="fa fa-credit-card"></i> xxxx xxxx xxxx <?=$value["last4"]?> <span class="date"><?=$value["exp_month"]?> / <?=substr($value["exp_year"],-2)?></span> <span class="corg"><?=$value["brand"]?></span></div>
                                            <form action="" method="post" id="delete_card" style="display:inline-block">
                                                <input type="hidden" name="delete_card_id" value="<?=$value["id"]?>">
                                                <button type="submit" name="submit">Remove</button>
                                            </form>
                                            </div>
                                            
                                            <?php
                                            }else{
                                                ?>
                                                <div class="ccard_container">
                                            <div class="ccard"><i class="fa fa-credit-card"></i> xxxx xxxx xxxx <?=$value["last4"]?> <span class="date"><?=$value["exp_month"]?> / <?=substr($value["exp_year"],-2)?></span> <span class="corg"><?=$value["brand"]?></span> </div><form action="" method="post" class="setDefaultForm"><input type="hidden" value="<?=$value["id"]?>" name="default_source" > <button>Set as Default</button></form> </div>
                                            <?php
                                            }
                                        }
                                        //list out all cards so can manage
                                        /*
                                        if(!\storify\stripe::checkPayment($project_id)){
                                            //only charge if payment is not made

                                            //try charge user
                                            $charge_result = \Stripe\Charge::create(array(
                                              "amount" => 587432,
                                              "currency" => "sgd",
                                              "customer" => $stripe_id,
                                              "description" => "Charge for Project #1"
                                            ));

                                            if($charge_result->paid){
                                                \storify\stripe::insertPayment($charge_result->id, $charge_result->amount, $charge_result->description, $charge_result->balance_transaction, $current_user->ID, $project_id, $project_id."_all", $charge_result->receipt_url, $charge_result->source->fingerprint, json_encode($charge_result));
                                                print_r("payment done");
                                            }else{
                                                \storify\stripe::recordError("charge error for Project ".$project_id, json_encode($charge_result));
                                            }
                                        }else{
                                            print_r("payment already made");
                                        }
                                        */
                                        
                                    }else{
                                        if(!$default_group["invoice"]){
                                            ?><h2>No payment method is set, please enter credit card or request for invoice support: <small style="inline-block">use these card for testing 4000007020000003 4000004580000002 4000005540000008 4000001560000002</small></h2><?php
                                        }
                                    }
                                    ?>
                                    <form action="" method="post" id="payment-form">
                                      <div style="width:70%">
                                        <label for="card-element">
                                          Add Card
                                        </label>
                                        <div id="card-element">
                                          <!-- A Stripe Element will be inserted here. -->
                                        </div>

                                        <!-- Used to display form errors. -->
                                        <div id="card-errors" role="alert"></div>
                                      </div>

                                      <button>Add</button>
                                    </form>
                                    <?php

                                    if($cards && sizeof($cards->data)){

                                    }else{
                                        if(!$default_group["invoice"]){
                                            ?>
                                            <hr>
                                            <p>Request for invoice payment method, by sending email / filling form</p>
                                            <?php
                                        }else{
                                            ?>
                                            <hr>
                                            <h3>Invoice support is enabled</h3>
                                            <p>Enter credit card to change the payment source.</p>
                                            <?php
                                        }
                                    }
                                }else{
                                    print_r("cannot create Stripe Customer Account");
                                }

                                ?>
                                
                            </div>
                        <hr>
                        </div>
                    </div>
                </div>
                <!--end container-->
            </section>
            <!--end block-->
        </section>
    <?php include("page/component/footer.php"); ?>
    </div>
    <!--end page-->
    <script type="text/javascript">
        var stripe = Stripe('<?=STRIPE_PUBLIC_KEY?>');
        var elements = stripe.elements();

        $(function(){
            // Create an instance of the card Element.
            var style = {
              base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                  color: '#aab7c4'
                }
              },
              invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
              }
            };

            var card = elements.create('card', {style:style});

            // Add an instance of the card Element into the `card-element` <div>.
            card.mount('#card-element');

            // Handle real-time validation errors from the card Element.
            card.addEventListener('change', function(event) {
              var displayError = document.getElementById('card-errors');
              if (event.error) {
                displayError.textContent = event.error.message;
              } else {
                displayError.textContent = '';
              }
            });

            // Handle form submission.
            var form = document.getElementById('payment-form');
            form.addEventListener('submit', function(event) {
              event.preventDefault();

              stripe.createToken(card).then(function(result) {
                if (result.error) {
                  // Inform the user if there was an error.
                  var errorElement = document.getElementById('card-errors');
                  errorElement.textContent = result.error.message;
                } else {
                  // Send the token to your server.
                  stripeTokenHandler(result.token);
                }
              });
            });

            // Submit the form with the token ID.
            function stripeTokenHandler(token) {
              // Insert the token ID into the form so it gets submitted to the server
              var form = document.getElementById('payment-form');
              var hiddenInput = document.createElement('input');
              hiddenInput.setAttribute('type', 'hidden');
              hiddenInput.setAttribute('name', 'stripeToken');
              hiddenInput.setAttribute('value', token.id);
              form.appendChild(hiddenInput);

              // Submit the form
              form.submit();
            }
        });
    </script>
</body>
</html>