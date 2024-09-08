<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Todo;

use PHPUnit\Framework\TestCase;
use PDO, PDOStatement;


final class TodoCreateOperationTest extends TestCase
{

	private PDO|MockObject $pdo;

	private TodoCreateOperation $operation;



	protected function setUp(): void
	{
		$this->pdo = $this->createMock(PDO::class);
		$this->operation = new TodoCreateOperation($this->pdo);
	}



	function testDoPersistNew()
	{
		$stm = $this->createMock(PDOStatement::class);

		$this->pdo->expects($this->once())
			->method('prepare')
			->with('INSERT INTO todo (title, description, status) VALUES (:title, :description, :status)', [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY])
			->willReturn($stm);

		$stm->expects($this->once())
			->method('execute')
			->with([
				'title' => 'Test Todo',
				'description' => 'This is a test description',
				'status' => 'pending',
			]);

		$this->pdo->expects($this->once())
			->method('lastInsertId')
			->willReturn("42");

		$id = $this->operation->doPersistNew(new Todo(
			1,
			'Test Todo',
			'This is a test description',
			'pending'
		));
		$this->assertEquals(42, $id);
	}

}
