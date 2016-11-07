<?php
// Merchant key here as provided by Payu
$MERCHANT_KEY = "ibrBAwmy";

// Merchant Salt as provided by Payu
$SALT = "FOq8WsvPk4";

// End point - change to https://secure.payu.in for LIVE mode
$PAYU_BASE_URL = "https://secure.payu.in";
$surl = "http://vedkrishna.com/success.php";
$furl = "http://vedkrishna.com/failure.php";


$action = '';

$posted = array();
if(!empty($_POST)) {
    //print_r($_POST);
  foreach($_POST as $key => $value) {
    $posted[$key] = $value;

  }
}

$formError = 0;

if(empty($posted['txnid'])) {
  // Generate random transaction id
  $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
} else {
  $txnid = $posted['txnid'];
}
$hash = '';
// Hash Sequence
$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
if(empty($posted['hash']) && sizeof($posted) > 0) {
  if(
          empty($posted['key'])
          || empty($posted['txnid'])
          || empty($posted['amount'])
          || empty($posted['firstname'])
          || empty($posted['email'])
          || empty($posted['phone'])
          || empty($posted['productinfo'])
          || empty($posted['surl'])
          || empty($posted['furl'])
          || empty($posted['service_provider'])
  ) {
    $formError = 1;
  } else {
    //$posted['productinfo'] = json_encode(json_decode('[{"name":"tutionfee","description":"","value":"500","isRequired":"false"},{"name":"developmentfee","description":"monthly tution fee","value":"1500","isRequired":"false"}]'));
    $hashVarsSeq = explode('|', $hashSequence);
    $hash_string = '';
    foreach($hashVarsSeq as $hash_var) {
      $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
      $hash_string .= '|';
    }

    $hash_string .= $SALT;


    $hash = strtolower(hash('sha512', $hash_string));
    $action = $PAYU_BASE_URL . '/_payment';
  }
} elseif(!empty($posted['hash'])) {
  $hash = $posted['hash'];
  $action = $PAYU_BASE_URL . '/_payment';
}
?>
<?php include 'partials/header.php';?>
    <!-- Hero Area -->
    <div class="hero-area">
    	<div class="page-banner parallax" style="background-image:url(images/parallax6.jpg);">
        	<div class="container">
            	<div class="page-banner-text">
        			<h1 class="block-title">Donate Us</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content -->
    <div id="main-container">
    	<div class="content">
        	<div class="container">
        		<div class="row">


                	<div class="col-md-12 col-sm-12">

                    <div >
    <div >
        <div class="modal-content">
            <div class="modal-header">

                <div class="row">
                  <!--  <div class="col-md-6 col-sm-6 donate-amount-option">
                        <h4>Choose an amount</h4>
                        <ul class="predefined-amount">
                            <li><label><input type="radio" name="donation-amount">$10</label></li>
                            <li><label><input type="radio" name="donation-amount">$20</label></li>
                            <li><label><input type="radio" name="donation-amount">$30</label></li>
                            <li><label><input type="radio" name="donation-amount">$50</label></li>
                            <li><label><input type="radio" name="donation-amount">$100</label></li>
                        </ul>
                    </div> -->
               <form method="post" action="<?php echo $action; ?>" name="payuForm">
                <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
                <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
                <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
                    <div class="col-md-6 col-sm-6 donate-amount-option">
                        <h4>Enter your own</h4>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">₹</span>
                            <input type="number" class="form-control" name="amount" value="<?php echo (empty($posted['amount'])) ? '' : $posted['amount'] ?>" placeholder="Amount in INR"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                                    <div class="col-md-6 col-sm-6 donation-form-infocol">
                        <h4>Personal info</h4>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo (empty($posted['firstname'])) ? '' : $posted['firstname']; ?>" placeholder="First Name"/>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo (empty($posted['lastname'])) ? '' : $posted['lastname']; ?>" placeholder="Last Name"/>
                            </div>
                        </div>

           <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6">
           <input name="email" id="email" class="form-control"  value="<?php echo (empty($posted['email'])) ? '' : $posted['email']; ?>" placeholder="Email address" />
           </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
           <input name="phone" class="form-control"  value="<?php echo (empty($posted['phone'])) ? '' : $posted['phone']; ?>" placeholder="Phone Number"/>
 </div>
                        </div>

           <input type="hidden" name="productinfo" value="<?php echo $productinfo ?>" />
           <!--input  type="hidden" name="surl" value="<?php echo (empty($posted['surl'])) ? '' : $posted['surl'] ?>" size="64" />
           <input  type="hidden" name="furl" value="<?php echo (empty($posted['furl'])) ? '' : $posted['furl'] ?>" size="64" /-->

           <input  type="hidden" name="surl" value="<?php echo $surl ?>" size="64" />
           <input  type="hidden" name="furl" value="<?php echo $furl ?>" size="64" />

           <input type="hidden" name="service_provider" value="payu_paisa" size="64" />
                        <!--input type="text" class="form-control" name="email" placeholder="Email address">
                        <input type="text" class="form-control" name="contact" placeholder="Phone no."-->

                    </div>
                    <div class="col-md-6 col-sm-6 donation-form-infocol">
                        <h4>Address</h4>
                        <input type="text" name="a1" class="form-control" placeholder="Address line 1">
                        <input type="text" name="a2" class="form-control" placeholder="Address line 2">
                        <div class="row">
                            <div class="col-md-8 col-sm-8 col-xs-8">
                                <input type="text" name="sc" class="form-control" placeholder="State/City">
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4">
                                <input type="text" name="zipc" class="form-control" placeholder="Zipcode">
                            </div>
                        </div>
                      <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <label>Country</label>
                            </div>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                                <select name="country" class="selectpicker">
                                    <option>India</option>
                                </select>
                            </div>
                        </div>
                    </div>

                 </div>
            </div>
            <div class="modal-footer text-align-center">
                <!--button type="submit" name="subs" class="btn btn-primary">Make your donation now</button-->
                <input class="btn btn-primary" type="submit" value="Submit" />
                <div class="spacer-20"></div>
                <p ></p>
            </div>
        </div>
    </div>
</div>
</form>

                   	</div>

               	</div>


                <!-- <div class="spacer-30"></div>

                <div class="row">
                	<div class="col-md-5 col-sm-5">
                		<h2>Our Staff &amp; Volunteers</h2>
                        <hr class="sm">
                       	<p>Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat</p>
                    </div>
                    <div class="col-md-7 col-sm-7">
                    	<div class="row">
                        	<div class="col-ms-4 col-sm-4 col-xs-4">
                                <ul class="carets">
                                    <li>Adamu Makinwa</li>
                                    <li>Casper Lundin</li>
                                    <li>Thomas Gagné</li>
                                    <li>Christina Morgan </li>
                                    <li>Markovics Zoltán </li>
                                    <li>Jacolien Hendriks</li>
                                </ul>
                           	</div>
                        	<div class="col-ms-4 col-sm-4 col-xs-4">
                                <ul class="carets">
                                    <li>Isabela Barboza </li>
                                    <li>Juhani Virtanen </li>
                                    <li>Phan Châu</li>
                                    <li>Kuzey Ünal</li>
                                    <li>Juan Rubio</li>
                                    <li>Marko Mlakar</li>
                                </ul>
                           	</div>
                        	<div class="col-ms-4 col-sm-4 col-xs-4">
                                <ul class="carets">
                                    <li>Kelly Lambert</li>
                                    <li>Walid Ahelluc</li>
                                    <li>Ernst Graf</li>
                                    <li>Lore Smets</li>
                                    <li>Camiel de Graaf</li>
                                    <li>Ladislau Berindei</li>
                                </ul>
                           	</div>
                        </div>
                    </div>
                </div>
                <div class="spacer-20"></div>
                <div class="row">
                	<div class="col-md-4 col-sm-4">
                    	<div class="grid-item grid-staff-item">
                            <div class="grid-item-inner">
                              	<div class="media-box"><img src="images/staff1.jpg" alt=""></div>
                              	<div class="grid-item-content">
                                	<h3>Tayri awragh</h3>
                                    <span class="meta-data">CEO/Founder</span>
                                	<ul class="social-icons-rounded social-icons-colored">
                                    	<li class="facebook"><a href="#"><i class="fa fa-facebook"></i></a></li>
                                        <li class="twitter"><a href="#"><i class="fa fa-twitter"></i></a></li>
                                        <li class="googleplus"><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                        <li class="pinterest"><a href="#"><i class="fa fa-pinterest"></i></a></li>
                                  	</ul>
                                	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla convallis egestas rhoncus. Donec facilisis fermentum sem, ac viverra ante luctus vel. Donec vel mauris quam.</p>
                              	</div>
                            </div>
                       	</div>
                    </div>
                	<div class="col-md-4 col-sm-4">
                    	<div class="grid-item grid-staff-item">
                            <div class="grid-item-inner">
                              	<div class="media-box"><img src="images/post1.jpg" alt=""></div>
                              	<div class="grid-item-content">
                                	<h3>Howard Porter</h3>
                                    <span class="meta-data">Education Campaigns Manager</span>
                                	<ul class="social-icons-rounded social-icons-colored">
                                    	<li class="facebook"><a href="#"><i class="fa fa-facebook"></i></a></li>
                                        <li class="twitter"><a href="#"><i class="fa fa-twitter"></i></a></li>
                                        <li class="googleplus"><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                        <li class="pinterest"><a href="#"><i class="fa fa-pinterest"></i></a></li>
                                  	</ul>
                                	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla convallis egestas rhoncus. Donec facilisis fermentum sem, ac viverra ante luctus vel. Donec vel mauris quam.</p>
                              	</div>
                            </div>
                       	</div>
                    </div>
                	<div class="col-md-4 col-sm-4">
                    	<div class="grid-item grid-staff-item">
                            <div class="grid-item-inner">
                              	<div class="media-box"><img src="images/staff2.jpg" alt=""></div>
                              	<div class="grid-item-content">
                                	<h3>Ayoub Ameqran</h3>
                                    <span class="meta-data">Environment Campaigns Manager</span>
                                	<ul class="social-icons-rounded social-icons-colored">
                                    	<li class="facebook"><a href="#"><i class="fa fa-facebook"></i></a></li>
                                        <li class="twitter"><a href="#"><i class="fa fa-twitter"></i></a></li>
                                        <li class="googleplus"><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                        <li class="pinterest"><a href="#"><i class="fa fa-pinterest"></i></a></li>
                                  	</ul>
                                	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla convallis egestas rhoncus. Donec facilisis fermentum sem, ac viverra ante luctus vel. Donec vel mauris quam.</p>
                              	</div>
                            </div>
                       	</div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>

    </div>
    <!-- Site Footer -->
<?php include 'partials/footer.php';?>
