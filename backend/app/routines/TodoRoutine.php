<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Todo;

use LogicException;
use RuntimeException;
use Nette\Utils\Strings;
use Nette\Utils\Json;
use Nette\Utils\Validators;


final readonly class TodoRoutine implements Routine
{

	function __construct(private TodoQuery $query
		, private TodoCreateOperation $creater
		, private TodoUpdateOperation $updater
		, private TodoRemoveOperation $remover
		)
	{
	}



	function process(Request $req): Response
	{
		switch (True) {
			case $req->getMethod() === $req::MethodGet && empty($req->getSelector()):
				return new Response(Response::S200_OK
					, Response::ContentTypeJson
					, Json::encode(array_map(fn($x) => $x->toObject(), $this->query->findAll()))
					);

			case $req->getMethod() === $req::MethodGet && $req->getSelector():
				Validators::assert($req->getSelector(), 'numericint:1..');
				if ($row = $this->query->getById((int) $req->getSelector())) {
					return new Response(Response::S200_OK
						, Response::ContentTypeJson
						, Json::encode($row->toObject())
						);
				}
				return new Response(Response::S404_NotFound
					, Response::ContentTypeJson
					, ''
					);

			case $req->getMethod() === $req::MethodPost:
				$id = $this->creater->doPersistNew(Todo::FromObject(Json::decode($req->getRawBody())));
				return new Response(Response::S201_Created
					, Response::ContentTypeJson
					, Json::encode($this->query->getById($id)->toObject())
					);

			case $req->getMethod() === $req::MethodPut && $req->getSelector():
				Validators::assert($req->getSelector(), 'numericint:1..');
				if ( ! $entity = $this->query->getById((int) $req->getSelector())) {
					throw new RuntimeException("Record is not found.");
				}
				$this->updater->doPersistChanges($entity, (array) Json::decode($req->getRawBody()));
				return new Response(Response::S202_Accepted
					, Response::ContentTypeJson
					, Json::encode($this->query->getById((int) $req->getSelector())->toObject())
					);

			case $req->getMethod() === $req::MethodDelete && $req->getSelector():
				Validators::assert($req->getSelector(), 'numericint:1..');
				$this->remover->doPersistRemove((int) $req->getSelector());
				return new Response(Response::S204_NoContent, Response::ContentTypeJson, '');

			default:
				throw new LogicException("Not implemented.");
		}
	}

}
