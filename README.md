# Drupal project

# Issues (thoughts and explanations)
- downgrade `php8` to `php7`
- downgrade `composer` v2 to v1

- **`composer install` & `composer update` give errors**

	`Updating dependencies (including require-dev)`
	`PHP Fatal error:  Allowed memory size of 1610612736 bytes exhausted (tried to allocate 4096 bytes) in phar:///usr/local/bin/composer/src/Composer/DependencyResolver/RuleSetGenerator.php on line 129`

	`Fatal error: Allowed memory size of 1610612736 bytes exhausted (tried to allocate 4096 bytes) in phar:///usr/local/bin/composer/src/Composer/DependencyResolver/RuleSetGenerator.php on line 129`

	`Check https://getcomposer.org/doc/articles/troubleshooting.md#memory-limit-errors for more info on how to handle out of memory errors.%`

	tried not touching the `composer.json` but i will still have to, in order to fix the issue above…
	so i started migrating: [drupal docs](https://www.drupal.org/docs/develop/using-composer/using-drupals-composer-scaffold#s-migrating-composer-scaffold)

	used: [github issue](https://github.com/cweagans/composer-patches/issues/423#issuecomment-1301026697)

	stopped with the migration because it gave errors on the patches and the server would not start. So then I realised that the packages in `composer.json` should work even if it is outdated. the first time I deleted the `composer.lock` which probably also caused the error above ;)

	went back to the initial `composer.json` and copied & pasted back the original `composer.lock` file, then ran `composer install` directly without updating packages.

	**now it successfully installed all packages and runs the server**

- **inside the localhost cms created account**

	wouldnt let me update the default email so -> threw the database away and tried again with a new database. 
	now it lets me change the email so i can finally save my account with the `administrator` role. smh

	the cms wouldn't even show the “Add field” button or the “Add type” button after adding the first type and a single field. smh x2
	I came across this: [forum post](https://www.drupal.org/forum/support/post-installation/2024-07-24/add-field-button-missing)
	so i changed theme and that solved the problem.