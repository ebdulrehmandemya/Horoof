<?php
/**
 * Created by Abdolrahman Damia
 * User: Home
 * Date: 1/19/14
 * Time: 10:44 AM
 */


namespace Dahatu\Horoof;

class OrdinalNumber extends NumberToHoroof {

    const NUMTYPE_ORDINAL = 0;
    const NUMTYPE_ADJECTIVE = 1;

    private $textType;

    public function __construct($strNumber='0',$textType = ordinalNumber::NUMTYPE_ORDINAL){
        $this->textType = $textType;
        $this->strNumber = $strNumber;
        $this->chrPoint = '.';
        $this->convertToText();
    }

    public function setTextType($textType){
        if ($textType == ordinalNumber::NUMTYPE_ADJECTIVE)
            $this->textType = ordinalNumber::NUMTYPE_ADJECTIVE;
        else
            $this->textType = ordinalNumber::NUMTYPE_ORDINAL;
    }

    protected function convertToText(){
        $this->getCharPoint();
        $numParts = explode($this->chrPoint,$this->strNumber);
        if (isset($numParts[0]))
            $strIntPart = $this->integerPartText($numParts[0]);
        else
            $strIntPart = '';
        $this->strText = trim($strIntPart,' و ');
        if ($this->textType == ordinalNumber::NUMTYPE_ORDINAL)
            $this->determineOrdinalNumberPrefix();
        else
            $this->determineAdjectiveNumberPrefix();

    }

   private function determineAdjectiveNumberPrefix(){
        switch((double)$this->strNumber){
            case 0:
                $this->strText = '';
                break;
            case 1:
                $this->strText = 'اولین';
                break;
            case 3:
                $this->strText = 'سومین';
                break;
            default:
                $this->strText .= 'مین';
       }
   }

    private function determineOrdinalNumberPrefix(){
        switch((double)$this->strNumber){
            case 0:
                $this->strText = '';
                break;
            case 1:
                $this->strText = 'اول';
                break;
            case 3:
                $this->strText = 'سوم';
                break;
            default:
                $this->strText .= 'م';
        }
    }

    public function __toString(){
        return $this->strText;
    }

}