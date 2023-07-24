Pre-face: kur komentaruose "break-character" - rašau Lietuviškai, tai yra komentarai tikrintojui, o ne komentarai, kuriuos rašyčiau kode.
Kartais paaiškinu sub-optimalų sprendimą. Pavyzdžiui Validatoriaus nekūriau, nes yra tik 2 formos (basically viena per 2 vietas), kuriuose jis būtų naudojamas - cost/benefit - stengiausi fokusuotis prie "levels" bei kitų nurodytų punktų.


Sonaro test
============

Test project for job application in sonaro meant to test basics of php, html, css and js

Table of Contents
-----
- [Setup](#setup)
  * [Project setup](#project-setup)
- [Uploading documents](#uploading-document)
- [Viewing emails](#viewing-email)
- [Viewing db](#viewing-db)
Setup
======

Project setup
-------------
rename dist.htaccess to .htaccess
docker-compose up -d
This command sets all dependencies needed for php project: db, phpmyadmin and mailhog

Uploading documents
======

Since no styling example was provided I made simple forms with their own pages:
1. http://localhost:8000/json_form_page - for JSON
2. http://localhost:8000/csv_form_page - for csv

Viewing emails
======

To view sent emails go to http://localhost:8025

Viewing db
======
http://localhost:8080
username: root
password: insecuredevpassword