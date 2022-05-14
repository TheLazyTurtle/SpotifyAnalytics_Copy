const baseUrl = "http://localhost/api";
const url = `${baseUrl}/album`;

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

        console.log(`Log server http error: ${JSON.stringify(httpErrorInfo, null , " ")}`);

        let errorMessage = translateStatusToErrorMessage(httpErrorInfo.status)
        throw new Error(errorMessage);
    }
}

function parseJSON(response: Response) {
    return response.json();
}

const AlbumAPI = {
    async getOne(id: string) {
        try {
            const response = await fetch(`${url}?albumID=${id}`);
            const response_1 = await checkStatus(response);
            return parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the projects");
        }
    },
    async get() {
        try {
            const response = await fetch(`${url}/read.php`);
            const response_1 = await checkStatus(response);
            return parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the projects");
        }
    },
    async search(artistID: string) {
        try {
            const response = await fetch(`${url}/search.php?artistID=${artistID}`);
            const response_1 = await checkStatus(response);
            return parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the projects");
        }
    },
};

export { AlbumAPI };

