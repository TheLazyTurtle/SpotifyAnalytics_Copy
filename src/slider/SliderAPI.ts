const baseUrl = "http://localhost/api";

function translateStatusToErrorMessage(status: number) {
    switch (status) {
        case 401:
            return "Please login";
        case 403:
            return "You do not have permission to view the slider item data";
        default:
            return "There was an error getting the slider item data";
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

const SliderAPI = {
    async getData(url: string, userId: string, minDate: string = "2020-01-01", maxDate: string = "2099-01-01") {
        try {
            const response = await fetch(`${baseUrl}${url}?userID=${userId}&minDate=${minDate}&maxDate=${maxDate}&amount=1`);
            const response_1 = await checkStatus(response);
            return parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the data for slider");
        }
    },
}

export { SliderAPI };
