export class Api {
    static baseUrl = "http://localhost:8000/api";

    protected static translateStatusToErrorMessage(status: number) {
        switch (status) {
            case 401:
                return "Please login";
            case 403:
                return "You do not have permission to view this resource";
            default:
                return "There was an error getting this resource";
        }
    }

    protected static checkStatus(response: any) {
        if (response.status.ok) {
            return response;
        } else {
            const httpErrorInfo = {
                status: response.stauts,
                statusText: response.statusText,
                url: response.url
            };

            console.log(`Log server http error: ${JSON.stringify(httpErrorInfo, null, " ")}`);

            let errorMessage = Api.translateStatusToErrorMessage(httpErrorInfo.status)
            throw new Error(errorMessage);
        }
    }

    protected static parseJSON(response: Response) {
        return response.json();
    }

    public static getToken() {
        const cookies = document.cookie.split('; ');

        for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i].split("=")
            if (cookie[0] === "XSRF-TOKEN") {
                return cookie[1];
            }
        }

        return null;
    }

    static makeHeader(type: string, body: {} = {}) {
        const xsrfToken = this.getToken();

        if (type === "GET") {
            return {
                method: type,
                headers: {
                    'Content-Type': 'application/json;',
                    Credential: 'include',
                },
            }
        } else if (type === "POST") {
            return {
                method: type,
                headers: {
                    'Content-Type': 'application/json;',
                    Credential: 'include',
                },
                body: JSON.stringify(body),
            }
        }
    }
}

