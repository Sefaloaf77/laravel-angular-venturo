#!/bin/bash

git fetch

git checkout main

git pull

wait

composer install

wait

php artisan migrate

