#!/bin/sh

python3 manage.py migrate
python3 manage.py makemigrations todo
python3 manage.py migrate todo

python3 manage.py runserver 0.0.0.0:8082

