<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice".
 *
 * @property integer $id
 * @property integer $internal_number
 * @property string $vs
 * @property string $date_1
 * @property string $date_2
 * @property string $date_3
 * @property string $date_4
 * @property integer $id_supplier
 * @property double $price
 * @property double $dph
 * @property integer $currency
 * @property string $account_prefix
 * @property string $account_number
 * @property string $bank_code
 * @property string $iban
 * @property string $swift
 * @property string $ks
 * @property string $debet_info
 * @property string $comment
 * @property string $status
 * @property string $created_at 
 * @property string $updated_at 
 *
 * @property Supplier $idSupplier
 */
class MainModel extends \yii\base\Model {

    const EUR = 0;
    const USD = 1;
    const CZK = 2;
    const HUF = 3;

    public static function getCurrencyList() {
        return [
            SELF::EUR => "EUR",
            SELF::USD => "USD",
            SELF::CZK => "CZK",
            SELF::HUF => "HUF",
        ];
    }
    
    public static function getCurrencyName($currencyId) {
        $currencyList = MainModel::getCurrencyList();
        
        return isset($currencyList[$currencyId]) ? $currencyList[$currencyId] : "EUR";
    }

    public function getCislafirmy() {
        return [
            "1" => "Linwe/KRAFT",
            "2" => "Printline",
            "3" => "KraftGROUP",
            "4" => "Lwk, s.r.o.",
            "9" => "P78, s.r.o."
        ];
    }

    public function getTypydokladu() {
        return [
            "51" => "domáce faktúry vystavené (ostré)",
            "52" => "zahraničné faktúry vystavené (ostré)",
            "53" => "domáce faktúry vystavene (zálohové)",
            "54" => "zahraničné faktúry vystavené (zálohové)",
            "55" => "domáce vystavené dobropisy",
            "56" => "zahraničné vystavené dobropisy",
            "91" => "domáce faktúry došlé (ostré)",
            "92" => "zahraničné faktúry došlé (ostré)",
            "93" => "domáce faktúry došlé (zálohové)",
            "94" => "zahraničné faktúry došlé (zálohové)",
            "95" => "domáce došlé dobropisy",
            "96" => "zahraničné došlé dobropisy",
            "97" => "interné doklady",
            "99" => "všetky ostatné platby",
        ];
    }
    
    public static function getBICFromIBAN($iban){
        
        $iban = str_replace(" ", "", $iban);
        $kod = substr($iban, 4, 4);
        
        $bank = Bank::find()->where(["code" => $kod])->one();
        
        if ($bank)
            return $bank->bic;
        else 
            return "";
    }
    
    public static function validateIBAN($iban){
//        return true;
        
        function convertAlphaToNumbers($str){
            $_str = "";
            for ($i = 0; $i < strlen($str); $i++){
                if (ord($str[$i]) > 64 && ord($str[$i]) < 91){
                    $_str .= ord($str[$i]) - 55;
                } else if (ord($str[$i]) > 96 && ord($str[$i]) < 123){
                    $_str .= ord($str[$i]) - 87;
                } else {
                    $_str .= $str[$i];
                }
            }
            
            return $_str;
        }
        
        function modulo97($number){
            if (strlen($number) > 9){
                return modulo97((substr($number, 0, 9) % 97) . substr($number, 9));
            } else {
                return $number % 97;
            }
        } 
        
        // STEP 1
        $iban = str_replace(" ", "", $iban);
        // STEP 2
        $iban = substr($iban,4) . substr($iban, 0, 4);
        // STEP 3
        $iban_num = convertAlphaToNumbers($iban);
        
//        $validation = gmp_strval(gmp_mod($iban_num, "97"));
        $validation = modulo97($iban_num);
        if ($validation !== 1)
            return false;
        else 
            return true;
        
    }
    
    public static function getTypyPlatby(){
        return [
            0 => "Hotovosť",
            1 => "Zápočet",
            2 => "Bankový prevod",
			4 => "Dobropis",
			5 => "Zálohová faktúra",
            3 => "Iné",
            100 => "Bankový prevod (XML)",
        ];
    }
    
    public static function getTypPlatby($id){
        $array = MainModel::getTypyPlatby();
        return isset($array[$id]) ? $array[$id] : "";
    }

}
