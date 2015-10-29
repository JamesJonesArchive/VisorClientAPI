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
