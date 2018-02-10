<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Movement extends CI_Controller {
    const rkdBaseUri = 'https://rkd.nl/nl/explore/artists/%s';
    const rkdApiUri = 'https://api.rkd.nl/api/record/artists/%s?format=json';
    const rkdImagesUri = 'https://api.rkd.nl/api/search/images?filters[kunstenaar]=%s&format=json';
    const wikimediaQuery = 'https://www.wikidata.org/w/api.php?action=wbgetentities&format=xml&props=sitelinks&ids=%s&sitefilter=%swiki';
    const wikiPage = 'https://%s.wikipedia.org/wiki/%s';

    /**
     * @param string $query Query to run against Wikidata
     */
    private function queryWikiData($query){


        $endpointUrl = 'https://query.wikidata.org/sparql';

        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL,  $endpointUrl . '?query=' . urlencode( $query ) );

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/sparql-results+json'
        ));

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        return json_decode($output, true);

    }

    private function getRKDId($artistId){
        $queryRKDId = <<<QUERY_RKD
SELECT *  WHERE {
    wd:%s wdt:P650 ?rkdId
}
QUERY_RKD;
        $queryRKDId = sprintf($queryRKDId, $artistId);

        $rkdIdData = $this->queryWikiData($queryRKDId);

        $result = 0;
        if(isset($rkdIdData['results']['bindings'][0]['rkdId']['value'])){
            $result = (int)($rkdIdData['results']['bindings'][0]['rkdId']['value']);
        }



        return array('queries' => (array)$queryRKDId, 'data' =>$result);
    }


    private function getRKDData($rkdCode){

        if($rkdCode === 0){
            return array();
        }

        $apiUrl = sprintf(self::rkdApiUri, $rkdCode);

        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $apiUrl );

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        $returnData = json_decode($output, true);

        $artistData = $returnData['response']['docs'][0];
        return $artistData;
        //return array('queries' => (array)$queryRKDId, 'data' =>$artistData);
    }

    private function getRKDImages($rkdName){

        if($rkdName === ''){
            return array();
        }

        //Unfortunately, there is no search for images on artist priref in RKD api
        $apiUrl = sprintf(self::rkdImagesUri, urlencode($rkdName));

        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $apiUrl );

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        $returnData = json_decode($output, true);

        $imageData = $returnData['response']['docs'];


        $allImages = array();
        foreach ($imageData as $image){
            if(strpos($image['image_url'][0],  '0d963e21-f176-d222-e878-6b5871220083') === false) {
                $data = array(
                    'rkdid' => $image['priref'],
                    'url' => $image['image_url'][0],
                    'name' => $image['benaming_kunstwerk'][0],
                );
                $allImages[] = $data;
            }
        }

        return $allImages;
    }

    private  function getWikipediaPage($id, $langCode){

        $query = sprintf(self::wikimediaQuery, $id, $langCode);

        try {
            // create curl resource
            $ch = curl_init();

            // set url
            curl_setopt($ch, CURLOPT_URL, $query);

            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            // $output contains the output string
            $output = curl_exec($ch);

            $xml = simplexml_load_string($output);

            $name = (string)(@$xml->entities[0]->entity[0]->sitelinks[0]->sitelink->attributes()->title);
            $page = sprintf(self::wikiPage, $langCode, $name);
            return $page;
        }
        catch (\Exception $e){
            //Let's just assume the page isn't there today
            return '';

        }

    }



    private function extractCodeFromFilename($filename){

        $strOut = '';
        if(preg_match('#\\\\(\w+).jpg$#', $filename, $capture)){
            $strOut = $capture[1];
        }
        return $strOut;
    }

    private function extractImages($rkdData){
        $dataOut = array();
        if (isset($rkdData['kunstwerk'])) {
            foreach ($rkdData['kunstwerk'] as $artwork){
                $rkdUid = false;
                $rkdImageId = $artwork['afbeeldingsnummer'][0]['priref'];

                //Try to match the image to a Picturae ID
                $standplaats = isset($artwork['afbeeldingsnummer'][0]['standplaats']) ? $artwork['afbeeldingsnummer'][0]['standplaats'] : array();
                if(count($standplaats)){
                    foreach($standplaats as $pic){
                        $filename = isset($pic['afbeeldingsnummer'][0]['afbeelding_path']) ? $pic['afbeeldingsnummer'][0]['afbeelding_path'] : null;
                        if($filename){
                            $imageCode = $this->extractCodeFromFilename($filename);
                            if($imageCode){
                                if(isset($rkdData['afbeeldingsnummer_rkd_picturae_mapping'][$imageCode])){
                                    $rkdUid = $rkdData['afbeeldingsnummer_rkd_picturae_mapping'][$imageCode];
                                    print "$rkdUid<br>";
                                    break;
                                }
                            }
                        }
                        print "$imageCode<br>";

                    }

                }
                $rkdImageId = $artwork['afbeeldingsnummer'][0]['priref'];

                print("$rkdImageId<br/>");


                //afbeeldingsnummer_rkd_picturae_mapping
            }
        }

    }

	public function index($movementCode, $movementName)
	{

	    $wikiEn = $this->getWikipediaPage($movementCode, 'en');
        $wikiNl = $this->getWikipediaPage($movementCode, 'nl');

        $data = array(  'code' => $movementCode,
                        'name' => $movementName,
                        'wikiEn' => $wikiEn,
                        'wikiNl' => $wikiNl,
        );
		$this->load->view('movement', $data);
	}

    public function artist($artistCode)
    {

        $wikiEn = $this->getWikipediaPage($artistCode, 'en');
        $wikiNl = $this->getWikipediaPage($artistCode, 'nl');

        $data = array(  'code' => $artistCode,
            'wikiEn' => $wikiEn,
            'wikiNl' => $wikiNl,
        );

        $rkdCode = $this->getRKDId($artistCode)['data'];
        $data['rkdId'] = $rkdCode;

        $rkdData = $this->getRKDData($rkdCode);

        $data['name'] = isset($rkdData['kunstenaarsnaam']) ? $rkdData['kunstenaarsnaam'] : '';
        $data['rkdData'] = $rkdData;

        $imageData = $this->getRKDImages($data['name']);
        $data['rkdImageData'] = $imageData;

        $data['rkdUri'] = sprintf(self::rkdBaseUri, $rkdCode);

        $this->load->view('artist', $data);
    }
}
