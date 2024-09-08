<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Todo;

use RuntimeException;
use LogicException;
use PDO;


final class DIC
{

	/**
	 * @param array<string, string> $parameters
	 */
	function __construct(private array $parameters)
	{
	}



	/**
	 * @return object
	 */
	function getByClassName(string $klass)
	{
		switch ($klass) {
			case PDO::class:
				return new PDO($this->getParameter('databaseDSN'));
			case TodoQuery::class:
				return new TodoQuery($this->getByClassName(PDO::class));
			case TodoCreateOperation::class:
				return new TodoCreateOperation($this->getByClassName(PDO::class));
			case TodoUpdateOperation::class:
				return new TodoUpdateOperation($this->getByClassName(PDO::class));
			case TodoRemoveOperation::class:
				return new TodoRemoveOperation($this->getByClassName(PDO::class));
			case TodoRoutine::class:
				return new TodoRoutine($this->getByClassName(TodoQuery::class)
					, $this->getByClassName(TodoCreateOperation::class)
					, $this->getByClassName(TodoUpdateOperation::class)
					, $this->getByClassName(TodoRemoveOperation::class)
					);
			default:
				throw new LogicException("Unsupported class: '{$klass}'.");
		}
	}



	private function getParameter(string $name): string
	{
		if ( ! array_key_exists($name, $this->parameters)) {
			throw new LogicException("Unknow parameter '{$name}'.");
		}
		return $this->parameters[$name];
	}
}
