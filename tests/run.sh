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

# Lint every PHP file first: the suites don't load features/*/admin.php.

if find . -name '*.php' -not -path './tests/bin/*' -not -path './dist/*' -not -path './node_modules/*' -print0 | xargs -0 -n1 -P4 php -l | grep -v '^No syntax errors'; then
	exit 1
fi

php tests/bin/phpunit.phar -c phpunit.xml "$@"
php tests/bin/phpunit.phar -c phpunit-inheritance.xml "$@"
php tests/bin/phpunit.phar -c phpunit-dropins.xml "$@"
