import { Api } from "./api";

export class NotificationAPI extends Api {
    protected static url = `${this.baseUrl}/notification`;

    static async makeRequest(notificationTypeID: number, userID: string) {
        try {
            const body = {
                notification_type_id: notificationTypeID,
                receiver_user_id: userID
            }

            let header = super.makeHeader("POST", body);

            const response = await fetch(`${NotificationAPI.url}/create`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error making the request");
        }
    }

    static async removeRequest(userID: string) {
        try {
            const body = {
                receiver_user_id: userID
            }

            let header = super.makeHeader("POST", body);

            const response = await fetch(`${NotificationAPI.url}/delete`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error making the request");
        }
    }

    static async getNotifications() {
        try {
            let header = super.makeHeader("GET");

            const response = await fetch(`${NotificationAPI.url}/`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error making the request");
        }
    }

    static async handleRequest(notificationID: number, result: boolean) {
        try {
            const body = {
                notification_id: notificationID,
                response: result
            }

            let header = super.makeHeader("POST", body);

            const response = await fetch(`${NotificationAPI.url}/handle`, header);
            const response_1 = await super.checkStatus(response);
            return super.parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error making the request");
        }

    }
}
