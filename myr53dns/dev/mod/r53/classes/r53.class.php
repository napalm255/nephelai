<?php
class r53
{
  //properties

  public $awsKey;
  public $awsSecret;
  public $awsDate;
  public $ch;

  //public functions

  public function getDate() { return $this->awsDate; }
  public function getKey() { return $this->awsKey; }
  public function setKey($s) { $this->awsKey = $s; }
  public function getSecret() { return $this->awsSecret; }
  public function setSecret($s) { $this->awsSecret = $s; }

  public function init() {
    $this->ch = curl_init ("https://route53.amazonaws.com/date");
    curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($this->ch, CURLOPT_HEADER, 1);
    $awsDate_Xml = curl_exec($this->ch);
    $awsDate_Error = curl_error($this->ch);
    $awsDate_Array = explode("\r", $awsDate_Xml);
    $this->awsDate = ltrim((string)str_replace("Date:","",$awsDate_Array[4]));
  }

  public function close() {
    curl_close($this->ch);
  }

  public function getZones() {
    $xmlZones = $this->_xmlGet('','hostedzone','');
    return $xmlZones->HostedZones->HostedZone;
  }

  public function getZoneNS($id) {
    $xmlZoneNS = $this->_xmlGet($id,'hostedzone','');
    return $xmlZoneNS;
  }
  
  public function getZoneSOA($id,$name) {
    $xmlZoneSOA = $this->_xmlGet($id, 'hostedzone', '/rrset?type=SOA&maxitems=1&name='.$name);
    $arySOA_Values = explode(" ", $xmlZoneSOA->ResourceRecordSets->ResourceRecordSet->ResourceRecords->ResourceRecord->Value);
    $objSOA = (object)null;
    $objSOA->Name = $xmlZoneSOA->ResourceRecordSets->ResourceRecordSet->Name;
    $objSOA->TTL = $xmlZoneSOA->ResourceRecordSets->ResourceRecordSet->TTL;
    $objSOA->Source = $arySOA_Values[0];
    $objSOA->Contact = $arySOA_Values[1];
    $objSOA->Serial = $arySOA_Values[2];
    $objSOA->Refresh = $arySOA_Values[3];
    $objSOA->Retry = $arySOA_Values[4];
    $objSOA->Expire = $arySOA_Values[5];
    $objSOA->MinimumTTL = $arySOA_Values[6];
    return $objSOA;
  }
  
  public function getZoneRR($id) {
    $xmlRecords = $this->_xmlGet($id,'hostedzone','/rrset');
    return $xmlRecords->ResourceRecordSets->ResourceRecordSet;
  }

  public function recordExist($name, $id, $type) {
    $match = false;
    foreach ($this->getZoneRR($id) as $rec) {
      if ((preg_match('/^'. preg_quote($this->_filterRecordName($name)) .'$/',$rec->Name)) & (preg_match('/^'.$type.'$/',$rec->Type))) { $match = true; }
    }
    return $match;
  }

  public function createZone($zname) {
    return $this->_xmlPost('',$this->_xmlZone($zname));
  }

  public function deleteZone($id) {
    $xmlZoneDelete = $this->_xmlGet($id,'hostedzone','DELETE');
    return $xmlZoneDelete;
  }

  public function createZoneRR($name, $id, $type, $ttl, $value) {
    if (($type == "TXT") || ($type == "SPF")) { $aryValues = $this->_handlerRR_TXT($value); } else { $aryValues = array_filter(explode(";",$value)); }
    $aryNew = array("name" => $this->_filterRecordName($name), "id" => $id, "type" => $type, "ttl" => $ttl, "value" => $aryValues);
    return $this->_xmlPost($aryNew['id'], $this->_xmlRecord('CREATE', $aryNew, array()));
  }

  public function updateZoneRR($name, $id, $type, $ttl, $value) {
    if (($type == "TXT") || ($type == "SPF")) { $aryValues = $this->_handlerRR_TXT($value); } else { $aryValues = array_filter(explode(";",$value)); }
    $aryUpd = array("name" => $this->_filterRecordName($name), "id" => $id, "type" => $type, "ttl" => $ttl, "value" => $aryValues);
    foreach($this->getZoneRR($id) as $rec) {
      if ((preg_match('/^'.preg_quote($this->_filterRecordName($name)).'$/', $rec->Name)) & (preg_match("/^$type$/", $rec->Type))) {
        foreach ($rec->ResourceRecords->ResourceRecord as $resRec) {
          $aryExistValues[] = $resRec->Value;
        }
        $aryExist = array("id" => $id, "name" => $rec->Name, "type" => $rec->Type, "ttl" => $rec->TTL, "value" => $aryExistValues);
      }
    }
    return $this->_xmlPost($aryExist['id'], $this->_xmlRecord('UPDATE', $aryUpd, $aryExist));
  }

  public function deleteZoneRR($name, $id, $type, $ttl, $value) {
    if (($type == "TXT") || ($type == "SPF")) { $aryValues = $this->_handlerRR_TXT($value); } else { $aryValues = array_filter(explode(";",$value)); }
    $aryDel = array("name" => $this->_filterRecordName($name), "id" => $id, "type" => $type, "ttl" => $ttl, "value" => $aryValues);
    return $this->_xmlPost($aryDel['id'], $this->_xmlRecord('DELETE','',$aryDel));
  }

  public function queryZoneChange($id) {
    $xmlQueryChange = $this->_xmlGet($id,'change','');
    return $xmlQueryChange;
  }

  public function getResponse($xml) {
    $res = $this->_responseHandler($xml);
    return $res;
  }

  //private functions

  private function _filterRecordName($name) {
    $filteredRecordName = preg_replace(array('/^@\./','/^\*/'),array('','\\\\052'),$name);
    return $filteredRecordName;
  }

  private function _handlerRR_TXT($value) {
    $value = trim($value);
    $filterPtn = array('/^\"/', '/;$/', '/\"$/', '/\\\\\"/', '/\";\ *\"/', '/\";\"/', '/\{%22\}/');
    $filterVal = array('', '', '', '{%22}', '{%brk}', '{%brk}', '\"');
    $value = preg_replace($filterPtn, $filterVal, $value);
    $rr = explode('{%brk}', $value);
    for ($i=0;$i < count($rr);$i++) {
      $rr[$i] = '"'.$rr[$i].'"';
    }
    return $rr;
  }

  private function _responseHandler($xml) {
    $res = array();
    if (array_key_exists("Error",$xml[0])) {
      $res = array('Name' => 'Error', 'Type' => $xml[0]->Error->Type, 'Code' => $xml[0]->Error->Code, 'Message' => $xml[0]->Error->Message);
    } elseif (array_key_exists("ChangeInfo",$xml[0])) {
      $res = array('Name' => 'Change', 'Id' => str_replace('/change/','',$xml[0]->ChangeInfo->Id), 'Status' => $xml[0]->ChangeInfo->Status, 'Timestamp' => $xml[0]->ChangeInfo->SubmittedAt);
    }
    return $res;
  }

  private function _xmlZone($name) {
    $xmlRequest = '<?xml version="1.0" encoding="UTF-8"?><CreateHostedZoneRequest xmlns="https://route53.amazonaws.com/doc/2010-10-01/">';
    $xmlRequest .= '<Name>'.trim($name).'</Name>';
    $xmlRequest .= '<CallerReference>'.trim($this->_guid()).'</CallerReference>';
    $xmlRequest .= '</CreateHostedZoneRequest>';
    return $xmlRequest;
  }

  private function _xmlRecord($method, $recCreate, $recDelete) {
    $xmlRequest = '<?xml version="1.0" encoding="UTF-8"?><ChangeResourceRecordSetsRequest xmlns="https://route53.amazonaws.com/doc/2010-10-01/">';
    $xmlRequest .= '<ChangeBatch><Comment></Comment><Changes>';
    switch ($method) {
      case 'UPDATE':
        # DELETE
        $xmlRequest .= '<Change><Action>DELETE</Action><ResourceRecordSet><Name>'.$recDelete['name'].'</Name><Type>'.$recDelete['type'].'</Type><TTL>'.$recDelete['ttl'].'</TTL>';
        $xmlRequest .= '<ResourceRecords>';
        foreach ($recDelete['value'] as $resRec) {
          $xmlRequest .= '<ResourceRecord><Value>'.$resRec.'</Value></ResourceRecord>';
        }
        $xmlRequest .= '</ResourceRecords>';
        $xmlRequest .= '</ResourceRecordSet></Change>';
        # CREATE
        $xmlRequest .= '<Change><Action>CREATE</Action><ResourceRecordSet><Name>'.trim($recCreate['name']).'</Name><Type>'.trim($recCreate['type']).'</Type><TTL>'.trim($recCreate['ttl']).'</TTL>';
        $xmlRequest .= '<ResourceRecords>';
        foreach ($recCreate['value'] as $resRec) {
          $xmlRequest .= '<ResourceRecord><Value>'.trim($resRec).'</Value></ResourceRecord>';
        }
        $xmlRequest .= '</ResourceRecords>';
        $xmlRequest .= '</ResourceRecordSet></Change>';
        break;
      case 'CREATE':
        $xmlRequest .= '<Change><Action>CREATE</Action><ResourceRecordSet><Name>'.trim($recCreate['name']).'</Name><Type>'.trim($recCreate['type']).'</Type><TTL>'.trim($recCreate['ttl']).'</TTL>';
        $xmlRequest .= '<ResourceRecords>';
        foreach ($recCreate['value'] as $resRec) {
          $xmlRequest .= '<ResourceRecord><Value>'.trim($resRec).'</Value></ResourceRecord>';
        }
        $xmlRequest .= '</ResourceRecords>';
        $xmlRequest .= '</ResourceRecordSet></Change>';
        break;
      case 'DELETE':
        $xmlRequest .= '<Change><Action>DELETE</Action><ResourceRecordSet><Name>'.$recDelete['name'].'</Name><Type>'.$recDelete['type'].'</Type><TTL>'.$recDelete['ttl'].'</TTL>';
        $xmlRequest .= '<ResourceRecords>';
        foreach ($recDelete['value'] as $resRec) {
          $xmlRequest .= '<ResourceRecord><Value>'.$resRec.'</Value></ResourceRecord>';
        }
        $xmlRequest .= '</ResourceRecords>';
        $xmlRequest .= '</ResourceRecordSet></Change>';
        break;
    }
    $xmlRequest .= '</Changes></ChangeBatch></ChangeResourceRecordSetsRequest>';
    return $xmlRequest;
  }

  private function _xmlPost($id, $xml) {
    if (!empty($id)) { $id = '/' . $id . '/rrset'; }
    $this->ch = curl_init();
    curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->_headers());
    curl_setopt($this->ch, CURLOPT_URL, "https://route53.amazonaws.com/2010-10-01/hostedzone".$id);
    curl_setopt($this->ch, CURLOPT_POST, 1);
    curl_setopt($this->ch, CURLOPT_POSTFIELDS, $xml);
    curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
    $awsPostXML = curl_exec($this->ch);
    $xmlPostXML = simplexml_load_string($awsPostXML);
    return $xmlPostXML;
  }

  private function _xmlGet($id, $prefix, $options) {
    $this->ch = curl_init();
    curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->_headers());
    if ($options == 'DELETE') { curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "DELETE"); $options=''; }
    curl_setopt($this->ch, CURLOPT_URL, "https://route53.amazonaws.com/2010-10-01/".$prefix."/".$id.$options);
    curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
    $awsXmlGet = curl_exec($this->ch);
    $xmlGetResponse = simplexml_load_string($awsXmlGet);
    return $xmlGetResponse;
  }

  private function _guid() {
    $charid = strtoupper(md5(uniqid(rand(), true)));
    $hyphen = chr(45);// "-"
    $uuid =
      substr($charid, 0, 8).$hyphen
      .substr($charid, 8, 4).$hyphen
      .substr($charid,12, 4).$hyphen
      .substr($charid,16, 4).$hyphen
      .substr($charid,20,12);
      return strtolower($uuid);
  }

  private function _awssecret() {
    return base64_encode(hash_hmac('sha256', $this->awsDate, $this->awsSecret, true)); 
  }

  private function _headers() {
    return array(
      "X-Amzn-Authorization: AWS3-HTTPS AWSAccessKeyId=$this->awsKey,Algorithm=HmacSHA256,Signature=".$this->_awssecret(),
      "x-amz-date: $this->awsDate",
      "Content-Type: text/xml; charset=UTF-8",
      "Host: route53.amazonaws.com"
    );
  }

}
?>
