<?php

namespace Elementor;

if ( !class_exists( 'Restrict_Elementor_Addon_Location' ) ) {

    class Restrict_Elementor_Addon_Location {

        function __construct() {
            add_filter( 'restrict_for_elementor_show_to_main_options', array( $this, 'show_to_main_options' ) );
            add_filter( 'restrict_for_elementor_should_render_location', array( $this, 'should_render' ), 10, 2 );
            add_action( 'restrict_for_elementor_add_controls', array( $this, 'add_control' ), 10, 2 );
        }

        function show_to_main_options( $array ) {
            $array[ 'location' ] = __( 'Visitor location', 'restrict-for-elementor' );
            return $array;
        }

        function is_matching( $selected, $ip, $type = 'country' ) {

            if ( !is_array( $selected ) ) {
                $selected = explode( '|', $selected ); // Fix for string value (when only one element is selected, the value is string instead of an array)
            }

            $response = geoip_detect2_get_info_from_ip( $ip );

            switch ( $type ) {

                case 'continent':
                    $value = ( isset( $response->raw[ 'continent' ] ) && $response->raw[ 'continent' ][ 'code' ] ) ? $response->raw[ 'continent' ][ 'code' ] : '';
                    break;

                case 'currency':
                    $value = ( isset( $response->raw[ 'extra' ] ) && $response->raw[ 'extra' ][ 'currency_code' ] ) ? $response->raw[ 'extra' ][ 'currency_code' ] : '';
                    break;

                default:
                    $value = ( isset( $response->raw[ 'country' ] ) && $response->raw[ 'country' ][ 'iso_code' ] ) ? $response->raw[ 'country' ][ 'iso_code' ] : '';
            }

            try {
                return ( in_array( $value, $selected ) ) ? true : false;

            } catch ( Exception $ex ) {
                return false;
            }
        }

        function should_render( $should_render, $settings ) {

            if ( !empty( $settings[ 'restrict_for_elementor_show_to' ] ) ) {

                $action = ( !isset( $settings[ 'restrict_for_elementor_action' ] ) || ( isset( $settings[ 'restrict_for_elementor_action' ] ) && $settings[ 'restrict_for_elementor_action' ] !== 'yes' ) ) ? 'hide' : 'show';

                if ( $settings[ 'restrict_for_elementor_show_to' ] == 'location' && !empty( $settings[ 'location_options_selection' ] ) ) {

                    $country_selection = $settings[ 'country_selection' ];
                    $continent_selection = $settings[ 'continent_selection' ];
                    $ip = ( apply_filters( 'restrict_for_elementor_match_client_ip_location', false ) ) ? geoip_detect2_get_client_ip() : geoip_detect2_get_external_ip_adress();

                    if (
                        $settings[ 'location_options_selection' ] == 'country'
                        && $this->is_matching( $country_selection, $ip, 'country' )
                    ) {
                        $should_render = ( $action == 'show' ) ? true : false;

                    } elseif (
                        $settings[ 'location_options_selection' ] == 'continent'
                        && $this->is_matching( $continent_selection, $ip, 'continent' )
                    ) {
                        $should_render = ( $action == 'show' ) ? true : false;

                    } else {
                        $should_render = ( $action == 'show' ) ? false : true;
                        do_action( 'restrict_for_elementor_rest_api' );
                    }
                }
            }

            return $should_render;
        }

        function add_control( $element, $args ) {
            $element->add_control(
                'restrict_for_elementor_location_hr',
                [
                    'type' => Controls_Manager::DIVIDER,
                    'condition' => [
                        'restrict_for_elementor_show_to' => 'location',
                    ],
                ]
            );

            $element->add_control(
                'location_options_selection',
                [
                    'label' => __( 'Type:', 'restrict-for-elementor' ),
                    'label_block' => true,
                    'type' => Controls_Manager::SELECT,
                    'options' => array(
                        'country' => __( 'Country', 'restrict-for-elementor' ),
                        'continent' => __( 'Continent', 'restrict-for-elementor' ),
                    ),
                    'default' => 'country',
                    'condition' => [
                        'restrict_for_elementor_show_to' => 'location',
                    ],
                ]
            );

            $countries = array
            (
                'AF' => 'Afghanistan',
                'AX' => 'Aland Islands',
                'AL' => 'Albania',
                'DZ' => 'Algeria',
                'AS' => 'American Samoa',
                'AD' => 'Andorra',
                'AO' => 'Angola',
                'AI' => 'Anguilla',
                'AQ' => 'Antarctica',
                'AG' => 'Antigua And Barbuda',
                'AR' => 'Argentina',
                'AM' => 'Armenia',
                'AW' => 'Aruba',
                'AU' => 'Australia',
                'AT' => 'Austria',
                'AZ' => 'Azerbaijan',
                'BS' => 'Bahamas',
                'BH' => 'Bahrain',
                'BD' => 'Bangladesh',
                'BB' => 'Barbados',
                'BY' => 'Belarus',
                'BE' => 'Belgium',
                'BZ' => 'Belize',
                'BJ' => 'Benin',
                'BM' => 'Bermuda',
                'BT' => 'Bhutan',
                'BO' => 'Bolivia',
                'BA' => 'Bosnia And Herzegovina',
                'BW' => 'Botswana',
                'BV' => 'Bouvet Island',
                'BR' => 'Brazil',
                'IO' => 'British Indian Ocean Territory',
                'BN' => 'Brunei Darussalam',
                'BG' => 'Bulgaria',
                'BF' => 'Burkina Faso',
                'BI' => 'Burundi',
                'KH' => 'Cambodia',
                'CM' => 'Cameroon',
                'CA' => 'Canada',
                'CV' => 'Cape Verde',
                'KY' => 'Cayman Islands',
                'CF' => 'Central African Republic',
                'TD' => 'Chad',
                'CL' => 'Chile',
                'CN' => 'China',
                'CX' => 'Christmas Island',
                'CC' => 'Cocos (Keeling) Islands',
                'CO' => 'Colombia',
                'KM' => 'Comoros',
                'CG' => 'Congo',
                'CD' => 'Congo, Democratic Republic',
                'CK' => 'Cook Islands',
                'CR' => 'Costa Rica',
                'CI' => 'Cote D\'Ivoire',
                'HR' => 'Croatia',
                'CU' => 'Cuba',
                'CY' => 'Cyprus',
                'CZ' => 'Czech Republic',
                'DK' => 'Denmark',
                'DJ' => 'Djibouti',
                'DM' => 'Dominica',
                'DO' => 'Dominican Republic',
                'EC' => 'Ecuador',
                'EG' => 'Egypt',
                'SV' => 'El Salvador',
                'GQ' => 'Equatorial Guinea',
                'ER' => 'Eritrea',
                'EE' => 'Estonia',
                'ET' => 'Ethiopia',
                'FK' => 'Falkland Islands (Malvinas)',
                'FO' => 'Faroe Islands',
                'FJ' => 'Fiji',
                'FI' => 'Finland',
                'FR' => 'France',
                'GF' => 'French Guiana',
                'PF' => 'French Polynesia',
                'TF' => 'French Southern Territories',
                'GA' => 'Gabon',
                'GM' => 'Gambia',
                'GE' => 'Georgia',
                'DE' => 'Germany',
                'GH' => 'Ghana',
                'GI' => 'Gibraltar',
                'GR' => 'Greece',
                'GL' => 'Greenland',
                'GD' => 'Grenada',
                'GP' => 'Guadeloupe',
                'GU' => 'Guam',
                'GT' => 'Guatemala',
                'GG' => 'Guernsey',
                'GN' => 'Guinea',
                'GW' => 'Guinea-Bissau',
                'GY' => 'Guyana',
                'HT' => 'Haiti',
                'HM' => 'Heard Island & Mcdonald Islands',
                'VA' => 'Holy See (Vatican City State)',
                'HN' => 'Honduras',
                'HK' => 'Hong Kong',
                'HU' => 'Hungary',
                'IS' => 'Iceland',
                'IN' => 'India',
                'ID' => 'Indonesia',
                'IR' => 'Iran, Islamic Republic Of',
                'IQ' => 'Iraq',
                'IE' => 'Ireland',
                'IM' => 'Isle Of Man',
                'IL' => 'Israel',
                'IT' => 'Italy',
                'JM' => 'Jamaica',
                'JP' => 'Japan',
                'JE' => 'Jersey',
                'JO' => 'Jordan',
                'KZ' => 'Kazakhstan',
                'KE' => 'Kenya',
                'KI' => 'Kiribati',
                'KR' => 'Korea',
                'KW' => 'Kuwait',
                'KG' => 'Kyrgyzstan',
                'LA' => 'Lao People\'s Democratic Republic',
                'LV' => 'Latvia',
                'LB' => 'Lebanon',
                'LS' => 'Lesotho',
                'LR' => 'Liberia',
                'LY' => 'Libyan Arab Jamahiriya',
                'LI' => 'Liechtenstein',
                'LT' => 'Lithuania',
                'LU' => 'Luxembourg',
                'MO' => 'Macao',
                'MK' => 'Macedonia',
                'MG' => 'Madagascar',
                'MW' => 'Malawi',
                'MY' => 'Malaysia',
                'MV' => 'Maldives',
                'ML' => 'Mali',
                'MT' => 'Malta',
                'MH' => 'Marshall Islands',
                'MQ' => 'Martinique',
                'MR' => 'Mauritania',
                'MU' => 'Mauritius',
                'YT' => 'Mayotte',
                'MX' => 'Mexico',
                'FM' => 'Micronesia, Federated States Of',
                'MD' => 'Moldova',
                'MC' => 'Monaco',
                'MN' => 'Mongolia',
                'ME' => 'Montenegro',
                'MS' => 'Montserrat',
                'MA' => 'Morocco',
                'MZ' => 'Mozambique',
                'MM' => 'Myanmar',
                'NA' => 'Namibia',
                'NR' => 'Nauru',
                'NP' => 'Nepal',
                'NL' => 'Netherlands',
                'AN' => 'Netherlands Antilles',
                'NC' => 'New Caledonia',
                'NZ' => 'New Zealand',
                'NI' => 'Nicaragua',
                'NE' => 'Niger',
                'NG' => 'Nigeria',
                'NU' => 'Niue',
                'NF' => 'Norfolk Island',
                'MP' => 'Northern Mariana Islands',
                'NO' => 'Norway',
                'OM' => 'Oman',
                'PK' => 'Pakistan',
                'PW' => 'Palau',
                'PS' => 'Palestinian Territory, Occupied',
                'PA' => 'Panama',
                'PG' => 'Papua New Guinea',
                'PY' => 'Paraguay',
                'PE' => 'Peru',
                'PH' => 'Philippines',
                'PN' => 'Pitcairn',
                'PL' => 'Poland',
                'PT' => 'Portugal',
                'PR' => 'Puerto Rico',
                'QA' => 'Qatar',
                'RE' => 'Reunion',
                'RO' => 'Romania',
                'RU' => 'Russian Federation',
                'RW' => 'Rwanda',
                'BL' => 'Saint Barthelemy',
                'SH' => 'Saint Helena',
                'KN' => 'Saint Kitts And Nevis',
                'LC' => 'Saint Lucia',
                'MF' => 'Saint Martin',
                'PM' => 'Saint Pierre And Miquelon',
                'VC' => 'Saint Vincent And Grenadines',
                'WS' => 'Samoa',
                'SM' => 'San Marino',
                'ST' => 'Sao Tome And Principe',
                'SA' => 'Saudi Arabia',
                'SN' => 'Senegal',
                'RS' => 'Serbia',
                'SC' => 'Seychelles',
                'SL' => 'Sierra Leone',
                'SG' => 'Singapore',
                'SK' => 'Slovakia',
                'SI' => 'Slovenia',
                'SB' => 'Solomon Islands',
                'SO' => 'Somalia',
                'ZA' => 'South Africa',
                'GS' => 'South Georgia And Sandwich Isl.',
                'ES' => 'Spain',
                'LK' => 'Sri Lanka',
                'SD' => 'Sudan',
                'SR' => 'Suriname',
                'SJ' => 'Svalbard And Jan Mayen',
                'SZ' => 'Swaziland',
                'SE' => 'Sweden',
                'CH' => 'Switzerland',
                'SY' => 'Syrian Arab Republic',
                'TW' => 'Taiwan',
                'TJ' => 'Tajikistan',
                'TZ' => 'Tanzania',
                'TH' => 'Thailand',
                'TL' => 'Timor-Leste',
                'TG' => 'Togo',
                'TK' => 'Tokelau',
                'TO' => 'Tonga',
                'TT' => 'Trinidad And Tobago',
                'TN' => 'Tunisia',
                'TR' => 'Turkey',
                'TM' => 'Turkmenistan',
                'TC' => 'Turks And Caicos Islands',
                'TV' => 'Tuvalu',
                'UG' => 'Uganda',
                'UA' => 'Ukraine',
                'AE' => 'United Arab Emirates',
                'GB' => 'United Kingdom',
                'US' => 'United States',
                'UM' => 'United States Outlying Islands',
                'UY' => 'Uruguay',
                'UZ' => 'Uzbekistan',
                'VU' => 'Vanuatu',
                'VE' => 'Venezuela',
                'VN' => 'Viet Nam',
                'VG' => 'Virgin Islands, British',
                'VI' => 'Virgin Islands, U.S.',
                'WF' => 'Wallis And Futuna',
                'EH' => 'Western Sahara',
                'YE' => 'Yemen',
                'ZM' => 'Zambia',
                'ZW' => 'Zimbabwe',
            );

            $element->add_control(

                'country_selection',
                [
                    'label' => __( 'Country:', 'restrict-for-elementor' ),
                    'label_block' => true,
                    'type' => Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => $countries,
                    'conditions' => [
                        'relation' => 'and',
                        'terms' => [
                            [
                                'name' => 'restrict_for_elementor_show_to',
                                'operator' => '=',
                                'value' => 'location'
                            ],
                            [
                                'name' => 'location_options_selection',
                                'operator' => '==',
                                'value' => 'country'
                            ],

                        ]
                    ],
                ]
            );


            $element->add_control(

                'continent_selection',
                [
                    'label' => __( 'Continent:', 'restrict-for-elementor' ),
                    'label_block' => true,
                    'type' => Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => array(
                        'AF' => __( 'Africa', 'restrict-for-elementor' ),
                        'AN' => __( 'Antarctica', 'restrict-for-elementor' ),
                        'AS' => __( 'Asia', 'restrict-for-elementor' ),
                        'EU' => __( 'Europe', 'restrict-for-elementor' ),
                        'NA' => __( 'North America', 'restrict-for-elementor' ),
                        'OC' => __( 'Oceania', 'restrict-for-elementor' ),
                        'SA' => __( 'South America', 'restrict-for-elementor' ),
                    ),
                    'default' => 'EU',
                    'conditions' => [
                        'relation' => 'and',
                        'terms' => [
                            [
                                'name' => 'restrict_for_elementor_show_to',
                                'operator' => '=',
                                'value' => 'location'
                            ],
                            [
                                'name' => 'location_options_selection',
                                'operator' => '==',
                                'value' => 'continent'
                            ],

                        ]
                    ],
                ]
            );
        }
    }

    new Restrict_Elementor_Addon_Location();
}
