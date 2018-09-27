<?php

namespace PAG\Bridges\Sherlocks;


class RequestInfo
{
    use AssertDefined;
    use NoExceptionTostring;

    public  $merchant_id;
    public  $pathfile;
    public  $merchant_country = 'fr';
    public  $amount;
    public  $currency_code    = "978"; // for euros;
    public  $transaction_id;
    public  $language         = 'fr';
    public  $payment_means    = 'CB,1,VISA,1,MASTERCARD,1';
    public  $bgcolor;
    public  $block_align      = 'center';
    public  $textcolor;
    public  $caddie;
    public  $customer_id;
    public  $customer_email;
    public  $target           = '_self';
    public  $order_id;
    public  $automatic_response_url;
    public  $normal_return_url;
    public  $cancel_return_url;
    public  $header_flag;
    public  $capture_day;
    public  $capture_mode;
    public  $block_order;
    public  $receipt_complement;
    public  $customer_ip_address;
    public  $return_context;
    public  $customer_title;
    public  $customer_name;
    public  $customer_firstname;
    public  $customer_birthdate;
    public  $customer_phone;
    public  $customer_mobile_phone;
    public  $customer_nationality_country;
    public  $customer_birth_zipcode;
    public  $customer_birth_city;
    public  $home_city;
    public  $home_streetnumber;
    public  $home_street;
    public  $home_zipcode;
    public  $normal_return_logo;
    public  $cancel_return_logo;
    public  $submit_logo;
    public  $logo_id;
    public  $logo_id2;
    public  $advert;
    public  $background_id;
    public  $templatefile;
    private $data;

    protected function __construct(CompactSemicolonData $data)
    {
        $this->data = $data;
    }

    public static function makeAllowEurope(string $zipcode, string $city):RequestInfo
    {
        $allowCardCountries = [
            "FRA",
            "BEL",
            "GBR",
            "DEU",
            "AUT",
            "DNK",
            "ESP",
            "FIN",
            "GRC",
            "IRL",
            "ITA",
            "LUX",
            "NLD",
            "PRT",
            "SWE",
        ];

        $allowCardCountries = join(',', $allowCardCountries);
        $data               = new CompactSemicolonData([
            "DATA_MERCHANT_ZIPCODE=$zipcode",
            "DATA_MERCHANT_CITY=$city",
            "<CONTROLS>ALLOW_CARD_CTRY=$allowCardCountries;</CONTROLS>",
            "SWITCH_OFF_RATE",
            "CARD_NO_LOGO",
            "NO_COPYRIGHT",
            "NO_RESPONSE_PAGE",
        ]);
        return new RequestInfo($data);
    }

    public static function makeDefault(string $zipcode, string $city):RequestInfo
    {
        return new RequestInfo(new CompactSemicolonData([
            "DATA_MERCHANT_ZIPCODE=$zipcode",
            "DATA_MERCHANT_CITY=$city",
            "SWITCH_OFF_RATE",
            "CARD_NO_LOGO",
            "NO_COPYRIGHT",
            "NO_RESPONSE_PAGE",
        ]));
    }

    public function computeString():string
    {
        $options = [];
        $this->assertDefined('merchant_id', 'pathfile', 'amount', 'transaction_id');
        foreach ($this as $optionName => $optionValue) {
            if (!$optionValue) {
                continue;
            }
            $options[] = "$optionName=$optionValue";
        }

        return ' ' . escapeshellcmd(join(' ', $options));
    }
}