<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Todo;

use Nette\Utils\Validators;
use Nette\Utils\Json;
use RuntimeException;
use LogicException;


/**
 * Díky tomu je možné celou aplikaci zavolat, a z indexu volat pomocí fluent
 * interface. Kod je pak možno sdílet mezi různými instancemi.
 *
 * (new Builder())
 * 		->setLogDirectory('logs')
 * 		->setTempDirectory('temp')
 * 		->run();
 */
class Builder
{

	private string $logDir = 'logs';
	private string $dataDir = 'data';
	private string $tempDir = 'temp';


	/**
	 * Kam se bude logovat neúspěchy a všechno.
	 */
	function setLogDirectory(string $path): self
	{
		Validators::assert($path, 'string');
		$this->logDir = $path;
		return $this;
	}



	/**
	 * Dočasné soubory. Možno promazávat.
	 */
	function setTempDirectory(string $path): self
	{
		Validators::assert($path, 'string');
		$this->tempDir = $path;
		return $this;
	}



	/**
	 * Umístění vlastních dat aplikace, lokální databáze, konfigurace, uploadované soubory,
	 * formuláře, stránky, etc. Nikdy nemazat.
	 */
	function setDataDirectory(string $path): self
	{
		Validators::assert($path, 'string');
		$this->dataDir = $path;
		return $this;
	}



	/**
	 * @param string $name Jméo aplikace. Z ní se dohledává umístění dat, tempů, logů, etc.
	 * @param bool $develop Při true se zobrazuje laděnka, a případně prostor pro nějaké autovytváření.
	 */
	function run(string $name, bool $develop = Null): void
	{
		try {
			self::assertWritable($this->logDir, 'log');
			self::assertWritable($this->tempDir, 'temp');
			self::assertWritable($this->dataDir, 'data');
		}
		catch (\Exception $e) {
			echo "Fatální chyba. Kontaktujte správce.";
			if ($develop) {
				echo "\n" . $e->getMessage();
			}
			return;
		}

		error_reporting(~E_USER_DEPRECATED);

		(new Application(new DIC($this->fetchConfiguration())))
			->run(Request::FromGlobals());
	}



	/**
	 * @return array<string, string>
	 */
	private function fetchConfiguration(): array
	{
		$file = "{$this->dataDir}/config.json";
		if ( ! file_exists($file)) {
			throw new RuntimeException("File with configuration '$file' is not found.");
		}
		return array_map(function($x) {
			return strtr($x, [
				'%logDir%' => $this->logDir,
				'%tempDir%' => $this->tempDir,
				'%dataDir%' => $this->dataDir,
			]);
		}, (array) Json::decode(file_get_contents($file) ?: ''));
	}



	private static function assertWritable(string $path, string $id): void
	{
		if ( ! file_exists($path)) {
			throw new RuntimeException("Path '$path' of $id is not found.");
		}
		if ( ! is_writable($path)) {
			throw new RuntimeException("Path '$path' of $id is not writable.");
		}
	}

}
