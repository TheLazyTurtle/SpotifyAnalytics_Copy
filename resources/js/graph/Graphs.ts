import { PlayedAPI } from "../api/PlayedAPI";
import { inputField } from "../inputField/InputFieldWrapper";
import { GraphDataType, GraphName } from "./GraphWrapper";

export interface Graph {
    type: GraphDataType;
    inputFields: inputField[];
    name: GraphName;
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
            name: GraphName.allSongsPlayed
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
            name: GraphName.topSongs
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
            name: GraphName.topArtist
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
                // {
                //     name: "artistName",
                //     type: "text",
                //     placeholder: "Artist Name",
                //     filterValue: "",
                //     autocompleteFunction: PlayedAPI.topArtistSearch
                // },
            ],
            name: GraphName.playedPerDay
        },
    ];

}

export { graphs }
