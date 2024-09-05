Sample TODO app
===============

Example TODO crud app php+reactjs.
PHP, including how to handle routing, request processing, and database interactions manually.
The proposed test task is to develop a simple RESTful API for a "To-Do List" system using plain PHP. The API should allow users to perform
CRUD operations (Create, Read, Update, Delete) on to-do items. Each to-do item should have attributes like title, description, and status.


## První spuštění:

1. Vytvoříme adresáře temp, data a var v kořeni projektu. Nastavíme práva zápisu.
2. Zkopírujeme předlohu .env-example na .env, a nastavíme porty podle sebe.
3. Spustíme kontainer, například

	```
	project=todo
	podman-compose -p $project up -d
	```

4. Pokusně si zkusíme backend: http://localhost:<BACKEND_PORT>/ a zjistíme, že do adresáře data přidat konfiguraci a soubor s databází. Použijeme předlohu: backend/config.json-example backend/data.sqlite3-example
5. Spustíme v adresáři backend `composer install`
6. Mělo by fungovat.
