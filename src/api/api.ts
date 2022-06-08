export class Api {
    protected static baseUrl = "http://localhost:8000/api";

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
        if (response.ok) {
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
            if (cookie[0] === "laravel_token") {
                return cookie[1];
            }
        }

        return null;
    }

    protected static makeHeader(type: string, options: {} = {}) {
        const token = Api.getToken();

        if (token === null) {
            // window.location.href="/login";
        }

        if (type === "GET") {
            return {
                method: type,
                headers: {
                    'Content-Type': 'application/json;',
                    Authorization: `Bearer ${token}`,
                },
            }
        } else if (type === "POST") {
            return {
                method: type,
                headers: {
                    'Content-Type': 'application/json;',
                    Authorization: `Bearer ${token}`,
                },
                options: options
            }
        }
    }
}

