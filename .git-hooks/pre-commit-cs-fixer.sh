#!/usr/bin/env bash
set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}php-cs-fixer pre commit hook start${NC}"

COMPOSER_PATH="$GIT_DIR/../bin/composer.sh"
ESLINT="$GIT_DIR/../bin/yarn.sh lint"


echo -e "${BLUE}executing cs fixer${NC}"
#identify staged php files and execute cs linter
PHP_FILES=$(git status --porcelain | egrep -i '^\s*[AM].*.(php)$' | cut -c 3- | tr '\n' ' ')
$COMPOSER_PATH run-script csFixer $PHP_FILES
for dir in $PHP_FILES; do
    git add "$dir"
done

echo -e "${BLUE}executing eslint${NC}"
#identify staged vue and js files and execute eslint (correct the path by suppressing /app/resources/js)
JS_FILES=$(git status --porcelain | egrep -i '^\s*[AM].*.(vue|js)$' | cut -c 3- | tr '\n' ' ' | sed -e 's#app/resources/js/##g')
$ESLINT $JS_FILES

echo -e "${GREEN}Linting passed. Committing files..${NC}"
