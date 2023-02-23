<?php
/**
 * Template Name: Landing page
 */
?>

<?php get_header(); ?>

<header class="header">
    <div class="container">
        <div class="header__wrapper">
            <button class="header__prev"></button>
            <button class="header__next"></button>

            <div class="header__logo">
                <?php
                $header_logo = get_field('header_logo');
                ?>
                <img class="header__logo__img" src="<?= $header_logo['url']; ?>" alt="<?= $header_logo['alt']; ?>">
            </div>

            <input id="firstStep" type="hidden" value="<?php echo (!empty($_GET['step']) ? $_GET['step'] : 1) ?>">


            <form class="header__form form js_main_form">
                <div data-step="1" class="form__item">
                    <input type="hidden" class="js_input_zodiac_sign" name="zodiac-sign" value="">
                    <p class="form__item__title"><?php the_field('step_1_title'); ?></p>
                    <?php
                    // Remove on prod. START
                    ?>
                    <div style="display: none;" class="stpl_test">
                        <?php
                        $userId = 621073;
                        $apiKey = 'ba5239f850ff07221db978bb5c6bde5e';

                        $data = array(
                            'date' => 25,
                            'month' => 12,
                            'year' => 1988,
                            'hour' => 4,
                            'minute' => 0,
                            'latitude' => 25.123,
                            'longitude' => 82.34,
                            'timezone' => 5.5
                        );
                        //$resourceName = "personality_report/tropical";
                        $resourceName = "general_ascendant_report/tropical";
                        //$resourceName = "natal_wheel_chart";
                        
                        //                        $vedicRishi = new VedicRishiClient($userId, $apiKey);
//                        $responseData = $vedicRishi->call($resourceName, $data['date'], $data['month'], $data['year'], $data['hour'], $data['minute'], $data['latitude'], $data['longitude'], $data['timezone']);
//                        $data = json_decode($responseData, true);
//                        print_r($data);
                        

                        //$curl = curl_init();
                        
                        // Test data
                        //$api_url = 'https://freelance1669134722.api-us1.com';
                        //$api_key = 'dd07151b83b5c4f14273f76824a45699128abaac0fcb9e408ac61e65094e66e1d0138d94';
                        
                        // Clients data
                        $api_url = 'https://bestday.api-us1.com';
                        $api_key = '3c64b291a8857acf08f037c2a16bff969429fda5b16820f986922bbea4f0f111c60e1474';

                        // Get custom fields
//                                                curl_setopt_array($curl, [
//                                                    CURLOPT_URL => "{$api_url}/api/3/fields?limit=100",
//                                                    CURLOPT_RETURNTRANSFER => true,
//                                                    CURLOPT_ENCODING => "",
//                                                    CURLOPT_MAXREDIRS => 10,
//                                                    CURLOPT_TIMEOUT => 30,
//                                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                                                    CURLOPT_CUSTOMREQUEST => "GET",
//                                                    CURLOPT_HTTPHEADER => [
//                                                        "Api-Token: $api_key",
//                                                        "accept: application/json"
//                                                    ],
//                                                ]);
                        

                        // Get list
                        //                        curl_setopt_array($curl, [
                        //                            CURLOPT_URL => "{$api_url}/api/3/lists",
                        //                            CURLOPT_RETURNTRANSFER => true,
                        //                            CURLOPT_ENCODING => "",
                        //                            CURLOPT_MAXREDIRS => 10,
                        //                            CURLOPT_TIMEOUT => 30,
                        //                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        //                            CURLOPT_CUSTOMREQUEST => "GET",
                        //                            CURLOPT_HTTPHEADER => [
                        //                                "Api-Token: $api_key",
                        //                                "accept: application/json"
                        //                            ],
                        //                        ]);
                        
                        // Get tag
                        //                        curl_setopt_array($curl, [
                        //                            CURLOPT_URL => "{$api_url}/api/3/tags",
                        //                            CURLOPT_RETURNTRANSFER => true,
                        //                            CURLOPT_ENCODING => "",
                        //                            CURLOPT_MAXREDIRS => 10,
                        //                            CURLOPT_TIMEOUT => 30,
                        //                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        //                            CURLOPT_CUSTOMREQUEST => "GET",
                        //                            CURLOPT_HTTPHEADER => [
                        //                                "Api-Token: $api_key",
                        //                                "accept: application/json"
                        //                            ],
                        //                        ]);
                        




                        //                                                $response = curl_exec($curl);
//                                                $err = curl_error($curl);
//
//                                                curl_close($curl);
//
//                                                if ($err) {
//                                                    echo "cURL Error #:" . $err;
//                                                } else {
//                                                    $data = json_decode($response, true);
//                                                    print_r($data);
//                                                }
                        ?>
                    </div>
                    <?php
                    // Remove on prod. END
                    ?>
                    <div class="form__item__zodiac">
                        <?php
                        $zodiac_signs = get_field('step_1_zodiac_signs');
                        if ($zodiac_signs) {
                            $i = 1;
                            foreach ($zodiac_signs as $item) {
                                $img = $item['image'];
                                ?>
                                        <a data-zodiac="<?= $i; ?>" class="form__item__zodiac__item js_choose_zodiac_sign"
                                           data-title="<?= $item['title']; ?>">
                                            <img src="<?= $img['url']; ?>" alt="<?= $img['alt']; ?>">
                                        </a>
                                        <?php
                                        $i++;
                            }
                        }
                        ?>
                    </div>
                </div>


                <div data-step="2" class="form__item">
                    <p class="form__item__title"><?php the_field('step_2_title'); ?></p>
                    <div class="form__item__birthday">
                        <?php if ($zodiac_signs): ?>
                                <?php
                                $i = 1;
                                foreach ($zodiac_signs as $item) {
                                    if ($i === 1) {
                                        $active_class = ' active';
                                    } else {
                                        $active_class = '';
                                    }
                                    $start_date_string = $item['start'];
                                    $end_date_string = $item['end'];
                                    $start_date_convert = DateTime::createFromFormat("d/m/Y", $start_date_string);
                                    $end_date_convert = DateTime::createFromFormat("d/m/Y", $end_date_string);

                                    $month_start = $start_date_convert->format("F");
                                    $month_start_number = $start_date_convert->format("m");
                                    $month_end = $end_date_convert->format("F");
                                    $month_end_number = $end_date_convert->format("m");

                                    $day_start = $start_date_convert->format("d");
                                    $day_end = $end_date_convert->format("d");

                                    $month_start_count_days = cal_days_in_month(CAL_GREGORIAN, $month_start_number, 2024);
                                    ?>
                                        <div data-zodiac="<?= $i; ?>" class="form__item__birthday__months<?= $active_class; ?>">
                                            <div class="form__item__birthday__month">
                                                <p class="form__item__birthday__month__title"><?= $month_start; ?></p>
                                                <div class="form__item__birthday__month__days">
                                                    <?php while ($day_start <= $month_start_count_days) { ?>
                                                            <a data-day="<?= $day_start . '-' . $month_start_number; ?>"
                                                               class="js_choose_birthday"><?= $day_start; ?></a>
                                                            <?php
                                                            $day_start++;
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="form__item__birthday__month">
                                                <p class="form__item__birthday__month__title"><?= $month_end; ?></p>
                                                <div class="form__item__birthday__month__days">
                                                    <?php
                                                    $i_day = 1;
                                                    while ($i_day <= $day_end) {
                                                        $day_for_crm = $i_day < 10 ? '0' . $i_day : $i_day;
                                                        ?>
                                                            <a data-day="<?= $day_for_crm . '-' . $month_end_number; ?>"
                                                               class="js_choose_birthday"><?= $i_day; ?></a>
                                                            <?php
                                                            $i_day++;
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $i++;
                                }
                                ?>
                        <?php endif; ?>
                    </div>
                </div>


                <div data-step="3" class="form__item">
                    <p class="form__item__title"><?php the_field('step_3_title'); ?></p>
                    <div class="form__item__decade">
                        <?php
                        $from_decade = get_field('step_3_from_decade');
                        $to_decade = get_field('step_3_to_decade');
                        $step_3_decade = $from_decade;

                        while ($step_3_decade <= $to_decade) { ?>
                                <a data-decade="<?= $step_3_decade; ?>"><?= $step_3_decade; ?></a>
                                <?php
                                $step_3_decade += 10;
                        }
                        ?>
                    </div>
                </div>


                <div data-step="4" class="form__item">
                    <input type="hidden" class="js_input_birthday" name="birthday" value="">
                    <p class="form__item__title"><?php the_field('step_4_title'); ?></p>
                    <div class="form__item__year">
                        <?php
                        $step_4_decade = $from_decade;
                        while ($step_4_decade <= $to_decade) {
                            if ($step_4_decade === $from_decade) {
                                $active_class = ' active';
                            } else {
                                $active_class = '';
                            }
                            ?>
                                <div data-decade="<?= $step_4_decade; ?>"
                                     class="form__item__year__wrapper<?= $active_class; ?>">
                                    <?php
                                    $step_4_decade_inner = $step_4_decade;
                                    while ($step_4_decade_inner < ($step_4_decade + 10)) { ?>
                                            <a data-year="<?= $step_4_decade_inner; ?>"
                                               class="js_choose_year_birth"><?= $step_4_decade_inner; ?></a>
                                            <?php
                                            $step_4_decade_inner++;
                                    }
                                    ?>
                                </div>
                                <?php
                                $step_4_decade += 10;
                        }
                        ?>
                    </div>
                </div>


                <div data-step="5" class="form__item">
                    <input type="hidden" class="js_input_time_of_birthday" name="time-of-birth" value="">
                    <p class="form__item__title"><?php the_field('step_5_title'); ?></p>
                    <div class="form__item__time">
                        <div class="form__item__time__top">
                            <div data-name="hour" class="form__item__time__hour custom_select">
                                <?php
                                $step_5_txt_hour = get_field('step_5_text_hour');
                                ?>
                                <p class="custom_select__current"><?= $step_5_txt_hour; ?></p>
                                <ul class="custom_select__list">
                                    <li data-val=""
                                        class="custom_select__item js_choose_hour_birth custom_select__item_active">
                                        <?= $step_5_txt_hour; ?>
                                    </li>
                                    <?php
                                    $step_5_hour_start = 1;
                                    while ($step_5_hour_start <= 12) {
                                        $step_5_hour_current = $step_5_hour_start < 10 ? '0' . $step_5_hour_start : $step_5_hour_start;
                                        ?>
                                            <li data-val="<?= $step_5_hour_current; ?>"
                                                class="custom_select__item js_choose_hour_birth"><?= $step_5_hour_current; ?></li>
                                            <?php
                                            $step_5_hour_start++;
                                    }
                                    ?>
                                </ul>
                            </div>

                            <div data-name="minute" class="form__item__time__minutes custom_select">
                                <?php
                                $step_5_txt_minute = get_field('step_5_text_minute');
                                ?>
                                <p class="custom_select__current"><?= $step_5_txt_minute; ?></p>
                                <ul class="custom_select__list">
                                    <li data-val=""
                                        class="custom_select__item js_choose_minute_birth custom_select__item_active">
                                        <?= $step_5_txt_minute; ?>
                                    </li>
                                    <?php
                                    $step_5_minute_start = 0;
                                    while ($step_5_minute_start <= 59) {
                                        $step_5_minute_current = $step_5_minute_start < 10 ? '0' . $step_5_minute_start : $step_5_minute_start;
                                        ?>
                                            <li data-val="<?= $step_5_minute_current; ?>"
                                                class="custom_select__item js_choose_minute_birth"><?= $step_5_minute_current; ?></li>
                                            <?php
                                            $step_5_minute_start++;
                                    }
                                    ?>
                                </ul>
                            </div>

                            <div data-name="pm" class="form__item__time__type custom_select">
                                <?php
                                $step_5_txt_ampm = get_field('step_5_text_ampm');
                                ?>
                                <p class="custom_select__current"><?= $step_5_txt_ampm; ?></p>
                                <ul class="custom_select__list">
                                    <li data-val=""
                                        class="custom_select__item js_choose_part_birth custom_select__item_active">
                                        <?= $step_5_txt_ampm; ?>
                                    </li>
                                    <li data-val="AM" class="custom_select__item js_choose_part_birth">
                                        <?php the_field('step_5_text_am'); ?>
                                    </li>
                                    <li data-val="PM" class="custom_select__item js_choose_part_birth">
                                        <?php the_field('step_5_text_pm'); ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="form__item__time__bottom">
                            <a class="form__item__time__skip"><?php the_field('step_5_text_i_dont_now'); ?></a>
                        </div>
                    </div>
                </div>


                <div data-step="6" class="form__item">
                    <p class="form__item__title"><?php the_field('step_6_title'); ?></p>
                    <div class="form__item__place">
                        <select name="country" id="country" style="width: 100%;background: #05191e;">
                            <option value="">Country</option>
                            <option value="US">United States</option>
                            <option value="GB">United Kingdom</option>
                            <option value="CA">Canada</option>
                            <option value="AU">Australia</option>
                            <option value="IE">Ireland</option>
                            <option value="NZ">New Zealand</option>
                            <option value="x" disabled>-----</option>
                            <option value="AF">Afghanistan</option>
                            <option value="AX">Aland Islands</option>
                            <option value="AL">Albania</option>
                            <option value="DZ">Algeria</option>
                            <option value="AS">American Samoa</option>
                            <option value="AD">Andorra</option>
                            <option value="AO">Angola</option>
                            <option value="AI">Anguilla</option>
                            <option value="AG">Antigua and Barbuda</option>
                            <option value="AR">Argentina</option>
                            <option value="AM">Armenia</option>
                            <option value="AW">Aruba</option>
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
                            <option value="BO">Bolivia</option>
                            <option value="BQ">Bonaire, Saint Eustatius and Saba</option>
                            <option value="BA">Bosnia and Herzegovina</option>
                            <option value="BW">Botswana</option>
                            <option value="BR">Brazil</option>
                            <option value="VG">British Virgin Islands</option>
                            <option value="BN">Brunei</option>
                            <option value="BG">Bulgaria</option>
                            <option value="BF">Burkina Faso</option>
                            <option value="BI">Burundi</option>
                            <option value="KH">Cambodia</option>
                            <option value="CM">Cameroon</option>
                            <option value="CV">Cape Verde</option>
                            <option value="KY">Cayman Islands</option>
                            <option value="CF">Central African Republic</option>
                            <option value="TD">Chad</option>
                            <option value="CL">Chile</option>
                            <option value="CN">China</option>
                            <option value="CX">Christmas Island</option>
                            <option value="CC">Cocos Islands</option>
                            <option value="CO">Colombia</option>
                            <option value="KM">Comoros</option>
                            <option value="CK">Cook Islands</option>
                            <option value="CR">Costa Rica</option>
                            <option value="HR">Croatia</option>
                            <option value="CU">Cuba</option>
                            <option value="CW">Curacao</option>
                            <option value="CY">Cyprus</option>
                            <option value="CZ">Czech Republic</option>
                            <option value="CD">Democratic Republic of the Congo</option>
                            <option value="DK">Denmark</option>
                            <option value="DJ">Djibouti</option>
                            <option value="DM">Dominica</option>
                            <option value="DO">Dominican Republic</option>
                            <option value="TL">East Timor</option>
                            <option value="EC">Ecuador</option>
                            <option value="EG">Egypt</option>
                            <option value="SV">El Salvador</option>
                            <option value="GQ">Equatorial Guinea</option>
                            <option value="ER">Eritrea</option>
                            <option value="EE">Estonia</option>
                            <option value="ET">Ethiopia</option>
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
                            <option value="HN">Honduras</option>
                            <option value="HK">Hong Kong</option>
                            <option value="HU">Hungary</option>
                            <option value="IS">Iceland</option>
                            <option value="IN">India</option>
                            <option value="ID">Indonesia</option>
                            <option value="IR">Iran</option>
                            <option value="IQ">Iraq</option>
                            <option value="IL">Israel</option>
                            <option value="IT">Italy</option>
                            <option value="CI">Ivory Coast</option>
                            <option value="JM">Jamaica</option>
                            <option value="JP">Japan</option>
                            <option value="JE">Jersey</option>
                            <option value="JO">Jordan</option>
                            <option value="KZ">Kazakhstan</option>
                            <option value="KE">Kenya</option>
                            <option value="KI">Kiribati</option>
                            <option value="XK">Kosovo</option>
                            <option value="KW">Kuwait</option>
                            <option value="KG">Kyrgyzstan</option>
                            <option value="LA">Laos</option>
                            <option value="LV">Latvia</option>
                            <option value="LB">Lebanon</option>
                            <option value="LS">Lesotho</option>
                            <option value="LR">Liberia</option>
                            <option value="LY">Libya</option>
                            <option value="LI">Liechtenstein</option>
                            <option value="LT">Lithuania</option>
                            <option value="LU">Luxembourg</option>
                            <option value="MO">Macao</option>
                            <option value="MK">Macedonia</option>
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
                            <option value="FM">Micronesia</option>
                            <option value="MD">Moldova</option>
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
                            <option value="NI">Nicaragua</option>
                            <option value="NE">Niger</option>
                            <option value="NG">Nigeria</option>
                            <option value="NU">Niue</option>
                            <option value="KP">North Korea</option>
                            <option value="MP">Northern Mariana Islands</option>
                            <option value="NO">Norway</option>
                            <option value="OM">Oman</option>
                            <option value="PK">Pakistan</option>
                            <option value="PW">Palau</option>
                            <option value="PS">Palestinian Territory</option>
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
                            <option value="CG">Republic of the Congo</option>
                            <option value="RE">Reunion</option>
                            <option value="RO">Romania</option>
                            <option value="RU">Russia</option>
                            <option value="RW">Rwanda</option>
                            <option value="BL">Saint Barthelemy</option>
                            <option value="SH">Saint Helena</option>
                            <option value="KN">Saint Kitts and Nevis</option>
                            <option value="LC">Saint Lucia</option>
                            <option value="MF">Saint Martin</option>
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
                            <option value="SX">Sint Maarten</option>
                            <option value="SK">Slovakia</option>
                            <option value="SI">Slovenia</option>
                            <option value="SB">Solomon Islands</option>
                            <option value="SO">Somalia</option>
                            <option value="ZA">South Africa</option>
                            <option value="GS">South Georgia &amp; South Sandwich Islnds</option>
                            <option value="KR">South Korea</option>
                            <option value="SS">South Sudan</option>
                            <option value="ES">Spain</option>
                            <option value="LK">Sri Lanka</option>
                            <option value="SD">Sudan</option>
                            <option value="SR">Suriname</option>
                            <option value="SJ">Svalbard and Jan Mayen</option>
                            <option value="SZ">Swaziland</option>
                            <option value="SE">Sweden</option>
                            <option value="CH">Switzerland</option>
                            <option value="SY">Syria</option>
                            <option value="TW">Taiwan</option>
                            <option value="TJ">Tajikistan</option>
                            <option value="TZ">Tanzania</option>
                            <option value="TH">Thailand</option>
                            <option value="TG">Togo</option>
                            <option value="TK">Tokelau</option>
                            <option value="TO">Tonga</option>
                            <option value="TT">Trinidad and Tobago</option>
                            <option value="TN">Tunisia</option>
                            <option value="TR">Turkey</option>
                            <option value="TM">Turkmenistan</option>
                            <option value="TV">Tuvalu</option>
                            <option value="VI">U.S. Virgin Islands</option>
                            <option value="UG">Uganda</option>
                            <option value="UA">Ukraine</option>
                            <option value="AE">United Arab Emirates</option>
                            <option value="UY">Uruguay</option>
                            <option value="UZ">Uzbekistan</option>
                            <option value="VU">Vanuatu</option>
                            <option value="VA">Vatican</option>
                            <option value="VE">Venezuela</option>
                            <option value="VN">Vietnam</option>
                            <option value="WF">Wallis and Futuna</option>
                            <option value="EH">Western Sahara</option>
                            <option value="YE">Yemen</option>
                            <option value="ZM">Zambia</option>
                            <option value="ZW">Zimbabwe</option>
                        </select>
                        <div data-name="state" class="form__item__place__state custom_select custom_select_disable">
                            <input type="hidden" class="js_input_state" name="state" value="">
                            <?php
                            $step_6_txt_state = get_field('step_6_text_state');
                            ?>
                            <p class="custom_select__current"><?= $step_6_txt_state; ?></p>
                            <ul class="custom_select__list js_states_list">
                                <li data-val="" data-title=""
                                    class="custom_select__item js_choose_state custom_select__item_active">
                                    <?= $step_6_txt_state; ?>
                                </li>
                            </ul>
                        </div>
                        <select name="city" id="city" style="width: 100%;background: #05191e;">
                            <option value="">City</option>
                        </select>
                    </div>
                </div>


                <div data-step="7" class="form__item">
                    <input type="hidden" class="js_input_gender" name="gender" value="">
                    <p class="form__item__title"><?php the_field('step_7_title'); ?></p>
                    <div class="form__item__gender">
                        <?php
                        if (have_rows('step_7_gender')) {
                            while (have_rows('step_7_gender')) {
                                the_row();
                                $title_gender = get_sub_field('title');
                                ?>
                                        <a data-gender="<?= $title_gender; ?>" class="form__item__gender__item">
                                            <img class="form__item__gender__item__img" src="<?php the_sub_field('image'); ?>"
                                                 alt="<?= $title_gender; ?>-icon">
                                            <p class="form__item__gender__item__title"><?= $title_gender; ?></p>
                                        </a>
                                    <?php
                            }
                        }
                        ?>
                    </div>
                </div>


                <div data-step="8" class="form__item">
                    <p class="form__item__title"><?php the_field('step_8_title'); ?></p>
                    <div class="form__item__input">
                        <input type="text" name="name"
                               placeholder="<?php the_field('step_8_placeholder_in_input_field'); ?>"
                               class="form__item__input__val form__item__input__val_name">
                        <div class="form__item__input__wrapper">
                            <button class="form__item__btn"
                                    type="button"><?php the_field('step_8_button_text'); ?></button>
                        </div>
                    </div>
                </div>


                <div data-step="9" class="form__item js_last_step">
                    <p class="form__item__title"><?php the_field('step_9_title'); ?></p>
                    <div class="form__item__input">
                        <input type="text" name="email"
                               placeholder="<?php the_field('step_9_placeholder_in_input_field'); ?>"
                               class="form__item__input__val form__item__input__val_email">
                        <div class="form__item__input__wrapper">
                            <button class="form__item__btn"
                                    type="submit"><?php the_field('step_9_button_text'); ?></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</header>

<?php add_action('wp_footer', function () { ?>
        <style>
            .form__item__btn__inactive {
                pointer-events: none;
                cursor: pointer;
            }
        </style>
<?php }, 999); ?>


<footer class="footer">
    <div class="container">
        <a>Home Page</a> | Privacy Policy | Terms of Use | Contact Us
    </div>
</footer>

<?php get_footer(); ?>
