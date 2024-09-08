<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Todo;

use Nette\Utils\AssertionException;
use Nette\Utils\Validators;
use RuntimeException;
use LogicException;


final class Todo
{
	const
		StatusPending = 'pending',
		StatusCompleted = 'completed';


	/**
	 * @param object{id: int, title: string, description: string, status: string} $obj
	 */
	static function FromObject($obj): self
	{
		Validators::assertField((array) $obj, 'title', 'string:1..', "item '%' in object");
		return new static($obj->id ?? Null
			, $obj->title
			, $obj->description
			, $obj->status);
	}



	function __construct(private ?int $id, private string $title, private string $description, private string $status)
	{
		if (!in_array($status, [
				self::StatusPending,
				self::StatusCompleted,
				])) {
			throw new AssertionException("The 'status' must be of ['pending', 'completed']; '$status' given.");
		}
	}



	/**
	 * @param array{id: int, title: string, description: string, status: string} $xs
	 */
	function fillChanges(array $xs): void
	{
		foreach ($xs as $prop => $val) {
			$setter = 'set' . ucfirst($prop);
			if (method_exists($this, $setter)) {
				$this->$setter($val);
			}
		}
	}



	private function setTitle(string $m): void
	{
		$this->title = $m;
	}



	private function setDescription(string $m): void
	{
		$this->description = $m;
	}



	private function setStatus(string $m): void
	{
		$this->status = $m;
	}



	/**
	 * @return object{id: int|null, title: string, description: string, status: string}
	 */
	function toObject()
	{
		return (object) [
			'id' => $this->id,
			'title' => $this->title,
			'description' => $this->description,
			'status' => $this->status,
		];
	}

}
