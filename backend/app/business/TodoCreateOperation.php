<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Todo;

use RuntimeException;
use LogicException;
use PDO;


class TodoCreateOperation
{

	private PDO $conn;


	function __construct(PDO $conn)
	{
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->conn = $conn;
	}



	function doPersistNew(Todo $entity): int
	{
		$sql = "INSERT INTO todo (title, description, status) VALUES (:title, :description, :status)";
		$sth = $this->conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
		$entity = $entity->toObject();
		$sth->execute([
			'title' => $entity->title,
			'description' => $entity->description,
			'status' => $entity->status,
		]);
		return (int) $this->conn->lastInsertId();
	}

}
