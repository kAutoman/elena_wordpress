<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/elenatheme/astro-api-php-client/src/VedicRishiClient.php';


/**
 * Get states by country iso2 code
 */
function em_get_states_by_country_iso2_code()
{
    $status = 'error';
    $states_list = "<li data-val='' data-title='' class='custom_select__item js_choose_state custom_select__item_active'>State</li>";
    $cities_list = "<option value='' selected>City</option>";
    if (isset($_POST['iso2']) && !empty($_POST['iso2'])) {
        $iso2 = htmlspecialchars($_POST['iso2']);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.countrystatecity.in/v1/countries/' . $iso2 . '/states',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'X-CSCAPI-KEY: TVhVZ1RmVjVMbkhSbndpYlVmd1lpYXd3WEZTa29NVGZXd3BTY0ZUOQ=='
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $status = 'error';
        } else {
            if ($response) {
                $status = 'ok';
                $data = json_decode($response, true);

                function cmp($a, $b)
                {
                    return strcmp($a["name"], $b["name"]);
                }

                usort($data, "cmp");

                foreach ($data as $item) {
                    $state_iso2 = $item['iso2'];
                    $state_title = $item['name'];

                    $states_list .= "<li data-val='$state_iso2' data-title='$state_title' class='custom_select__item js_choose_state'>$state_title</li>";
                }
            }
        }
    }
    echo json_encode(array('status' => $status, 'states_list' => $states_list, 'cities_list' => $cities_list));
    wp_die();
}

add_action('wp_ajax_em_get_states_by_country_iso2_code', 'em_get_states_by_country_iso2_code');
add_action('wp_ajax_nopriv_em_get_states_by_country_iso2_code', 'em_get_states_by_country_iso2_code');


/**
 * Get cities by country and state iso2 code
 */
function em_get_cities_by_state_and_country_iso2_code()
{
    $status = 'error';
    $cities_list = "<option value=''>City</option>";
    if (isset($_POST['country_iso2']) && !empty($_POST['country_iso2']) && ($_POST['country_iso2'] === 'US') && isset($_POST['state_iso2']) && !empty($_POST['state_iso2'])) {
        $country_iso2 = trim(htmlspecialchars($_POST['country_iso2']));
        $state_iso2 = trim(htmlspecialchars($_POST['state_iso2']));

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.countrystatecity.in/v1/countries/' . $country_iso2 . '/states/' . $state_iso2 . '/cities',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'X-CSCAPI-KEY: TVhVZ1RmVjVMbkhSbndpYlVmd1lpYXd3WEZTa29NVGZXd3BTY0ZUOQ=='
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $status = 'error';
        } else {
            if ($response) {
                $status = 'ok';
                $data = json_decode($response, true);

                function cmp($a, $b)
                {
                    return strcmp($a["name"], $b["name"]);
                }

                usort($data, "cmp");

                foreach ($data as $item) {
                    $city_title = $item['name'];

                    $cities_list .= "<option value='$city_title'>$city_title</option>";
                }
            }
        }
    }
    elseif (isset($_POST['country_iso2']) && !empty($_POST['country_iso2']) && ($_POST['country_iso2'] !== 'US')) {
        $country_iso2 = trim(htmlspecialchars($_POST['country_iso2']));

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.countrystatecity.in/v1/countries/' . $country_iso2 . '/cities',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'X-CSCAPI-KEY: TVhVZ1RmVjVMbkhSbndpYlVmd1lpYXd3WEZTa29NVGZXd3BTY0ZUOQ=='
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $status = 'error';
        } else {
            if ($response) {
                $status = 'ok';
                $data = json_decode($response, true);

                function cmp($a, $b)
                {
                    return strcmp($a["name"], $b["name"]);
                }

                usort($data, "cmp");

                foreach ($data as $item) {
                    $city_title = $item['name'];

                    $cities_list .= "<option value='$city_title'>$city_title</option>";
                }
            }
        }
    }
    echo json_encode(array('status' => $status, 'cities_list' => $cities_list));
    wp_die();
}

add_action('wp_ajax_em_get_cities_by_state_and_country_iso2_code', 'em_get_cities_by_state_and_country_iso2_code');
add_action('wp_ajax_nopriv_em_get_cities_by_state_and_country_iso2_code', 'em_get_cities_by_state_and_country_iso2_code');


/**
 * Send data to CRM
 */
function em_send_data_to_crm()
{
    $name = strip_tags($_POST['first_name']);
    $email = strip_tags($_POST['email']);
    $zodiac_sign = strip_tags($_POST['zodiac_sign']);
    $birthday = strip_tags($_POST['birthday']);
    $time_of_birth = strip_tags($_POST['time_of_birth']);
    $country = strip_tags($_POST['country']);
    $state = strip_tags($_POST['state']);
    $city = strip_tags($_POST['city']);
    $gender = strip_tags($_POST['gender']);

    $time_object = DateTime::createFromFormat("h:i A", $time_of_birth);
    $time_object_new_format = $time_object->format("H:i");
    $full_birthday = $birthday . ' ' . $time_object_new_format;

    $url_params = http_build_query($_POST);
    $free_reading_url = get_field('landing_free_reading_page', 'options');
    $free_reading_url_with_params = $free_reading_url . '?' . $url_params;


    $thanks_page_url = '';
    $status = 'error';

    // Test data
    //$api_url = 'https://freelance1669134722.api-us1.com';
    //$api_key = 'dd07151b83b5c4f14273f76824a45699128abaac0fcb9e408ac61e65094e66e1d0138d94';
    // custom fields  CURLOPT_POSTFIELDS => "{\"contact\":{\"email\":\"$email\",\"firstName\":\"$name\",\"fieldValues\":[{\"field\":\"6\",\"value\":\"$full_birthday\"},{\"field\":\"2\",\"value\":\"$country\"},{\"field\":\"3\",\"value\":\"$state\"},{\"field\":\"4\",\"value\":\"$city\"},{\"field\":\"7\",\"value\":\"$zodiac_sign\"},{\"field\":\"5\",\"value\":\"$gender\"},{\"field\":\"8\",\"value\":\"$free_reading_url_with_params\"}]}}",
// add to list  CURLOPT_POSTFIELDS => "{\"contactList\":{\"list\":1,\"contact\":$contact_id,\"status\":1}}",
// Add tag to contact CURLOPT_POSTFIELDS => "{\"contactTag\":{\"contact\":\"$contact_id\",\"tag\":\"2\"}}",

    // Clients data
    $api_url = 'https://bestday.api-us1.com';
    $api_key =  '3c64b291a8857acf08f037c2a16bff969429fda5b16820f986922bbea4f0f111c60e1474';
    // custom fields        CURLOPT_POSTFIELDS => "{\"contact\":{\"email\":\"$email\",\"firstName\":\"$name\",\"fieldValues\":[{\"field\":\"1\",\"value\":\"$full_birthday\"},{\"field\":\"2\",\"value\":\"$country\"},{\"field\":\"3\",\"value\":\"$state\"},{\"field\":\"4\",\"value\":\"$city\"},{\"field\":\"5\",\"value\":\"$zodiac_sign\"},{\"field\":\"6\",\"value\":\"$gender\"},{\"field\":\"7\",\"value\":\"$free_reading_url_with_params\"}]}}",
    // add to list  CURLOPT_POSTFIELDS => "{\"contactList\":{\"list\":2,\"contact\":$contact_id,\"status\":1}}",
// Add tag to contact CURLOPT_POSTFIELDS => "{\"contactTag\":{\"contact\":\"$contact_id\",\"tag\":\"1\"}}",


    /* Add new contact to crm */
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "{$api_url}/api/3/contacts",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"contact\":{\"email\":\"$email\",\"firstName\":\"$name\",\"fieldValues\":[{\"field\":\"1\",\"value\":\"$full_birthday\"},{\"field\":\"2\",\"value\":\"$country\"},{\"field\":\"3\",\"value\":\"$state\"},{\"field\":\"4\",\"value\":\"$city\"},{\"field\":\"5\",\"value\":\"$zodiac_sign\"},{\"field\":\"6\",\"value\":\"$gender\"},{\"field\":\"7\",\"value\":\"$free_reading_url_with_params\"}]}}",
        CURLOPT_HTTPHEADER => [
            "Api-Token: $api_key",
            "accept: application/json",
            "content-type: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $data = json_decode($response, true);
        $contact_id = $data['contact']['id'];

        /* Add new contact to list */
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "{$api_url}/api/3/contactLists",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"contactList\":{\"list\":2,\"contact\":$contact_id,\"status\":1}}",
            CURLOPT_HTTPHEADER => [
                "Api-Token: $api_key",
                "accept: application/json",
                "content-type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            /* Add tag to new contact */
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "{$api_url}/api/3/contactTags",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\"contactTag\":{\"contact\":\"$contact_id\",\"tag\":\"1\"}}",
                CURLOPT_HTTPHEADER => [
                    "Api-Token: $api_key",
                    "accept: application/json",
                    "content-type: application/json"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $thanks_page_url = get_field('landing_thank_you_page', 'options');
                $status = 'ok';
            }
        }
    }


    echo json_encode(array('status' => $status, 'response' => $response, 'thanks_page_url' => $thanks_page_url, 'birth' => $full_birthday));

    wp_die();
}

add_action('wp_ajax_em_send_data_to_crm', 'em_send_data_to_crm');
add_action('wp_ajax_nopriv_em_send_data_to_crm', 'em_send_data_to_crm');


/**
 * Send letter
 */
function em_send_letter($email, $arr, $full_birthday)
{
    $url_params = http_build_query($arr);
    $full_birthday_object = DateTime::createFromFormat("m-d-Y H:i", $full_birthday);
    $birth_day = $full_birthday_object->format("j");
    $birth_month = $full_birthday_object->format("n");
    $birth_year = $full_birthday_object->format("Y");
    $birth_hour = $full_birthday_object->format("G");
    $birth_minute = $full_birthday_object->format("i");

    $to = $email;
    $type = 'Free reading';

    $userId = 621073;
    $apiKey = 'ba5239f850ff07221db978bb5c6bde5e';
    $data = array(
        'date' => $birth_day,
        'month' => $birth_month,
        'year' => $birth_year,
        'hour' => $birth_hour,
        'minute' => $birth_minute,
        'latitude' => 25.123,
        'longitude' => 82.34,
        'timezone' => 5.5
    );
    //$resourceName = "personality_report/tropical";
    $resourceName = "general_ascendant_report/tropical";

    $vedicRishi = new VedicRishiClient($userId, $apiKey);
    $responseData = $vedicRishi->call($resourceName, $data['date'], $data['month'], $data['year'], $data['hour'], $data['minute'], $data['latitude'], $data['longitude'], $data['timezone']);

    $text = '';
    $text_title = '';
    if ($responseData) {
        $data = json_decode($responseData, true);
        $text_title = $data['ascendant'];
        $text = $data['report'];
    }


    $site_name = trim(get_option('blogname'));
    $site_url = trim(get_option('siteurl'));
    $site_url_without_protocol = str_replace(array('http://', 'https://'), '', $site_url);

    $subject = "{$site_name}: {$type}";
    $message = "
                <html>
                    <head>
                        <title>'.$subject.'</title>
                    </head>
                    <body>
                    <h1>Ascendant: $text_title</h1>
                    $text
                           <p style='text-align: center;'><a href='https://wordpress-540472-3025773.cloudwaysapps.com/free-reading/?{$url_params}' target='_blank'>Read your full version</a></p>
                    </body>
                </html>";

    $email = "no-reply@{$site_url_without_protocol}";
    $headers = "Content-type: text/html; charset=utf-8 \r\n";
    $headers .= "From: $email\r\n";

    $result = wp_mail($to, $subject, $message, $headers);
    //echo $result;
    //wp_die();
}
