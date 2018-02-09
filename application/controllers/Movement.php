<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Movement extends CI_Controller {
    const rkdBaseUri = 'https://rkd.nl/nl/explore/artists/%s';

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

        $result = (int)($rkdIdData['results']['bindings'][0]['rkdId']['value']);


        return array('queries' => (array)$queryRKDId, 'data' =>$result);
    }

	public function index($movementCode, $movementName)
	{
	    $data = array(  'code' => $movementCode,
                        'name' => $movementName);


		$this->load->view('movement', $data);
	}

    public function artist($artistCode, $artistName)
    {
        $data = array(  'code' => $artistCode,
                        'name' => $artistName);

        $rkdCode = $this->getRKDId($artistCode)['data'];
        $data['rkdId'] = $rkdCode;
        $data['rkdUri'] = sprintf(self::rkdBaseUri, $rkdCode);

        $this->load->view('artist', $data);
    }
}
