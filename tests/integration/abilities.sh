#!/bin/bash
#
# Runs tests/integration/abilities.php on a throwaway WordPress with its own
# database, then removes both. Nothing else is touched. Needs WP-CLI, PHP's
# mysqli and a local MySQL, and the network the first time WP-CLI fetches core.
#
#   bash tests/integration/abilities.sh
#
# Defaults suit MAMP. Elsewhere, set MD_TEST_DB_USER, MD_TEST_DB_PASSWORD and
# MD_TEST_DB_SOCKET. WP sets the WP-CLI command.

set -e

THEME="$(cd "$(dirname "$0")/../.." && pwd)"
DROPINS="$(cd "$THEME/../../md-dropins" && pwd)"
CHECKS="$THEME/tests/integration/abilities.php"
WP="${WP:-wp}"
USER="${MD_TEST_DB_USER:-root}"
PASSWORD="${MD_TEST_DB_PASSWORD:-root}"
SOCKET="${MD_TEST_DB_SOCKET:-/Applications/MAMP/tmp/mysql/mysql.sock}"
DB="md_abilities_scratch_$$"
SITE="$(mktemp -d)"

database() {
	MD_SQL="$1" MD_USER="$USER" MD_PASSWORD="$PASSWORD" MD_SOCKET="$SOCKET" php -r '
		$db = mysqli_connect( "localhost", getenv( "MD_USER" ), getenv( "MD_PASSWORD" ), "", 0, getenv( "MD_SOCKET" ) );
		mysqli_query( $db, getenv( "MD_SQL" ) );
	'
}

clean_up() {
	database "DROP DATABASE IF EXISTS \`$DB\`" || true
	rm -rf "$SITE"
}

trap clean_up EXIT

database "CREATE DATABASE \`$DB\`"

$WP core download --path="$SITE" --skip-content --quiet
$WP config create --path="$SITE" --dbname="$DB" --dbuser="$USER" --dbpass="$PASSWORD" --dbhost="localhost:$SOCKET" --skip-check --quiet
$WP core install --path="$SITE" --url=http://abilities.test --title="Abilities Test" --admin_user=builder --admin_password="$(php -r 'echo bin2hex( random_bytes( 12 ) );')" --admin_email=builder@example.test --skip-email --quiet

mkdir -p "$SITE/wp-content/themes" "$SITE/wp-content/plugins"
ln -s "$THEME" "$SITE/wp-content/themes/marketers-delight"
ln -s "$DROPINS" "$SITE/wp-content/md-dropins"

$WP theme activate marketers-delight --path="$SITE" --quiet
$WP eval --path="$SITE" --user=builder 'md_activate_dropins( array( "stream", "bookshelf", "changelog", "optins", "footnotes" ) );'

echo "Trash on:"
$WP eval-file "$CHECKS" --path="$SITE" --user=builder --exec='define( "MD_ABILITIES_SCRATCH", true );'

echo "Trash off:"
$WP eval-file "$CHECKS" --path="$SITE" --user=builder --exec='define( "MD_ABILITIES_SCRATCH", true ); define( "EMPTY_TRASH_DAYS", 0 );'
