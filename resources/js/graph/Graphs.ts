import { PlayedAPI } from "../api/PlayedAPI";
import { inputField } from "../inputField/InputFieldWrapper";
import { GraphDataType, GraphName } from "./GraphWrapper";

export interface Graph {
    type: GraphDataType;
    inputFields: inputField[];
    name: GraphName;
    title: string;
};

class graphs {
    graphs: Graph[] = [
        {
            type: GraphDataType.Bar,
            inputFields: [
                {
                    name: "minPlayed",
                    allowedInputType: "number",
                    placeholder: "Min Played",
                    filterValue: "",
                },
                {
                    name: "maxPlayed",
                    allowedInputType: "number",
                    placeholder: "Max Played",
                    filterValue: "",
                }
            ],
            name: GraphName.allSongsPlayed,
            title: "Streams Per Song"
        },
        {
            type: GraphDataType.Bar,
            inputFields: [
                {
                    name: "artistName",
                    allowedInputType: "text",
                    placeholder: "Artist name",
                    filterValue: "",
                    autocompleteFunction: PlayedAPI.topArtistSearch
                },
                {
                    name: "amount",
                    allowedInputType: "number",
                    placeholder: "Amount",
                    filterValue: "",
                },
            ],
            name: GraphName.topSongs,
            title: "Top Songs"
        },
        {
            type: GraphDataType.Bar,
            inputFields: [
                {
                    name: "amount",
                    allowedInputType: "number",
                    placeholder: "Amount",
                    filterValue: "",
                },
            ],
            name: GraphName.topArtist,
            title: "Top Artist"

        },
        {
            type: GraphDataType.Line,
            inputFields: [
                {
                    name: "songName",
                    allowedInputType: "text",
                    placeholder: "Song Name",
                    filterValue: "",
                    autocompleteFunction: PlayedAPI.topSongsSearch
                },
            ],
            name: GraphName.playedPerDay,
            title: "Total Streams Per Time Frame"
        },
    ];

}

export { graphs }
