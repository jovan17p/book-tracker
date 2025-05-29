#!/usr/bin/env bash
set -o errexit

composer install
php bin/console cache:clear
