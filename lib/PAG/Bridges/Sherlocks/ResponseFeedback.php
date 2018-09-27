<?php

namespace PAG\Bridges\Sherlocks;

class ResponseFeedback
{
    private $code;
    private $error;
    private $merchant_id;
    private $merchant_country;
    private $amount;
    private $transaction_id;
    private $payment_means;
    private $transmission_date;
    private $payment_time;
    private $payment_date;
    private $response_code;
    private $payment_certificate;
    private $authorisation_id;
    private $currency_code;
    private $card_number;
    private $cvv_flag;
    private $cvv_response_code;
    private $bank_response_code;
    private $complementary_code;
    private $complementary_info;
    private $return_context;
    private $caddie;
    private $receipt_complement;
    private $merchant_language;
    private $language;
    private $customer_id;
    private $order_id;
    private $customer_email;
    private $customer_ip_address;
    private $capture_day;
    private $capture_mode;
    private $data;
    private $order_validity;
    private $transaction_condition;
    private $statement_reference;
    private $card_validity;
    private $score_value;
    private $score_color;
    private $score_info;
    private $score_threshold;
    private $score_profile;
    private $threed_ls_code;
    private $threed_relegation_code;

    public function __construct(string $feedback)
    {
        $parsed                       = explode("!", $feedback);
        $this->code                   = $parsed[1];
        $this->error                  = $parsed[2];
        $this->merchant_id            = $parsed[3];
        $this->merchant_country       = $parsed[4];
        $this->amount                 = $parsed[5];
        $this->transaction_id         = $parsed[6];
        $this->payment_means          = $parsed[7];
        $this->transmission_date      = $parsed[8];
        $this->payment_time           = $parsed[9];
        $this->payment_date           = $parsed[10];
        $this->response_code          = $parsed[11];
        $this->payment_certificate    = $parsed[12];
        $this->authorisation_id       = $parsed[13];
        $this->currency_code          = $parsed[14];
        $this->card_number            = $parsed[15];
        $this->cvv_flag               = $parsed[16];
        $this->cvv_response_code      = $parsed[17];
        $this->bank_response_code     = $parsed[18];
        $this->complementary_code     = $parsed[19];
        $this->complementary_info     = $parsed[20];
        $this->return_context         = $parsed[21];
        $this->caddie                 = $parsed[22];
        $this->receipt_complement     = $parsed[23];
        $this->merchant_language      = $parsed[24];
        $this->language               = $parsed[25];
        $this->customer_id            = $parsed[26];
        $this->order_id               = $parsed[27];
        $this->customer_email         = $parsed[28];
        $this->customer_ip_address    = $parsed[29];
        $this->capture_day            = $parsed[30];
        $this->capture_mode           = $parsed[31];
        $this->data                   = $parsed[32];
        $this->order_validity         = $parsed[33];
        $this->transaction_condition  = $parsed[34];
        $this->statement_reference    = $parsed[35];
        $this->card_validity          = $parsed[36];
        $this->score_value            = $parsed[37];
        $this->score_color            = $parsed[38];
        $this->score_info             = $parsed[39];
        $this->score_threshold        = $parsed[40];
        $this->score_profile          = $parsed[41];
        $this->threed_ls_code         = $parsed[43];
        $this->threed_relegation_code = $parsed[44];
    }

    public function isInvalid(): bool
    {
        return $this->code == '' && $this->error == '';
    }

    public function isError(): bool
    {
        return $this->code != 0;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getMerchantId(): string
    {
        return $this->merchant_id;
    }

    public function getMerchantCountry(): string
    {
        return $this->merchant_country;
    }

    public function getAmountInCents(): string
    {
        return $this->amount;
    }

    public function getTransactionId(): string
    {
        return $this->transaction_id;
    }

    public function getPaymentMeans(): string
    {
        return $this->payment_means;
    }

    public function getTransmissionDate(): string
    {
        return $this->transmission_date;
    }

    public function getPaymentTime(): string
    {
        return $this->payment_time;
    }

    public function getPaymentDate(): string
    {
        return $this->payment_date;
    }

    public function getPaymentCertificate(): string
    {
        return $this->payment_certificate;
    }

    public function getAuthorisationId(): string
    {
        return $this->authorisation_id;
    }

    public function getCurrencyCode(): string
    {
        return $this->currency_code;
    }

    public function getCardNumber(): string
    {
        return $this->card_number;
    }

    public function getCvvFlag(): string
    {
        return $this->cvv_flag;
    }

    public function getCvvResponseCode(): string
    {
        return $this->cvv_response_code;
    }

    public function getBankResponseCode(): string
    {
        return $this->bank_response_code;
    }

    public function getAsAssociativeArray(): array
    {
        $array = [];
        foreach ($this as $key => $value) {
            $array[$key] = $value;
        }
        return $array;
    }

    public function getCaddie(): string
    {
        return $this->caddie;
    }

    public function getCaptureDay(): string
    {
        return $this->capture_day;
    }

    public function getComplementaryCode(): string
    {
        return $this->complementary_code;
    }

    public function getComplementaryInfo(): string
    {
        return $this->complementary_info;
    }

    public function getReturnContext(): string
    {
        return $this->return_context;
    }

    public function getReceiptComplement(): string
    {
        return $this->receipt_complement;
    }

    public function getMerchantLanguage(): string
    {
        return $this->merchant_language;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getCustomerId(): string
    {
        return $this->customer_id;
    }

    public function getOrderId(): string
    {
        return $this->order_id;
    }

    public function getCustomerEmail(): string
    {
        return $this->customer_email;
    }

    public function getCustomerIpAddress(): string
    {
        return $this->customer_ip_address;
    }

    public function getCaptureMode(): string
    {
        return $this->capture_mode;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function getOrderValidity(): string
    {
        return $this->order_validity;
    }

    public function getTransactionCondition(): string
    {
        return $this->transaction_condition;
    }

    public function getStatementReference(): string
    {
        return $this->statement_reference;
    }

    public function getCardValidity(): string
    {
        return $this->card_validity;
    }

    public function getScoreValue(): string
    {
        return $this->score_value;
    }

    public function getScoreColor(): string
    {
        return $this->score_color;
    }

    public function getScoreInfo(): string
    {
        return $this->score_info;
    }

    public function getScoreThreshold(): string
    {
        return $this->score_threshold;
    }

    public function getScoreProfile(): string
    {
        return $this->score_profile;
    }

    public function getThreedLsCode(): string
    {
        return $this->threed_ls_code;
    }

    public function getThreedRelegationCode(): string
    {
        return $this->threed_relegation_code;
    }

    public function getConvertedErrorString(): string
    {
        return self::convertErrorString($this->getResponseCode());
    }

    private static function convertErrorString($errorCode)
    {
        $codes = [
            "02" => "Contacter l'&eacute;metteur de la carte",
            "03" => "Accepteur invalide",
            "04" => "Conserver la carte",
            "05" => "Ne pas honorer",
            "07" => "Conserver la carte, conditions sp&eacute;ciales",
            "08" => "Approuver apres identification",
            "12" => "Transaction invalide, v&eacute;rifier les param&eacute;tres transf&eacute;r&eacute;s dans la requete.",
            "13" => "Montant invalide",
            "14" => "Numero de porteur invalide",
            "15" => "Emetteur de carte inconnu",
            "17" => "Annulation client",
            "19" => "R&eacute;p&eacute;ter la transaction ulterieurement",
            "20" => "R&eacute;ponse erron&eacute;e (erreur dans le domaine serveur)",
            "24" => "Mise a jour de fichier non support&eacute;e",
            "25" => "Impossible de localiser l'enregistrement dans le fichier",
            "26" => "Enregistrement dupliqu&eacute;, anicien enregistrement remplac&eacute;",
            "27" => "erreur en edit sur champ de mise a jour fichier",
            "28" => "Acces interdit au fichier",
            "29" => "Mise a jour fichier impossible",
            "30" => "Erreur de format",
            "31" => "Identifiant de l'organisme acquereur inconnu",
            "33" => "Date de validite de la carte d&eacute;pass&eacute;e",
            "34" => "Suspicion de fraude",
            "38" => "nombre d'essai code confidentiel d&eacute;pass&eacute;",
            "41" => "Carte perdue",
            "43" => "Carte vol&eacute;e",
            "51" => "Provision insuffisante ou cr&eacute;dit d&eacute;pass&eacute;",
            "54" => "Date de validit&eacute; de la carte d&eacute;pass&eacute;e",
            "55" => "Code confidentiel erron&eacute;",
            "56" => "Carte absente du fichier",
            "57" => "Transaction non permise &agrave; ce porteur",
            "58" => "Transaction interdite au terminal",
            "59" => "Suspicion de fraude",
            "60" => "l'accepteur de la carte doit contacter l'acqu&eacute;reur",
            "61" => "Montant de retrait hors limite",
            "63" => "regles de s&eacute;curit&eacute; non respect&eacute;es",
            "68" => "R&eacute;ponse non parvenue ou reçue trop tard",
            "75" => "Nombre d'essai code confidentiel d&eacute;pass&eacute;",
            "76" => "porteur d&eacute;j&agrave; en opposition, ancien enregistrement conserv&eacute;",
            "90" => "Arret momentan&eacute; du systeme",
            "91" => "Emetteur de carte inaccessible",
            "94" => "Transaction duppliqu&eacute;e",
            "96" => "Mauvais fonctionnement du systeme",
            "97" => "Ech&eacute;ance de la temporisation de surveillance globale",
            "98" => "Serveur indisponible routage r&eacute;seau demand&eacute; &agrave; nouveau",
            "99" => "Ech&eacute;ance de la temporisation de surveillance globale",
        ];

        if (array_key_exists($errorCode, $codes)) {
            return $codes[$errorCode];
        }

        return "Num&eacute;ro d'erreur non identifi&eacute;e";
    }

    public function getResponseCode(): string
    {
        return $this->response_code;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function isMalicious(): bool
    {
        return in_array($this->code, [4, 34, 43, 59]);
    }

    public function isDuplicate(): bool
    {
        return in_array($this->code, [26, 94]);
    }

    public function isCancelled(): bool
    {
        return $this->code == 17;
    }
}