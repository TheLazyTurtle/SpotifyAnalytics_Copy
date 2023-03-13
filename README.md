# About Spotify Analytics
This is small web application that makes it possible for you to track your listening behaviour on Spotify. Every hour the application will update your listening history to stay up to date.

# How to run
* Run composer install 
* Run npm i
* Make a copy of the .env.example file and name it .env
* fill out: 
`SPOTIFY_CLIENT_ID=
SPOTIFY_CLIENT_SECRET=
SPOTIFY_CALLBACK_URL=
FETCHER_TOKEN=`
* To apply the database migrations run php artisan migrate
* To run the back-end run php artisan serve
* To run the front-end run npm run dev
