<?php
/**
 * Plugin Name: CF7 Country Flag for Phone Fields
 * Plugin URI: https://github.com/gabrielepiccinnu/contact-form-7-phone-flag
 * Description: Adds a country flag and country code selector to telephone fields in Contact Form 7. This lightweight plugin uses system-supported emoji flags, optimizing performance and improving SEO for telephone fields.
 * Version: 1.0.0
 * Author: Gabriele Piccinnu
 * Author URI: https://github.com/gabrielepiccinnu
 * License: GPL2
 *
 * This plugin provides a custom form tag for Contact Form 7 that displays a dropdown list of country flags.
 * When a user selects a flag, the corresponding international phone prefix is automatically inserted into the telephone input field.
 * The plugin uses emoji flags, which are supported by modern systems, eliminating the need for external image assets.
 */

/**
 * Enqueue plugin assets (CSS and JS).
 */
function cf7_country_flag_enqueue_scripts() {
    if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
        wp_enqueue_style( 'cf7-phone-country-flag-style', plugin_dir_url( __FILE__ ) . 'css/cf7-phone-country-flag.css' );
        wp_enqueue_script( 'cf7-phone-country-flag-script', plugin_dir_url( __FILE__ ) . 'js/cf7-phone-country-flag.js', array( 'jquery' ), '1.0.0', true );
    }
}
add_action( 'wp_enqueue_scripts', 'cf7_country_flag_enqueue_scripts' );

/**
 * Handle the custom form tag for telephone fields with country flag selector.
 *
 * @param WPCF7_FormTag $tag Contact Form 7 form tag object.
 * @return string HTML output for the custom telephone field.
 */
function cf7_country_flag_form_tag_handler( $tag ) {
    if ( empty( $tag->name ) ) {
        return '';
    }

    $html = '<div class="cf7-phone-country-flag-wrapper">';
    $html .= '<select class="cf7-phone-country-flag-selector" name="cf7_country_flag_country">';
    
    // Complete list of countries with international dialing codes and emoji flags.
    $countries = array(
        'AF' => array( 'name' => 'Afghanistan', 'prefix' => '+93', 'flag' => '🇦🇫' ),
        'AL' => array( 'name' => 'Albania', 'prefix' => '+355', 'flag' => '🇦🇱' ),
        'DZ' => array( 'name' => 'Algeria', 'prefix' => '+213', 'flag' => '🇩🇿' ),
        'AS' => array( 'name' => 'American Samoa', 'prefix' => '+1-684', 'flag' => '🇦🇸' ),
        'AD' => array( 'name' => 'Andorra', 'prefix' => '+376', 'flag' => '🇦🇩' ),
        'AO' => array( 'name' => 'Angola', 'prefix' => '+244', 'flag' => '🇦🇴' ),
        'AI' => array( 'name' => 'Anguilla', 'prefix' => '+1-264', 'flag' => '🇦🇮' ),
        'AG' => array( 'name' => 'Antigua and Barbuda', 'prefix' => '+1-268', 'flag' => '🇦🇬' ),
        'AR' => array( 'name' => 'Argentina', 'prefix' => '+54', 'flag' => '🇦🇷' ),
        'AM' => array( 'name' => 'Armenia', 'prefix' => '+374', 'flag' => '🇦🇲' ),
        'AW' => array( 'name' => 'Aruba', 'prefix' => '+297', 'flag' => '🇦🇼' ),
        'AU' => array( 'name' => 'Australia', 'prefix' => '+61', 'flag' => '🇦🇺' ),
        'AT' => array( 'name' => 'Austria', 'prefix' => '+43', 'flag' => '🇦🇹' ),
        'AZ' => array( 'name' => 'Azerbaijan', 'prefix' => '+994', 'flag' => '🇦🇿' ),
        'BS' => array( 'name' => 'Bahamas', 'prefix' => '+1-242', 'flag' => '🇧🇸' ),
        'BH' => array( 'name' => 'Bahrain', 'prefix' => '+973', 'flag' => '🇧🇭' ),
        'BD' => array( 'name' => 'Bangladesh', 'prefix' => '+880', 'flag' => '🇧🇩' ),
        'BB' => array( 'name' => 'Barbados', 'prefix' => '+1-246', 'flag' => '🇧🇧' ),
        'BY' => array( 'name' => 'Belarus', 'prefix' => '+375', 'flag' => '🇧🇾' ),
        'BE' => array( 'name' => 'Belgium', 'prefix' => '+32', 'flag' => '🇧🇪' ),
        'BZ' => array( 'name' => 'Belize', 'prefix' => '+501', 'flag' => '🇧🇿' ),
        'BJ' => array( 'name' => 'Benin', 'prefix' => '+229', 'flag' => '🇧🇯' ),
        'BM' => array( 'name' => 'Bermuda', 'prefix' => '+1-441', 'flag' => '🇧🇲' ),
        'BT' => array( 'name' => 'Bhutan', 'prefix' => '+975', 'flag' => '🇧🇹' ),
        'BO' => array( 'name' => 'Bolivia', 'prefix' => '+591', 'flag' => '🇧🇴' ),
        'BA' => array( 'name' => 'Bosnia and Herzegovina', 'prefix' => '+387', 'flag' => '🇧🇦' ),
        'BW' => array( 'name' => 'Botswana', 'prefix' => '+267', 'flag' => '🇧🇼' ),
        'BR' => array( 'name' => 'Brazil', 'prefix' => '+55', 'flag' => '🇧🇷' ),
        'IO' => array( 'name' => 'British Indian Ocean Territory', 'prefix' => '+246', 'flag' => '🇮🇴' ),
        'VG' => array( 'name' => 'British Virgin Islands', 'prefix' => '+1-284', 'flag' => '🇻🇬' ),
        'BN' => array( 'name' => 'Brunei', 'prefix' => '+673', 'flag' => '🇧🇳' ),
        'BG' => array( 'name' => 'Bulgaria', 'prefix' => '+359', 'flag' => '🇧🇬' ),
        'BF' => array( 'name' => 'Burkina Faso', 'prefix' => '+226', 'flag' => '🇧🇫' ),
        'BI' => array( 'name' => 'Burundi', 'prefix' => '+257', 'flag' => '🇧🇮' ),
        'KH' => array( 'name' => 'Cambodia', 'prefix' => '+855', 'flag' => '🇰🇭' ),
        'CM' => array( 'name' => 'Cameroon', 'prefix' => '+237', 'flag' => '🇨🇲' ),
        'CA' => array( 'name' => 'Canada', 'prefix' => '+1', 'flag' => '🇨🇦' ),
        'CV' => array( 'name' => 'Cape Verde', 'prefix' => '+238', 'flag' => '🇨🇻' ),
        'KY' => array( 'name' => 'Cayman Islands', 'prefix' => '+1-345', 'flag' => '🇰🇾' ),
        'CF' => array( 'name' => 'Central African Republic', 'prefix' => '+236', 'flag' => '🇨🇫' ),
        'TD' => array( 'name' => 'Chad', 'prefix' => '+235', 'flag' => '🇹🇩' ),
        'CL' => array( 'name' => 'Chile', 'prefix' => '+56', 'flag' => '🇨🇱' ),
        'CN' => array( 'name' => 'China', 'prefix' => '+86', 'flag' => '🇨🇳' ),
        'CX' => array( 'name' => 'Christmas Island', 'prefix' => '+61', 'flag' => '🇨🇽' ),
        'CC' => array( 'name' => 'Cocos Islands', 'prefix' => '+61', 'flag' => '🇨🇨' ),
        'CO' => array( 'name' => 'Colombia', 'prefix' => '+57', 'flag' => '🇨🇴' ),
        'KM' => array( 'name' => 'Comoros', 'prefix' => '+269', 'flag' => '🇰🇲' ),
        'CK' => array( 'name' => 'Cook Islands', 'prefix' => '+682', 'flag' => '🇨🇰' ),
        'CR' => array( 'name' => 'Costa Rica', 'prefix' => '+506', 'flag' => '🇨🇷' ),
        'HR' => array( 'name' => 'Croatia', 'prefix' => '+385', 'flag' => '🇭🇷' ),
        'CU' => array( 'name' => 'Cuba', 'prefix' => '+53', 'flag' => '🇨🇺' ),
        'CW' => array( 'name' => 'Curaçao', 'prefix' => '+599', 'flag' => '🇨🇼' ),
        'CY' => array( 'name' => 'Cyprus', 'prefix' => '+357', 'flag' => '🇨🇾' ),
        'CZ' => array( 'name' => 'Czech Republic', 'prefix' => '+420', 'flag' => '🇨🇿' ),
        'DK' => array( 'name' => 'Denmark', 'prefix' => '+45', 'flag' => '🇩🇰' ),
        'DJ' => array( 'name' => 'Djibouti', 'prefix' => '+253', 'flag' => '🇩🇯' ),
        'DM' => array( 'name' => 'Dominica', 'prefix' => '+1-767', 'flag' => '🇩🇲' ),
        'DO' => array( 'name' => 'Dominican Republic', 'prefix' => '+1-809', 'flag' => '🇩🇴' ),
        'EC' => array( 'name' => 'Ecuador', 'prefix' => '+593', 'flag' => '🇪🇨' ),
        'EG' => array( 'name' => 'Egypt', 'prefix' => '+20', 'flag' => '🇪🇬' ),
        'SV' => array( 'name' => 'El Salvador', 'prefix' => '+503', 'flag' => '🇸🇻' ),
        'GQ' => array( 'name' => 'Equatorial Guinea', 'prefix' => '+240', 'flag' => '🇬🇶' ),
        'ER' => array( 'name' => 'Eritrea', 'prefix' => '+291', 'flag' => '🇪🇷' ),
        'EE' => array( 'name' => 'Estonia', 'prefix' => '+372', 'flag' => '🇪🇪' ),
        'ET' => array( 'name' => 'Ethiopia', 'prefix' => '+251', 'flag' => '🇪🇹' ),
        'FK' => array( 'name' => 'Falkland Islands', 'prefix' => '+500', 'flag' => '🇫🇰' ),
        'FO' => array( 'name' => 'Faroe Islands', 'prefix' => '+298', 'flag' => '🇫🇴' ),
        'FJ' => array( 'name' => 'Fiji', 'prefix' => '+679', 'flag' => '🇫🇯' ),
        'FI' => array( 'name' => 'Finland', 'prefix' => '+358', 'flag' => '🇫🇮' ),
        'FR' => array( 'name' => 'France', 'prefix' => '+33', 'flag' => '🇫🇷' ),
        'GF' => array( 'name' => 'French Guiana', 'prefix' => '+594', 'flag' => '🇬🇫' ),
        'PF' => array( 'name' => 'French Polynesia', 'prefix' => '+689', 'flag' => '🇵🇫' ),
        'GA' => array( 'name' => 'Gabon', 'prefix' => '+241', 'flag' => '🇬🇦' ),
        'GM' => array( 'name' => 'Gambia', 'prefix' => '+220', 'flag' => '🇬🇲' ),
        'GE' => array( 'name' => 'Georgia', 'prefix' => '+995', 'flag' => '🇬🇪' ),
        'DE' => array( 'name' => 'Germany', 'prefix' => '+49', 'flag' => '🇩🇪' ),
        'GH' => array( 'name' => 'Ghana', 'prefix' => '+233', 'flag' => '🇬🇭' ),
        'GR' => array( 'name' => 'Greece', 'prefix' => '+30', 'flag' => '🇬🇷' ),
        'GL' => array( 'name' => 'Greenland', 'prefix' => '+299', 'flag' => '🇬🇱' ),
        'GD' => array( 'name' => 'Grenada', 'prefix' => '+1-473', 'flag' => '🇬🇩' ),
        'GP' => array( 'name' => 'Guadeloupe', 'prefix' => '+590', 'flag' => '🇬🇵' ),
        'GU' => array( 'name' => 'Guam', 'prefix' => '+1-671', 'flag' => '🇬🇺' ),
        'GT' => array( 'name' => 'Guatemala', 'prefix' => '+502', 'flag' => '🇬🇹' ),
        'GG' => array( 'name' => 'Guernsey', 'prefix' => '+44', 'flag' => '🇬🇬' ),
        'GN' => array( 'name' => 'Guinea', 'prefix' => '+224', 'flag' => '🇬🇳' ),
        'GW' => array( 'name' => 'Guinea-Bissau', 'prefix' => '+245', 'flag' => '🇬🇼' ),
        'GY' => array( 'name' => 'Guyana', 'prefix' => '+592', 'flag' => '🇬🇾' ),
        'HT' => array( 'name' => 'Haiti', 'prefix' => '+509', 'flag' => '🇭🇹' ),
        'HN' => array( 'name' => 'Honduras', 'prefix' => '+504', 'flag' => '🇭🇳' ),
        'HK' => array( 'name' => 'Hong Kong', 'prefix' => '+852', 'flag' => '🇭🇰' ),
        'HU' => array( 'name' => 'Hungary', 'prefix' => '+36', 'flag' => '🇭🇺' ),
        'IS' => array( 'name' => 'Iceland', 'prefix' => '+354', 'flag' => '🇮🇸' ),
        'IN' => array( 'name' => 'India', 'prefix' => '+91', 'flag' => '🇮🇳' ),
        'ID' => array( 'name' => 'Indonesia', 'prefix' => '+62', 'flag' => '🇮🇩' ),
        'IR' => array( 'name' => 'Iran', 'prefix' => '+98', 'flag' => '🇮🇷' ),
        'IQ' => array( 'name' => 'Iraq', 'prefix' => '+964', 'flag' => '🇮🇶' ),
        'IE' => array( 'name' => 'Ireland', 'prefix' => '+353', 'flag' => '🇮🇪' ),
        'IM' => array( 'name' => 'Isle of Man', 'prefix' => '+44', 'flag' => '🇮🇲' ),
        'IL' => array( 'name' => 'Israel', 'prefix' => '+972', 'flag' => '🇮🇱' ),
        'IT' => array( 'name' => 'Italy', 'prefix' => '+39', 'flag' => '🇮🇹' ),
        'JM' => array( 'name' => 'Jamaica', 'prefix' => '+1-876', 'flag' => '🇯🇲' ),
        'JP' => array( 'name' => 'Japan', 'prefix' => '+81', 'flag' => '🇯🇵' ),
        'JE' => array( 'name' => 'Jersey', 'prefix' => '+44', 'flag' => '🇯🇪' ),
        'JO' => array( 'name' => 'Jordan', 'prefix' => '+962', 'flag' => '🇯🇴' ),
        'KZ' => array( 'name' => 'Kazakhstan', 'prefix' => '+7', 'flag' => '🇰🇿' ),
        'KE' => array( 'name' => 'Kenya', 'prefix' => '+254', 'flag' => '🇰🇪' ),
        'KI' => array( 'name' => 'Kiribati', 'prefix' => '+686', 'flag' => '🇰🇮' ),
        'XK' => array( 'name' => 'Kosovo', 'prefix' => '+383', 'flag' => '🇽🇰' ),
        'KW' => array( 'name' => 'Kuwait', 'prefix' => '+965', 'flag' => '🇰🇼' ),
        'KG' => array( 'name' => 'Kyrgyzstan', 'prefix' => '+996', 'flag' => '🇰🇬' ),
        'LA' => array( 'name' => 'Laos', 'prefix' => '+856', 'flag' => '🇱🇦' ),
        'LV' => array( 'name' => 'Latvia', 'prefix' => '+371', 'flag' => '🇱🇻' ),
        'LB' => array( 'name' => 'Lebanon', 'prefix' => '+961', 'flag' => '🇱🇧' ),
        'LS' => array( 'name' => 'Lesotho', 'prefix' => '+266', 'flag' => '🇱🇸' ),
        'LR' => array( 'name' => 'Liberia', 'prefix' => '+231', 'flag' => '🇱🇷' ),
        'LY' => array( 'name' => 'Libya', 'prefix' => '+218', 'flag' => '🇱🇾' ),
        'LI' => array( 'name' => 'Liechtenstein', 'prefix' => '+423', 'flag' => '🇱🇮' ),
        'LT' => array( 'name' => 'Lithuania', 'prefix' => '+370', 'flag' => '🇱🇹' ),
        'LU' => array( 'name' => 'Luxembourg', 'prefix' => '+352', 'flag' => '🇱🇺' ),
        'MO' => array( 'name' => 'Macao', 'prefix' => '+853', 'flag' => '🇲🇴' ),
        'MK' => array( 'name' => 'Macedonia', 'prefix' => '+389', 'flag' => '🇲🇰' ),
        'MG' => array( 'name' => 'Madagascar', 'prefix' => '+261', 'flag' => '🇲🇬' ),
        'MW' => array( 'name' => 'Malawi', 'prefix' => '+265', 'flag' => '🇲🇼' ),
        'MY' => array( 'name' => 'Malaysia', 'prefix' => '+60', 'flag' => '🇲🇾' ),
        'MV' => array( 'name' => 'Maldives', 'prefix' => '+960', 'flag' => '🇲🇻' ),
        'ML' => array( 'name' => 'Mali', 'prefix' => '+223', 'flag' => '🇲🇱' ),
        'MT' => array( 'name' => 'Malta', 'prefix' => '+356', 'flag' => '🇲🇹' ),
        'MH' => array( 'name' => 'Marshall Islands', 'prefix' => '+692', 'flag' => '🇲🇭' ),
        'MQ' => array( 'name' => 'Martinique', 'prefix' => '+596', 'flag' => '🇲🇶' ),
        'MR' => array( 'name' => 'Mauritania', 'prefix' => '+222', 'flag' => '🇲🇷' ),
        'MU' => array( 'name' => 'Mauritius', 'prefix' => '+230', 'flag' => '🇲🇺' ),
        'YT' => array( 'name' => 'Mayotte', 'prefix' => '+262', 'flag' => '🇾🇹' ),
        'MX' => array( 'name' => 'Mexico', 'prefix' => '+52', 'flag' => '🇲🇽' ),
        'FM' => array( 'name' => 'Micronesia', 'prefix' => '+691', 'flag' => '🇫🇲' ),
        'MD' => array( 'name' => 'Moldova', 'prefix' => '+373', 'flag' => '🇲🇩' ),
        'MC' => array( 'name' => 'Monaco', 'prefix' => '+377', 'flag' => '🇲🇨' ),
        'MN' => array( 'name' => 'Mongolia', 'prefix' => '+976', 'flag' => '🇲🇳' ),
        'ME' => array( 'name' => 'Montenegro', 'prefix' => '+382', 'flag' => '🇲🇪' ),
        'MS' => array( 'name' => 'Montserrat', 'prefix' => '+1-664', 'flag' => '🇲🇸' ),
        'MA' => array( 'name' => 'Morocco', 'prefix' => '+212', 'flag' => '🇲🇦' ),
        'MZ' => array( 'name' => 'Mozambique', 'prefix' => '+258', 'flag' => '🇲🇿' ),
        'MM' => array( 'name' => 'Myanmar', 'prefix' => '+95', 'flag' => '🇲🇲' ),
        'NA' => array( 'name' => 'Namibia', 'prefix' => '+264', 'flag' => '🇳🇦' ),
        'NR' => array( 'name' => 'Nauru', 'prefix' => '+674', 'flag' => '🇳🇷' ),
        'NP' => array( 'name' => 'Nepal', 'prefix' => '+977', 'flag' => '🇳🇵' ),
        'NL' => array( 'name' => 'Netherlands', 'prefix' => '+31', 'flag' => '🇳🇱' ),
        'NC' => array( 'name' => 'New Caledonia', 'prefix' => '+687', 'flag' => '🇳🇨' ),
        'NZ' => array( 'name' => 'New Zealand', 'prefix' => '+64', 'flag' => '🇳🇿' ),
        'NI' => array( 'name' => 'Nicaragua', 'prefix' => '+505', 'flag' => '🇳🇮' ),
        'NE' => array( 'name' => 'Niger', 'prefix' => '+227', 'flag' => '🇳🇪' ),
        'NG' => array( 'name' => 'Nigeria', 'prefix' => '+234', 'flag' => '🇳🇬' ),
        'NU' => array( 'name' => 'Niue', 'prefix' => '+683', 'flag' => '🇳🇺' ),
        'KP' => array( 'name' => 'North Korea', 'prefix' => '+850', 'flag' => '🇰🇵' ),
        'MP' => array( 'name' => 'Northern Mariana Islands', 'prefix' => '+1-670', 'flag' => '🇲🇵' ),
        'NO' => array( 'name' => 'Norway', 'prefix' => '+47', 'flag' => '🇳🇴' ),
        'OM' => array( 'name' => 'Oman', 'prefix' => '+968', 'flag' => '🇴🇲' ),
        'PK' => array( 'name' => 'Pakistan', 'prefix' => '+92', 'flag' => '🇵🇰' ),
        'PW' => array( 'name' => 'Palau', 'prefix' => '+680', 'flag' => '🇵🇼' ),
        'PS' => array( 'name' => 'Palestine', 'prefix' => '+970', 'flag' => '🇵🇸' ),
        'PA' => array( 'name' => 'Panama', 'prefix' => '+507', 'flag' => '🇵🇦' ),
        'PG' => array( 'name' => 'Papua New Guinea', 'prefix' => '+675', 'flag' => '🇵🇬' ),
        'PY' => array( 'name' => 'Paraguay', 'prefix' => '+595', 'flag' => '🇵🇾' ),
        'PE' => array( 'name' => 'Peru', 'prefix' => '+51', 'flag' => '🇵🇪' ),
        'PH' => array( 'name' => 'Philippines', 'prefix' => '+63', 'flag' => '🇵🇭' ),
        'PL' => array( 'name' => 'Poland', 'prefix' => '+48', 'flag' => '🇵🇱' ),
        'PT' => array( 'name' => 'Portugal', 'prefix' => '+351', 'flag' => '🇵🇹' ),
        'PR' => array( 'name' => 'Puerto Rico', 'prefix' => '+1-787', 'flag' => '🇵🇷' ),
        'QA' => array( 'name' => 'Qatar', 'prefix' => '+974', 'flag' => '🇶🇦' ),
        'RE' => array( 'name' => 'Réunion', 'prefix' => '+262', 'flag' => '🇷🇪' ),
        'RO' => array( 'name' => 'Romania', 'prefix' => '+40', 'flag' => '🇷🇴' ),
        'RU' => array( 'name' => 'Russia', 'prefix' => '+7', 'flag' => '🇷🇺' ),
        'RW' => array( 'name' => 'Rwanda', 'prefix' => '+250', 'flag' => '🇷🇼' ),
        'BL' => array( 'name' => 'Saint Barthélemy', 'prefix' => '+590', 'flag' => '🇧🇱' ),
        'SH' => array( 'name' => 'Saint Helena', 'prefix' => '+290', 'flag' => '🇸🇭' ),
        'KN' => array( 'name' => 'Saint Kitts and Nevis', 'prefix' => '+1-869', 'flag' => '🇰🇳' ),
        'LC' => array( 'name' => 'Saint Lucia', 'prefix' => '+1-758', 'flag' => '🇱🇨' ),
        'MF' => array( 'name' => 'Saint Martin', 'prefix' => '+590', 'flag' => '🇲🇫' ),
        'PM' => array( 'name' => 'Saint Pierre and Miquelon', 'prefix' => '+508', 'flag' => '🇵🇲' ),
        'VC' => array( 'name' => 'Saint Vincent and the Grenadines', 'prefix' => '+1-784', 'flag' => '🇻🇨' ),
        'WS' => array( 'name' => 'Samoa', 'prefix' => '+685', 'flag' => '🇼🇸' ),
        'SM' => array( 'name' => 'San Marino', 'prefix' => '+378', 'flag' => '🇸🇲' ),
        'ST' => array( 'name' => 'São Tomé and Príncipe', 'prefix' => '+239', 'flag' => '🇸🇹' ),
        'SA' => array( 'name' => 'Saudi Arabia', 'prefix' => '+966', 'flag' => '🇸🇦' ),
        'SN' => array( 'name' => 'Senegal', 'prefix' => '+221', 'flag' => '🇸🇳' ),
        'RS' => array( 'name' => 'Serbia', 'prefix' => '+381', 'flag' => '🇷🇸' ),
        'SC' => array( 'name' => 'Seychelles', 'prefix' => '+248', 'flag' => '🇸🇨' ),
        'SL' => array( 'name' => 'Sierra Leone', 'prefix' => '+232', 'flag' => '🇸🇱' ),
        'SG' => array( 'name' => 'Singapore', 'prefix' => '+65', 'flag' => '🇸🇬' ),
        'SX' => array( 'name' => 'Sint Maarten', 'prefix' => '+1-721', 'flag' => '🇸🇽' ),
        'SK' => array( 'name' => 'Slovakia', 'prefix' => '+421', 'flag' => '🇸🇰' ),
        'SI' => array( 'name' => 'Slovenia', 'prefix' => '+386', 'flag' => '🇸🇮' ),
        'SB' => array( 'name' => 'Solomon Islands', 'prefix' => '+677', 'flag' => '🇸🇧' ),
        'SO' => array( 'name' => 'Somalia', 'prefix' => '+252', 'flag' => '🇸🇴' ),
        'ZA' => array( 'name' => 'South Africa', 'prefix' => '+27', 'flag' => '🇿🇦' ),
        'KR' => array( 'name' => 'South Korea', 'prefix' => '+82', 'flag' => '🇰🇷' ),
        'SS' => array( 'name' => 'South Sudan', 'prefix' => '+211', 'flag' => '🇸🇸' ),
        'ES' => array( 'name' => 'Spain', 'prefix' => '+34', 'flag' => '🇪🇸' ),
        'LK' => array( 'name' => 'Sri Lanka', 'prefix' => '+94', 'flag' => '🇱🇰' ),
        'SD' => array( 'name' => 'Sudan', 'prefix' => '+249', 'flag' => '🇸🇩' ),
        'SR' => array( 'name' => 'Suriname', 'prefix' => '+597', 'flag' => '🇸🇷' ),
        'SZ' => array( 'name' => 'Swaziland', 'prefix' => '+268', 'flag' => '🇸🇿' ),
        'SE' => array( 'name' => 'Sweden', 'prefix' => '+46', 'flag' => '🇸🇪' ),
        'CH' => array( 'name' => 'Switzerland', 'prefix' => '+41', 'flag' => '🇨🇭' ),
        'SY' => array( 'name' => 'Syria', 'prefix' => '+963', 'flag' => '🇸🇾' ),
        'TW' => array( 'name' => 'Taiwan', 'prefix' => '+886', 'flag' => '🇹🇼' ),
        'TJ' => array( 'name' => 'Tajikistan', 'prefix' => '+992', 'flag' => '🇹🇯' ),
        'TZ' => array( 'name' => 'Tanzania', 'prefix' => '+255', 'flag' => '🇹🇿' ),
        'TH' => array( 'name' => 'Thailand', 'prefix' => '+66', 'flag' => '🇹🇭' ),
        'TL' => array( 'name' => 'Timor-Leste', 'prefix' => '+670', 'flag' => '🇹🇱' ),
        'TG' => array( 'name' => 'Togo', 'prefix' => '+228', 'flag' => '🇹🇬' ),
        'TO' => array( 'name' => 'Tonga', 'prefix' => '+676', 'flag' => '🇹🇴' ),
        'TT' => array( 'name' => 'Trinidad and Tobago', 'prefix' => '+1-868', 'flag' => '🇹🇹' ),
        'TN' => array( 'name' => 'Tunisia', 'prefix' => '+216', 'flag' => '🇹🇳' ),
        'TR' => array( 'name' => 'Turkey', 'prefix' => '+90', 'flag' => '🇹🇷' ),
        'TM' => array( 'name' => 'Turkmenistan', 'prefix' => '+993', 'flag' => '🇹🇲' ),
        'TC' => array( 'name' => 'Turks and Caicos Islands', 'prefix' => '+1-649', 'flag' => '🇹🇨' ),
        'TV' => array( 'name' => 'Tuvalu', 'prefix' => '+688', 'flag' => '🇹🇻' ),
        'UG' => array( 'name' => 'Uganda', 'prefix' => '+256', 'flag' => '🇺🇬' ),
        'UA' => array( 'name' => 'Ukraine', 'prefix' => '+380', 'flag' => '🇺🇦' ),
        'AE' => array( 'name' => 'United Arab Emirates', 'prefix' => '+971', 'flag' => '🇦🇪' ),
        'GB' => array( 'name' => 'United Kingdom', 'prefix' => '+44', 'flag' => '🇬🇧' ),
        'US' => array( 'name' => 'United States', 'prefix' => '+1', 'flag' => '🇺🇸' ),
        'UY' => array( 'name' => 'Uruguay', 'prefix' => '+598', 'flag' => '🇺🇾' ),
        'UZ' => array( 'name' => 'Uzbekistan', 'prefix' => '+998', 'flag' => '🇺🇿' ),
        'VU' => array( 'name' => 'Vanuatu', 'prefix' => '+678', 'flag' => '🇻🇺' ),
        'VA' => array( 'name' => 'Vatican City', 'prefix' => '+379', 'flag' => '🇻🇦' ),
        'VE' => array( 'name' => 'Venezuela', 'prefix' => '+58', 'flag' => '🇻🇪' ),
        'VN' => array( 'name' => 'Vietnam', 'prefix' => '+84', 'flag' => '🇻🇳' ),
        'WF' => array( 'name' => 'Wallis and Futuna', 'prefix' => '+681', 'flag' => '🇼🇫' ),
        'EH' => array( 'name' => 'Western Sahara', 'prefix' => '+212', 'flag' => '🇪🇭' ),
        'YE' => array( 'name' => 'Yemen', 'prefix' => '+967', 'flag' => '🇾🇪' ),
        'ZM' => array( 'name' => 'Zambia', 'prefix' => '+260', 'flag' => '🇿🇲' ),
        'ZW' => array( 'name' => 'Zimbabwe', 'prefix' => '+263', 'flag' => '🇿🇼' ),
    );

    // Create option elements for each country.
    foreach ( $countries as $code => $data ) {
        $html .= sprintf(
            '<option value="%s" data-prefix="%s">%s %s</option>',
            esc_attr( $code ),
            esc_attr( $data['prefix'] ),
            esc_html( $data['flag'] ),
            esc_html( $data['name'] )
        );
    }
    $html .= '</select>';

    // Create the telephone input field. JavaScript will prepend the selected country's prefix.
    $html .= sprintf(
        '<input type="tel" name="%s" class="cf7-phone-country-flag-input" value="" />',
        esc_attr( $tag->name )
    );
    $html .= '</div>';

    return $html;
}

/**
 * Register the custom form tag with Contact Form 7.
 */
function cf7_country_flag_add_shortcode() {
    wpcf7_add_form_tag( 'phone-flag', 'cf7_country_flag_form_tag_handler', true );
}
add_action( 'wpcf7_init', 'cf7_country_flag_add_shortcode' );
