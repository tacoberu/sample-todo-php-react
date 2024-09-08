<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Todo;

use RuntimeException;
use LogicException;
use PDO;


class TodoQuery
{

	private PDO $conn;


	function __construct(PDO $conn)
	{
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->conn = $conn;
	}



	function getById(int $id): ?Todo
	{
		$sql = 'SELECT * FROM todo WHERE id = :id LIMIT 1';
		$sth = $this->conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
		$sth->execute(['id' => $id]);
		if ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			return self::buildEntity($row);
		}
		return Null;
	}



	/**
	 * @return array<Todo>
	 */
	function findAll(): array
	{
		$sql = 'SELECT * FROM todo LIMIT 999';
		$sth = $this->conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
		$sth->execute([]);
		return array_map(fn($x) => self::buildEntity($x), $sth->fetchAll(PDO::FETCH_ASSOC));
	}



	/**
	 * @param array{id: int, title: string, description: string, status: string} $src
	 */
	private static function buildEntity(array $src): Todo
	{
		return new Todo($src['id']
			, $src['title']
			, $src['description']
			, $src['status']);
	}

}
