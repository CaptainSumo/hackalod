<?php
/**
 * Created by PhpStorm.
 * User: bhillier
 * Date: 09/02/2018
 * Time: 19:34
 */


defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {

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
     *
     *
     *
     */
    private function getAllMovements(){
        $query = <<<QUERY_ALL_MOVEMENTS
SELECT * WHERE {
  SELECT *   WHERE {
  ?s wdt:P135 ?movement.    #Subject in movement
  ?s wdt:P106 wd:Q1028181.  #Subject is painter
  ?s wdt:P31 wd:Q5.         #Subject is person
  ?s rdfs:label ?person.
   filter (lang(?person) = "nl").

  {
SELECT ?movement ?label WHERE {
     
   ?movement wdt:P31 wd:Q968159  .   #Instance of movement
   ?movement rdfs:label ?label.
   filter (lang(?label) = "nl").
}
}
 
}
  
  
}

QUERY_ALL_MOVEMENTS;

        $allMovements = $this->queryWikiData($query);

        $resultData = $allMovements['results']['bindings'];

        $dataOut = array();

        foreach ($resultData as $row){
            $movement = $row['label']['value'];
            if(!isset($dataOut[$movement])){
                $dataOut[$movement] = 0;
            }
            $dataOut[$movement]++;
        }

        return array('queries' => (array)$query, 'data' =>$dataOut);

    }


	//Gets all movements for this artist
	public function kunststroming($artist = '')
	{
	    $returnData = $this->getAllMovements();
	    print json_encode($returnData);
	    return;
	}
}
