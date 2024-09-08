<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Todo;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PDO, PDOStatement;


final class TodoQueryTest extends TestCase
{

	private PDO|MockObject $pdo;

	private TodoQuery $query;



	protected function setUp(): void
	{
		$this->pdo = $this->createMock(PDO::class);
		$this->query = new TodoQuery($this->pdo);
	}



	#[DataProvider('dataIds')]
	function testGetById(int $id)
	{
		$stm = $this->createMock(PDOStatement::class);

		$this->pdo->expects($this->once())
			->method('prepare')
			->with('SELECT * FROM todo WHERE id = :id LIMIT 1', [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY])
			->willReturn($stm);

		$stm->expects($this->once())
			->method('execute')
			->with(['id' => $id]);

		$stm->expects($this->once())
			->method('fetch')
			->with(PDO::FETCH_ASSOC)
			->willReturn([
				'id' => $id,
				'title' => 'Test Todo',
				'description' => 'This is a test todo',
				'status' => 'pending'
			]);

		$entity = $this->query->getById($id);
		$this->assertInstanceOf(Todo::class, $entity);
		$this->assertEquals((object) [
			'id' => $id,
			'title' => 'Test Todo',
			'description' => 'This is a test todo',
			'status' => 'pending',
		], $entity->toObject());
	}



	function testGetByIdNotFound()
	{
		$id = 9999;

		$stm = $this->createMock(PDOStatement::class);

		$this->pdo->expects($this->once())
			->method('prepare')
			->with('SELECT * FROM todo WHERE id = :id LIMIT 1', [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY])
			->willReturn($stm);

		$stm->expects($this->once())
			->method('execute')
			->with(['id' => $id]);

		$stm->expects($this->once())
			->method('fetch')
			->with(PDO::FETCH_ASSOC)
			->willReturn(False);

		$this->assertNull($this->query->getById($id));
	}



	static function dataIds()
	{
		return [[5], [42]];
	}



	function testFindAll()
	{
		$stm = $this->createMock(PDOStatement::class);

		$this->pdo->expects($this->once())
			->method('prepare')
			->with('SELECT * FROM todo LIMIT 999', [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY])
			->willReturn($stm);

		$stm->expects($this->once())
			->method('execute')
			->with([]);

		$stm->expects($this->once())
			->method('fetchAll')
			->with(PDO::FETCH_ASSOC)
			->willReturn([
				[
					'id' => 1,
					'title' => 'Test Todo 1',
					'description' => 'This is a test todo 1',
					'status' => Todo::StatusPending
				],
				[
					'id' => 2,
					'title' => 'Test Todo 2',
					'description' => 'This is a test todo 2',
					'status' => Todo::StatusCompleted
				]
			]);

		$xs = $this->query->findAll();
		$this->assertIsArray($xs);
		$this->assertCount(2, $xs);
		$this->assertInstanceOf(Todo::class, $xs[0]);
		$this->assertInstanceOf(Todo::class, $xs[1]);
		$this->assertEquals((object) [
			'id' => 1,
			'title' => 'Test Todo 1',
			'description' => 'This is a test todo 1',
			'status' => Todo::StatusPending,
		], $xs[0]->toObject());
		$this->assertEquals((object) [
			'id' => 2,
			'title' => 'Test Todo 2',
			'description' => 'This is a test todo 2',
			'status' => Todo::StatusCompleted,
		], $xs[1]->toObject());
	}

}
