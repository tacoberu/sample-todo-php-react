<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Todo;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use InvalidArgumentException;
use Nette\Utils\AssertionException;


class TodoTest extends TestCase
{

	function testFromObject() {
		$item = Todo::FromObject((object) [
			'title' => 'a',
			'description' => 'b',
			'status' => 'pending',
		]);
		$this->assertEquals((object) [
			'id' => Null,
			'title' => 'a',
			'description' => 'b',
			'status' => 'pending',
		], $item->toObject());
	}



	#[DataProvider('dataFromObjectFail')]
	function testFromObjectFail(string $errmsg, array $obj) {
		$this->expectException(AssertionException::class);
		$this->expectExceptionMessage($errmsg);
		$this->expectExceptionCode(0);
		Todo::FromObject((object) $obj);
	}



	static function dataFromObjectFail()
	{
		$base = [
			'title' => 'a',
			'description' => 'b',
			'status' => 'completed',
		];
		return [
			'illagal value of title' => ["The item 'title' in object expects to be string in range 1.., string '' given."
				, array_merge($base, ['title' => ''])
				],
			'illegal value of status' => ["The 'status' must be of ['pending', 'completed']; 'c' given."
				, array_merge($base, ['status' => 'c'])
				],
			'missing title' => ["Missing item 'title' in object."
				, [
						'description' => 'b',
						'status' => 'completed',
					]
				],
			];
	}

}
