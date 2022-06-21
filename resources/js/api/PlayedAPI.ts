import axios from "axios";
import { Response } from "../App";
import { Api } from "./api";

export class PlayedAPI extends Api {
    protected static url = `${this.baseUrl}/played`;

    static async allSongsPlayed(minDate: string = "2020-01-01", maxDate: string = "2099-01-01", minPlayed: string = "0", maxPlayed: string = "9999", userID?: string) {
        try {
            // const header = super.makeHeader("GET");
            // const userIDExtensinon = userID !== undefined ? `&user_id=${userID}` : "";
            //
            // const response = await fetch(`${PlayedAPI.url}/allSongsPlayed?min_date=${minDate}&max_date=${maxDate}&min_played=${minPlayed}&max_played=${maxPlayed}${userIDExtensinon}`, header);
            // const response_1 = await super.checkStatus(response);
            // return super.parseJSON(response_1);
            const params = {
                params: {
                    min_date: minDate,
                    max_date: maxDate,
                    min_played: minPlayed,
                    max_played: maxPlayed,
                    user_id: userID
                }
            };
            const result = await axios.get("/api/played/allSongsPlayed", params);
            if (result.status === 200) {
                return result.data;
            }
            throw new Error("DIE YOU BEACH");
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the data from graph All Songs Played");
        }
    }

    static async topSongs(minDate: string = "2020-01-01", maxDate: string = "2099-01-01", artistName: string = "%", amount: string = "10", userID?: string) {
        try {
            const header = super.makeHeader("GET");
            const userIDExtensinon = userID !== undefined ? `&user_id=${userID}` : "";

            const response = await fetch(`${PlayedAPI.url}/topSongs?min_date=${minDate}&max_date=${maxDate}&artist_name=${artistName}&limit=${amount}${userIDExtensinon}`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the data from graph Top Songs");
        }
    }

    static async topArtist(minDate: string = "2020-01-01", maxDate: string = "2099-01-01", amount: string = "10", userID?: string) {
        try {
            const header = super.makeHeader("GET");
            const userIDExtensinon = userID !== undefined ? `&user_id=${userID}` : "";

            const response = await fetch(`${PlayedAPI.url}/topArtists?min_date=${minDate}&max_date=${maxDate}&limit=${amount}${userIDExtensinon}`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the data from graph Top Artist");
        }
    }

    static async playedPerDay(minDate: string = "2020-01-01", maxDate: string = "2099-01-01", songName: string = "%", artistName: string = "%", userID?: string) {
        try {
            const header = super.makeHeader("GET");
            const userIDExtensinon = userID !== undefined ? `&user_id=${userID}` : "";

            const response = await fetch(`${PlayedAPI.url}/playedPerDay?min_date=${minDate}&max_date=${maxDate}&song_name=${songName}&artist_name=${artistName}${userIDExtensinon}`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the data from graph Played Per Day");
        }
    }

    static async topArtistSearch(artistName: string, limit: string, userID?: string) {
        try {
            const header = super.makeHeader("GET");
            const userIDExtensinon = userID !== undefined ? `&user_id=${userID}` : "";

            const response = await fetch(`${PlayedAPI.url}/topArtistSearch?artist_name=${artistName}&limit=${limit}${userIDExtensinon}`, header);
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

    static async search(name: string) {
        const params = {
            params: {
                name: name
            }
        }
        return await axios.get<Response<any>>(`/api/search`, params).then((response) => response.data)
    }
}
