<?php
/**
 * Created by AbdolRahman Damia
 * User: Home
 * Date: 1/14/14
 * Time: 12:26 AM
 */

namespace Dahatu\Horoof;

class NumberToHoroof{

    protected  $strNumber = '0';
    protected $chrPoint = '.';
    protected $strText = '';

    public function __construct($strNumber = '0'){
        $this->setNumber($strNumber);
    }

    public function setNumber($strNumber){
        $this->strNumber = (string)$strNumber;
        $this->convertToText();
    }

    public function getNumber(){
        return $this->strNumber;
    }

    protected function convertToText(){
        $this->getCharPoint();
        $numParts = explode($this->chrPoint,$this->strNumber);
        if (isset($numParts[0]))
            $strIntPart = trim($this->integerPartText($numParts[0]),' و ');
        else
            $strIntPart = '';
        if (isset($numParts[1]))
            $strRealPart = trim($this->realPartText($numParts[1]),' و ');
        else
            $strRealPart = '';
        if ($strIntPart!='' && $strRealPart!='')
            $this->strText = $strIntPart . ' ممیز ' . $strRealPart;
        elseif ($strIntPart!='' && $strRealPart=='')
            $this->strText = $strIntPart;
        elseif ($strIntPart=='' && $strRealPart!='')
            $this->strText = $strRealPart;
        else
            $this->strText = '';
    }

    protected  function integerPartText($intPart){
        $a3Digits = $this->split3Digits($intPart);
        $strText = '';
        foreach($a3Digits as $keyPerfix=>$valNums){
            $strCurDigits = $this->con3PartText($valNums,$keyPerfix);
            $strText .= (($strText=='' || $strCurDigits=='')?'':' و ') . $strCurDigits;
        }
        return $strText;
    }

    protected  function realPartText($realPart){
        $arrPostFix = array('','دهم','صدم','هزارم','ده هزارم','صد هزارم','میلیونم','ده میلیونم','صد میلیونم');
        $a3Digits = $this->split3Digits($realPart);
        $strText = '';
        foreach($a3Digits as $keyPerfix=>$valNums){
            $strCurDigits = $this->con3PartText($valNums,$keyPerfix);
            $strText .= (($strText=='' || $strCurDigits=='')?'':' و ') . $strCurDigits;
        }
        $realLenDigits = strlen($realPart);
        if ($realLenDigits<count($arrPostFix))
            $strText .= $arrPostFix[$realLenDigits];
        return $strText;
    }

    private function con3PartText($strDigits,$posNums){
        $numText =array('صفر','یک','دو‌','سه','چهار','‌پنج','شش','‌هفت','‌هشت','‌نه',
            'ده','یانزده','دوازده','سیزده','چهارده','‌پانزده','شانزده','‌هفده','‌هجده','‌نوزده',
            20=>'بیست',30=>'سی',40=>'چهل',50=>'‌پنجاه',60=>'شصت',70=>'‌هفتاد',80=>'‌هشتاد',90=>'‌نود',
            100=>'یکصد',200=>'دویست',300=>'سیصد',400=>'چهارصد',500=>'‌پانصد',600=>'ششصد',700=>'‌هفتصد',800=>'‌هشتصد',900=>'‌نهصد');
        //American Zillion
        $zillionPostfix = array('','هزار','میلیون','بیلیون','تریلیون','کوادریلیون','کوینتیلیون','سیکستیلون','سپتیلیون','اکتیلیون','نونیلیون','دسیلیون','آندسیلیون','دودسیلیون','تریدسیلیون','کواتردسیلیون','کویندسیلیون','سیکسدسیلیون','سپتندسیلیون','اکتودسیلیوم','نومدسیلیون');
        $strDigits = (string)$strDigits;
        $firstDigit = -1;
        $secondDigit = -1;
        $thirdDigit = -1;
        if (strlen($strDigits) == 3){
            $firstDigit = $strDigits[0];
            $secondDigit = $strDigits[1];
            $thirdDigit = $strDigits[2];
        }
        elseif (strlen($strDigits) == 2){
            $secondDigit = $strDigits[0];
            $thirdDigit = $strDigits[1];
        }
        elseif (strlen($strDigits) == 1){
            $thirdDigit = $strDigits[0];
        }
        $strText = '';
        if (($firstDigit<0) && ($secondDigit<0) && ($thirdDigit ==0) && ($posNums==0)) $strText=$numText[0];
        else {
            if ($firstDigit > 0) $strText = $numText[$firstDigit . '00'];
            if ($secondDigit == 1) $strText .= (($strText == '') ? '' : ' و ') . $numText[$secondDigit . $thirdDigit];
            if ($secondDigit > 1) $strText .= (($strText == '') ? '' : ' و ') . $numText[$secondDigit . '0'];
            if (($thirdDigit > 0) && (($secondDigit > 1) || (($secondDigit <= 0)))) $strText .= (($strText == '') ? '' : ' و ') . $numText[$thirdDigit];
            if ($posNums < count($zillionPostfix) && ($strText != ''))
                $strText .= ' ' . $zillionPostfix[$posNums];
        }
        return $strText;
    }

    private function split3Digits($strNum){
        $arrSplit3Digit = array();
        $split3Digit = '';
        $strNum = strrev(ltrim($strNum," 0"));
        $strNum = ($strNum=='')?'0':$strNum;
        $lenStrNum = strlen($strNum);
        for ($i = 0 ; $i <$lenStrNum ;$i++){
            $split3Digit = $strNum[$i] . $split3Digit;
            if (($i+1)%3==0){
                $arrSplit3Digit[] = $split3Digit;
                $split3Digit = '';
            }
        }
        if ($split3Digit !== '')
            $arrSplit3Digit[] = $split3Digit;
        $arrSplit3Digit = array_reverse($arrSplit3Digit,true);
        return $arrSplit3Digit;
    }

    protected  function getCharPoint(){
        $index = 0;
        $chDelimiter = '';
        while ($index<strlen($this->strNumber) && (is_numeric($this->strNumber[$index])))  $index++;
        while ($index<strlen($this->strNumber) && (!is_numeric($this->strNumber[$index]))) {
            $chDelimiter .= $this->strNumber[$index];
            $index++;
        }
        $this->chrPoint = ($chDelimiter=='')?'.':$chDelimiter;
    }

    public  function showYourself(){
        return $this->strText;
    }
}