<?php

umask(0);

(require(__dir__ . '/../app/loader.php'))
	->setLogDirectory(__dir__ . '/../../var/logs')
	->setTempDirectory(__dir__ . '/../../temp')
	->setDataDirectory(__dir__ . '/../../data')
	->run('', True);
