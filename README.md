# HBF's turneringsystem

Dette er koden til turneringssystemet i Hovedstadens Bordfodboldforening. Det er oprindeligt udviklet omkring 2013 af Simon K's ven Peter. Eventuel grimhed af kode (:poop: :poop:) stammer derfor fra ham, og ikke de nuværende personer, der vedligeholder systemet.

## Kom godt i gang

1. Installer WAMP ( http://www.wampserver.com/en/ )
2. Hent Github for Windows og clone dette repository
3. Gå ind i mysql i phpmyadmin og kør koden fra de to sql scripts
4. Gå til filen *WAMP INSTALLATIONSMAPPE*\bin\apache\apache2.4.9\bin\php.ini
	1. Sæt short_open_tag = on
	2. Sæt error_reporting = E_ALL ^ E_DEPRECATED
	3. Gem og luk filen
5. Gå til filen *WAMP INSTALLATIONSMAPPE*\bin\apache\apache2.4.9\conf\httpd.conf
	1. Find stedet hvor der står DocumentRoot
	2. Lav en search & replace på dette bibliotek med der, hvor du har clonet repo'et til
	3. Gem og luk filen
6. Genstart WAMP
7. Så burde det køre ved at gå til http://localhost bruger og kode er begge hbf som default. Kan ændres ved at ændre de relevante entries i tabellen hbf_indstillinger
