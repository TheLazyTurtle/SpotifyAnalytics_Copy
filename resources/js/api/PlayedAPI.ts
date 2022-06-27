import axios from "axios";
import { redirectToLogin, Response } from "../App";
import { Played } from "../graph/Played";
import { AutocompleteItem } from "../inputField/AutocompleteItem";
import { SliderItemName } from "../slider/SliderItems";

export class PlayedAPI {
    private static handleErrors(error: any) {
        if (error.response.status === 401) {
            redirectToLogin();
        }
    }

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

        return await axios.get<Response<Played[]>>("/api/played/allSongsPlayed", params).catch((error) => this.handleErrors(error))
    }

    static async topSongs(minDate: string = "2020-01-01", maxDate: string = "2099-01-01", amount: string = "10", artistName: string = "%", userID?: string) {
        const params = {
            params: {
                min_date: minDate,
                max_date: maxDate,
                artist_name: artistName,
                amount: amount,
                user_id: userID
            }
        }

        const result = await axios.get<Response<Played[]>>("/api/played/topSongs", params).catch((error) => this.handleErrors(error));

        return result;
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

        const result = await axios.get<Response<Played[]>>("/api/played/topArtists", params).catch((error) => this.handleErrors(error))

        return result;
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

        const result = await axios.get<Response<Played[]>>("/api/played/playedPerDay", params).catch((error) => this.handleErrors(error));
        return result;
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

    static async timeListened(minDate: string, maxDate: string) {
        const params = {
            params: {
                min_date: minDate,
                max_date: maxDate
            }
        }
        return await axios.get<Response<Played>>(`/api/played/timeListened`, params);
    }

    static async amountSongs(minDate: string, maxDate: string) {
        const params = {
            params: {
                min_date: minDate,
                max_date: maxDate
            }
        }
        return await axios.get<Response<Played>>(`/api/played/amountSongs`, params);
    }

    static async amountNewSongs(minDate: string, maxDate: string) {
        const params = {
            params: {
                min_date: minDate,
                max_date: maxDate
            }
        }
        return await axios.get<Response<Played>>(`/api/played/amountNewSongs`, params);
    }

    static async sliderItemData(minDate: string, maxDate: string) {
        return {
            [SliderItemName.topSongs]: await this.topSongs(minDate, maxDate, "1").then((data) => typeof data === "string" ? {} as Played : data?.data.data[0] as Played),
            [SliderItemName.topArtists]: await this.topArtist(minDate, maxDate, "1").then((data) => typeof data === "string" ? {} as Played : data?.data.data[0] as Played),
            [SliderItemName.timeListened]: await this.timeListened(minDate, maxDate).then((data) => data.data.data as Played),
            [SliderItemName.amountSongs]: await this.amountSongs(minDate, maxDate).then((data) => data.data.data as Played),
            [SliderItemName.amountNewSongs]: await this.amountNewSongs(minDate, maxDate).then((data) => data.data.data as Played)
        };
    }
}
