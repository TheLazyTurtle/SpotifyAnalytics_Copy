import { Api } from "./api";

export class PlayedAPI extends Api {
    protected static url = `${this.baseUrl}/played`

    static async allSongsPlayed(minDate: string = "2020-01-01", maxDate: string = "2099-01-01", minPlayed: string | undefined = "0", maxPlayed: string | undefined = "9999") {
        try {
            const header = super.makeHeader("GET");

            const response = await fetch(`${PlayedAPI.url}/allSongsPlayed?min_date=${minDate}&max_date=${maxDate}&min_played=${minPlayed}&max_played=${maxPlayed}`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the data from graph All Songs Played");
        }
    }

    static async topSongs(minDate: string = "2020-01-01", maxDate: string = "2099-01-01", artistName: string | undefined = "%", amount: string | undefined = "10") {
        try {
            const header = super.makeHeader("GET");

            const response = await fetch(`${PlayedAPI.url}/topSongs?min_date=${minDate}&max_date=${maxDate}&artist_name=${artistName}&amount=${amount}`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the data from graph Top Songs");
        }
    }

    static async topArtist(minDate: string = "2020-01-01", maxDate: string = "2099-01-01", amount: string | undefined = "10") {
        try {
            const header = super.makeHeader("GET");

            const response = await fetch(`${PlayedAPI.url}/topArtists?min_date=${minDate}&max_date=${maxDate}&amount=${amount}`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the data from graph Top Artist");
        }
    }

    static async playedPerDay(minDate: string = "2020-01-01", maxDate: string = "2099-01-01", songName: string | undefined = "%", artistName: string | undefined = "%") {
        try {
            const header = super.makeHeader("GET");

            const response = await fetch(`${PlayedAPI.url}/playedPerDay?min_date=${minDate}&max_date=${maxDate}&song_name=${songName}&artist_name=${artistName}`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the data from graph Played Per Day");
        }
    }

    static async topArtistSearch(artistName: string, limit: string | number) {
        try {
            const header = super.makeHeader("GET");

            const response = await fetch(`${PlayedAPI.url}/topArtistSearch?artist_name=${artistName}&limit=${limit}`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the data from graph Played Per Day");
        }
    }

    static async topSongsSearch(songName: string, limit: string | number) {
        try {
            const header = super.makeHeader("GET");

            const response = await fetch(`${PlayedAPI.url}/topSongsSearch?song_name=${songName}&limit=${limit}`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the data from graph Played Per Day");
        }
    }

    static async timeListend(minDate: string, maxDate: string) {
        try {
            const header = super.makeHeader("GET");

            const response = await fetch(`${PlayedAPI.url}/timeListend?min_date=${minDate}&max_date=${maxDate}`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the data from graph Played Per Day");
        }
    }

    static async amountSongs(minDate: string, maxDate: string) {
        try {
            const header = super.makeHeader("GET");

            const response = await fetch(`${PlayedAPI.url}/amountSongs?min_date=${minDate}&max_date=${maxDate}`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the data from graph Played Per Day");
        }
    }

    static async amountNewSongs(minDate: string, maxDate: string) {
        try {
            const header = super.makeHeader("GET");

            const response = await fetch(`${PlayedAPI.url}/amountNewSongs?min_date=${minDate}&max_date=${maxDate}`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the data from graph Played Per Day");
        }
    }
}
