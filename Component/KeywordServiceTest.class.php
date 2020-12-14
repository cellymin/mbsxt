<?
 namespace Component;
 require_once 'baiduV4API/sms_service_KeywordService.php';
 
   const MAXPRICE=30; 
    const IDTYPE = 11;  //5 单元ID   11关键词ID
    const ISTMP = 0;   // 0 只查关键词本身， 1， 查询影子关键词
    class KeywordServiceTest extends sms_service_KeywordService{

       
        function get($arrkeyID){
            $request=new GetWordRequest();
           // $ids=array();    //带进来ID的参数， 格式  arraay([0][keywordID])  
            $fields=array("keyword","keywordId","pcDestinationUrl" ,"mobileDestinationUrl");
         /*    for($i=0;$i<BEAN_COUNT;$i++){
                array_push($ids,$datas[$i]->keywordId);
            } */
			//print_r($datas);
			//$arrkeyID = array($arrkeyID);
            $request->setIds($arrkeyID); // 关键词ID的数组  不超过10000个
            $request->setWordFields($fields); // 返回关键词 和关键词ID
            $request->setIdType(IDTYPE); // 11关键词ID
            $request->setGetTemp(ISTMP);  // 0 只差关键词本身
            $this->setIsJson(true);
            $response=$this->getWord($request);
            //echo json_encode($response)."\n";
            $head=$this->getJsonHeader();
            //echo "status:".json_encode($head)."\n";
            assert(SUCCESS==$head->desc&&0==$head->status);
            return $response->data;
        }
    
    }
?>