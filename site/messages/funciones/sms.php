<?php
    function sms($movil,$sms, $envia){
        $ahora=date("Y-m-d H:i:s");
        $movil=$movil;
        $text=substr($sms,0,500);
        $text = mb_convert_encoding($sms, 'UTF-8', 'ISO-8859-1');
        $ch = curl_init();

        if ($envia=="NOC"){
            $xml = "<?xml version='1.0'?><message><user>psg</user><service>psg</service><passwd>psg123</passwd><from>$envia</from><to>$movil</to><text>$text</text><validity>24h</validity><nrq>N</nrq></message>";
        }
        else{
            $xml = "<?xml version='1.0'?><message><user>psg</user><service>psg</service><passwd>psg123</passwd><from>e)$envia</from><to>$movil</to><text>$text</text><validity>24h</validity><nrq>N</nrq></message>";
        }
		
        curl_setopt($ch, CURLOPT_URL, "http://emsg.vas.entelpcs.cl/sendsms");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "xmlData=".$xml);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        $resultado = curl_exec($ch);
        $error = curl_error($ch);
        
        if ($resultado!="" ){
                curl_close($ch);
                return 10;
        }
        if ($error!=""){
                curl_close($ch);
                return 1;
        }
    }

?>