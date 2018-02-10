<?php
/**
 * Created by PhpStorm.
 * User: bhillier
 * Date: 09/02/2018
 * Time: 19:34
 */


defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {


    private function  extractWikiEntityId($wikiEntity){
        $strOut = '';
        if(preg_match('#http://www.wikidata.org/entity/(\w+)$#', $wikiEntity, $capture)){
            $strOut = $capture[1];
        }
        return $strOut;
    }
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


    /*
     * Gets all art movements.
     * We use this when there is no artist because the query will be faster
     */
    private function getAllMovements(){
        $query = <<<QUERY_ALL_MOVEMENTS
SELECT *   WHERE {
    ?s wdt:P135 ?movement.    #Subject in movement
    ?s wdt:P106 wd:Q1028181.  #Subject is painter
    ?s wdt:P31 wd:Q5.         #Subject is person
    ?s rdfs:label ?person.
    filter (lang(?person) = "nl").
    ?movement wdt:P31 ?movementCategory  .   #Instance of movement
    ?movement rdfs:label ?label.
    filter (lang(?label) = "nl").
}
VALUES (?movementCategory) { (wd:Q3326867) (wd:Q968159) }  #Instance of movement or painting movement

QUERY_ALL_MOVEMENTS;

        $allMovements = $this->queryWikiData($query);


        $resultData = $allMovements['results']['bindings'];

        $dataOut = array();


        foreach ($resultData as $row){
            $label = $row['label']['value'];
            $movement = $this->extractWikiEntityId($row['movement']['value']);
            if(!isset($dataOut[$label])){
                $dataOut[$label] = array(   'text' => $label,
                                            'size' => 0,
                                            'href' => sprintf('/movement/index/%s/%s', $movement, $label),
                                            );

            }
            if($dataOut[$label]['size'] < 60) {
                $dataOut[$label]['size']++;
            }
        }

        return array('queries' => (array)$query, 'data' =>$dataOut);
    }

    /*
     * Gets all art movements.
     * We use this when there is no artist because the query will be faster
     */
    private function getMatchingArtists($searchTerm){
        $query = <<<QUERY_ALL_MOVEMENTS
SELECT *  WHERE {
{
    ?artist wdt:P106 wd:Q1028181.  #Subject is painter
    ?artist wdt:P31 wd:Q5.         #Subject is person
    ?artist rdfs:label ?artistName.  #Subject label
    filter (lang(?artistName) = "nl") #Label is Dutch
    FILTER(LCASE(STR(?artistName)) = '%1\$s')
}
  UNION
  {
        ?artist wdt:P106 wd:Q1028181.  #Subject is painter
        ?artist wdt:P31 wd:Q5.         #Subject is person
        ?artist rdfs:label ?artistName.  #Subject label
        ?artist skos:altLabel ?name
        filter (lang(?artistName) = "nl") #Label is Dutch
        FILTER(LCASE(STR(?name)) = '%1\$s')

   }
}

QUERY_ALL_MOVEMENTS;
        $query = sprintf($query, strtolower($searchTerm) );

        $matchingArtists = $this->queryWikiData($query);
        $resultData = $matchingArtists['results']['bindings'];
        $allMatchingArtists = array();

        foreach ($resultData as $row){
            $code = $this->extractWikiEntityId($row['artist']['value']);
            $data = array(
                'code' => $code,
                'name' => $row['artistName']['value'],
                'url' => sprintf('/movement/artist/%s', $code),
            );
            $allMatchingArtists[$code]  = $data;
        }


        return array('artists' => $allMatchingArtists);
        $dataOut = array();


        foreach ($resultData as $row){
            $label = $row['label']['value'];
            $movement = $this->extractWikiEntityId($row['movement']['value']);
            if(!isset($dataOut[$label])){
                $dataOut[$label] = array(   'text' => $label,
                    'size' => 0,
                    'href' => sprintf('/movement/index/%s/%s', $movement, $label),
                );

            }
            if($dataOut[$label]['size'] < 60) {
                $dataOut[$label]['size']++;
            }
        }

        return array('queries' => (array)$query, 'data' =>$dataOut);
    }

    private function getMovementsWithArtists($aArtists){
        $query = <<<QUERY_MOVEMENTS_WITH_ARTIST
SELECT * WHERE{
  ?artist wdt:P106 wd:Q1028181.  #is painter
  ?artist wdt:P135 ?movement.     #movement
  ?movement rdfs:label ?label
     filter (lang(?label) = "nl")    
}
LIMIT 1000
VALUES (?artist){ %s }
QUERY_MOVEMENTS_WITH_ARTIST;

        $searchCodes = array();
        foreach ($aArtists['artists'] as $key => $val) {
            $searchCodes[] = sprintf("( wd:%s ) ", $key);
        }

        $query = sprintf($query, join(' ', $searchCodes) );

        $matchingMovements = $this->queryWikiData($query);
        $resultData = $matchingMovements['results']['bindings'];


        $dataOut = array();

        foreach ($resultData as $row){
            $label = $row['label']['value'];
            $movement = $this->extractWikiEntityId($row['movement']['value']);
            if(!isset($dataOut[$label])){
                $dataOut[$label] = array(   'text' => $label,
                    'size' => 0,
                    'href' => sprintf('/movement/index/%s/%s', $movement, $label),
                );

            }
            if($dataOut[$label]['size'] < 60) {
                $dataOut[$label]['size']++;
            }
        }
        return array($dataOut);
    }


    private function  getArtistsInMovement($movement){

        $queryArtists =<<<QUERY_MOVEMENT
SELECT *  WHERE {
    ?artist wdt:P135 wd:%s .  #Subject part of movement
    ?artist rdfs:label ?artistName.  #Subject label
    ?artist wdt:P31 wd:Q5        #Subject is person
    filter (lang(?artistName) = "nl") #Label is Dutch
}
QUERY_MOVEMENT;

        $queryArtists = sprintf($queryArtists, $movement);
        $allArtists = $this->queryWikiData($queryArtists);
        $resultData = $allArtists['results']['bindings'];

        $dataOut = array();


        foreach ($resultData as $row){
            $label = $row['artistName']['value'];
            $code = $this->extractWikiEntityId($row['artist']['value']);
            if(!isset($dataOut[$label])){
                $dataOut[$label] = array(   'text' => $label,
                    'size' => 10,
                    'href' => sprintf('/movement/artist/%s', $code),
                );

            }
            if($dataOut[$label]['size'] < 20) {
                $dataOut[$label]['size']++;
            }
        }

        return array('queries' => (array)$queryArtists, 'data' =>$dataOut);

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
        highlight_string("<?php\n\$marker =\n" . var_export($result, true) . ";\n?>");  //FIND_ME_AGAIN


        return array('queries' => (array)$queryRKDId, 'data' =>array_values($result));
    }


	//Gets all movements for this artist
	public function kunststromingSearch($artist = '')
	{
	    $returnData = $this->getAllMovements();
	    print json_encode($returnData);
	    return;
	}

    //Gets all movements for this artist
    public function kunststroming($artist = '')
    {
        header('Content-Type: application/json');
        if($artist){
            $matchingArtists = $this->getMatchingArtists($artist);
            $matchingMovements = $this->getMovementsWithArtists($matchingArtists);

            print json_encode(
                array ('artists' => array_values( $matchingArtists),
                        'movements' => array_values($matchingMovements[0]),
                    ),
                    true
                );
        }
        else {
            $returnData = $this->getAllMovements();
            print json_encode(array_values($returnData['data']));
        }
           //add the header here

        //print json_encode($returnData);
        /*
        print "var words = ";
        print json_encode(array_values($returnData['data']));
        print ";";
        */
        return;
    }

    //Gets all movements for this artist
    public function kunstenaars($movement = '')
    {
        $returnData = $this->getArtistsInMovement($movement);
        header('Content-Type: application/json');
        print "var words = ";
        print json_encode(array_values($returnData['data']));
        print ";";
        return;
    }


    //Gets all movements for this artist
    public function kunstenaar($artistId = '')
    {
        $rkdId = $this->getRKDId($artistId);


        header('Content-Type: application/json');
        print json_encode($rkdId);
        return;
    }
}
