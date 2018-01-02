<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Henvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Fresh\SinchBundle\Helper;

/**
 * SinchSupportedCountries.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class SinchSupportedCountries
{
    /**
     * @const array
     */
    public const SUPPORTED_COUNTRIES = [
        'AF' => 'Afghanistan',
        'AM' => 'Armenia',
        'AR' => 'Argentina',
        'AT' => 'Austria',
        'AU' => 'Australia',
        'AZ' => 'Azerbaijan',
        'BA' => 'Bosnia & Herzegovina',
        'BD' => 'Bangladesh',
        'BE' => 'Belgium',
        'BG' => 'Bulgaria',
        'BH' => 'Bahrain',
        'BO' => 'Bolivia',
        'BR' => 'Brazil',
        'BS' => 'Bahamas',
        'BY' => 'Belarus',
        'CA' => 'Canada',
        'CD' => 'Congo - Kinshasa',
        'CF' => 'Central African Republic',
        'CG' => 'Congo - Brazzaville',
        'CH' => 'Switzerland',
        'CL' => 'Chile',
        'CN' => 'China',
        'CO' => 'Colombia',
        'CR' => 'Costa Rica',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DE' => 'Germany',
        'DK' => 'Denmark',
        'DZ' => 'Algeria',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'ES' => 'Spain',
        'FI' => 'Finland',
        'FR' => 'France',
        'GA' => 'Gabon',
        'GB' => 'United Kingdom',
        'GE' => 'Georgia',
        'GH' => 'Ghana',
        'GM' => 'Gambia',
        'GR' => 'Greece',
        'HK' => 'Hong Kong SAR China',
        'HR' => 'Croatia',
        'HU' => 'Hungary',
        'ID' => 'Indonesia',
        'IE' => 'Ireland',
        'IL' => 'Israel',
        'IN' => 'India',
        'IR' => 'Iran',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'KE' => 'Kenya',
        'KG' => 'Kyrgyzstan',
        'KH' => 'Cambodia',
        'KR' => 'South Korea',
        'KW' => 'Kuwait',
        'KZ' => 'Kazakhstan',
        'LB' => 'Lebanon',
        'LR' => 'Liberia',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'LV' => 'Latvia',
        'LY' => 'Libya',
        'MA' => 'Morocco',
        'MC' => 'Monaco',
        'MD' => 'Moldova',
        'MK' => 'Macedonia',
        'MN' => 'Mongolia',
        'MX' => 'Mexico',
        'MY' => 'Malaysia',
        'NG' => 'Nigeria',
        'NL' => 'Netherlands',
        'NO' => 'Norway',
        'NZ' => 'New Zealand',
        'PA' => 'Panama',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PK' => 'Pakistan',
        'PL' => 'Poland',
        'PR' => 'Puerto Rico',
        'PT' => 'Portugal',
        'PY' => 'Paraguay',
        'QA' => 'Qatar',
        'RO' => 'Romania',
        'RU' => 'Russia',
        'SA' => 'Saudi Arabia',
        'SD' => 'Sudan',
        'SE' => 'Sweden',
        'SG' => 'Singapore',
        'SI' => 'Slovenia',
        'SK' => 'Slovakia',
        'SN' => 'Senegal',
        'SY' => 'Syria',
        'TH' => 'Thailand',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TW' => 'Taiwan',
        'UA' => 'Ukraine',
        'UG' => 'Uganda',
        'US' => 'United States',
        'UY' => 'Uruguay',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
        'ZA' => 'South Africa',
    ];

    /**
     * @param string $countryCode
     *
     * @return bool
     */
    public static function isCountrySupported(string $countryCode): bool
    {
        return isset(self::SUPPORTED_COUNTRIES[$countryCode]);
    }
}
