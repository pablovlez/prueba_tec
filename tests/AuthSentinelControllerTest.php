<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Http\Controllers\AuthSentinel;

class AuthSentinelControllerTest extends TestCase
{
	use WithoutMiddleware;

	protected $baseUrl = 'http://institucion1.desarrollo-sia.com.co';

	public function configurarDB()
    {
		\Config::set('database.connections.pgsql.database', 'sia_institucion1');
        \Config::set('database.connections.pgsql.username', 'postgres');
        \Config::set('database.connections.pgsql.password', 'jdpp');
        \Config::set('database.default', 'pgsql');
    }

    public function testPostLoginError()
    {
    	$this->configurarDB();

    	$response = $this->post('/v1/auth/login')
    			    ->seeJson([ 'msg' => 'Error Login' ]);
    }

    public function testPostLoginError2()
    {
    	$this->configurarDB();

    	$response = $this->call('POST', '/v1/auth/login', array('email' => 'test@test.com', 'password' => 'password'));
    	$this->assertEquals( json_encode(['msg' => 'No se puede Autenticar']), json_encode($response->getData()));
    }

	public function testPostLogin()
    {
    	$this->configurarDB();

    	$response = $this->call('POST', '/v1/auth/login', array('email' => 'john.doe@example.c', 'password' => 'password'));
    	$this->assertEquals( 200, $response->status());
   }

    public function testLogout()
    {
    	$response = $this->get('/v1/auth/logout')
    			    ->seeJsonEquals([ 'msg' => 'Logout' ]);
    } 

	public function testAuthuserError()
    {
    	$response = $this->get('/v1/auth/authuser')
    			    ->seeJson([ 'msg' => 'No hay usuario' ]);
    }

    public function testAuthuser()
    {
    	$this->testPostLogin();
    	$response = $this->get('/v1/auth/authuser')
    			    ->seeJson([ 'msg' => 'usuario' ]);
    }




/*
    public function testCreateUserWithCredentials()
    {
        $this->assertTrue(false);
    }
    */

}
