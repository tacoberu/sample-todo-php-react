<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Todo;

use RuntimeException;
use LogicException;
use PDO;


final class TodoRemoveOperation
{

	private PDO $conn;


	function __construct(PDO $conn)
	{
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->conn = $conn;
	}



	function doPersistRemove(int $id): void
	{
		$sql = 'DELETE
			FROM todo
			WHERE id = :id
			LIMIT 1';
		$sth = $this->conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
		$sth->execute([
			'id' => $id,
		]);
	}

}
