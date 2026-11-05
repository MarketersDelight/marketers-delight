#!/bin/sh
# Runs the theme test suites with one command.
#
# They're split into three PHPUnit configs (phpunit.xml, phpunit-inheritance.xml,
# phpunit-dropins.xml). The first two
# because their bootstraps declare conflicting stubs for the same WP function
# names (see tests/inheritance/bootstrap.php) and can't share a process — so
# each config runs in its own `php` invocation here rather than one shared run.

set -e
cd "$(dirname "$0")/.."

php tests/bin/phpunit.phar -c phpunit.xml "$@"
php tests/bin/phpunit.phar -c phpunit-inheritance.xml "$@"
php tests/bin/phpunit.phar -c phpunit-dropins.xml "$@"
