import { Api } from "./api";

export class ArtistAPI extends Api {
    protected static url = `${this.baseUrl}/artist`

    static async getOne(artistID: string) {
        try {
            const header = super.makeHeader("GET");

            const response = await fetch(`${ArtistAPI.url}/${artistID}`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the artist");
        }
    }

    static async albums(artistID: string) {
        try {
            const header = super.makeHeader("GET");

            const response = await fetch(`${ArtistAPI.url}/albums?artist_id=${artistID}`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the albums for the artist");
        }
    }

    static async topSongs(artistID: string) {
        try {
            const header = super.makeHeader("GET");

            const response = await fetch(`${ArtistAPI.url}/topSongs?artist_id=${artistID}`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the top songs for the artist");
        }

    }
}
