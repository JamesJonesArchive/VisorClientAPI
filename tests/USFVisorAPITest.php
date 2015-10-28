<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace USF\IdM;

use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use \JSend\JSendResponse;
/**
 * Description of USFVisorAPITest
 *
 * @author james
 */
class USFVisorAPITest extends \PHPUnit_Framework_TestCase {
    private $_usfVisorAPI;
    public function setUp() {
        $this->_usfVisorAPI = new \USF\IdM\USFVisorAPI([
            'casurl' => $_SERVER['VISOR_CASURL'],
            'username' => $_SERVER['VISOR_USERNAME'],
            'password' => $_SERVER['VISOR_PASSWORD'],
            'url' => $_SERVER['VISOR_URL']
        ]);
        parent::setUp();
    }
    /**
     * @covers \USF\IdM\USFVisorAPI::getVisor
     */
    public function testGetVisor() {
        $mock = new Mock([
            new Response(201, ['Location' => $_SERVER['VISOR_CASURL'].'/cas/v1/tickets/TGT-1-1qaz2wsx3ecd']).
            new Response(201,[],Stream::factory('ST-1-abc123')),
            new Response(200,[],Stream::factory((new \JSend\JSendResponse("success",[
                'employee_id' => '123456789',
                'employees' => [],
                'supervisors' => []
            ]))->encode()))
        ]);
        $this->_usfVisorAPI->client->getGuzzleClient()->getEmitter()->attach($mock);
        $response = $this->_usfVisorAPI->getVisor("someidentifier");
        $this->assertCount(0, $response->getData()['employees']);
        $this->assertCount(0, $response->getData()['supervisors']);
    }
}
