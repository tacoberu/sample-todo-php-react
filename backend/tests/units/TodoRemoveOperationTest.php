<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Todo;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PDO, PDOStatement;


final class TodoRemoveOperationTest extends TestCase
{

	private PDO|MockObject $pdo;

	private TodoRemoveOperation $operation;



	protected function setUp(): void
	{
		$this->pdo = $this->createMock(PDO::class);
		$this->operation = new TodoRemoveOperation($this->pdo);
	}



	#[DataProvider('dataIds')]
	function testDoPersistRemove(int $id)
	{
		$stm = $this->createMock(PDOStatement::class);

		$this->pdo->expects($this->once())
			->method('prepare')
			->with('DELETE
			FROM todo
			WHERE id = :id
			LIMIT 1', [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY])
			->willReturn($stm);

		$stm->expects($this->once())
			->method('execute')
			->with([
				'id' => $id,
			]);

		$this->operation->doPersistRemove($id);
	}



	static function dataIds()
	{
		return [[5], [42]];
	}

}
