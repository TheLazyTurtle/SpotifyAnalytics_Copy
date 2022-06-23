import axios from "axios";
import { Response } from "../App";
import { Played } from "../graph/Played";
import { AutocompleteItem } from "../inputField/AutocompleteItem";
import { Api } from "./api";

export class PlayedAPI extends Api {
    protected static url = `${this.baseUrl}/played`;

    static async allSongsPlayed(minDate: string = "2020-01-01", maxDate: string = "2099-01-01", minPlayed: string = "0", maxPlayed: string = "9999", userID?: string) {
        const params = {
            params: {
                min_date: minDate,
                max_date: maxDate,
                min_played: minPlayed,
                max_played: maxPlayed,
                user_id: userID
            }
        };
        return await axios.get<Response<Played[]>>("/api/played/allSongsPlayed", params);
    }

    static async topSongs(minDate: string = "2020-01-01", maxDate: string = "2099-01-01", artistName: string = "%", amount: string = "10", userID?: string) {
        const params = {
            params: {
                min_date: minDate,
                max_date: maxDate,
                artist_name: artistName,
                amount: amount,
                user_id: userID
            }
        }
        return await axios.get<Response<Played[]>>("/api/played/topSongs", params);
    }

    static async topArtist(minDate: string = "2020-01-01", maxDate: string = "2099-01-01", amount: string = "10", userID?: string) {
        const params = {
            params: {
                min_date: minDate,
                max_date: maxDate,
                amount: amount,
                user_id: userID
            }
        }
        return await axios.get<Response<Played[]>>("/api/played/topArtists", params);
    }

    static async playedPerDay(minDate: string = "2020-01-01", maxDate: string = "2099-01-01", songName: string = "%", artistName: string = "%", userID?: string) {
        const params = {
            params: {
                min_date: minDate,
                max_date: maxDate,
                song_name: songName,
                artist_name: artistName,
                user_id: userID
            }
        }
        return await axios.get<Response<Played[]>>("/api/played/playedPerDay", params);
    }

    static async topArtistSearch(artistName: string, amount: string, userID?: string) {
        const params = {
            params: {
                artist_name: artistName,
                amount: amount,
                user_id: userID
            }
        }
        return await axios.get<Response<AutocompleteItem[]>>("/api/played/topArtistSearch", params);
    }

    static async topSongsSearch(songName: string, amount: string | number, userID?: string) {
        const params = {
            params: {
                song_name: songName,
                amount: amount,
                user_id: userID
            }
        }
        return await axios.get<Response<AutocompleteItem[]>>("/api/played/topSongsSearch", params);
    }

    static async search(name: string) {
        const params = {
            params: {
                name: name
            }
        }
        return await axios.get<Response<AutocompleteItem[]>>(`/api/search`, params)
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
