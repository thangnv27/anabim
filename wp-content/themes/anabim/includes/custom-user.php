<?php
if (!function_exists('salutation_list')) {

    function salutation_list() {
        return array( "Mr", "Ms", "Mrs", "Miss" );
    }

}
if (!function_exists('country_list')) {

    function country_list() {
        return array(
            "Afghanistan",
            "Albania",
            "Algeria",
            "Andorra",
            "Angola",
            "Antigua and Barbuda",
            "Argentina",
            "Armenia",
            "Australia",
            "Austria",
            "Azerbaijan",
            "Bahamas",
            "Bahrain",
            "Bangladesh",
            "Barbados",
            "Belarus",
            "Belgium",
            "Belize",
            "Benin",
            "Bhutan",
            "Bolivia",
            "Bosnia and Herzegovina",
            "Botswana",
            "Brazil",
            "Brunei",
            "Bulgaria",
            "Burkina Faso",
            "Burundi",
            "Cambodia",
            "Cameroon",
            "Canada",
            "Cape Verde",
            "Central African Republic",
            "Chad",
            "Chile",
            "China",
            "Colombi",
            "Comoros",
            "Congo (Brazzaville)",
            "Congo",
            "Costa Rica",
            "Cote d'Ivoire",
            "Croatia",
            "Cuba",
            "Cyprus",
            "Czech Republic",
            "Denmark",
            "Djibouti",
            "Dominica",
            "Dominican Republic",
            "East Timor (Timor Timur)",
            "Ecuador",
            "Egypt",
            "El Salvador",
            "Equatorial Guinea",
            "Eritrea",
            "Estonia",
            "Ethiopia",
            "Fiji",
            "Finland",
            "France",
            "Gabon",
            "Gambia, The",
            "Georgia",
            "Germany",
            "Ghana",
            "Greece",
            "Grenada",
            "Guatemala",
            "Guinea",
            "Guinea-Bissau",
            "Guyana",
            "Haiti",
            "Honduras",
            "Hungary",
            "Iceland",
            "India",
            "Indonesia",
            "Iran",
            "Iraq",
            "Ireland",
            "Israel",
            "Italy",
            "Jamaica",
            "Japan",
            "Jordan",
            "Kazakhstan",
            "Kenya",
            "Kiribati",
            "Korea, North",
            "Korea, South",
            "Kuwait",
            "Kyrgyzstan",
            "Laos",
            "Latvia",
            "Lebanon",
            "Lesotho",
            "Liberia",
            "Libya",
            "Liechtenstein",
            "Lithuania",
            "Luxembourg",
            "Macedonia",
            "Madagascar",
            "Malawi",
            "Malaysia",
            "Maldives",
            "Mali",
            "Malta",
            "Marshall Islands",
            "Mauritania",
            "Mauritius",
            "Mexico",
            "Micronesia",
            "Moldova",
            "Monaco",
            "Mongolia",
            "Morocco",
            "Mozambique",
            "Myanmar",
            "Namibia",
            "Nauru",
            "Nepal",
            "Netherlands",
            "New Zealand",
            "Nicaragua",
            "Niger",
            "Nigeria",
            "Norway",
            "Oman",
            "Pakistan",
            "Palau",
            "Panama",
            "Papua New Guinea",
            "Paraguay",
            "Peru",
            "Philippines",
            "Poland",
            "Portugal",
            "Qatar",
            "Romania",
            "Russia",
            "Rwanda",
            "Saint Kitts and Nevis",
            "Saint Lucia",
            "Saint Vincent",
            "Samoa",
            "San Marino",
            "Sao Tome and Principe",
            "Saudi Arabia",
            "Senegal",
            "Serbia and Montenegro",
            "Seychelles",
            "Sierra Leone",
            "Singapore",
            "Slovakia",
            "Slovenia",
            "Solomon Islands",
            "Somalia",
            "South Africa",
            "Spain",
            "Sri Lanka",
            "Sudan",
            "Suriname",
            "Swaziland",
            "Sweden",
            "Switzerland",
            "Syria",
            "Taiwan",
            "Tajikistan",
            "Tanzania",
            "Thailand",
            "Togo",
            "Tonga",
            "Trinidad and Tobago",
            "Tunisia",
            "Turkey",
            "Turkmenistan",
            "Tuvalu",
            "Uganda",
            "Ukraine",
            "United Arab Emirates",
            "United Kingdom",
            "United States",
            "Uruguay",
            "Uzbekistan",
            "Vanuatu",
            "Vatican City",
            "Venezuela",
            "Vietnam",
            "Yemen",
            "Zambia",
            "Zimbabwe"
        );
    }

}
if (!function_exists('month_list')) {

    function month_list() {
        return array(
            '1' => "Tháng 1",
            '2' => 'Tháng 2',
            '3' => 'Tháng 3',
            '4' => 'Tháng 4',
            '5' => 'Tháng 5',
            '6' => 'Tháng 6',
            '7' => 'Tháng 7',
            '8' => 'Tháng 8',
            '9' => 'Tháng 9',
            '10' => 'Tháng 10',
            '11' => 'Tháng 11',
            '12' => 'Tháng 12'
        );
//        return array(
//            '1' => "January",
//            '2' => 'February',
//            '3' => 'March',
//            '4' => 'April',
//            '5' => 'May',
//            '6' => 'June',
//            '7' => 'July',
//            '8' => 'August',
//            '9' => 'September',
//            '10' => 'October',
//            '11' => 'November',
//            '12' => 'December'
//        );
    }

}
$user_fields = array(
    "user_phone", "user_address1", "user_address2","user_country", "user_city", "user_zipcode", 
    "dob_month", "dob_day", "dob_year", "workplace", "user_membership", "membership_expire"
);

add_action('show_user_profile', 'my_show_extra_profile_fields');
add_action('edit_user_profile', 'my_show_extra_profile_fields');
add_action('personal_options_update', 'my_save_extra_profile_fields');
add_action('edit_user_profile_update', 'my_save_extra_profile_fields');

function my_show_extra_profile_fields($user) {
    ?>
    <h3>Extra profile information</h3>
    <table class="form-table">
        <tr>
            <th><label for="user_phone"><?php _e('Số điện thoại', SHORT_NAME) ?></label></th>
            <td>
                <input type="text" name="user_phone" id="user_phone" value="<?php echo esc_attr(get_the_author_meta('user_phone', $user->ID)); ?>" class="regular-text" /><br />
                <!--<span class="description">Please enter your phone number.</span>-->
            </td>
        </tr>
        <tr>
            <th><label><?php _e('Ngày sinh', SHORT_NAME) ?></label></th>
            <td>
                <select name="dob_month" id="dob_month" style="width: 8em;">
                    <?php
                    $user_months = month_list();
                    foreach ($user_months as $key => $value) {
                        if (esc_attr(get_the_author_meta('dob_month', $user->ID)) == $key) {
                            echo '<option value="' . $key . '" selected="selected">' . $value . '</option>';
                        } else {
                            echo '<option value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
                <select name="dob_day" id="dob_day" style="width: 8em;">
                    <?php
                    for($i = 1; $i <= 31; $i++) {
                        if (esc_attr(get_the_author_meta('dob_day', $user->ID)) == $i) {
                            echo '<option value="' . $i . '" selected="selected">' . $i. '</option>';
                        } else {
                            echo '<option value="' . $i . '">' . $i . '</option>';
                        }
                    }
                    ?>
                </select>
                <select name="dob_year" id="dob_year" style="width: 8em;">
                    <?php
                    $year_max = date('Y') - 5;
                    $year_min = $year_max - 71;
                    for($i = $year_max; $i >= $year_min; $i--) {
                        if(esc_attr(get_the_author_meta('dob_year', $user->ID)) == $i){
                            echo "<option value=\"$i\" selected>$i</option>";
                        } else {
                            echo "<option value=\"$i\">$i</option>";
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="user_city"><?php _e('Tỉnh thành', SHORT_NAME) ?></label></th>
            <td>
                <select name="user_city" id="user_city">
                    <?php
                    $cities = vn_city_list();
                    foreach ($cities as $city) {
                        if (esc_attr(get_the_author_meta('user_city', $current_user->ID)) == $city) {
                            echo '<option value="' .$city .'" selected>' . $city . '</option>';
                        } else {
                            echo "<option value=\"$city\">$city</option>";
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="user_zipcode">Zip code</label></th>
            <td>
                <input type="text" name="user_zipcode" id="user_zipcode" value="<?php echo esc_attr(get_the_author_meta('user_zipcode', $user->ID)); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Xem zipcode của bạn tại đây', SHORT_NAME) ?>: <a href="http://ppo.vn/zip-postal-code-ma-buu-chinh-64-tinh-thanh-viet-nam-la-gi-987.html" title="Zip Postal Code (Mã bưu chính) 64 tỉnh thành Việt Nam" target="_blank">Click</a></span>
            </td>
        </tr>
        <tr>
            <th><label for="user_address1"><?php _e('Địa chỉ', SHORT_NAME) ?></label></th>
            <td>
                <input type="text" name="user_address1" id="user_address1" value="<?php echo esc_attr(get_the_author_meta('user_address1', $user->ID)); ?>" class="regular-text" /><br />
                <!--<span class="description">Please enter your phone number.</span>-->
            </td>
        </tr>
        <tr>
            <th><label for="workplace"><?php _e('Nơi công tác', SHORT_NAME) ?></label></th>
            <td>
                <input type="text" name="workplace" id="workplace" value="<?php echo esc_attr(get_the_author_meta('workplace', $user->ID)); ?>" class="regular-text" /><br />
                <!--<span class="description">Please enter your phone number.</span>-->
            </td>
        </tr>
        <tr>
            <th><label for="user_country"><?php _e('Quốc gia', SHORT_NAME) ?></label></th>
            <td>
                <select name="user_country" id="user_country" style="width: 15em;">
                    <?php
                    $countres = country_list();
                    foreach ($countres as $country) {
                        if (esc_attr(get_the_author_meta('user_country', $user->ID)) == $country) {
                            echo '<option value="' . $country . '" selected="selected">' . $country . '</option>';
                        } else {
                            echo '<option value="' . $country . '">' . $country . '</option>';
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        <?php /*if(current_user_can('administrator')): ?>
        <tr>
            <th><label for="user_membership"><?php _e('Membership', SHORT_NAME) ?></label></th>
            <td>
                <select name="user_membership" id="user_membership" style="width: 15em;">
                    <?php
                    $user_membership = esc_attr(get_the_author_meta('user_membership', $user->ID));
                    $loop = new WP_Query(array(
                        'post_type' => 'membership',
                        'posts_per_page' => -1,
                        'meta_key' => 'mem_order',
                        'orderby' => 'meta_value_num',
                        'order' => 'ASC',
                    ));
                    while ($loop->have_posts()) : $loop->the_post();
                        if ($user_membership == get_the_ID()) {
                            echo '<option value="' . get_the_ID() . '" selected="selected">' . get_the_title() . '</option>';
                        } else {
                            echo '<option value="' . get_the_ID() . '">' . get_the_title() . '</option>';
                        }
                    endwhile;
                    wp_reset_query();
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="membership_expire"><?php _e('Thời hạn Membership', SHORT_NAME) ?></label></th>
            <td>
                <input type="text" name="membership_expire" value="<?php echo esc_attr(get_the_author_meta('membership_expire', $user->ID)); ?>" class="regular-text membership-datepicker" placeholder="yyyy-mm-dd" />
            </td>
        </tr>
        <?php endif;*/ ?>
    </table>
    <?php
}

function my_save_extra_profile_fields($user_id) {
    global $user_fields;

    if (!current_user_can('edit_user', $user_id))
        return false;

    foreach ($user_fields as $field) {
//        if($field == 'user_membership' and current_user_can('administrator')){
//            update_usermeta($user_id, 'user_membership', intval($_POST['user_membership']));
//        } else {
            update_usermeta($user_id, $field, $_POST[$field]);
//        }
    }
}