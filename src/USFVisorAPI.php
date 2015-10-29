<?php

/**
 * Copyright 2015 University of South Florida
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace USF\IdM;

use \JSend\JSendResponse;
use \epierce\CasRestClient;
use \GuzzleHttp\Exception\ClientException;
/**
 * Description of USFVisorAPI
 *
 * @author james
 */
class USFVisorAPI {
    public $config;
    public $proxyEmplid;
    public $client;
    public function __construct($config = [],$proxyEmplid = null) {
        $this->config = $config;
        $this->proxyEmplid = $proxyEmplid;
        $this->client = new CasRestClient();
        $this->client->setCasServer($this->config['casurl']);
        $this->client->setCasRestContext('/v1/tickets');
        $this->client->setCredentials($this->config['username'], $this->config['password']);
        $this->client->login('/tmp/cas_tgt.json');
    }
    /**
     * Runs the Visor Client initialized config
     * 
     * @param string $id
     * @return JSendResponse
     */
    public function getVisor($id) {
        $response;
        try {
            if(!isset($this->proxyEmplid)) {
                $response = $this->client->get($this->config['url'] . $id);
            } else {
                // force proxy authorization for all others
                $response = $this->client->get($this->config['url'] . $id, ['headers' => ['PROXY_USER_EMPLID' => $this->proxyEmplid]]);
            }                    
        } catch (ClientException $ex) {            
//            echo $ex->getRequest();
//            echo $ex->getResponse();
            return new \JSend\JSendResponse('fail', [
                "description" => $ex->getMessage(),
                "status" => $ex->getResponse()->getStatusCode(),
                "statusText" => $ex->getResponse()->getReasonPhrase()
            ]);
        }
        try {
            return \JSend\JSendResponse::decode($response->getBody());
        } catch (\JSend\InvalidJSendException $e) {
            return new \JSend\JSendResponse('fail', [
                "description" => $response->getBody(),
                "status" => $response->getStatusCode(),
                "statusText" => $response->getReasonPhrase()
            ]);
        }
    }

}
