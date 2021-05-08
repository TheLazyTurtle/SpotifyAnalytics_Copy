# What is it
This is a little python based program that based on the spotify api will collect the last 25 songs you have listend to in the past 5 minutes. 
It also has a web interface to show some analytics in graphs

# How to setup
* Download the master repo
* Run the database.sql commands to make the database ready to go.
* Run playing.py in the folder Python to start collecting songs
the python script will not do anything yet because it doesn't have accounts and auth tokens yet. 
To fix this do the following steps:
* Put all the files and folders (excluding the Python folder) in the root of your webserver
* Go to your host in the browser and make an account.
* When you login it will ask you to login with spotify. 
When you allowed the program to read your spotify recently played the python script will start collecting your latest songs and displaying them on the web page

# Info
* The api key is valid for a day (it doesn't yet get refreshed after expiration)

# Database
Most of the config for the database is in the database.sql file
