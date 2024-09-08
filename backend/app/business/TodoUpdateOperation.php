<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Todo;

use RuntimeException;
use LogicException;
use PDO;


final class TodoUpdateOperation
{

	private PDO $conn;


	function __construct(PDO $conn)
	{
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->conn = $conn;
	}



	/**
	 * @param array{id: int, title: string, description: string, status: string} $changes
	 */
	function doPersistChanges(Todo $entity, array $changes): void
	{
		$entity->fillChanges($changes);

		$sql = "UPDATE todo
			SET title = :title,
				description = :description,
				status = :status
			WHERE id = :id";
		$sth = $this->conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
		$entity = $entity->toObject();
		$sth->execute([
			'id' => $entity->id,
			'title' => $entity->title,
			'description' => $entity->description,
			'status' => $entity->status,
		]);
	}

}
