import { Api } from "./api";

export class AlbumAPI extends Api {
    protected static url = `${this.baseUrl}/album`

    static async getOne(id: string) {
        try {
            const response = await fetch(`${this.url}/${id}`);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the projects");
        }
    }

    static async get() {
        try {
            const response = await fetch(`${this.url}/`);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the projects");
        }
    }

    static async search(artistID: string) {
        try {
            const response = await fetch(`${this.url}/search/${artistID}`);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the projects");
        }
    }
}
