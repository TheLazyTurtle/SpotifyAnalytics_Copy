const baseUrl = "http://localhost/api";
const url = `${baseUrl}/system`;

function translateStatusToErrorMessage(status: number) {
    switch (status) {
        case 401:
            return "Please login";
        case 403:
            return "You do not have permission to login";
        default:
            return "There was an error logging you in";
    }
}

function checkStatus(response: any) {
    if (response.ok) {
        return response;
    } else {
        console.log(response)
        const httpErrorInfo = {
            status: response.status,
            statusText: response.statusText,
            url: response.url
        };

        console.log(`Log server http error: ${JSON.stringify(httpErrorInfo, null, " ")}`);

        let errorMessage = translateStatusToErrorMessage(httpErrorInfo.status)
        throw new Error(errorMessage);
    }
}

function parseJSON(response: Response) {
    return response.json();
}

const LoginAPI = {
    async login(username: string, password: string) {
        try {
            // request options
            const options = {
                method: "POST",
                body: JSON.stringify({ username, password }),
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
            }

            const response = await fetch(`${url}/login.php`, options);
            const response_1 = await checkStatus(response);
            return parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            // throw new Error("There was an error logging you in");
            return error;
        }
    },
};

export { LoginAPI };
