<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>
    <h1 style="margin-top: 20px; text-align: center;"><?php the_title(); ?></h1>

    <div style="margin-top: 100px;">
        <?php
        function validateDate($date, $format = 'd-m-Y')
        {
            $d = DateTime::createFromFormat($format, $date);
            return $d && $d->format($format) === $date;
        }


        $userId = 621073;
        $apiKey = 'ba5239f850ff07221db978bb5c6bde5e';


        if (isset($_GET['birthday']) && !empty($_GET['birthday']) && validateDate($_GET['birthday']) && isset($_GET['time_of_birth']) && !empty($_GET['time_of_birth']) && isset($_GET['country']) && !empty($_GET['country']) && isset($_GET['state']) && !empty($_GET['state']) && isset($_GET['city']) && !empty($_GET['city'])) {
            $country = strip_tags($_GET['country']);
            $state = strip_tags($_GET['state']);
            $city = strip_tags($_GET['city']);

            $birthday = strip_tags($_GET['birthday']);
            $birth_time = $_GET['time_of_birth'];
            $full_birthday_object = DateTime::createFromFormat("d-m-Y h:i A", $birthday . ' ' . $birth_time);
            $birth_day = $full_birthday_object->format("j");
            $birth_month = $full_birthday_object->format("n");
            $birth_year = $full_birthday_object->format("Y");
            $birth_hour = $full_birthday_object->format("G");
            $birth_minute = $full_birthday_object->format("i");

            // Get city lat and long
            $address = $city . "+" . $state . "+" . $country;
            $url = "https://maps.google.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=AIzaSyBVG_LDO30Tqov1j1PCgUFX-3vwVhviahU";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $responseJson = curl_exec($ch);
            curl_close($ch);

            $response = json_decode($responseJson);

            if ($response->status == 'OK') {
                $latitude = $response->results[0]->geometry->location->lat;
                $longitude = $response->results[0]->geometry->location->lng;

                $vedicRishi = new VedicRishiClient($userId, $apiKey);

                // Start. Timezone
                $tz_data = array(
                    'date' => $birth_month . "-" . $birth_day . "-" . $birth_year,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                );
                $tz_resource_name = "timezone_with_dst";

                $tz_response_data = $vedicRishi->callApi($tz_resource_name, $tz_data);

                $timezone = '-6';
                if ($tz_response_data) {
                    $tz_data = json_decode($tz_response_data, true);
                    $timezone = $tz_data['timezone'];
                }
                // End. Timezone


                echo "<p>Latitude: " . $latitude . "</p>";
                echo "<p>Longitude: " . $longitude . "</p>";
                echo "<p>Timezone: " . $timezone . "</p>";
                echo "<p>Day: " . $birth_day . "</p>";
                echo "<p>Month: " . $birth_month . "</p>";
                echo "<p>Year: " . $birth_year . "</p>";
                echo "<p>Hour: " . $birth_hour . "</p>";
                echo "<p>Minute: " . $birth_minute . "</p>";
                echo '<br>';

                // Check API
                $data = array(
                    'date' => $birth_day,
                    'month' => $birth_month,
                    'year' => $birth_year,
                    'hour' => $birth_hour,
                    'minute' => $birth_minute,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'timezone' => $timezone
                );

                $resourceName = trim(get_field('api_endpoint'));
                echo "<h2 style='margin:50px 0;text-align: center; font-size: 28px;'>{$resourceName}</h2>";
                $responseData = $vedicRishi->call($resourceName, $data['date'], $data['month'], $data['year'], $data['hour'], $data['minute'], $data['latitude'], $data['longitude'], $data['timezone']);

                if ($responseData) {
                    $data = json_decode($responseData, true);
                    echo '<pre>';
                    print_r($data);
                    echo '</pre>';
                }


                /**
                 * 1. API natal_wheel_chart
                 */
//                $data_1 = array(
//                    'date' => $birth_day,
//                    'month' => $birth_month,
//                    'year' => $birth_year,
//                    'hour' => $birth_hour,
//                    'minute' => $birth_minute,
//                    'latitude' => $latitude,
//                    'longitude' => $longitude,
//                    'timezone' => $timezone
//                );
//
//                $resourceName_1 = "natal_wheel_chart";
//                echo "<h2 style='margin-top:50px;text-align: center; font-size: 28px; text-decoration: underline;'><a href='https://www.astrologyapi.com/docs/api-ref/151/natal_wheel_chart' target='_blank'>1. API: natal_wheel_chart</a></h2>";
//                $responseData_1 = $vedicRishi->call($resourceName_1, $data_1['date'], $data_1['month'], $data_1['year'], $data_1['hour'], $data_1['minute'], $data_1['latitude'], $data_1['longitude'], $data_1['timezone']);
//
//                if ($responseData_1) {
//                    $data = json_decode($responseData_1, true);
//                    echo "<p><img src='{$data['chart_url']}'></p>";
//                }


                /**
                 * 2. API personality_report/tropical
                 */
//                $data_2 = array(
//                    'date' => $birth_day,
//                    'month' => $birth_month,
//                    'year' => $birth_year,
//                    'hour' => $birth_hour,
//                    'minute' => $birth_minute,
//                    'latitude' => $latitude,
//                    'longitude' => $longitude,
//                    'timezone' => $timezone
//                );
//                $resourceName_2 = "personality_report/tropical";
//                echo "<h2 style='margin-top:50px;text-align: center; font-size: 28px; text-decoration: underline;'><a href='https://www.astrologyapi.com/western-api-docs/api-ref/138/personality_report/tropical' target='_blank'>2. API: personality_report/tropical</a></h2>";
//                $responseData_2 = $vedicRishi->call($resourceName_2, $data_2['date'], $data_2['month'], $data_2['year'], $data_2['hour'], $data_2['minute'], $data_2['latitude'], $data_2['longitude'], $data_2['timezone']);
//
//                if ($responseData_2) {
//                    $data_2 = json_decode($responseData_2, true);
//                    echo "<pre>";
//                    print_r($data_2);
//                    echo "</pre>";
//                }


                /**
                 * 3. API life_forecast_report/tropical
                 */
//                $data_3 = array(
//                    'date' => $birth_day,
//                    'month' => $birth_month,
//                    'year' => $birth_year,
//                    'hour' => $birth_hour,
//                    'minute' => $birth_minute,
//                    'latitude' => $latitude,
//                    'longitude' => $longitude,
//                    'timezone' => $timezone
//                );
//                $resourceName_3 = "life_forecast_report/tropical";
//                echo "<h2 style='margin-top:50px;text-align: center; font-size: 28px; text-decoration: underline;'><a href='https://www.astrologyapi.com/western-api-docs/api-ref/141/life_forecast_report/tropical' target='_blank'>3. API: life_forecast_report/tropical</a></h2>";
//                $responseData_3 = $vedicRishi->call($resourceName_3, $data_3['date'], $data_3['month'], $data_3['year'], $data_3['hour'], $data_3['minute'], $data_3['latitude'], $data_3['longitude'], $data_3['timezone']);
//
//                if ($responseData_3) {
//                    $data_3 = json_decode($responseData_3, true);
//                    echo "<pre>";
//                    print_r($data_3);
//                    echo "</pre>";
//                }


                /**
                 * 4. API general_house_report/tropical/:planetName
                 */
//                $data_4 = array(
//                    'date' => $birth_day,
//                    'month' => $birth_month,
//                    'year' => $birth_year,
//                    'hour' => $birth_hour,
//                    'minute' => $birth_minute,
//                    'latitude' => $latitude,
//                    'longitude' => $longitude,
//                    'timezone' => $timezone
//                );
//                $resourceName_4 = "general_house_report/tropical/sun";
//                echo "<h2 style='margin-top:50px;text-align: center; font-size: 28px; text-decoration: underline;'><a href='https://www.astrologyapi.com/western-api-docs/api-ref/137/general_house_report/tropical/:planetName' target='_blank'>4. API: general_house_report/tropical/:planetName - use Sun</a></h2>";
//                $responseData_4 = $vedicRishi->call($resourceName_4, $data_4['date'], $data_4['month'], $data_4['year'], $data_4['hour'], $data_4['minute'], $data_4['latitude'], $data_4['longitude'], $data_4['timezone']);
//
//                if ($responseData_4) {
//                    $data_4 = json_decode($responseData_4, true);
//                    echo "<pre>";
//                    print_r($data_4);
//                    echo "</pre>";
//                }


                /**
                 * 5. API personalized_planet_prediction/daily/:planetName
                 */
//                $data_5 = array(
//                    'date' => $birth_day,
//                    'month' => $birth_month,
//                    'year' => $birth_year,
//                    'hour' => $birth_hour,
//                    'minute' => $birth_minute,
//                    'latitude' => $latitude,
//                    'longitude' => $longitude,
//                    'timezone' => $timezone
//                );
//                $resourceName_5 = "personalized_planet_prediction/daily/sun";
//                echo "<h2 style='margin-top:50px;text-align: center; font-size: 28px; text-decoration: underline;'><a href='https://www.astrologyapi.com/western-api-docs/api-ref/140/personalized_planet_prediction/daily/:planetName' target='_blank'>5. API: personalized_planet_prediction/daily/:planetName - use Sun</a></h2>";
//                $responseData_5 = $vedicRishi->call($resourceName_5, $data_5['date'], $data_5['month'], $data_5['year'], $data_5['hour'], $data_5['minute'], $data_5['latitude'], $data_5['longitude'], $data_5['timezone']);
//
//                if ($responseData_5) {
//                    $data_5 = json_decode($responseData_5, true);
//                    echo "<pre>";
//                    print_r($data_5);
//                    echo "</pre>";
//                }


                /**
                 * 6. API western_chart_data
                 */
//                $data_6 = array(
//                    'date' => $birth_day,
//                    'month' => $birth_month,
//                    'year' => $birth_year,
//                    'hour' => $birth_hour,
//                    'minute' => $birth_minute,
//                    'latitude' => $latitude,
//                    'longitude' => $longitude,
//                    'timezone' => $timezone
//                );
//                $resourceName_6 = "western_chart_data";
//                echo "<h2 style='margin-top:50px;text-align: center; font-size: 28px; text-decoration: underline;'><a href='https://www.astrologyapi.com/western-api-docs/api-ref/163/western_chart_data' target='_blank'>6. API: western_chart_data</a></h2>";
//                $responseData_6 = $vedicRishi->call($resourceName_6, $data_6['date'], $data_6['month'], $data_6['year'], $data_6['hour'], $data_6['minute'], $data_6['latitude'], $data_6['longitude'], $data_6['timezone']);
//
//                if ($responseData_6) {
//                    $data_6 = json_decode($responseData_6, true);
//                    echo "<pre>";
//                    print_r($data_6);
//                    echo "</pre>";
//                }

            } else {
                echo 'Latitude and longitude not found!';
            }
        } else {
            echo '<p>Data error!</p>';
        }
        ?>
    </div>
<?php endwhile; ?>

<?php get_footer(); ?>
