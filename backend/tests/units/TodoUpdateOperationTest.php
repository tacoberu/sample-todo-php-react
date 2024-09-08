<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Todo;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PDO, PDOStatement;


final class TodoUpdateOperationTest extends TestCase
{

	private PDO|MockObject $pdo;

	private TodoUpdateOperation $operation;



	protected function setUp(): void
	{
		$this->pdo = $this->createMock(PDO::class);
		$this->operation = new TodoUpdateOperation($this->pdo);
	}



	function testDoPersistChanges()
	{
		$changes = [
			'id' => 1,
			'title' => 'Updated Title',
			'description' => 'Updated Description',
			'status' => 'pending',
		];

		$stm = $this->createMock(PDOStatement::class);

		$this->pdo->expects($this->once())
			->method('prepare')
			->with('UPDATE todo
			SET title = :title,
				description = :description,
				status = :status
			WHERE id = :id', [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY])
			->willReturn($stm);

		$stm->expects($this->once())
			->method('execute')
			->with([
				'id' => 1,
				'title' => 'Updated Title',
				'description' => 'Updated Description',
				'status' => 'pending',
			]);

        $entity = new Todo(
            1,
            'Original Title',
            'Original Description',
            'completed'
        );

		$this->operation->doPersistChanges($entity, $changes);

		$this->assertEquals($changes['title'], $entity->toObject()->title);
		$this->assertEquals($changes['description'], $entity->toObject()->description);
		$this->assertEquals($changes['status'], $entity->toObject()->status);
    }

}
