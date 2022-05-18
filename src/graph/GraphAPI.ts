const baseUrl = "http://localhost/api";
const playedUrl = `${baseUrl}/played`;
const artistUrl = `${baseUrl}/artist`;

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

const GraphAPI = {
    async allSongsPlayed(id: string, minDate: string = "2020-01-01", maxDate: string = "2099-01-01", minPlayed: string | undefined = "0", maxPlayed: string | undefined = "9999") {
        try {
            const response = await fetch(`${playedUrl}/allSongsPlayed.php?userID=${id}&minPlayed=${minPlayed}&maxPlayed=${maxPlayed}&minDate=${minDate}&maxDate=${maxDate}`);
            const response_1 = await checkStatus(response);
            return parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the data from graph All Songs Played");
        }
    },
    async topSongs(id: string, minDate: string = "2020-01-01", maxDate: string = "2099-01-01", artistName: string | undefined = "", amount: string | undefined = "10") {
        try {
            const response = await fetch(`${playedUrl}/topSongs.php?userID=${id}&minDate=${minDate}&maxDate=${maxDate}&artist=${artistName}&amount=${amount}`);
            const response_1 = await checkStatus(response);
            return parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the data from graph Top Songs");
        }
    },
    async topArtist(id: string, minDate: string = "2020-01-01", maxDate: string = "2099-01-01", amount: string | undefined = "10") {
        try {
            const response = await fetch(`${artistUrl}/topArtist.php?userID=${id}&minDate=${minDate}&maxDate=${maxDate}&amount=${amount}`);
            const response_1 = await checkStatus(response);
            return parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the data from graph Top Artist");
        }
    },
    async playedPerDay(id: string, minDate: string = "2020-01-01", maxDate: string = "2099-01-01", songName: string | undefined = "", artistName: string | undefined = "") {
        try {
            const response = await fetch(`${playedUrl}/playedPerDay.php?userID=${id}&minDate=${minDate}&maxDate=${maxDate}&song=${songName}&artist=${artistName}`);
            const response_1 = await checkStatus(response);
            return parseJSON(response_1);
        } catch (error) {
            console.log("log client error " + error);
            throw new Error("There was an error getting the data from graph Played Per Day");
        }
    }
}

export { GraphAPI };
