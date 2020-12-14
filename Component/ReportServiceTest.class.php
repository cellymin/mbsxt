<?php
namespace Component;

  require_once 'baiduV4API/sms_service_ReportService.php';
    class ReportServiceTest extends sms_service_ReportService{
		
     function getRealTimeDataTest($StartDate,$setEndDate,$setdevice,$levelOfDetails,$setUnitOfTime){  //账户层级报告 
           $request=new GetRealTimeDataRequest();
           $type=new ReportRequestType();
           $type->setPerformanceData(array("impression","click","conversion","cost","cpc","cpm"));
           $type->setLevelOfDetails($levelOfDetails); //3 指定返回的数据层级 2账户层级 3，计划层级
           $type->setUnitOfTime($setUnitOfTime);  //7 统计时间单位 5 分日， 4分周， 3分月，1分年，7分小时，8请求时间段汇总
           $type->setReportType($levelOfDetails);  // 10 实时数据类型 2账户层级 3,计划层级
		   $type->setdevice($setdevice); //搜索推广渠道 0：全部搜索推广设备 1：仅计算机 2：仅移动
           $type->setStartDate($StartDate);
           $type->setEndDate($setEndDate);
           $request->setRealTimeRequestType($type);
           $response=$this->getRealTimeData($request);
           $head=$this->getJsonHeader();
           //echo "status:".json_encode($head)."\n";
           assert(SUCCESS==$head->desc&&0==$head->status);
          return $response->data;
        }
		
        function getCampaignReport($StartDate,$setEndDate,$setdevice,$levelOfDetails,$setUnitOfTime,$ReportType){ // 返回计划 时间段报表
		
            $request=new GetRealTimeDataRequest();
            $type=new ReportRequestType();
            $type->setPerformanceData(array("impression","click","conversion","cost","cpc","cpm"));
            $type->setLevelOfDetails($levelOfDetails); //3 指定返回的数据层级 2账户层级 3，计划层级
            $type->setUnitOfTime(7);  //7 统计时间单位 5 分日， 4分周， 3分月，1分年，7分小时，8请求时间段汇总
            $type->setReportType($ReportType);  // 10 实时数据类型 2账户层级 3,计划层级
		    $type->setdevice($setdevice); //搜索推广渠道 0：全部搜索推广设备 1：仅计算机 2：仅移动
            $type->setStartDate($StartDate);
            $type->setEndDate($setEndDate);
            $request->setRealTimeRequestType($type);
            $response=$this->getRealTimeData($request);
            $head=$this->getJsonHeader();
            //echo "status:".json_encode($head)."\n";
            assert(SUCCESS==$head->desc&&0==$head->status);
            return $response->data;
        }
		
		
        function getRealTimeQueryDataTest($StartDate,$setEndDate,$setdevice,$levelOfDetails,$setUnitOfTime){  //搜索词报告
           $request=new GetRealTimeQueryDataRequest();
           $type=new ReportRequestType();
           $type->setPerformanceData(array("impression","click"));
           $type->setLevelOfDetails($levelOfDetails);  //搜索关键词层级
           $type->setUnitOfTime($setUnitOfTime);  // 统计时间单位 5 分日， 4分周， 3分月，1分年，7分小时，8请求时间段汇总
           $type->setReportType(6);  //实时数据类型 6 关键词层级
		   $type->setnumber(5000); //最大返回次数
		   $type->setdevice($setdevice); //搜索推广渠道 0：全部搜索推广设备 1：仅计算机 2：仅移动
           $type->setStartDate($StartDate);
           $type->setEndDate($setEndDate);
           $request->setRealTimeQueryRequestType($type);
           $response=$this->getRealTimeQueryData($request);
           $head=$this->getJsonHeader();
           //echo "status:".json_encode($head)."\n";
           assert(SUCCESS==$head->desc&&0==$head->status);
          return $response->data;
        }
		
        function getRealTimePairDataTest($StartDate,$setEndDate,$setdevice,$levelOfDetails,$setUnitOfTime){  // 账户关键词报告
           $request=new GetRealTimePairDataRequest();
           $type=new ReportRequestType();
           $type->setPerformanceData(array("impression","click","cost","cpc"));
           $type->setLevelOfDetails(12);  // 此处指能是 关键词或者创意的粒度
           $type->setUnitOfTime($setUnitOfTime);//5
           $type->setReportType(15);
		   $type->setnumber(5000); //最大返回次数
		    $type->setdevice($setdevice); //搜索推广渠道 0：全部搜索推广设备 1：仅计算机 2：仅移动
     	   $type->setStartDate($StartDate);
           $type->setEndDate($setEndDate);
           $request->setRealTimePairRequestType($type);
           $response=$this->getRealTimePairData($request);
           $head=$this->getJsonHeader();
           //echo "status:".json_encode($head)."\n";
           assert(SUCCESS==$head->desc&&0==$head->status);
          return $response->data;
        }
		
        function getProfessionalReportIdTest($StartDate,$setEndDate,$arrfanhui,$LevelOfDetails,$ReportType,$setDevice,$unitOfTime=8){ //获取异步报告ID       
		  $request=new GetProfessionalReportIdRequest();
           $type=new ReportRequestType();
           $type->setPerformanceData($arrfanhui);
           $type->setLevelOfDetails($LevelOfDetails); //指定返回的数据层级
           $type->setUnitOfTime($unitOfTime); //统计时间单位  8:请求时间段汇总  7：小时报	5：日报	
           $type->setReportType($ReportType); //实时数据类型
		   $type->setDevice($setDevice); //搜索推广渠道 0：全部搜索推广设备 1：仅计算机 2：仅移动
           $type->setStartDate($StartDate); //开始时间
           $type->setEndDate($setEndDate); //结束时间
           $request->setReportRequestType($type);
           $response=$this->getProfessionalReportId($request);
           $head=$this->getJsonHeader();
           //echo "status:".json_encode($head)."\n";
           assert(SUCCESS==$head->desc&&0==$head->status);
          return $response->data;
        }
		
		
        function getReportStateTest($jiance){// 返回reportID 当前的生成状态，返回中带有当前报告ID的处理状态，1等待 2处理中 3处理成功
           $request=new GetReportStateRequest();
           $request->setReportId($jiance);
           $response=$this->getReportState($request);
           $head=$this->getJsonHeader();
           //echo "status:".json_encode($head)."\n";
           assert(SUCCESS==$head->desc&&0==$head->status);
          return $response->data;
        }
		
		
        function getReportFileUrlTest($ReportID){  //获取报告地址
           $request=new GetReportFileUrlRequest();
           $request->setReportId($ReportID);
           $response=$this->getReportFileUrl($request);
           $head=$this->getJsonHeader();
           //echo "status:".json_encode($head)."\n";
           assert(SUCCESS==$head->desc&&0==$head->status);
           return $response->data;
        }

    }


?>