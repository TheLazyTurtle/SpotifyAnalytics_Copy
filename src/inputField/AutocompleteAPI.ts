const baseUrl = "http://localhost/api";

function translateStatusToErrorMessage(status: number) {
    switch (status) {
        case 401:
            return "Please login";
        case 403:
            return "You do not have permission to view the albums";
        default:
            return "There was an error getting the albums";
    }
}

function checkStatus(response: any) {
    if (response.ok) {
        return response;
    } else {
        const httpErrorInfo = {
            status: response.stauts,
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

const AutocompleteAPI = {
    async autocomplete(url: string, id: string, keyword: string, amount: string | number) {
        try {
            const response = await fetch(`${baseUrl}${url}?userID=${id}&keyword=${keyword}&amount=${amount}`);
            const response_1 = await checkStatus(response);
            return parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the projects");
        }
    },
}

export { AutocompleteAPI }
