// Get the userID form the session
var userID = '<%= Session["userID"] %>'

// This will contain all the graphs
var graphs = []

// This contains the filter settings for all the graphs
var filterSettings = []
filterSettings["asp"] = {
    minPlayed: 20,
    maxPlayed: 9999,
    minDate: "2020-01-01",
    maxDate: "2099-01-01",
}
filterSettings["ts"] = {
    artist: "",
    amount: 10,
    minDate: "2020-01-01",
    maxDate: "2099-01-01",
}
filterSettings["ta"] = {
    amount: 10,
    minDate: "2020-01-01",
    maxDate: "2099-01-01",
}
filterSettings["ppd"] = {
    song: "",
    minDate: "2020-01-01",
    maxDate: "2099-01-01",
}

// This will contain all the info for input fields if a graph has input fields
var inputFields = []
inputFields["asp"] = {
    0: {
        name: "minPlayed",
        placeholder: "Minimaal afgespeeld",
        type: "number",
    },
    1: {
        name: "maxPlayed",
        placeholder: "Maximaal afgespeeld",
        type: "number",
    },
}

inputFields["ts"] = {
    0: {
        name: "artist",
        placeholder: "Artiest",
        type: "text",
    },
    1: {
        name: "amount",
        placeholder: "Top Hoeveel",
        type: "number",
    },
}

inputFields["ta"] = {
    0: {
        name: "amount",
        placeholder: "Top hoeveel",
        type: "number",
    },
}

inputFields["ppd"] = {
    0: {
        name: "song",
        placeholder: "Nummer naam",
        type: "text",
    },
}

// TODO: Might have to make it that it will make its own div for everyting instead of makeing a div in index.php and than passing the name in here
// This graph is all songs played
getGraphData(
    "all_Songs_Played",
    "All Songs Played",
    "",
    "",
    "string",
    "/api/song/allSongsPlayed.php",
    filterSettings["asp"],
    inputFields["asp"]
)

// This is top 10 songs
getGraphData(
    "top_Songs",
    "Top Songs",
    "",
    "",
    "string",
    "/api/song/topSongs.php",
    filterSettings["ts"],
    inputFields["ts"]
)

// This is top 10 artist
getGraphData(
    "top_Artist",
    "Top Artist",
    "",
    "",
    "string",
    "/api/artist/topArtist.php",
    filterSettings["ta"],
    inputFields["ta"]
)

// This is top 10 artist
getGraphData(
    "played_Per_Day",
    "Player Per Day",
    "",
    "",
    "dateTime",
    "/api/song/playedPerDay.php",
    filterSettings["ppd"],
    inputFields["ppd"],
    "line"
)
