<?php
/*
 *
 */
class Raa{
    private $labelArray;
    private $tooltipsObject;
    private $recipientArray=array();
    private $smsRecipientArray=array();
    public function addLabel($key,$label,$size,$align='left'){
        $this->labelArray[$key]['label']=$label;
        $this->labelArray[$key]['size']=$size;
        $this->labelArray[$key]['align']=$align;
        return $this;
    }
    
    public function getLabel(){
        return $this->labelArray;
    }
    
    public function clearLabel(){
        $this->labelArray='';
    }
    
    public function addTooltips(&$value){
        $value="<span title='$value'>$value</span>";
        return $this;
    }
    public function getTooltips(){
        return $this->tooltipsObject;
    }
    
    public function clearTooltips(){
        $this->tooltipsObject='';
    }
    
    
    public function addMailRecipient($type,$var1,$var2,$var3=''){
        switch($type){
            case 'i':
                $this->addSingleRecipient($var1,$var2);
                break;
            case 'g':
                $this->addDivisionRecipient($var1,$var2,$var3);
                break;
            default:
            die('Recipient Type Not Supported');   
        }
        return $this;
    }
    
    public function addSingleRecipient($type,$user){
        array_push($this->recipientArray,"i;$type;$user");
        return $this;
    }
    
    public function addDivisionRecipient($type,$division,$region){        
        array_push($this->recipientArray,"g;$type;$division;$region");
        return $this;
    }
    
    public function getMailRecipient(){
        return implode('||',$this->recipientArray);
    }
    
    public function clearMailRecipient(){
        $this->recipientArray='';
    }
    
    
    public function addSMSRecipient($type,$var1,$var2,$var3=''){
        switch($type){
            case 'i':
                $this->addSMSSingleRecipient($var1);
                break;
            case 'g':
                $this->addSMSDivisionRecipient($var1,$var2);
                break;
            default:
            die('Recipient Type Not Supported');   
        }
        return $this;
    }
    
    public function addSMSSingleRecipient($user){
        array_push($this->smsRecipientArray,"i;$user");
        return $this;
    }
    
    public function addSMSDivisionRecipient($division,$region){        
        array_push($this->smsRecipientArray,"g;$division;$region");
        return $this;
    }
    
    public function getSMSRecipient(){
        return implode('||',$this->smsRecipientArray);
    }
    
    public function clearSMSRecipient(){
        $this->smsRecipientArray='';
    }
  
}
?>