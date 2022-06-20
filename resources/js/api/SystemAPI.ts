import { Api } from "./api";

export class SystemAPI extends Api {
    protected static url = `${this.baseUrl}`

    static async login(username: string, password: string) {
        try {
            const body = { username, password };
            const header = super.makeHeader("POST", body);

            const response = await fetch(`http://localhost:8000/test`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error logging you in");
        }
    }

    static async validateToken() {
        try {
            const header = super.makeHeader("POST");

            const response = await fetch(`${this.url}/validateToken`, header);
            const res = await super.checkStatus(response);
            return super.parseJSON(res);
        } catch (error) {
            console.log("log client error " + error);
            // throw new Error("There was an error logging you in");
            return new Error("There was an error logging you in");
        }
    }
}
