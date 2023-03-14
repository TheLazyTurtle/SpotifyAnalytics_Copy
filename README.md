# Quick note
This is a copy of the original repository because idiot me 3 years ago though it would be smart to commit secrets. Even though all the secrets have been removed from the repo the secrets still remained in the pull request and could not be removed.
For that reason I made a copy of the repo.

# About Spotify Analytics
This is small web application that makes it possible for you to track your listening behaviour on Spotify. Every hour the application will update your listening history to stay up to date.

# How to run
1. Run `composer install`
2. Run `npm i`
3. Make a copy of the `.env.example` file and name it `.env`
4. Fill out these values in the `.env`. You can create these values by making an app via [Spotify's developer portal](https://developer.spotify.com/):
	- `SPOTIFY_CLIENT_ID=`
	- `SPOTIFY_CLIENT_SECRET=`
	- `SPOTIFY_CALLBACK_URL=`
5. To apply the database migrations run `php artisan migrate`
6. To run the back-end run `php artisan serve`
7. To run the front-end run `npm run dev`
