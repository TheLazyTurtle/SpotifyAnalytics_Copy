import { PlayedAPI } from "../api/PlayedAPI";
import { inputField } from "../inputField/InputFieldWrapper";
import { GraphType, GraphValue } from "./GraphWrapper";

export interface Graph {
    name: string;
    type: GraphType;
    inputFields: inputField[];
    graphValue: GraphValue;
};

class graphs {
    graphs: Graph[] = [
        {
            name: "AllSongsPlayed",
            type: GraphType.Bar,
            inputFields: [
                {
                    name: "minPlayed",
                    type: "number",
                    placeholder: "Min Played",
                    startValue: "",
                },
                {
                    name: "maxPlayed",
                    type: "number",
                    placeholder: "Max Played",
                    startValue: "",
                }
            ],
            graphValue: GraphValue.allSongsPlayed
        },
        {
            name: "TopSongs",
            type: GraphType.Bar,
            inputFields: [
                {
                    name: "artistName",
                    type: "text",
                    placeholder: "Artist name",
                    startValue: "",
                    autocompleteFunction: PlayedAPI.topArtistSearch
                },
                {
                    name: "amount",
                    type: "number",
                    placeholder: "Amount",
                    startValue: "",
                },
            ],
            graphValue: GraphValue.topSongs
        },
        {
            name: "TopArtist",
            type: GraphType.Bar,
            inputFields: [
                {
                    name: "amount",
                    type: "number",
                    placeholder: "Amount",
                    startValue: "",
                },
            ],
            graphValue: GraphValue.topArtist
        },
        {
            name: "PlayedPerDay",
            type: GraphType.Line,
            inputFields: [
                {
                    name: "songName",
                    type: "text",
                    placeholder: "Song Name",
                    startValue: "",
                    autocompleteFunction: PlayedAPI.topSongsSearch
                },
                // {
                //     name: "artistName",
                //     type: "text",
                //     placeholder: "Artist Name",
                //     startValue: "",
                //     autocompleteFunction: PlayedAPI.topArtistSearch
                // },
            ],
            graphValue: GraphValue.playedPerDay
        },
    ];

}

export { graphs }
