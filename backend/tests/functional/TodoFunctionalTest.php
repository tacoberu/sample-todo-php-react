<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Todo;


require_once __dir__ . '/FunctionalUse.php';


use PHPUnit\Framework\TestCase;
use GuzzleHttp\Exception\ClientException;


class TodoFunctionalTest extends TestCase
{

	use FunctionalUse;


	function testFindAll()
	{
		$response = $this->fetchGet('/api/todo');
		$this->assertStatusCode(200, $response);
		$this->assertContent([
			(object) [
				'id' => 1,
				'title' => 'une',
				'description' => 'lorem ipsum doler ist',
				'status' => 'pending',
			],
		], $response);
	}



	function testGetById()
	{
		$response = $this->fetchGet('/api/todo/1');
		$this->assertStatusCode(200, $response);
		$this->assertContent((object) [
			'id' => 1,
			'title' => 'une',
			'description' => 'lorem ipsum doler ist',
			'status' => 'pending',
		], $response);
	}



	function testGetByIdUnknown()
	{
		$response = $this->fetchGet('/api/todo/999999');
		$this->assertStatusCode(404, $response);
	}



	function testPush()
	{
		$response = $this->fetchPush('/api/todo', (object)[
			'title' => 'deux',
			'description' => 'lorem ipsum doler ist',
			'status' => 'pending',
		]);
		$this->assertStatusCode(201, $response);
		$this->assertContent((object) [
			'id' => 7,
			'title' => 'deux',
			'description' => 'lorem ipsum doler ist',
			'status' => 'pending',
		], $response);

		$response = $this->fetchGet('/api/todo');
		$this->assertStatusCode(200, $response);
		$this->assertContent([
			(object) [
				'id' => 1,
				'title' => 'une',
				'description' => 'lorem ipsum doler ist',
				'status' => 'pending',
			],
			(object) [
				'id' => 7,
				'title' => 'deux',
				'description' => 'lorem ipsum doler ist',
				'status' => 'pending',
			],
			], $response);
	}



	function testPushIllegal()
	{
		$response = $this->fetchPush('/api/todo', (object)[
			'description' => 'lorem ipsum doler ist',
			'status' => 'pending',
		]);
		$this->assertStatusCode(400, $response);
		$this->assertContent("Missing item 'title' in object.", $response);
	}



	function testPut()
	{
		$response = $this->fetchPut('/api/todo/1', (object)[
			'title' => 'eins',
			'description' => 'b',
			'status' => 'completed',
		]);
		$this->assertStatusCode(202, $response);
		$this->assertContent((object) [
			'id' => 1,
			'title' => 'eins',
			'description' => 'b',
			'status' => 'completed',
		], $response);

		$response = $this->fetchGet('/api/todo');
		$this->assertStatusCode(200, $response);
		$this->assertContent([(object) [
			'id' => 1,
			'title' => 'eins',
			'description' => 'b',
			'status' => 'completed',
		]], $response);
	}



	function testPutNotFound()
	{
		$response = $this->fetchPut('/api/todo/99999', (object)[
			'title' => 'eins',
			'description' => 'b',
			'status' => 'x',
		]);
		$this->assertStatusCode(400, $response);
		$this->assertContent("Record is not found.", $response);
	}



	function testPutIllegal()
	{
		$response = $this->fetchPut('/api/todo/1', (object)[
			'title' => 'eins',
			'description' => 'b',
			'status' => 'x',
		]);
		$this->assertStatusCode(400, $response);
		$this->assertContent("The 'status' must be of ['pending', 'completed']; 'x' given.", $response);
	}



	function testDelete()
	{
		$response = $this->fetchDelete('/api/todo/1');
		$this->assertStatusCode(204, $response);

		$response = $this->fetchGet('/api/todo');
		$this->assertStatusCode(200, $response);
		$this->assertContent([], $response);
	}



	function testDeleteUnknown()
	{
		$response = $this->fetchDelete('/api/todo/9999');
		$this->assertStatusCode(204, $response);

		$response = $this->fetchGet('/api/todo');
		$this->assertStatusCode(200, $response);
		$this->assertContent([(object) [
			'id' => 1,
			'title' => 'une',
			'description' => 'lorem ipsum doler ist',
			'status' => 'pending',
		]], $response);
	}

}
