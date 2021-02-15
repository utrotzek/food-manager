#!/usr/bin/env bash
set -e
echo "php-cs-fixer pre commit hook start"
COMPOSER_PATH="$GIT_DIR/../bin/composer.sh"

FILES=` git status --porcelain | grep -e '^[AM]\(.*\).php$' | cut -c 3- | tr '\n' ' '`
$COMPOSER_PATH run-script csFixer $FILES

for dir in $FILES; do
    git add "$dir"
done
echo "php-cs-fixer pre commit hook finish"
