install:
	sudo apt  install php-curl \
	sudo sed -i 's/;extension=php_curl.dll/extension=php_curl.dll/' php.ini \

permission:
	chmod 0777 bot_access.json; chmod 0777 message.json; chmod 0777 database.json
