import { Api } from "./api";

export class UserAPI extends Api {
    protected static url = `${this.baseUrl}/user`;

    static async getExternal(username: string) {
        try {
            const header = super.makeHeader("GET");

            const response = await fetch(`${UserAPI.url}/${username}`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the data for the user");
        }
    }

    static async get(username?: string) {
        try {
            const header = super.makeHeader("GET");
            const usernameExtenstion = username !== undefined ? `${username}` : "";

            const response = await fetch(`${UserAPI.url}/${usernameExtenstion}`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            // throw new Error("There was an error getting the data for the user");
            return { success: false };
        }
    }

    static async getGuest(username?: string) {
        try {
            const header = super.makeHeader("GET");
            const usernameExtenstion = username !== undefined ? `${username}` : "";

            const response = await fetch(`${UserAPI.url}/guest/${usernameExtenstion}`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            // throw new Error("There was an error getting the data for the user");
            return { success: false };
        }
    }

    static async follow(userID: string) {
        try {
            const body = { following_user_id: userID }
            let header = super.makeHeader("POST", body);

            const response = await fetch(`${UserAPI.url}/follow?following_user_id=${userID}`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error following / unfollowing the user");
        }
    }
}
