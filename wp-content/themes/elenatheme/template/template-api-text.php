<?php
/**
 * Template Name: API content
 */
?>

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


                if (have_rows('apis_list')) {
                    $i = 1;
                    while (have_rows('apis_list')) {
                        the_row();
                        ${'data' . $i} = array(
                            'date' => intval($birth_day),
                            'month' => intval($birth_month),
                            'year' => intval($birth_year),
                            'hour' => intval($birth_hour),
                            'minute' => $birth_minute,
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'timezone' => $timezone
                        );

                        ${'resourceName' . $i} = get_sub_field('api_endpoint');
                        echo "<h2 style='margin-top:50px;text-align: center; font-size: 28px; text-decoration: underline;'>{$i}. {${'resourceName' . $i}}</h2>";
                        ${'responseData' . $i} = $vedicRishi->call(${'resourceName' . $i}, ${'data' . $i}['date'], ${'data' . $i}['month'], ${'data' . $i}['year'], ${'data' . $i}['hour'], ${'data' . $i}['minute'], ${'data' . $i}['latitude'], ${'data' . $i}['longitude'], ${'data' . $i}['timezone']);

                        if (${'responseData' . $i}) {
                            ${'data' . $i} = json_decode(${'responseData' . $i}, true);
                            echo "<pre>";
                            print_r(${'data' . $i});
                            echo "</pre>";
                        }
                        $i++;
                    }
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
