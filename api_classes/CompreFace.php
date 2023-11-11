<?php

class CompreFace extends API {

	function getInfo() : array
	{
    		return [
        		"icon" => 'https://user-images.githubusercontent.com/3736126/147130206-17234c47-8d40-490f-8d93-57014fa6d87e.png',
        		"site" => 'https://exadel.com/solutions/compreface/',
        		"info" => `CompreFace is an open-source face recognition service that can be run on premises`,
    		];
	}

	function getConfParams() : array
	{
    		return [
        		'ENDPOINT' => 'API Endpoint', 
        		'KEY'=> 'API Key'
    		];
	}

	function generateTags($conf, $params) : array
	{
    		global $logger;
    		$file_path = $this->getFileName($params['imageId']);

         
    		$url ='http://'.$conf["ENDPOINT"].'/api/v1/recognition/recognize?limit=0&det_prob_threshold=0.6&prediction_count=1&face_plugins=landmarks,%20gender,%20age,%20calculator&status=true';

    		$curl = curl_init();

    		curl_setopt_array($curl, array(
        		CURLOPT_URL => $url,
        		CURLOPT_RETURNTRANSFER => true,
        		CURLOPT_ENCODING => '',
        		CURLOPT_MAXREDIRS => 10,
        		CURLOPT_TIMEOUT => 0,
        		CURLOPT_FOLLOWLOCATION => true,
        		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        		CURLOPT_CUSTOMREQUEST => 'POST',
        		CURLOPT_POSTFIELDS => array('file'=> new CURLFILE($file_path)),
        		CURLOPT_HTTPHEADER => array(
            			'Content-Type: multipart/form-data',
            			'x-api-key: '.$conf["KEY"]
        		),
    		));
    
    		if (curl_errno($curl)) 
    		{
        		return [curl_error($curl)];
    		}
    
    		$response = curl_exec($curl);

    		curl_close($curl);

    		$json_response = json_decode($response);
    
    		$tags = [];

    		$json_result = $json_response->result;

    		foreach ($json_result as $result) {
       	 		if($result->subjects[0]->similarity > 0.65) {
            			array_push($tags,$result->subjects[0]->subject);
        			$logger->info("Found " . $result->subjects[0]->subject);
			}
    		}
  
    		return $tags;
	}
}
?>
