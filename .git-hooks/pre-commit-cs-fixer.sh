#!/usr/bin/env bash
set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}php-cs-fixer pre commit hook start${NC}"

ESLINT="$GIT_DIR/../bin/yarn.sh lint"
PROJECT_PATH=$(git rev-parse --show-toplevel)
COMPOSER_PATH="$PROJECT_PATH/bin/composer.sh"

#identify staged php files and execute cs linter
CHANGED_PHP_FILES=$(git diff --cached --name-only --diff-filter=ACM -- '*.php')
if [ -n "$CHANGED_PHP_FILES" ]; then
    echo -e "${BLUE}executing cs fixer${NC}"
    $COMPOSER_PATH run-script csFixer
    for dir in $CHANGED_PHP_FILES; do
        git add "$PROJECT_PATH/$dir"
    done
fi

CHANGED_JS_FILES=$(git diff --cached --name-only --diff-filter=ACM -- '*.vue' '*.js')
echo -e "${BLUE}executing eslint${NC}"
#identify staged vue and js files and execute eslint (correct the path by suppressing /app/resources/js)

if [ -n "$CHANGED_JS_FILES" ]; then
    $ESLINT
    for dir in $CHANGED_JS_FILES; do
        git add "$PROJECT_PATH/$dir"
    done
fi

echo -e "${GREEN}Linting passed. Committing files..${NC}"
