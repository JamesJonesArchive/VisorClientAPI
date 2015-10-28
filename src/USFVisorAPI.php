<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace USF\IdM;

use \JSend\JSendResponse;
use \epierce\CasRestClient;
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
        if(!isset($this->proxyEmplid)) {
            $response = $this->client->get($this->config['url'] . $id);
        } else {
            // force proxy authorization for all others
            $response = $this->client->get($this->config['url'] . $id, ['headers' => ['PROXY_USER_EMPLID' => $this->proxyEmplid]]);
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
