<?php

App::uses('AppController', 'Controller');
App::uses('measurementPair','Lib');
App::uses('MFile','Lib');
App::uses('FileFormatRecognizer','Lib');
App::uses('MeasurementObj','Lib');
App::uses('MeasurementData','Lib');

ini_set("memory_limit", "2048M");

/**
 * Measurements Controller
 *
 * @property Measurement $Measurement
 */
class MeasurementsController extends AppController {

   public $components = array('Session','Parser','RequestHandler','Plupload.Plupload');
   public $helpers = array('Session','Plupload.Plupload','Html');
   

   private $fileType = "";
   private $fileinfo = array();
   private $mm = null;

   public $paginate = array(
      'limit' => 50,
      'contain' => array("MeasurementQueue","History","Item","Device","User","MeasurementType","MeasurementTag")
   );
   
/**
 * index method
 *
 * @return void
 */
   public function index() {
      $this->Measurement->recursive = 0;
      $this->set('measurements', $this->paginate());
   }

   /**
   * _view_apvdaq_intcal private method that reacts to the chip and strip selected by the user and then returns the corresponding values for plotting
   */
   private function _view_apvdaq_intcal($id){
      $parameters = $this->Measurement->MeasuringPoint->Reading->Parameter->find("list");
      #$Reading = &$this->Measurement->MeasuringPoint->Reading;
      $chipParamId = array_search("Chip",$parameters);
      $stripParamId = array_search("Strip",$parameters);

      if($this->request->isAjax()){
         if($this->mm == null){
            $this->_setMeasurementObj($id);
            #$this->_runtime("setMeasurementObj");
         }
         if(isset($this->request->query["xParam"])){
            $xParamId = array_search($this->request->query["xParam"],$parameters);
            $yParamId = array_search($this->request->query["yParam"],$parameters);

            if(isset($this->request->query["chip"]) && isset($this->request->query["strip"])){
               //Define order according to the parameter Ids,
               if(isset($this->request->query["table"]) && !is_array($this->request->query["strip"])){
                  $this->set('measurementReadings', array(array($parameters[$xParamId],$parameters[$yParamId]),$this->mm->getColsWhere(array($chipParamId=>$this->request->query["chip"],$stripParamId=>$this->request->query["strip"]),$xParamId,$yParamId)->getData()));
                  return $this->render("view/table");
               }else{
                  $return = array();
                  //Get all values for the selected chip and strip
                  #$return[] = $Reading->getStripValuesFormatted($id,$this->request->query["chip"],$this->request->query["strip"]);
                  $return[] = $this->mm->getColsWhere(array($chipParamId=>$this->request->query["chip"],$stripParamId=>$this->request->query["strip"]),$xParamId,$yParamId)->getData();
                  echo json_encode($return);
               }
            }elseif(isset($this->request->query["chip"]) && isset($this->request->query["allstrips"])){
               $return = array();
               //Get all values for the selected chip and strip
               // foreach($Reading->getStrips($id,$this->request->query["chip"]) as $strip){
               //    $return[] = $Reading->getStripValuesFormatted($id,$this->request->query["chip"],$strip);
               // }
               $start = null;
               $tmp = array();
               $strip = 0;
               foreach($this->mm->getColsWhere(array($chipParamId=>$this->request->query["chip"]),$stripParamId,$xParamId,$yParamId)->getData() as $num=>$value){
                  if($num == 0){
                     $tmp = array("xAxisLabel" => "Time");
                     $start = $value[0];
                  }elseif($value[0] != $start){
                     $start = $value[0];
                     $tmp["label"] = "Chip: ".$this->request->query["chip"].", Strip: ".$strip;
                     $return[] = $tmp;
                     $tmp = array("xAxisLabel" => "Time");
                     $strip++;
                  }
                  $tmp["data"][] = array($value[1],$value[2]);
               }
               $return[] = $tmp; //Don't forget to also store the last one
               #foreach($this->mm->getDistinctValuesWhere(array(25=>$this->request->query["chip"]),26) as $strip){
               #  $this->_runtime("Loading Measurement for Chip ".$this->request->query["chip"]." and Strip ".$strip."");
               #}
               echo json_encode($return);
            }elseif(isset($this->request->query["chip"])) {
               //Get all strip ids for the selected chip
               #$options = $Reading->getStrips($id,$this->request->query["chip"]);
               $options = $this->mm->getDistinctValuesWhere(array($chipParamId => $this->request->query["chip"]), $stripParamId);
               #$this->_runtime("getStripsForChip");
               $name = "strips";
               $settings = array("size" => 6, "style" => "width:150px;", "empty" => false);
               $this->set(compact("name", "options", "settings"));
               // debug($this->Measurement->MeasuringPoint->Reading->find("all",array(
               // "conditions"=>array("Reading.measuring_point_id BETWEEN ? AND ? "=>array($first,$last),"Reading.parameter_id"=>$stripParameterId),
               // "fields"=>array("Reading.value"),
               // )));
               $this->render("/Elements/ajax_dropdown");
            }
         }else{
            $chips = $this->mm->getDistinctValuesWhere(array(),$chipParamId);
            $possibleParameters = $this->mm->getHeader();
            $this->set("chips",$chips);
            $this->set("possibleParameters",$possibleParameters);
            $this->render("view/apvdaq_plot");
         }
         $this->autoRender = false;
      }else{
         // use $measurementStandardPlot to set a javascript variable
         $hideLegend = "hideLegend = true";
         $this->set("measurementStandardPlot",$hideLegend);
         $this->render("view/apvdaq_intcal");
      }
   }


   private function _setMeasurementViewVariables($id,&$measurement){
      $this->Measurement->unbindModel(array("hasMany"=>"MeasuringPoint"));
      $measurement = $this->Measurement->findById($id);
      $measurementSet = $this->Measurement->MeasurementSetsMeasurement->find("first",array("conditions"=>array("MeasurementSetsMeasurement.measurement_id"=>$id)));

      $measurementTags = $this->Measurement->MeasurementTag->find("list");
      $this->set("measurementTags",$measurementTags);

      $measurementQueueStatus = $this->Measurement->MeasurementQueue->getMeasurementQueueStatus();
      $this->set("measurementQueueStatus",$measurementQueueStatus);
      $measurementParameter =$this->Measurement->MeasurementParameter->find("all",array("conditions"=>array("Measurement.id"=>$id)));
      $this->set("measurementParameter",$measurementParameter);
      if(isset($measurementSet["MeasurementSetsMeasurement"])){
         $measurementSet = $this->Measurement->MeasurementSet->find("first",array("conditions"=>array("MeasurementSet.id"=>$measurementSet["MeasurementSetsMeasurement"]["measurement_set_id"])));
         $this->set('measurementSet', $measurementSet);
      }
      #Set standard plot id to measurement id, if necessary this is changed later on
      $this->set("measurementPlotId",$id);
   }

   
   private function _setMeasurementObj($id,&$m=null){
      if($m == null){
         $this->_setMeasurementViewVariables($id,$m);
      }
      //Check if Measurement has a MeasurementFile assigned
      if($m["Measurement"]["measurement_file_id"] != null){
         //Yes there is a MeasurementFile 
         if(!MeasurementObj::cached($id)){ //Check if there is NO cached file for this measurement already.
            $mFile = new MFile($m["Measurement"]["measurement_file_id"]); //initialize the File
            $this->mm = $mFile->getSectionAsMeasurement($m["MeasurementType"]["marker"],true,$id);
            //$this->_runtime("Loading Measurement from File");
         }else{
            //There is a cached file, use it
            $mFile = new MFile($m["Measurement"]["measurement_file_id"]); //initialize the File
            //$this->_runtime("initialize file");
            $this->mm = $mFile->getSectionAsMeasurement($m["MeasurementType"]["marker"],true,$id);
            #$mm = new MeasurementObj($m["MeasurementType"],$m["MeasurementTag"],$m["MeasurementParameter"],null,$id);
            //$this->_runtime("Loading Measurement from Cache");
         }
      }
//    else{
//       //There is no measurement file assigned, create one from the database
//       $this->_storeMMFromDBToFile($id);
//       $this->_setMeasurementViewVariables($id,$m);
//       $mFile = new MFile($m["Measurement"]["measurement_file_id"]); //initialize the File
//       $this->mm = $mFile->getSectionAsMeasurement($m["MeasurementType"]["marker"],true,$id);
//    }
   }
/**
 * view method
 *
 * @param string $id
 * @return void
 */
   public function view($id = null) {
      //Check if Measurement exists
      $this->Measurement->id = $id;
      if (!$this->Measurement->exists()) {
         throw new NotFoundException(__('Invalid measurement'));
      }
      
      // $this->_runtime("Preamble");
      $this->_setMeasurementViewVariables($id,$m);
      // $this->_runtime("Setting variables for View");

      $this->set('measurement', $m);
      $MeasurementType = $this->Measurement->MeasurementType->find("list",array("conditions"=>array("name"=>array("APV Calibration Measurement","APV IntCal vs. Vsep Measurement"))));
      if(count($MeasurementType)==0)
         $MeasurementType[] = 0; //If none found set at least one element and set to 0

      if(in_array($m["Measurement"]["measurement_type_id"],array_flip($MeasurementType))){
         //The measurement to be displayed is a Calibration measurement. Render a different view with selection fields for which calibration curve should be displayed
         $this->set("measurementStandardPlot","");
         // $this->_runtime("initialize file");
         $this->_view_apvdaq_intcal($id);
         // $this->_runtime("set viewvars");
         return;
      }
      //From here on there should by a Measurement Object available that hides the fact, that there are two different ways of initialization and just reacts appropriately to the requests by the user.
      $this->_setMeasurementObj($id);
      if($this->mm == null){
         $this->render("view/not_yet_imported");
         return;
      }
      $parameters = $this->Measurement->MeasuringPoint->Reading->Parameter->find("list");
      $tmp = $this->mm->getAllCols();
      if(isset($tmp[0])){
         foreach($tmp[0] as $pos=>$parameter){
            $header[$pos] = array("id"=>array_search($parameter,$parameters),"name"=>$parameter);
         }
      }else{
         $header = array();
      }
      
      $measurementReadings = array($header,array_splice($tmp,1));
      $this->autoRender = false;

      $MeasurementType = $this->_measurementStandardplot($m["Measurement"]["measurement_type_id"],$this->mm->getHeader());

      switch($MeasurementType["MeasurementType"]["name"]){
         case "Strip measurement":
            $this->set('measurementReadings', $measurementReadings);
            $mmParameter = $this->Measurement->MeasurementParameter->find("first",
                  array("conditions"=>array(
                        "Parameter.name"=>"StripMeasurementId",
                        "MeasurementParameter.value"=>$id
                     ),
                  )
               );
            if(count($mmParameter)>0): #there exists a strip error measurement for this strip measurement, show tabs
               $stripErrorMeasurementId = $mmParameter["MeasurementParameter"]["measurement_id"];
               //Get strip error measurement table from strip error measurement
               $mmParameter = $this->Measurement->MeasurementParameter->find("first",
                     array("conditions"=>array(
                           "Parameter.name"=>"Strip Error Limits",
                           "MeasurementParameter.measurement_id"=>$stripErrorMeasurementId
                        ),
                     )
                  );
               if(count($mmParameter)>0){
                  $measurementParameter = $this->Measurement->MeasurementParameter->find("all",array("conditions"=>array("Measurement.id"=>$id)));
                  $measurementParameter[] = $mmParameter;
                  $this->set("measurementParameter",$measurementParameter);
               }
               //Create arrays to display strip errors
               $this->_strip_errors($this->Measurement->MeasuringPoint->Reading->getMeasurementByMeasuringpointId($stripErrorMeasurementId));
               $this->render("view/strips");
            else:
               #Render only the table
               $this->render("view/strips_only_table");
            endif;
            break;
         case "Strip errors":
            $mmParameter = $this->Measurement->MeasurementParameter->find("first",
                  array("conditions"=>array(
                        "Parameter.name"=>"StripMeasurementId",
                        "Measurement.id"=>$id
                     ),
                  )
               );
            if(count($mmParameter)>0): #there exists a strip measurement for this strip error measurement, show tabs
               $stripMeasurementId = $mmParameter["MeasurementParameter"]["value"];
               //pass the measurement id of the strip measurement as parameter that can be used by the plot library
               $this->set("measurementPlotId",$stripMeasurementId);
               $this->set('measurementReadings', $this->Measurement->MeasuringPoint->Reading->getMeasurementByMeasuringpointId($stripMeasurementId));
               $this->_strip_errors($measurementReadings);
               $this->render("view/strips");

            else:
               $this->_strip_errors($measurementReadings);
               $this->render("view/strip_errors");
            endif;

            break;
         case "APV Sensor Histogram":
         case "It measurement":
         case "APV Strip Measurement":
         default:
            $this->set('measurementReadings', $measurementReadings);
            $this->render("view/standard");
            break;
      }
   }
   
   
   private function _storeMMFromDBToFile($id){
      set_time_limit(120); //Make sure the system doesn't time out
      
      $parameterMappingArray = array('Broken or Leaky AC Capacitors'=>'pinhole',
            'high current'=>'high_current',
            'P_Leaky_strip'=>'p_high_current',
            'Implant or Poly open'=>'implant_or_resistor_open',
            'Implant Short'=>'implant_short',
            'P_Implant_open'=>'p_implant_or_resistor_open',
            'P_Implant_short'=>'p_implant_short',
            'Metal Open'=>'metal_open',
            'Metal Short'=>'metal_short',
            'N_AC_AL_open'=>'n_metal_open',
            'N_AC_AL_short'=>'n_metal_short',
            'P_AC_AL_open'=>'p_metal_open',
            'P_AC_AL_short'=>'p_metal_short',
            'N_Pinhole'=>'n_pinhole',
            'P_Pinhole'=>'p_pinhole',
            'P_Bad_Isolation'=>'p_bad_isolation',
            'P_PolySi_open'=>'p_high_resistor',
            'Strip'=>'strip number',
            'I_DC >50nA'=>'I_DC >50nA',
            'P_PolySi_short'=>'p_low_resistor');
      
      $requiresPrefix = array(
            'Broken or Leaky AC Capacitors',
            'high current',
            'Implant or Poly open',
            'Implant Short',
            'Metal Open',
            'Metal Short'
      );
      
      $this->_runtime("starting",false);
      $this->Measurement->unbindModel(array("hasMany"=>"MeasuringPoint"));
      $measurement = $this->Measurement->findById($id);
      $fileNameSet = false;
      if($measurement == null){
         return $id." Measurement not found, aborting";
      }
      if($measurement["Measurement"]["measurement_file_id"] !== null){
         $measurementFileId = $measurement["Measurement"]["measurement_file_id"];
         #return $id." Measurement already in file, done";
      }
      foreach($measurement["MeasurementParameter"] as $tmp){
         if($tmp["parameter_id"]==22){
            $fileNameSet = true;
            $filename = $tmp["value"].".csv";
         }
      }
      $addDataToFile = false;
      if($fileNameSet) { //Check if there is a filename set for this measurement
         $mFile = $this->Measurement->MeasurementFile->find("first", array("conditions" => array("originalFileName" => basename($filename, ".csv"))));
         if (!empty($mFile)) {
            //Measurement has already been assigned to a measurement file, use existing one
            //First check if the marker required exists in this file
            if( strpos(gzdecode(file_get_contents(MEAS_CONV.DS.MFile::fileFolderFromId($mFile["MeasurementFile"]["id"]).".gz")),$measurement["MeasurementType"]["marker"]) !== false) {
               //Found the marker in the file, everything seems ok -> skip data import and just assign file 
               $measurementFileId = $mFile["MeasurementFile"]["id"];
               $data = ""; //Just set it to something so the next step is skipped
            }else{
               //Marker not found in file, need to add the data to the file
               $measurementFileId = $mFile["MeasurementFile"]["id"];
               $addDataToFile = true;
               $currentData = gzdecode(file_get_contents(MEAS_CONV.DS.MFile::fileFolderFromId($mFile["MeasurementFile"]["id"]).".gz"));
            }
         } elseif (file_exists(MEAS_TMP . DS . str_replace("-","_",str_replace("+","_",$filename)))) {//Check if a file of this name exists in the files directory
            //get full file content
            $info = "";
            $table = "";
            $data = file_get_contents(MEAS_TMP . DS . str_replace("-","_",str_replace("+","_",$filename)));
            echo "File found, copying data, ".$filename."\n";
         }
      }
      if(!isset($data) || $addDataToFile){
         //Get content from db for file
         $measurementReadings = array();
         if(!in_array($measurement["MeasurementType"]["marker"],array("intcal","calvsep"))){
            $tmp = $this->Measurement->MeasuringPoint->Reading->getMeasurementByMeasuringpointId($id);
            foreach($tmp as $reading){
               $measurementReadings[$reading["Reading"]["measuring_point_id"]][$reading["Parameter"]["name"]] = $reading["Reading"]["value"];
            }
         }else{
            $Reading = &$this->Measurement->MeasuringPoint->Reading;
            if($measurement["MeasurementType"]["marker"] == "intcal"){
               $Reading->parameterX = "Time";
               $yCols = array("Signal");
            }else{
               $Reading->parameterX = "Vsep";
               $yCols = array("Mean","RMS");
            }
            $chips = $Reading->getChips($id);
            foreach($chips as $chip){
               $chip = $chip["Reading"]["value"];
               $strips = $Reading->getStrips($id,$chip);
               foreach($strips as $strip){
                  foreach($yCols as $col){
                     $Reading->parameterY = $col;
                     $tmp = $Reading->getStripValues($id,$chip,$strip);
                     foreach($tmp as $reading){
                        $measurementReadings[$reading["Reading"]["measuring_point_id"]]["Chip"] = $chip;
                        $measurementReadings[$reading["Reading"]["measuring_point_id"]]["Strip"] = $strip;
                        $measurementReadings[$reading["Reading"]["measuring_point_id"]][$reading["Parameter"]["name"]] = $reading["Reading"]["value"];
                        set_time_limit(60); //Make sure the system doesn't time out
                     }
                  }
               }
               if(!isset($data)){
                  $header = array();
                  $count = 0;
                  foreach($measurementReadings as $measuringPoint){
                     $header = array_merge($header,array_keys($measuringPoint));
                     if($count>10) break;
                     $count++;
                  }
                  $header = array_unique($header);
                  $data = implode(",",$header)."\n";
               }
               foreach($measurementReadings as $reading){
                  $tmp = array();
                  foreach($header as $pos=>$name){
                     $tmp[$pos] = (in_array($name,array_keys($reading)))?$reading[$name]:0;
                  }
                  $data .= implode(",",$tmp)."\n";
               }
               echo $this->_runtime($chip,false);
               echo " ".count($tmp)." ".memory_get_usage();
               echo "<br />";
               $measurementReadings = array();
            }
         }
#        $measuringPoints = array_keys($measurementReadings);
         $tmp  = array();
         $tags = "[tags]\n";
         foreach($measurement["MeasurementTag"] as $tag){
            $tmp[] = $tag["name"];
         }
         $tags .= implode(", ",$tmp)."\n";
         if($measurement["MeasurementType"]["marker"] == "striperrors"){
            //If measurement Type is strip error measurement replace parameter names (depending on n or p-side)
            if(strpos($tags,"n-side")!== false){
               $header = explode(",","strip number,Side,n_no_DC_measurement,n_implant_short,n_implant_or_resistor_open,n_high_current,n_low_current,n_high_resistor,n_low_resistor,n_no_AC_measurement,n_pinhole,n_metal_short,n_metal_open,n_low_cap");
               $prefix = "n_";
            }elseif(strpos($tags,"p-side")!== false){
               $header = explode(",","strip number,Side,p_no_DC_measurement,p_implant_short,p_implant_or_resistor_open,p_high_current,p_low_current,p_high_resistor,p_low_resistor,p_no_AC_measurement,p_pinhole,p_metal_short,p_metal_open,p_low_cap,p_bad_isolation");
               $prefix = "p_";
            }else{
               return "Error! No tag found ";
            }
            $data = implode(",",$header)."\n";
//          foreach($header as $p=>$n){
//             $header2[$p] = (in_array($n,$requiresPrefix))?$prefix.$parameterMappingArray[$n]:$parameterMappingArray[$n];
//          }
            foreach($measurementReadings as $reading){
               $tmp = array_fill(0,count($header),0);
               foreach(array_keys($reading) as $n){
                  $param = (in_array($n,$requiresPrefix))?$prefix.$parameterMappingArray[$n]:$parameterMappingArray[$n];
#                 $pos = (in_array($n,$requiresPrefix))?array_search($parameterMappingArray[$n],$header):array_search($param,$header);
                  $pos = array_search($param,$header);
                  if($pos !== false){
                     $tmp[$pos] = $reading[$n];
                  }else{
                     continue;
                  }
               }
               $tmp[array_search("Side",$header)] = ($prefix == "n_")? 1:2 ;
               $data .= implode(",",$tmp)."\n";
            }
         }else{
            if(!isset($header)){
               $header = array();
               $count = 0;
               foreach($measurementReadings as $measuringPoint){
                  $header = array_merge($header,array_keys($measuringPoint));
                  if($count>50) break;
                  $count++;
               }
               $header = array_unique($header);
   
               $data = implode(",",$header)."\n";
            }
            foreach($measurementReadings as $reading){
               $tmp = array();
               foreach($header as $pos=>$name){
                  $tmp[$pos] = (in_array($name,array_keys($reading)))?$reading[$name]:0;
               }
               $data .= implode(",",$tmp)."\n";
            }
         }
         $data = "\n[".$measurement["MeasurementType"]["marker"]."]\n".$data;
         
         if(!$addDataToFile){
            //not adding data to file but creating new file, create info header and so forth
            $table = "";
            $info = "[info]\n";
            $Parameters = ClassRegistry::init('Parameters');
            $dbParameters = $Parameters->find("list");
            $tmp = array();
            $tmp["StartDateTime"] = ($measurement["Measurement"]["start"] != null)? $measurement["Measurement"]["start"]:$measurement["History"]["created"];
            $tmp["StopDateTime"]  = ($measurement["Measurement"]["stop"] != null)? $measurement["Measurement"]["start"]:$measurement["History"]["created"];
            //Check if it was a CSV file from APVDAQ by checking for the 'Filename' marker
            if(!isset($tmp["Filename"])){
               $tmp["ID"] = $measurement["Item"]["code"];
            }
            foreach($measurement["MeasurementParameter"] as $parameter){
               $parameterName = $dbParameters[$parameter["parameter_id"]];
               if($parameterName == "Table") {
                  //Reconvert html table to csv table
                  $table = "\n[apv25]\n".trim(str_replace("\t","",str_replace("</th></tr>","",str_replace("\n\n","\n",str_replace("<tr><td>","",str_replace("<tr><th>","",str_replace("</th><th>",",",str_replace("</td></tr>","",str_replace("</td><td>",",",str_replace("<table>","",str_replace("</table>","",str_replace("<br />","",$parameter["value"]))))))))))))."\n\n";
               }else{
                  $tmp[$parameterName] = $parameter["value"];
                  if($parameterName == "File name" || $parameterName == "Filename") {
                     $filename = $parameter["value"];
                  }
               }
            }
            //Store Tags here as well from the database during conversion
            $info .= implode(",",array_keys($tmp))."\n";
            $info .= implode(",",array_values($tmp))."\n\n";
            $data = $tags.$data;
         }else{
            $data = $currentData.$data;
         }
         echo "File not found, data copied\n";
      }

      
      if(!isset($filename)){
         $filename = "Measurement_".$id.".csv";
      }
      if(isset($measurementFileId)){
         if(!$this->Measurement->saveAssociated(array("Measurement"=>array("id"=>$id),"MeasurementFile"=>array("id"=>$measurementFileId)),array("deep"=>true))){
            debug($this->Measurement->validationErrors);
         }else{
            //Store updated data in file
            $origFilePath = MEAS_ORIG.DS.MFile::fileFolderFromId($measurementFileId);
            $convFilePath = MEAS_CONV.DS.MFile::fileFolderFromId($measurementFileId);
            file_put_contents($origFilePath,$info.$data);
            MFile::_gZipFile($origFilePath);
            file_put_contents($convFilePath,$info.$data);
            MFile::_gZipFile($convFilePath);
         }
      }else{
         if($this->Measurement->saveAssociated(array("Measurement"=>array("id"=>$id),"MeasurementFile"=>array("originalFileName"=>$filename)),array("deep"=>true))){
            $measurement = $this->Measurement->findById($id);
            $origFilePath = MEAS_ORIG.DS.MFile::fileFolderFromId($measurement["Measurement"]["measurement_file_id"]);
            $convFilePath = MEAS_CONV.DS.MFile::fileFolderFromId($measurement["Measurement"]["measurement_file_id"]);
            //Check if target folders exist
            if(!file_exists(dirname(MEAS_ORIG.DS.MFile::fileFolderFromId($measurement["Measurement"]["measurement_file_id"])))){
               mkdir(dirname(MEAS_ORIG.DS.MFile::fileFolderFromId($measurement["Measurement"]["measurement_file_id"])),0777,true);
            }
            if(!file_exists(dirname(MEAS_CONV.DS.MFile::fileFolderFromId($measurement["Measurement"]["measurement_file_id"])))){
               mkdir(dirname(MEAS_CONV.DS.MFile::fileFolderFromId($measurement["Measurement"]["measurement_file_id"])),0777,true);
            }
            file_put_contents($origFilePath,$info.$table.$data);
            MFile::_gZipFile($origFilePath);
            file_put_contents($convFilePath,$info.$table.$data);
            MFile::_gZipFile($convFilePath);
         }else{
            debug($this->Measurement->validationErrors);
         }
      }
      
      return $this->_runtime($id." Generation",false);
   }

   private function _measurementStandardplot($measurementTypeId,$header=array()){
      $measurementStandardPlot = "";
      $MeasurementType = $this->Measurement->MeasurementType->findById($measurementTypeId);
      switch(trim($MeasurementType["MeasurementType"]["marker"])){
         case "strips":
               $tmp = $this->Measurement->MeasuringPoint->Reading->Parameter->find("first",array("conditions"=>array("Parameter.name"=>"Strip"),"recursive"=>-1,"fields"=>"id"));
               $x = $tmp["Parameter"]["id"];
               $tmp = $this->Measurement->MeasuringPoint->Reading->Parameter->find("first",array("conditions"=>array("Parameter.name"=>"Noise"),"recursive"=>-1,"fields"=>"id"));
               $y = $tmp["Parameter"]["id"];
            break;
         case "iv":
            $tmp = $this->Measurement->MeasuringPoint->Reading->Parameter->find("first",array("conditions"=>array("Parameter.name"=>"V[V]"),"recursive"=>-1,"fields"=>"id"));
            $x = $tmp["Parameter"]["id"];
            $tmp = $this->Measurement->MeasuringPoint->Reading->Parameter->find("first",array("conditions"=>array("Parameter.name"=>"I+[uA]"),"recursive"=>-1,"fields"=>"id"));
            $y = $tmp["Parameter"]["id"];
            break;
         case "sensor":
            $tmp = $this->Measurement->MeasuringPoint->Reading->Parameter->find("first",array("conditions"=>array("Parameter.name"=>"Bin"),"recursive"=>-1,"fields"=>"id"));
            $x = $tmp["Parameter"]["id"];
            $tmp = $this->Measurement->MeasuringPoint->Reading->Parameter->find("first",array("conditions"=>array("Parameter.name"=>"SNR"),"recursive"=>-1,"fields"=>"id"));
            $y = $tmp["Parameter"]["id"];
            break;
         case "stripmeas":
         case "striperrors":
               $tmp = $this->Measurement->MeasuringPoint->Reading->Parameter->find("first",array("conditions"=>array("Parameter.name"=>"Pad"),"recursive"=>-1,"fields"=>"id"));
               $x = $tmp["Parameter"]["id"];
               $tmp = $this->Measurement->MeasuringPoint->Reading->Parameter->find("first",array("conditions"=>array("Parameter.name"=>"I_strip [A]"),"recursive"=>-1,"fields"=>"id"));
               $y = $tmp["Parameter"]["id"];
            break;
         case "itmeas":
            $tmp = $this->Measurement->MeasuringPoint->Reading->Parameter->find("first",array("conditions"=>array("Parameter.name"=>"time [sec]"),"recursive"=>-1,"fields"=>"id"));
            $x = $tmp["Parameter"]["id"];
            $tmp = $this->Measurement->MeasuringPoint->Reading->Parameter->find("first",array("conditions"=>array("Parameter.name"=>"current meas [A]"),"recursive"=>-1,"fields"=>"id"));
            $y = $tmp["Parameter"]["id"];
            break;
         case "sensor":
            $tmp = $this->Measurement->MeasuringPoint->Reading->Parameter->find("first",array("conditions"=>array("Parameter.name"=>"Signal"),"recursive"=>-1,"fields"=>"id"));
            $x = $tmp["Parameter"]["id"];
            $tmp = $this->Measurement->MeasuringPoint->Reading->Parameter->find("first",array("conditions"=>array("Parameter.name"=>"SNR"),"recursive"=>-1,"fields"=>"id"));
            $y = $tmp["Parameter"]["id"];
            break;
         default:
            if(strpos($MeasurementType["MeasurementType"]["name"], " vs. ")){#if versus automatically click both possible parameters <--- old variant, doesn't work with the renamed measurement types
               $measurementStandardPlot = "$('.parameter:first').trigger({type: 'contextmenu'}); $('.parameter:last').click();";
               if(strpos($MeasurementType["MeasurementType"]["name"],"[F]") && strpos($MeasurementType["MeasurementType"]["name"],"[V]"))#if CV curve plot inverse square
                  $measurementStandardPlot .= "$('#calcInverseSquare').click(); setPlotType('inverseSquare');";
            }elseif(count($header)==2){ // <-- new variant, works with any measurement of two different values. 
               $measurementStandardPlot = "$('.parameter:first').trigger({type: 'contextmenu'}); $('.parameter:last').click();";
               $tmp = "";
               foreach($header as $h){
                  $tmp .= implode(array_values($h));
               }
               if(strpos($tmp,"[V]") >0 && strpos($tmp,"[F]") )#if CV curve plot inverse square
                  $measurementStandardPlot .= "$('#calcInverseSquare').click(); setPlotType('inverseSquare');";
            }
            // debug($MeasurementType["MeasurementType"]["name"]);
      }
      if(isset($x))
         $measurementStandardPlot = sprintf("$('#parameter_%s').trigger({type: 'contextmenu'}); $('#parameter_%s').click();",$x,$y);
      $this->set("measurementStandardPlot",$measurementStandardPlot);
      return $MeasurementType;
   }

   private function _strip_errors($measurementReadings, $returnValues = false)
   {
      $strips = array();
      $parameters = array();
      #Store twice, once errors per strip (e.g. strip 1 has error 1,5 and 7) ---> $strips
      #and once strips per error (e.g. error 1 is present on strip 1, 8, 203) --> $parameters
      $header = $measurementReadings[0];
      foreach($measurementReadings[1] as $row){
         foreach($row as $id=>$col){
            $header[$id]["name"] = trim($header[$id]["name"]);
            if($header[$id]["name"] == "Side") continue;
            if($header[$id]["name"] == "strip number" || $header[$id]["name"] == "Strip"){
               $strip = $col;
            }elseif($col == 1){
               $strips[$strip][] = $header[$id]["name"];
               $parameters[$header[$id]["name"]][] = $strip;
            }elseif($col > 1){
               //Special case for % based information e.g. metal open @ 54%
               $name = $header[$id]["name"]." @ ".$col."%";
               $strips[$strip][] = $name;
               $parameters[$name][] = $strip;
            }
         }
      }
      ksort($strips);
      if($returnValues){
         return array("strips"=>$strips,"parameters"=>$parameters);
      }else{
         $this->set(compact("strips","parameters"));
      }
   }



   public function saveFile() {
      $measurements = $this->Session->read('Measurements');
      pr($measurements);
   }

   public function chooseFile($item_id) {
      if ($this->request->is('post')) {
         $this->Session->delete('Debug'); //debug

         // check if everything worked
         if(isset($this->request->data['Data']['Files'])) {
            // Parse Files
            // debug($this->request->data['Data']['Files']);
            $result = $this->Parser->parse($this->request->data['Data']['Files'], '/[\t]/');
            $this->Session->write('Debug.MeasurementChooseFile.result', $result);   //debug

            //debug($result);
            // Check Filename to get the right Parse function
               /*
            foreach ($result['content'] as $filename => $file) {
               if ('CIV' == substr ($filename, 0, 3)) {
                  // Split result into the right form for Database
                  unset($file[0]);
                  $Parameter = $file[1];
                  unset($file[1]);
                  unset($file[2]);
                  $date = $file[3][0];
                  //* start: <2012-04-13 10:35:55> stop: <2012-04-13 10:48:43>
                  $start = substr($date,10, 19);
                  $stop = substr($date,38, 19);
                  //pr($start);
                  //pr($stop);
                  unset($file[3]);
                  unset($file[4]);

                  foreach($file as $line) {
                     $reading[0] = $line[0]; // Voltage
                     $reading[1] = $line[1]; // Current
                     $iv['MeasuringPoint'][] = $reading;
                  }

                  $iv['Parameter'][0]=$Parameter[0];
                  $iv['Parameter'][1]=$Parameter[2];
                  $iv['start'] = $start;
                  $iv['stop'] = $stop;
                  $iv['measurement_type'] = 'IV';
                  $measurements[]=$iv;
                  unset($reading);

                  foreach($file as $line) {
                     if(isset($line[2])) {
                        $reading[0] = $line[2]; // Voltage
                        $reading[1] = $line[3]; // Capacity
                        $cv['MeasuringPoint'][]=$reading;
                     }
                  }

                  $cv['Parameter'][0]=$Parameter[0];
                  $cv['Parameter'][1]=$Parameter[1];
                  $cv['start'] = $start;
                  $cv['stop'] = $stop;
                  $cv['measurement_type'] = 'CV';
                  $measurements[] = $cv;

                  //pr($iv);
                  //pr($cv);
               } else {
                  unset($result['content'][$filename]);
               }

            }
               */
            //$this->set('content', $result['content']);

         }
         if(isset($result['errors'])) {
            //$this->set('errors', $result['errors']);
            $this->Session->write('Debug.MeasurementChoose.Errors', $result['errors']);   //debug
         }
         $this->set('result', $result);
         //$this->set('measurements', $measurements);
         //$this->Session->write('Measurements', $measurements);
         $this->render('viewFile',null);
         //$this->redirect(array('action' => 'viewFile'));
      }
   }

/**
 * _interpretAPVDAQ
 * Tries to interpret given data as ascii data according to given formats (e.g. str, civ, ...)
 *
 * @param $file string Filename of the apvdaq file to be interpreted
 * @param $apvdaqType string
 *
 * @return object
 */

function _interpretAPVDAQ($file, $apvdaqType){
   $apvdaq = null;
   $apvdaq = new apvdaq($apvdaqType,$file);
   return $apvdaq;
}


/**
 * _interpretASCII
 * Tries to interpret given data as ascii data according to given formats (e.g. str, civ, ...)
 *
 * @param $file string Filename of the ascii file to be interpreted
 * @param $asciiType string
 *
 * @return object
 */

function _interpretASCII($file, $asciiType){
   $return = array();
   $ascii = new ascii($asciiType,$file);
#  debug($ascii->getAllParameters());
   return $ascii;
}

/**
 * _interpretCSV
 * Tries to interpret given data as a csv by trying different delimiters and quotechars
 *
 * @param $data array of rows to be interpreted
 *
 */

 function _interpretCSV($content){
   $data = explode("\n",$content);

   $possibleDelimiters = array(";",",","\t");
   $possibleQuotchars = array("\"","'");

   foreach($possibleDelimiters as $delimiter){
      foreach($possibleQuotchars as $quotechar){
         $colcount = 0;
         $wrong = FALSE;
         foreach($data as $num=>$row){
            $aRow = str_getcsv($row,$delimiter,$quotechar);
            if($aRow[0] == null) continue;
            if(count($aRow)<2){ //Check if at least two columns were recognized, and if not skip these settings
               $wrong = TRUE;
               break;
            }
            if($colcount == 0) $colcount = count($aRow);
            if(count($aRow) != $colcount){ //Check if each row has the same count of cols and if not abort these settings
               $wrong = TRUE;
               break;
            }
#           debug($aRow);
         }
         if($wrong) continue;
         //If the settings worked use them as final
         break(2);
      }
   }
   if($wrong){
      //No settings could be found, abort search
      $this->set("message","File format was not recognized");
      return array();
   }
   $csvdata = array();
   foreach($data as $num=>$row){
      $aRow = str_getcsv($row,$delimiter,$quotechar);
      if($aRow[0] == null) continue;
      $csvdata[] = $aRow;
   }
   //Check if the first row contains col names by checking the first col of the first row
   if(!is_numeric($csvdata[0][0])){
      //First row contains col names, store them separately and remove them from the value array
      $parameters = $csvdata[0];
      $organizedData["header"] = $csvdata[0];
      unset($csvdata[0]);
   }
   $organizedData["data"] = array_slice($csvdata,0,5);

#  debug($organizedData);
   return $organizedData;



 }


/**
 * _identifyData method
 * Designed to identify the type of text data received.
 *
 * @return string Represents the recognized datatype (e.g. csv, ascii) or FALSE if none was recognized.
 */

   function _identifyData($file) {
      ini_set("auto_detect_line_endings", true);
      //Open the file
      $fp = fopen($file,"r");
      $rows = array();
      //Read first 8 lines into array
      for($i=0;$i<8;$i++){
         $rows[$i] = trim(fgets($fp));
      }
#     debug($rows);
      fclose($fp);
      //TODO: Check filename additionally and make assumption based on both results

      //Check if str-File with format: first row *, second Row Char that is not *, 3rd row empty, 4th row *, 5th row *, 6th row empty, 7th row, Char that is not *, 8th row, Char that is not *
      if($rows[0][0] == "*" && $rows[1][0] != "*" && $rows[2] == "" && $rows[3][0] == "*" && $rows[4][0] == "*" && $rows[5] == "" && $rows[6][0] != "*" && $rows[7][0] != "*"){
         return "ascii-str";
      }
      //Check if CIV-File with format: first row *,  second Row Char that is not *, 3rd row *, 4th row *, 5th row *, 6th row empty, 7th row, Char that is not *, 8th row, Char that is not *
      if($rows[0][0] == "*" && $rows[1][0] != "*" && $rows[2][0] == "*" && $rows[3][0] == "*" && $rows[4][0] == "*" && $rows[5] == "" && $rows[6][0] != "*" && $rows[7][0] != "*"){
         return "ascii-civ";
      }
      if(substr($rows[0],0,5) == "HEPHY" && substr($rows[1],0,6) == "sensor" && $rows[6][0] == "t" ){
         return "ascii-it";
      }
      if(substr($rows[0],0,5) == "HEPHY" && substr($rows[1],0,6) == "sensor" && $rows[6][0] != "t" ){
         return "ascii-iv";
      }
      if(substr($rows[0],0,1) == "#"){ #The recognition symbol of a pxd file is a hash in the first row followed by the measurement name
         return "ascii-pxd";
      }
      //Check if no row is empty and if yes assume CSV file
      $emptyrow = false;
      foreach($rows as $row){
         if($row == "") $emptyrow = true;
      }
      if($emptyrow == false){
         return "csv";
      }else{
         //No CVI, STR or simple csv file. Next try if it is a APVDAQ file (3 distinctions hw, sw, cal)
         //Load the whole file as a string for a regex
         $fileAsString = file_get_contents($file);
         //use regex to find separators enclosed in brackets
         $found = array();
         if(preg_match_all("/\[(.*?)\]/", $fileAsString, $found)>0){
            //Found matches that are now saved in $found. $found[0] contains the matches including the square backets and $found[1] contains the matches without brackets
            if(in_array("intcal", $found[1])){
               return "apvdaq-cal";
            }elseif(in_array("sensor", $found[1])){
               return "apvdaq-hw";
            }elseif(in_array("calvsep", $found[1])){
               return "apvdaq-cvs";
            }else{
               return "apvdaq-sw";
            }
         }else{
            //Still couldn't recognize the file, abort
            return false;
         }

         //If found compare the found values to arrays to figure out the exact type
      }
      return false;
   }
   
/**
 * _checkData method
 * Designed to check the Data and present a preview of the received data for user validation before the data is stored
 *
 * @return organizedData fileContainer object offering functions to get the measurement pairs, cols and a preview
 */

   function _checkData($file) {
      $this->fileinfo["name"] = $file;
      //Check if File exists
      $fp = @fopen(MEAS_TMP.DS.$file, "r");
      if($fp == null){
         $this->set("message","File not found");
         throw new Exception("File not found");
      }
      fclose($fp);
      //Get file mime type
      $fi = new finfo(FILEINFO_MIME_TYPE);

      $mime = $fi->file(MEAS_TMP.DS.$file);
      $this->fileinfo["mime"] = $mime;

      switch($mime){
         case "text/csv":
         case "text/plain":
            //If file csv or plain call _identifyData to try and figure out which format was used.
            $ffr = new FileFormatRecognizer($file);
#           $recognizedType = $this->_identifyData($file);
            if($ffr->foundMatch){ //Found a matching function and converted the file
               $mFile = new MFile($ffr->convertedFilePath,$ffr->originalFilePath);
               if($mFile->error){
                  $message = "";
                  foreach($mFile->errors as $error){
                     $message .= $error["msg"]."<br />";
                  }
                  $this->set("message",$message);
                  return false;
               }
               $previewData = array();
               if(isset($this->request["data"]["itemId"])) {
                  $itemId = $this->request["data"]["itemId"];
                  //add item_id to parameters of fileContainers
                  $conditions = array(
                     'Item.id' => $itemId
                  );
                  $previewData["itemCode"] = $this->Measurement->Item->field('code', $conditions);
                  $previewData["passedCode"] = true;
//                debug($mFile->measurementParameters);

                  if($mFile->measurementParameters->itemCode !== ""){
                     $previewData["recognizedCode"] = $mFile->measurementParameters->itemCode;
                     $previewData["recognizedId"] = $this->Measurement->Item->field('id', array('Item.code' => $mFile->measurementParameters->itemCode));
                  }else{
                     $mFile->measurementParameters->setItemCode($previewData["itemCode"]);
//                   debug($mFile);
                     $previewData["recognizedCode"] = false;
                     $previewData["recognizedId"] = false;
                  }
               }else{
                  $conditions = array(
                     'Item.code' => $mFile->measurementParameters->itemCode
                  );

                  $itemId = $this->Measurement->Item->field('id', $conditions);
                  $previewData["itemCode"]      = $mFile->measurementParameters->itemCode;
                  $previewData["passedCode"]    = false;
               }
               if($previewData["itemCode"] == null){
                  $this->set("message","No item Code was found an none was passed, cannot associate measurement with File. Aborting!");
                  return false;
               }
               if($itemId !== false){//check if found
                  $previewData["item_id"] = $itemId;
               }else{
                  $this->set("message","The item code '".$previewData["itemCode"]."' was not found in the database, please make sure the item exists in the database. ");
                  return false;
               }
            }else{
               $this->set("message","File appears to contain text but was not recognized as an expected layout");
               return false;
            }
            $this->fileType = $ffr->match;
            $this->set("mFile",$mFile);
            $this->set("previewData",$previewData);
            break;
         case "application/octet-stream":
            //Possibly a root file, act accordingly
            //TODO: If file not plain or csv offer to attach as downloadable measurement file to an item (usually .root file)
         case "application/zip":
            //Unpack the Zip archive into folder
            $zip = new ZipArchive;
            if($zip->open($file) === TRUE){
               //create new folder for extraction
               $basepath = TMP_FILE.DS."archive_";
               $counter = 1;
               while(file_exists($basepath.$counter)){
                  $counter++;
               }
               $folderpath = $basepath.$counter.DS;
               mkdir($folderpath);
               $zip->extractTo($folderpath);
               $zip->close();
               $files = scandir($folderpath);
               foreach($files as $id=>$file){
                  if($id == 0 or $id == 1){
                     unset($files[$id]); //remove the . and .. paths
                     continue;
                  }
                  $files[$id] = "archive_".$counter."/".$files[$id];
               }
               //Return array of file paths for the unpacked files
               return $files;
               //Let browser do requests for the preview of each file.as usual
            }
            break;
         default:
            //If failed return empty array and error message
            $this->set("message","File format $mime was not recognized");
            return false;
      }
      return $mFile;
#     return $organizedData;
   }
   /**
   * download
   * returns either the original or converted file as non-gzipped version for download while setting the original filename again. 
   */

   public function download($id = null,$original=false)
   {
      $measurement = $this->Measurement->find("first",array("conditions"=>array("Measurement.id"=>$id)));
      if(!isset($measurement["MeasurementFile"])):
         throw new NotFoundException("This Measurement doesn't exist");
      endif;
      if($original){
         $path = MEAS_ORIG.DS.MFile::fileFolderFromId($measurement["MeasurementFile"]["id"]);
      }else{
         $path = MEAS_CONV.DS.MFile::fileFolderFromId($measurement["MeasurementFile"]["id"]);
      }
      $filename = $measurement["MeasurementFile"]["originalFileName"];
      //If the Measurementtype is a StripError measurement replace the filename with the defined format. 
      if(strpos($filename,"Measurement") !== false && $measurement["MeasurementType"]["marker"] == "striperrors"){
         //Check if n- or p-side
         $side = "";
         foreach($measurement["MeasurementTag"] as $tag){
            if($tag["name"] == "p-side"){
               $side = "Pside";
               break;
            }elseif($tag["name"] == "n-side"){
               $side = "Nside";
               break;
            }
         }
         $filename = "DSSDdefects_".trim($measurement["Item"]["code"])."_".$side.".csv";
      }
      
      //try if a not-gzipped version exists
      if(!file_exists($path)){
         //try if a gzipped version exists
         if(file_exists($path.".gz")){
            //Gunzip the data and set it
            $this->response->body(gzdecode(file_get_contents($path.".gz")));
            $this->response->download($filename);
         }else{
            $this->set("measurement",$measurement);
            return false;
         }
      }else{
         //Is a file, return as is
         $this->response->file(
            $path,
            array(
               'download' => true,
               'name' => $filename
            )
         );
      }
      return $this->response;
   }


   public function getSimilarMeasurements($measurementId = null){
//    $this->_runtime("init");
      $measurement = $this->Measurement->findById($measurementId);
//    $this->_runtime("getMeasurementData");
      $contain = array( //Values to be contained within the measurement results
         "MeasurementType",
         "Item",
         "Item.ItemSubtype",
         "Device.Location",
         "MeasurementTag"
      );

      //Measurements of the same type of this item with other dates
      $data[0] = $this->Measurement->find("all",array(
         "conditions"=>array(
            "MeasurementType.id"=>$measurement["MeasurementType"]["id"],
            "Item.id"=>$measurement["Item"]["id"],
            "Measurement.id <> "=>$measurementId
            ),
         "contain"=>$contain
         )
      );
//    $this->_runtime("get other Measurements with same type");

      //Measurements of the same item but with other types (e.g. CV when currently displaying an IV) 
      $data[1] = $this->Measurement->find("all",array(
            "conditions"=>array(
            "MeasurementType.id <>"=>$measurement["MeasurementType"]["id"],
            "Item.id"=>$measurement["Item"]["id"]
            ),
            "contain"=>$contain
         )
      );
//    $this->_runtime("get Measurements with different type");

      //Measurements of the same type of this items sub or parent items. 
      $parentItemIds = $this->Measurement->Item->getParentItemIdsRecursive($measurement["Item"]["id"]);
      $childItemIds = array_keys($this->Measurement->Item->getValidComponentsRecursive($measurement["Item"]["id"]));
      $data[2] = $this->Measurement->find("all",array(
            "conditions"=>array(
               "MeasurementType.id"=>$measurement["MeasurementType"]["id"],
               "Item.id"=>array_merge($parentItemIds,$childItemIds)
            ),
            "contain"=>$contain
         )
      );

//    $this->_runtime("get Measurements for sub or parent items with same type");
      
      //Measurements of the same type of other items with the same subtype
      $data[3] = $this->Measurement->find("all",array(
            "conditions"=>array(
               "MeasurementType.id"=>$measurement["MeasurementType"]["id"],
               "Item.item_subtype_id"=>$measurement["Item"]["item_subtype_id"],
               "Item.id <>"=>$measurement["Item"]["id"]
            ),
            "contain"=>$contain
         )
      );
//    $this->_runtime("get Measurements of the same type of other items with the same subtype");
      //Add header of each measurement to the respectable array
      foreach(array(0,1,2,3) as $n){
         foreach($data[$n] as $i1=>$m){
            $this->_setMeasurementObj($m["Measurement"]["id"],$m);
            $tmp = $this->mm->getHeader();
            foreach($tmp as $t){
               foreach($t as $i2=>$t2){
                  $data[$n][$i1]["Measurement"]["header"][$i2]=$t2;
               }
            }
         }
      }
      $this->set("measurementBlocks",$data);
   }
   
/**
 * getDataset method
 * Requires the id of the measurement as the first url parameter and the x and y axis as the second and third respectively
 * A correct URL looks something like this measurements/getDataset/244/38/45 with equals to measurement_id=244, x-parameter_id=38, y-parameter_id=45
 * @return $string returns a json encoded array of [x:y] pairs ready to be displayed by flot.
 */

   public function getDataset($id = null)
   {
      $this->autoRender = false;
      $this->Measurement->id = $id;
      if (!$this->Measurement->exists()) {
         return json_encode(array(1,1));
      }
      $measurement = $this->Measurement->findById($id);
      //Check if Measurement has a MeasurementFile assigned and if there is NO cached file for this measurement already. 
      if($measurement["Measurement"]["measurement_file_id"] != null && !MeasurementObj::cached($id)){
         //Yes there is a MeasurementFile, initialize the File 
         $mFile = new MFile($measurement["Measurement"]["measurement_file_id"]);
         $this->mm = $mFile->getSectionAsMeasurement($measurement["MeasurementType"]["marker"],true,$id);
      }else{
         //No, load the Measurement from the Database (or file cache)
         $this->mm = new MeasurementObj($measurement["MeasurementType"],$measurement["MeasurementTag"],$measurement["MeasurementParameter"],$id);
      }
      //From here on there should by a Measurement Object available that hides the fact, that there are two different ways of initialization and just reacts appropriately to the requests by the user. 
      $params = $this->request->params["pass"];
      
      
      $parameters = $this->Measurement->MeasuringPoint->Reading->Parameter->find("list");
      $measurementDescriptionString = $measurement["MeasurementType"]["name"]." with ".$measurement["Device"]["name"]." of ".$measurement["Item"]["code"];
      unset($params[0]); //Remove the first element as it equals the id already passed
      
      $prefix = "";
      if($parameters[$params[1]]=="Strip") {
         $segments = $this->mm->getDistinctValuesWhere(array(), array_search("Chip", $parameters));
         $prefix = "Chip ";
      }elseif($parameters[$params[1]]=="Bin"){
            $segments = $this->mm->getDistinctValuesWhere(array(),array_search("Hybrid",$parameters));
            $prefix = "Hybrid ";
      }else{
         $segments = array(0);
      }
      
      $outputData = array();
      //Iterate through the different segments and get the corresponding value pairs
      foreach($segments as $segmentId=>$segment){
         //loop over both parameters
         if(count($segments)>1){
            //To be grouped in Chips:
            $tmp = $this->mm->getColsWhere(array(array_search(trim($prefix),$parameters)=>$segment),$params[1],$params[2])->getData();
         }else{
            $tmp = $this->mm->getCols($params[1],$params[2])->getData();
         }
         $outputData[$segmentId]["data"] = array_values($tmp);
         if($prefix != ""){
            $outputData[$segmentId]["label"] = $prefix.$segmentId." ".$parameters[$params[2]];
         }else{
            $outputData[$segmentId]["label"] = $parameters[$params[2]]." of ".$measurement["Item"]["code"];
         }
         $outputData[$segmentId]["measurementId"] = $id;
         $outputData[$segmentId]["xAxisLabel"] = $parameters[$params[1]];
         $outputData[$segmentId]["yAxisLabel"] = $parameters[$params[2]];
         $outputData[$segmentId]["fullLabel"] = $measurementDescriptionString;
         $outputData[$segmentId]["measurementType"] = $measurement["MeasurementType"]["name"];
         $outputData[$segmentId]["device"] = $measurement["Device"]["name"];
         $outputData[$segmentId]["item"] = $measurement["Item"];
         $outputData[$segmentId]["date"] = $measurement["Measurement"]["start"];
         $mTags = array();
         foreach($measurement["MeasurementTag"] as $mTag){
            $mTags[] = $mTag["name"];
         }
         $outputData[$segmentId]["tags"] = implode(", ",$mTags);
         $outputData[$segmentId]["field"] = $parameters[$params[2]];
      }
      
      echo json_encode($outputData);
   }


   function saveData(){
      if(isset($this->request['data']['fileName'])){
         //User Request, just add filename to database and notify skript to deal with the rest
         $fileName = $this->request['data']['fileName'];
      }else{
         echo __("Couldn't find file pointer, abort");
         exit();
      }

      if(isset($this->request["data"]["measurementTags"])){
         foreach($this->request["data"]["measurementTags"] as $measurementTag){
            $measurementTags[] = $measurementTag;
         }
      }else{
         $measurementTags = array();
      }

      if(isset($this->request["data"]["measurementSetup"])){
         $measurementSetupId = $this->request["data"]["measurementSetup"];
      }else{
         $measurementSetupId = null;
      }
      //Get measurement by the file name
      $mFile = $this->_checkData($fileName);
      $mFile->setTags($measurementTags); //Update the tags from within the file to the ones selected by the user.
      $mFile->setMeasurementSetup($measurementSetupId);
      $measurementIds = $mFile->save();
      $this->set("measurementIds",$measurementIds);
      return;
   }

/**
 * preview method
 *
 * @return void
 */
   public function preview($previewId){

      if(isset($this->request['data']['local'])){
         $filename = $this->request['data']['local'];
      }else{
         #$filename = "VE52585201_2S_1.txt";  //For debugging the preview functionality enter the misbehaving file here and call Measurements/preview/1
         throw new NotFoundException(__("Couldn't find file pointer, abort"));  //Comment this to debug the preview
      }
      $filedata = $this->_checkData($filename);
      if($filedata !== false){
         if($filedata instanceof mFile){
            $this->set("previewId",$previewId);
            $this->set("fileinfo",$this->fileinfo);
            $this->set("filedata",$filedata);
            //Get all possible Measurement Setups that can produce all of these measurements
            foreach($filedata->getMeasurementSections() as $tmp){
               $marker[] = $tmp["name"];
            }
            $devices = array();
            $canDoAll = array();
            foreach($this->Measurement->MeasurementType->find("all",array("conditions"=>array("MeasurementType.marker"=>$marker),"contain"=>array("Device","Device.Location"))) as $count=>$type){
               if($count ==0){
                  foreach($type["Device"] as $device){
                     $devices[$device["id"]] = $device;
                     $canDoAll[] = $device["id"];
                  }
               }else{
                  $tmp = array();
                  foreach($type["Device"] as $device){
                     $devices[$device["id"]] = $device;
                     $tmp[] = $device["id"];
                  }
                  $toBeRemoved = array_diff($canDoAll,$tmp);
                  foreach($toBeRemoved as $id){
                     unset($devices[$id]);
                  }
               }
            }
            $this->set("devices",$devices);
            $this->loadModel("MeasurementTag");
            $this->set("measurementTagIds",$this->MeasurementTag->getTags());
         }
         if(is_array($filedata)){
            //Array returned, this was an archive unpacked and the array contains the file names. Echo the array as json and let the browser deal with the requests
            echo json_encode($filedata);
            $this->autoRender = false;
         }
      }
      #$this->autoRender=false;
   }

/**
 * add method
 *
 * @return void
 */
   public function add($itemId = null) {
      $url = Router::url(array('plugin'=>'plupload','controller' => 'plupload', 'action' => 'upload'));
      $this->Plupload->setUploaderOptions(array(
		'runtimes' => 'html5',
        //'widget_url' => '/plupload/plupload/widget',
        'url' => $url,
        'max_file_size' => Configure::read('Upload.max_file_size'),
        'chunk_size' => Configure::read('Upload.chunk_size'),
        'multipart_params' => array(
			'data[uploadtype]' => "measurement",
            'data[itemId]' => $itemId
        )
      ));
      //Additional callback (javascript) that executes after a file has been uploaded
      $callbackBase = "init: {";
      $callbackFunction = "FileUploaded: function(up, file, info) {
            // Called when a file has finished uploading
            //Get filename from response
            var filename = $.parseJSON(info.response).local;
            //call preview function and load result in new div
            parent.preview($.parseJSON(info.response));
            },";
      $callbackTail = "}";
      $additionalCallbacks = $callbackBase.$callbackFunction.$callbackTail;
      $this->Session->write('additionalCallbacks', $additionalCallbacks);
      // if ($this->request->is('post')) {
         // if($this->RequestHandler->isAjax()){
               // Configure::write('debug', 0);
               // $this->autoRender=false;
            // echo "test";
         // }else{
            // $devices = $this->Measurement->Device->find('list');
            // $measurementTypes = $this->Measurement->MeasurementType->find('list');
            // $this->set(compact('devices', 'measurementTypes','filedata'));
         // }
      //
         // try{
            // $filedata = $this->_checkData();
         // }catch(Exception $e){
            // $this->Session->setFlash($e->getMessage());
            // $filedata = array();
         // }
         // return;
      // }
      $conditions = array(
         'Item.id' => $itemId
      );
      $itemCode=$this->Measurement->Item->field('code', $conditions);

      $devices = $this->Measurement->Device->find('list');
      $measurementTypes = $this->Measurement->MeasurementType->find('list');

      $this->set(compact('devices', 'measurementTypes','itemId','itemCode'));
   }


/**
 * edit method
 *
 * @param string $id
 * @return void
 */
   public function edit($id = null) {
      $this->Measurement->id = $id;
      if (!$this->Measurement->exists()) {
         throw new NotFoundException(__('Invalid measurement'));
      }
      if ($this->request->is('post') || $this->request->is('put')) {
         if ($this->Measurement->save($this->request->data)) {
            $this->Session->setFlash(__('The measurement has been saved'), 'default', array('class' => 'notification'));
            return $this->redirect(array('action' => 'index'));
         } else {
            $this->Session->setFlash(__('The measurement could not be saved. Please, try again.'));
         }
      } else {
         $this->request->data = $this->Measurement->read(null, $id);
      }
      $items = $this->Measurement->Item->find('list');
      $devices = $this->Measurement->Device->find('list');
      $measurementTypes = $this->Measurement->MeasurementType->find('list');
      $this->set(compact('items', 'devices', 'measurementTypes'));
   }

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
   public function delete($id = null) {
      if (!$this->request->is('post')) {
         throw new MethodNotAllowedException();
      }
      $this->Measurement->id = $id;
      if (!$this->Measurement->exists()) {
         throw new NotFoundException(__('Invalid measurement'));
      }
      //Remove the cache file for this measurement. 
      if(MeasurementObj::cached($id)){
         MeasurementObj::deleteCache($id);
      }
      $measurement = $this->Measurement->findById($id);
      if ($this->Measurement->deleteWithCheck()) {
         //Check if another measurement is still using the measurement file
         if($this->Measurement->find("count",array("conditions"=>array("MeasurementFile.id"=>$measurement["MeasurementFile"]["id"])))==0){
            //Remove the original and converted files
            unlink(MEAS_ORIG.DS.MFile::fileFolderFromId($measurement["MeasurementFile"]["id"]).".gz");
            unlink(MEAS_CONV.DS.MFile::fileFolderFromId($measurement["MeasurementFile"]["id"]).".gz");
            //Remove the MeasurementFile in the Database. 
            $this->Measurement->MeasurementFile->delete($measurement["MeasurementFile"]["id"]);
         }
         $this->Session->setFlash(__('Measurement deleted'), 'default', array('class' => 'notification'));
      }else{
         $this->Session->setFlash(__($this->Measurement->message));
      }
      return $this->redirect(array("controller"=>"Measurements","action"=>"index","sort"=>"id","direction"=>"desc"));
   }
   /**
   * Adds the tag with the given $tagid to the item with $id
   * Only reacts to ajax requests
   */
   public function addTag($id=null,$tagid=null)
   {
      if($this->request->isAjax){
         if($id != null && $tagid != null){
            $this->autoRender = false;
            $this->Measurement->id = $id;
            $tagid = substr($tagid, 4);
            $measurement = $this->Measurement->find("first",array("conditions"=>array("Measurement.id"=>$id),"recursive"=>1));
            $measurementTags[] = array("measurement_tag_id"=>$tagid,"measurement_id"=>$id);
            echo json_encode(array("success"=>$this->Measurement->MeasurementTagsMeasurement->saveAll($measurementTags)));
         }else{
            throw new NotFoundException(__('Invalid item or tag'));
         }
      }else{
         throw new NotFoundException(__('Invalid Request'));
      }
   }
   /**
   * Removes the tag with the given $tagid from the item with $id if applicable
   * Only reacts to ajax requests
   */

   public function removeTag($id=null,$tagid=null){
      if($this->request->isAjax){
         if($id != null && $tagid != null){
            $this->autoRender = false;
            $tagid = substr($tagid, 4);
            $this->loadModel("MeasurementTagsMeasurement");
            $measurement_tag_ids = $this->MeasurementTagsMeasurement->find("first",array("conditions"=>array("measurement_id"=>$id,"measurement_tag_id"=>$tagid)));
            if(count($measurement_tag_ids)>0){
               $delId = $measurement_tag_ids["MeasurementTagsMeasurement"]["id"];
            }
            if(isset($delId)){
               echo json_encode(array("success"=>$this->Measurement->MeasurementTagsMeasurement->delete($delId)));
            }else{
               echo json_encode(array("success"=>false,"message"=>"Tag could not be removed"));
            }
         }else{
            echo json_encode(array("success"=>false,"message"=>"Invalid Item or Tag"));
         }
      }else{
         throw new NotFoundException(__('Invalid Request'));
      }

   }
   /**
   * Allows grading of a subtype version according to parameters passed on submit.
   */

   public function gradeSubtypeVersion($subtypeVersionId,$measurementId){
      if(!isset($this->request->data["selected"]) && !$this->request->isAjax()){
         //No measurements selected for grading, return to selector and display message
         $this->Session->setFlash("You need to select at least one measurement for grading","default",array("class"=>"warning"));
         $this->redirect($this->referer());
      }
      $this->autoRender = false;
      $this->_setMeasurementViewVariables($measurementId);

      $measurementReadings = $this->Measurement->MeasuringPoint->Reading->getMeasurementByMeasuringpointId($measurementId);
      $this->set('measurementReadings', $measurementReadings);

      $itemSubtypeVersion = $this->Measurement->Item->ItemSubtypeVersion->find("first",array("conditions"=>array("ItemSubtypeVersion.id"=>$subtypeVersionId),'contain'=>array("ItemSubtype")));
#     debug($itemSubtypeVersion);
      $this->set("itemSubtypeVersion",$itemSubtypeVersion);
#     debug($measurementReadings);
      $measurementData = $this->Measurement->find("first",array("contain"=>array("Item"),"conditions"=>array("Measurement.id"=>$measurementId)));
      $measurementTypeId = $measurementData["Measurement"]["measurement_type_id"];
      $this->_measurementStandardplot($measurementTypeId);

      #Normal request for the grading page, return sample measurement with correct view
      if($this->request->is("post") && !$this->request->isAjax()){
         $selectedMeasurements = array();
         foreach($this->request->data["selected"] as $selectedMeasurement){
            $selectedMeasurements[$selectedMeasurement] = $this->Measurement->find("first",array("conditions"=>array("Measurement.Id"=>$selectedMeasurement),"fields"=>"Item.code","contain"=>array("Item")));
         }
         $this->_strips($measurementReadings);
         $this->set(compact("selectedMeasurements","subtypeVersionId"));
         $this->render("view/strips_grading");
         return;
      }

      #In case of ajax request
      if($this->request->isAjax() && $this->request->is("post")){
         $this->set("displayTable","true");
         $newLimits = array();
         foreach($this->request->data as $name=>$value){
            if(strpos($name,"Value")!==FALSE){
               $parameter_id = substr($name, 7);
               $tmp = $this->Measurement->MeasuringPoint->Reading->Parameter->find("first",array("conditions"=>array("Parameter.id"=>$parameter_id),"recursive"=>-1,"fields"=>"name"));
               $parameter_name = $tmp["Parameter"]["name"];
               $parameter_type = substr($name,0,6);
               $newLimits[$parameter_name][$parameter_type] = $value;
            }
         }

         if($this->request->query["store"] == "false"){
            #in case of forall beeing false just update the limits to be set
            $this->_strips($measurementReadings,$newLimits);
            $this->render("view/strip_tabs");
            return;
         }elseif($this->request->query["store"] == "true"){
            #in case of store beeing true apply the limits posted to the measurement and store the result as a new measurement table.
            #Add a parameter to the strip measurement to signal that the grading measurement belongs to this strip measurement
            #Store the limits used as a parameter table with the measurement
            $this->_strips($measurementReadings,$newLimits); #run through the usual logic to get all the values set correctly

            if($this->_store_as_strip_errors($measurementId,$this->parameters,$newLimits)){
               #applying of strip limits worked, return itemCode and success
               echo json_encode(array("id"=>"mm_".$measurementId,"status"=>"success"));
            }else{
               #applying of strip limits failed, return item id and failed
               echo json_encode(array("id"=>"mm_".$measurementId,"status"=>"failed","error"=>$this->Measurement->validationErrors));
            }
         }

      }
   }

   private function _store_as_strip_errors($measurementId,$parameters,$newLimits){
#     $data = $this->Measurement->find("first",array("conditions"=>array("Measurement.id"=>$measurementId)));
      $data = $this->Measurement->find("first",array("conditions"=>array("Measurement.id"=>$measurementId),"contain"=>array("MeasurementTag")));

      $parameterTable = "<table>
         <tr>
            <th>
               lower limit
            </th>
            <th>
               median
            </th>
            <th>
               upper limit
            </th>
         </tr>";
      foreach($newLimits as $name=>$newLimit){
         $parameterTable .= "<tr>";

         $parameterTable .= "<td>";
         $parameterTable .= $newLimit["lValue"];
         $parameterTable .= "</td>";

         $parameterTable .= "<td>";
         $parameterTable .= $name;
         $parameterTable .= "</td>";

         $parameterTable .= "<td>";
         $parameterTable .= $newLimit["uValue"];
         $parameterTable .= "</td>";

         $parameterTable .= "</tr>";
      }

      $parameterTable .= "</table>";

#     debug($parameterTable);

      #Either get the id of "Strip Error Limits" from the database or construct the array that creates it
      $parameterRequestResult = $this->Measurement->MeasurementParameter->Parameter->find("first",array("conditions"=>array("name"=>"Strip Error Limits"),"contain"=>array()));
      if(count($parameterRequestResult)>0){
         $stripErrorLimitsParameterId = array("parameter_id"=>$parameterRequestResult["Parameter"]["id"],"value"=>$parameterTable);
      }else{
         $stripErrorLimitsParameterId = array("Parameter"=>array("Strip Error Limits"),"value"=>$parameterTable);
      }

      $newMM = array(
         'Measurement' => array(
            'item_id' => $data["Measurement"]["item_id"],
            'device_id' => $data["Measurement"]["device_id"],
            'user_id' => $data["Measurement"]["user_id"],
            'measurement_type_id' => '24',
            'start' => date("Y-m-d H:i:s"),
            'stop' => date("Y-m-d H:i:s")
         ),
         'History' => array(
            'item_id' => $data["Measurement"]["item_id"],
            'event_id' => '2',
            'user_id' => $this->Session->read('User.User.id'),
            'comment' => 'Saving Measurement',
         ),
         'MeasurementQueue' => array(
            array(
               'file_path' => 'nothing',
               'status' => '3',
               'parameters' => ''
            )
         ),
         'MeasurementParameter' => array(
            $stripErrorLimitsParameterId,
            array( #associate the strip errors measurement generated with the strip measurement where the data came from
               'parameter_id'=>123,
               'value' => $measurementId,
            )
         ),
      );
      foreach($data["MeasurementTag"] as $id=>$values){
         unset($data["MeasurementTag"][$id]["MeasurementTagsMeasurement"]);
         unset($data["MeasurementTag"][$id]["name"]);
      }
      #copy tags from strip measurement to strip error measurement
      foreach($data["MeasurementTag"] as $tag){
         $newMM['MeasurementTag']['MeasurementTag'][] = $tag["id"];
      }

      $parameterIds = array();
      foreach(array_keys($this->parameters) as $parameter){
         //check if parameter exists and if not add
         $parameterRequestResult = $this->Measurement->MeasurementParameter->Parameter->find("first",array("conditions"=>array("name"=>$parameter),"contain"=>array()));
         if(count($parameterRequestResult)==0){
            $parameterId = $this->Measurement->MeasurementParameter->Parameter->save(array("Parameter"=>array("name"=>$parameter)));
            $parameterRequestResult = $this->Measurement->MeasurementParameter->Parameter->find("first",array("conditions"=>array("name"=>$parameter),"contain"=>array()));
         }
         $parameterIds[$parameter] = $parameterRequestResult["Parameter"]["id"];

      }

      $parameterRequestResult = $this->Measurement->MeasurementParameter->Parameter->find("first",array("conditions"=>array("name"=>"Strip"),"contain"=>array()));

      $stripParameterId = $parameterRequestResult["Parameter"]["id"];
      #Add calculated data to array for insertion as measurement
      $newMeasurementMeasuringPoint = array();
      foreach($this->parameters as $name=>$values){
         $tmp = array();
         foreach($values as $value){
            $tmp = array(array(
                  'parameter_id' => $stripParameterId, #strip parameter id
                  'value'=>$value #strip number
               ),
               array(
                  'parameter_id' => $parameterIds[$name], #error parameter id
                  'value'=>1 #1 because it requires a value
               ));
            $newMeasurementMeasuringPoint[]["Reading"] = $tmp;
         }
      }
      $newMM["MeasuringPoint"] = $newMeasurementMeasuringPoint;

      // echo "<pre>";
      // print_r($newMM);
      // echo "</pre>";
      return $this->Measurement->saveAll($newMM, array('deep' => true));
#     debug($this->Measurement->validationErrors);
      #create the measurement array
   }

   private function _strips($measurementReadings,$newLimits=null)
   {
      $strips = array();
      $parameters = array();
      $error = "%s %s %s";
      #Need to be calculateable according to parameters provided at: http://www.hephy.at/wiki/index.php/Belle:HPK_Sensor_Failure
      #Results are then used as standard limits, the person grading may change the used values according to their choosing.
      // $standardLimits = array(
               // "P_Coupling_short" => array("field"=>"I_diel [A]","lValue"=>90e-9),
               // "P_Implant_short" => array("field"=>"I_strip [A]","lValue"=>1.8*$median["I_strip [A]"]),
               // "P_Implant_open" => array("field"=>"I_strip [A]","uValue"=>$median["I_strip [A]"]*10),
               // "P_PolySi_short" => array("field"=>"R_poly [Ohm]","uValue"=>1e5),
               // "P_PolySi_open" => array("field"=>"R_poly [Ohm]","lValue"=>1e8),
               // "P_Leaky_strip" => array("field"=>"I_strip [A]","lValue"=>20e-9),
               // "N_Coupling_short" => array("field"=>"I_diel [A]","lValue"=>90e-9),
            // );
      $availableParameters = array();
      //Array of parameters to not make available for parameterization.
      $skipParameters = array("Line","Pad","Temp [C]","Humidity","humidity  [%]","temperature [C]");
      //calculate median of all 6 parameters
      if($newLimits === null){
         foreach($measurementReadings as $r){
            if(!in_array($r["Parameter"]["name"], $availableParameters) && !in_array($r["Parameter"]["name"], $skipParameters)){
               $availableParameters[] = $r["Parameter"]["name"];
            }
         }
      }else{
         foreach($newLimits as $name=>$values){
            $availableParameters[] = $name;
         }
      }
      $median = array();
      foreach($measurementReadings as $reading){
         $parameter = $reading["Parameter"]["name"];
         $median[$parameter][] = $reading["Reading"]["value"];
      }
      foreach($median as $parameter=>$data){
         rsort($data);
         $middle = round(count($data) / 2);
         $median[$parameter] = $data[$middle-1];
      }
      #set the standard limits after the value of the median (50% over and below)
      foreach($availableParameters as $parameter){
         $standardLimits[$parameter] = ($median[$parameter]<0)? array("uValue"=>$median[$parameter]*0.5,"lValue"=>$median[$parameter]*1.5,"median"=>$median[$parameter]) : array("lValue"=>$median[$parameter]*0.5,"uValue"=>$median[$parameter]*1.5,"median"=>$median[$parameter]);
      }

      #If there were new limits passed as a argument replace the values
      if($newLimits != null){
         foreach($newLimits as $name=>$limits){
            foreach($limits as $type=>$value){
               $standardLimits[$name][$type] = $value;
            }
         }
      }

      #create a nice array layout of the strips and parameters
      foreach($measurementReadings as $reading){
         if($reading["Parameter"]["name"]=="Pad"){
            $strip = $reading["Reading"]["value"];
            $measuring_point_id = $reading["Reading"]["measuring_point_id"];
            continue;
         }
         $parameter_name = $reading["Parameter"]["name"];
         if(false){ #TODO: Question the decision to take absolute value $parameter_name == "I_diel [A]"
            $value = abs($reading["Reading"]["value"]);
         }else{
            $value = $reading["Reading"]["value"];
         }
         if(!isset($standardLimits[$parameter_name])) continue; //Skip this parameter if it is not part of the limits
         $standardLimits[$parameter_name]["id"] = $reading["Reading"]["parameter_id"];
         if($measuring_point_id = $reading["Reading"]["measuring_point_id"] && isset($standardLimits[$parameter_name]["uValue"])){
            if($value > $standardLimits[$parameter_name]["uValue"]){ #additional condition greater than taken from the $standardLimits array
               $strips[$strip][] = sprintf($error,$parameter_name,">",$this->_nEF($standardLimits[$parameter_name]["uValue"]));
               $parameters[sprintf($error,$parameter_name,">",$this->_nEF($standardLimits[$parameter_name]["uValue"]))][] = $strip;
            }elseif($value < $standardLimits[$parameter_name]["lValue"]){ #additional condition lower than taken from the $standardLimits array
               $strips[$strip][] = sprintf($error,$parameter_name,"<",$this->_nEF($standardLimits[$parameter_name]["lValue"]));
               $parameters[sprintf($error,$parameter_name,"<",$this->_nEF($standardLimits[$parameter_name]["lValue"]))][] = $strip;
            }
         }
      }
      foreach($parameters as $name=>$values){
         sort($parameters[$name]);
      }
      ksort($strips);
      $this->parameters = $parameters;
      $this->strips = $strips;
      $this->set(compact("standardLimits","strips","parameters"));
   }
   /**
   * Shortcut for _niceExponentialFormat function
   */
   private function _nEF($value){
      return $this->_niceExponentialFormat($value);
   }

   private function _niceExponentialFormat($value){
      if($value == 0) return 0;
      $exponent = floor(log10(abs($value))); #absolute value to make sure that negative values are also correctly interpreted by the log10 function
      $quotient = floor($exponent/3);  #get the quotient of a division by three since we want to group the values by a three
      if($quotient>=0) #add an additional + for the exponent in case of it beeing positive
         $factor = "e+".(3*$quotient);
      else
         $factor = "e".(3*$quotient);
      $preValue = $value/pow(10,3*$quotient);
      if((round($preValue,1)-floor($preValue))!=0)
         $formatString = "%1.1f%s"; #one number after the point if there is a number after the point
      else
         $formatString = "%1.0f%s"; #no number after the point if there is none because it would be zero
      return sprintf($formatString,$preValue,$factor);
   }
}
