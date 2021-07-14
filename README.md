# What is it
Spotify Analytics is a program that will keep track of your spotify listening activity. Every x amount of time it will get x amount of songs from your recently listend songs. All the collected data will be saved in a simple SQL database and is displayed in the web interface.

# How to setup
* Download the master repo
* Place all the files in your webhost ex. `/var/www/html`
* (optional) Move the backOffice folder to a different directory if you don't want backend on your webhost

## Database setup
* Have a version of MySQL/MariaDB installed.
* Run the commands given in the database.sql file to make the database.

## Backend setup
* Install the following plugins for python:
`pip3 install spotipy`
`pip3 termcolor`
* Go into backOffice/src directory and make a copy of the `creds-example.py` file and name it `creds.py`.
* Edit this file and change the following things:
	* Change `clientID` and `clientSec` with the clientID and clientSec with the id and secret key you get by regestering a app on [developer.spotify.com](url).
	* Change `apiUrl` to the domain where you have the web interface hosted ex. `http://localhost/api/`.
* To run the backend run the `python3 main.py` command in the backOffice/src directory. (If you have errors with the cache file try running the command as sudo (this might require you to install the plugins with sudo as well.))

## Web interface
* Go into the api/config folder and copy the file `core-example.php` and call it `core.php`.
* Open the file and change the following things:
	* Change `$key` to a value of your liking. This is the secret key the JWT will use.
	* Change `$issuer` to the domain your webinterface is hosted ex. `http://localhost`
	* (optional) Change `$minDate` to the date you started using this program.


# Database
Most of the config for the database is in the database.sql file
