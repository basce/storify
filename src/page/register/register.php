<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
    <link rel="stylesheet" href="/assets/css/main.css">
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
<?=get_option("custom_settings_header_js")?>
    <style type="text/css">
        .selectize-control.customselect{
            padding:0;
        }
        .selectize-control.customselect .selectize-input.items{
            box-shadow: none;
            border: none;
        }
    </style>
<body>
    <div class="page sub-page">
        <header class="hero">
            <div class="hero-wrapper">
<?php 
$header_without_toggle_button = true;
include("page/component/header.php"); ?>
                <div class="page-title">
                    <div class="container">
                        <h1>Sign up</h1>
                        <h2>This is the start to many great stories.</h2>
                    </div>
                    <!--end container-->
                </div>
            </div>
        </header>
        <section class="content">
            <section class="block">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8">
                            <form class="form clearfix custommsg storify-form" novalidate="novalidate" id="registration_form" method="post">
                                <!--end form-group-->
                                <div class="form-group">
                                    <div class="input-group-row">
                                        <label for="name" class="col-form-label required">Enter your name</label>
                                        <input name="name" type="text" class="form-control" id="name" placeholder="Name">
                                    </div>
                                    <div class="alert alert-danger hide">This does not appear to be a valid email address. Enter again?</div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group-row">
                                        <label for="email" class="col-form-label required">Enter your email address</label>
                                        <input name="email" type="email" class="form-control" id="email" placeholder="Email">
                                    </div>
                                    <div class="alert alert-danger hide"></div>
                                </div>
                                <!--end form-group-->
                                <div class="form-group">
                                    <div class="input-group-row">
                                        <label for="password" class="col-form-label required">Enter your password</label>
                                        <input name="password" type="password" class="form-control" id="password" placeholder="Password">
                                    </div>
                                    <div class="alert alert-danger hide"></div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group-row">
                                        <label for="password" class="col-form-label required">Repeat your password</label>
                                        <input name="repeatpassword" type="password" class="form-control" id="repeatpassword" placeholder="Repeat to confirm">
                                    </div>
                                    <div class="alert alert-danger hide"></div>
                                </div>
                                <!--end form-group-->
                                <div class="form-group">
                                    <div class="input-group-row">
                                        <label class="col-form-label required">Gender</label>
                                        <figure>
                                            <label>
                                                <input type="radio" name="gender" value="male" >
                                                Male
                                            </label>
                                            <label>
                                                <input type="radio" name="gender" value="female" >
                                                Female
                                            </label>
                                        </figure>
                                    </div>
                                    <div class="alert alert-danger hide">This does not appear to be a valid email address. Enter again?</div>
                                </div>
                                <!--end form-group-->
                                <div class="form-group">
                                    <div class="input-group-row">
                                        <label for="citycountry" class="col-form-label required">Country / City</label>
                                        <select name="citycountry" type="text" class="customselect form-control" id="citycountry" data-placeholder="Select country..." single>
                                            <option value="">Select country...</option>
                                            <option value="AF">Afghanistan</option>
                                            <option value="AX">Åland Islands</option>
                                            <option value="AL">Albania</option>
                                            <option value="DZ">Algeria</option>
                                            <option value="AS">American Samoa</option>
                                            <option value="AD">Andorra</option>
                                            <option value="AO">Angola</option>
                                            <option value="AI">Anguilla</option>
                                            <option value="AQ">Antarctica</option>
                                            <option value="AG">Antigua and Barbuda</option>
                                            <option value="AR">Argentina</option>
                                            <option value="AM">Armenia</option>
                                            <option value="AW">Aruba</option>
                                            <option value="AU">Australia</option>
                                            <option value="AT">Austria</option>
                                            <option value="AZ">Azerbaijan</option>
                                            <option value="BS">Bahamas</option>
                                            <option value="BH">Bahrain</option>
                                            <option value="BD">Bangladesh</option>
                                            <option value="BB">Barbados</option>
                                            <option value="BY">Belarus</option>
                                            <option value="BE">Belgium</option>
                                            <option value="BZ">Belize</option>
                                            <option value="BJ">Benin</option>
                                            <option value="BM">Bermuda</option>
                                            <option value="BT">Bhutan</option>
                                            <option value="BO">Bolivia, Plurinational State of</option>
                                            <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                            <option value="BA">Bosnia and Herzegovina</option>
                                            <option value="BW">Botswana</option>
                                            <option value="BV">Bouvet Island</option>
                                            <option value="BR">Brazil</option>
                                            <option value="IO">British Indian Ocean Territory</option>
                                            <option value="BN">Brunei Darussalam</option>
                                            <option value="BG">Bulgaria</option>
                                            <option value="BF">Burkina Faso</option>
                                            <option value="BI">Burundi</option>
                                            <option value="KH">Cambodia</option>
                                            <option value="CM">Cameroon</option>
                                            <option value="CA">Canada</option>
                                            <option value="CV">Cape Verde</option>
                                            <option value="KY">Cayman Islands</option>
                                            <option value="CF">Central African Republic</option>
                                            <option value="TD">Chad</option>
                                            <option value="CL">Chile</option>
                                            <option value="CN">China</option>
                                            <option value="CX">Christmas Island</option>
                                            <option value="CC">Cocos (Keeling) Islands</option>
                                            <option value="CO">Colombia</option>
                                            <option value="KM">Comoros</option>
                                            <option value="CG">Congo</option>
                                            <option value="CD">Congo, the Democratic Republic of the</option>
                                            <option value="CK">Cook Islands</option>
                                            <option value="CR">Costa Rica</option>
                                            <option value="CI">Côte d'Ivoire</option>
                                            <option value="HR">Croatia</option>
                                            <option value="CU">Cuba</option>
                                            <option value="CW">Curaçao</option>
                                            <option value="CY">Cyprus</option>
                                            <option value="CZ">Czech Republic</option>
                                            <option value="DK">Denmark</option>
                                            <option value="DJ">Djibouti</option>
                                            <option value="DM">Dominica</option>
                                            <option value="DO">Dominican Republic</option>
                                            <option value="EC">Ecuador</option>
                                            <option value="EG">Egypt</option>
                                            <option value="SV">El Salvador</option>
                                            <option value="GQ">Equatorial Guinea</option>
                                            <option value="ER">Eritrea</option>
                                            <option value="EE">Estonia</option>
                                            <option value="ET">Ethiopia</option>
                                            <option value="FK">Falkland Islands (Malvinas)</option>
                                            <option value="FO">Faroe Islands</option>
                                            <option value="FJ">Fiji</option>
                                            <option value="FI">Finland</option>
                                            <option value="FR">France</option>
                                            <option value="GF">French Guiana</option>
                                            <option value="PF">French Polynesia</option>
                                            <option value="TF">French Southern Territories</option>
                                            <option value="GA">Gabon</option>
                                            <option value="GM">Gambia</option>
                                            <option value="GE">Georgia</option>
                                            <option value="DE">Germany</option>
                                            <option value="GH">Ghana</option>
                                            <option value="GI">Gibraltar</option>
                                            <option value="GR">Greece</option>
                                            <option value="GL">Greenland</option>
                                            <option value="GD">Grenada</option>
                                            <option value="GP">Guadeloupe</option>
                                            <option value="GU">Guam</option>
                                            <option value="GT">Guatemala</option>
                                            <option value="GG">Guernsey</option>
                                            <option value="GN">Guinea</option>
                                            <option value="GW">Guinea-Bissau</option>
                                            <option value="GY">Guyana</option>
                                            <option value="HT">Haiti</option>
                                            <option value="HM">Heard Island and McDonald Islands</option>
                                            <option value="VA">Holy See (Vatican City State)</option>
                                            <option value="HN">Honduras</option>
                                            <option value="HK">Hong Kong</option>
                                            <option value="HU">Hungary</option>
                                            <option value="IS">Iceland</option>
                                            <option value="IN">India</option>
                                            <option value="ID">Indonesia</option>
                                            <option value="IR">Iran, Islamic Republic of</option>
                                            <option value="IQ">Iraq</option>
                                            <option value="IE">Ireland</option>
                                            <option value="IM">Isle of Man</option>
                                            <option value="IL">Israel</option>
                                            <option value="IT">Italy</option>
                                            <option value="JM">Jamaica</option>
                                            <option value="JP">Japan</option>
                                            <option value="JE">Jersey</option>
                                            <option value="JO">Jordan</option>
                                            <option value="KZ">Kazakhstan</option>
                                            <option value="KE">Kenya</option>
                                            <option value="KI">Kiribati</option>
                                            <option value="KP">Korea, Democratic People's Republic of</option>
                                            <option value="KR">Korea, Republic of</option>
                                            <option value="KW">Kuwait</option>
                                            <option value="KG">Kyrgyzstan</option>
                                            <option value="LA">Lao People's Democratic Republic</option>
                                            <option value="LV">Latvia</option>
                                            <option value="LB">Lebanon</option>
                                            <option value="LS">Lesotho</option>
                                            <option value="LR">Liberia</option>
                                            <option value="LY">Libya</option>
                                            <option value="LI">Liechtenstein</option>
                                            <option value="LT">Lithuania</option>
                                            <option value="LU">Luxembourg</option>
                                            <option value="MO">Macao</option>
                                            <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                                            <option value="MG">Madagascar</option>
                                            <option value="MW">Malawi</option>
                                            <option value="MY">Malaysia</option>
                                            <option value="MV">Maldives</option>
                                            <option value="ML">Mali</option>
                                            <option value="MT">Malta</option>
                                            <option value="MH">Marshall Islands</option>
                                            <option value="MQ">Martinique</option>
                                            <option value="MR">Mauritania</option>
                                            <option value="MU">Mauritius</option>
                                            <option value="YT">Mayotte</option>
                                            <option value="MX">Mexico</option>
                                            <option value="FM">Micronesia, Federated States of</option>
                                            <option value="MD">Moldova, Republic of</option>
                                            <option value="MC">Monaco</option>
                                            <option value="MN">Mongolia</option>
                                            <option value="ME">Montenegro</option>
                                            <option value="MS">Montserrat</option>
                                            <option value="MA">Morocco</option>
                                            <option value="MZ">Mozambique</option>
                                            <option value="MM">Myanmar</option>
                                            <option value="NA">Namibia</option>
                                            <option value="NR">Nauru</option>
                                            <option value="NP">Nepal</option>
                                            <option value="NL">Netherlands</option>
                                            <option value="NC">New Caledonia</option>
                                            <option value="NZ">New Zealand</option>
                                            <option value="NI">Nicaragua</option>
                                            <option value="NE">Niger</option>
                                            <option value="NG">Nigeria</option>
                                            <option value="NU">Niue</option>
                                            <option value="NF">Norfolk Island</option>
                                            <option value="MP">Northern Mariana Islands</option>
                                            <option value="NO">Norway</option>
                                            <option value="OM">Oman</option>
                                            <option value="PK">Pakistan</option>
                                            <option value="PW">Palau</option>
                                            <option value="PS">Palestinian Territory, Occupied</option>
                                            <option value="PA">Panama</option>
                                            <option value="PG">Papua New Guinea</option>
                                            <option value="PY">Paraguay</option>
                                            <option value="PE">Peru</option>
                                            <option value="PH">Philippines</option>
                                            <option value="PN">Pitcairn</option>
                                            <option value="PL">Poland</option>
                                            <option value="PT">Portugal</option>
                                            <option value="PR">Puerto Rico</option>
                                            <option value="QA">Qatar</option>
                                            <option value="RE">Réunion</option>
                                            <option value="RO">Romania</option>
                                            <option value="RU">Russian Federation</option>
                                            <option value="RW">Rwanda</option>
                                            <option value="BL">Saint Barthélemy</option>
                                            <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                                            <option value="KN">Saint Kitts and Nevis</option>
                                            <option value="LC">Saint Lucia</option>
                                            <option value="MF">Saint Martin (French part)</option>
                                            <option value="PM">Saint Pierre and Miquelon</option>
                                            <option value="VC">Saint Vincent and the Grenadines</option>
                                            <option value="WS">Samoa</option>
                                            <option value="SM">San Marino</option>
                                            <option value="ST">Sao Tome and Principe</option>
                                            <option value="SA">Saudi Arabia</option>
                                            <option value="SN">Senegal</option>
                                            <option value="RS">Serbia</option>
                                            <option value="SC">Seychelles</option>
                                            <option value="SL">Sierra Leone</option>
                                            <option value="SG">Singapore</option>
                                            <option value="SX">Sint Maarten (Dutch part)</option>
                                            <option value="SK">Slovakia</option>
                                            <option value="SI">Slovenia</option>
                                            <option value="SB">Solomon Islands</option>
                                            <option value="SO">Somalia</option>
                                            <option value="ZA">South Africa</option>
                                            <option value="GS">South Georgia and the South Sandwich Islands</option>
                                            <option value="SS">South Sudan</option>
                                            <option value="ES">Spain</option>
                                            <option value="LK">Sri Lanka</option>
                                            <option value="SD">Sudan</option>
                                            <option value="SR">Suriname</option>
                                            <option value="SJ">Svalbard and Jan Mayen</option>
                                            <option value="SZ">Swaziland</option>
                                            <option value="SE">Sweden</option>
                                            <option value="CH">Switzerland</option>
                                            <option value="SY">Syrian Arab Republic</option>
                                            <option value="TW">Taiwan, Province of China</option>
                                            <option value="TJ">Tajikistan</option>
                                            <option value="TZ">Tanzania, United Republic of</option>
                                            <option value="TH">Thailand</option>
                                            <option value="TL">Timor-Leste</option>
                                            <option value="TG">Togo</option>
                                            <option value="TK">Tokelau</option>
                                            <option value="TO">Tonga</option>
                                            <option value="TT">Trinidad and Tobago</option>
                                            <option value="TN">Tunisia</option>
                                            <option value="TR">Turkey</option>
                                            <option value="TM">Turkmenistan</option>
                                            <option value="TC">Turks and Caicos Islands</option>
                                            <option value="TV">Tuvalu</option>
                                            <option value="UG">Uganda</option>
                                            <option value="UA">Ukraine</option>
                                            <option value="AE">United Arab Emirates</option>
                                            <option value="GB">United Kingdom</option>
                                            <option value="US">United States</option>
                                            <option value="UM">United States Minor Outlying Islands</option>
                                            <option value="UY">Uruguay</option>
                                            <option value="UZ">Uzbekistan</option>
                                            <option value="VU">Vanuatu</option>
                                            <option value="VE">Venezuela, Bolivarian Republic of</option>
                                            <option value="VN">Viet Nam</option>
                                            <option value="VG">Virgin Islands, British</option>
                                            <option value="VI">Virgin Islands, U.S.</option>
                                            <option value="WF">Wallis and Futuna</option>
                                            <option value="EH">Western Sahara</option>
                                            <option value="YE">Yemen</option>
                                            <option value="ZM">Zambia</option>
                                            <option value="ZW">Zimbabwe</option>
                                        </select>
                                    </div>
                                    <div class="alert alert-danger hide">Please select your gender</div>
                                </div>
                                <!--end form-group-->
                                <div class="d-flex justify-content-between align-items-baseline">
                                    
                                    <label>
                                        <!--
                                        <div class="icheckbox"><input type="checkbox" name="newsletter" value="1" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                                        Receive Newsletter -->
                                    </label>
                                
                                    <button type="submit" class="btn btn-primary">Sign Up</button>
                                </div>
                            </form>
                            <!--
                            <hr>
                            <p>
                                By clicking "Register" button, you agree with our <a href="#" class="link">Terms &amp; Conditions.</a>
                            </p>
                        -->
                        </div>
                        <!--end col-md-6-->
                    </div>
                    <!--end row-->
                </div>
                <!--end container-->
            </section>
        </section>
        <script type="text/javascript">
            $(function(){

                $("#citycountry").selectize({
                    create: false,
                    sortField: 'text'
                });
                function isEmail(b){var a=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;if(a.test(b)){return true}else{return false}}
                function scorePassword(pass) {
                    var score = 0;
                    if (!pass)
                        return score;

                    // award every unique letter until 5 repetitions
                    var letters = new Object();
                    for (var i=0; i<pass.length; i++) {
                        letters[pass[i]] = (letters[pass[i]] || 0) + 1;
                        score += 5.0 / letters[pass[i]];
                    }

                    // bonus points for mixing it up
                    var variations = {
                        digits: /\d/.test(pass),
                        lower: /[a-z]/.test(pass),
                        upper: /[A-Z]/.test(pass),
                        nonWords: /\W/.test(pass),
                    }

                    variationCount = 0;
                    for (var check in variations) {
                        variationCount += (variations[check] == true) ? 1 : 0;
                    }
                    score += (variationCount - 1) * 10;

                    return parseInt(score);
                }

                $("#password").on('input',function(e){
                    if($("#password").val().length){
                        $("#password").parents(".form-group").find(".alert").removeClass("alert-success alert-warning alert-danger");
                        var score = scorePassword($("#password").val());
                        if(score > 80){
                            $("#password").parents(".form-group").find(".alert").addClass("alert-success").text("Password Strength : Strong");
                        }else if(score >50){
                            $("#password").parents(".form-group").find(".alert").addClass("alert-warning").text("Password Strength : Medium");
                        }else{
                            $("#password").parents(".form-group").find(".alert").addClass("alert-danger").text("Password Strength : Weak");
                        }
                        $("#password").parents(".form-group").find(".alert").removeClass("hide");
                    }else{
                        $("#password").parents(".form-group").find(".alert").addClass("hide");
                    }
                });

                $("#email").change(function(e){

                    var inputemail = $("#email").val();
                    if(isEmail(inputemail)){
                        $(".foremail.error").addClass("hide");
                        //ajax check is email
                        $.ajax({
                            url:"/register/ajax",
                            method:'POST',
                            data:{
                                method:"uniqueemail",
                                email:inputemail
                            },
                            success:function(data){
                                if(data.exist){
                                    $("#email").parents(".form-group").find(".alert").text(inputemail + " is already registered.").removeClass("hide");
                                }else{
                                    $("#email").parents(".form-group").find(".alert").addClass("hide");
                                }
                            },
                            dataType:"json"
                        });
                    }else{
                        $("#email").parents(".form-group").find(".alert").text("This does not appear to be a valid email address. Enter again?").removeClass("hide");
                    }
                });

                $("#name").change(function(e){
                    if($("#name").val().length < 1){
                        $("#name").parents(".form-group").find(".alert").text("We will like to know how to address you. Enter again?").removeClass("hide");
                    }else{
                        $("#name").parents(".form-group").find(".alert").removeClass("hide");
                    }
                });

                $("input[name='gender']").on("ifChecked", function(e){
                    if(!$("input[name='gender']:checked").length){
                        $("input[name='gender']").parents(".form-group").find(".alert").text("Please select your gender").removeClass("hide");
                    }else{
                        $("input[name='gender']").parents(".form-group").find(".alert").addClass("hide");
                    }
                });

                $("#citycountry").change(function(e){
                    if($("#citycountry").val() == ""){
                        er++;
                        $("#citycountry").parents(".alert").text("Please select your country").removeClass("hide");
                    }else{
                        $("#citycountry").parents(".alert").addClass("hide");
                    }
                });

                function verifyfields(){
                    var er = 0;
                    //email
                    if($("#email").parents(".form-group").find(".alert").is(":visible")){
                        er++;
                    }else{
                        if($("#email").val() == ""){
                            //empty
                            er++;
                            $("#email").parents(".form-group").find(".alert").text("This does not appear to be a valid email address. Enter again?").removeClass("hide");
                        }
                    }

                    //check password
                    if($("#password").val().length < 6){
                        er++;
                        $("#password").parents(".form-group").find(".alert").text("Please key in your password (min: 6 chars)").removeClass("hide");
                    }else{
                        if($("#repeatpassword").val().length == 0){
                            er++;
                            $("#password").parents(".form-group").find(".alert").text("Please enter your password again.").removeClass("hide");
                        }else if($("#password").val() !== $("#repeatpassword").val()){
                            er++;
                            $("#repeatpassword").parents(".form-group").find(".alert").text("Please confirm that your password is correct.").removeClass("hide");
                        }else{
                            $("#repeatpassword").parents(".form-group").find(".alert").addClass("hide");
                        }
                    }


                    //check name
                    if($("#name").val().length < 1){
                        er++;
                        $("#name").parents(".form-group").find(".alert").text("We will like to know how to address you. Enter again?").removeClass("hide");
                    }else{
                        $("#name").parents(".form-group").find(".alert").addClass("hide");
                    }

                    //check Gender
                    if(!$("input[name='gender']:checked").length){
                        er++;
                        $("input[name='gender']").parents(".form-group").find(".alert").text("Please select your gender").removeClass("hide");
                    }else{
                        $("input[name='gender']").parents(".form-group").find(".alert").addClass("hide");
                    }

                    //check country
                    if($("#citycountry").val() == ""){
                        er++;
                        $("#citycountry").parents(".form-group").find(".alert").text("Please select your country").removeClass("hide");
                    }else{
                        $("#citycountry").parents(".form-group").find(".alert").addClass("hide");
                    }                    

                    return er;
                }

                $("#registration_form").submit(function(e){
                    //validation
                    if(verifyfields()){
                        e.preventDefault();
                    }
                });
            });
        </script>
        <?php include("page/component/footer.php"); ?>
    </div>
</body>
</html>
