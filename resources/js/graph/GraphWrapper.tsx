import { useEffect, useState } from "react";
import ButtonWrapper from "../button/ButtonWrapper";
import InputFieldWrapper from "../inputField/InputFieldWrapper";
import { TimeFrame, convertTime } from "../dates";
import { PlayedAPI } from "../api/PlayedAPI";
import { FilterSetting } from "../inputField/FilterSetting";
import { useQuery } from "react-query";
import "./Graph.css";
import { Graph } from "./Graphs";
import GraphComponent from "./GraphComponent";

// TODO: Export all these things into a different file
//      Maybe just make a types file where we put all types, enums, interfaces (that are not component input values)
export enum GraphDataType {
    Line,
    Bar
};

export enum GraphName {
    allSongsPlayed = "allSongsPlayed",
    topSongs = "topSongs",
    topArtist = "topArtist",
    playedPerDay = "playedPerDay",
};

interface GraphWrapperProps {
    graph: Graph;
    userId?: string;
};

function GraphWrapper({ graph, userId }: GraphWrapperProps) {
    const [timeFrame, setTimeFrame] = useState(TimeFrame.year);
    const [filterSettings, setFilterSettings] = useState<FilterSetting>();
    const { data, refetch } = useQuery([graph.name, timeFrame], () => chooseEndPoint(graph.name, timeFrame, filterSettings, userId));

    useEffect(() => {
        refetch();
    }, [filterSettings, refetch]);

    const handleTimeFrameClick = (timeFrame: TimeFrame) => {
        setTimeFrame(timeFrame);
    }

    function handleInputFieldUpdate(filterSettings: FilterSetting) {
        setFilterSettings(filterSettings)
        refetch();
    }

    async function chooseEndPoint(graphValue: GraphName, timeFrame: TimeFrame, filterSettings: FilterSetting | undefined, userId?: string) {
        if (filterSettings === undefined) {
            return;
        }

        const { minDate, maxDate } = convertTime(timeFrame);

        switch (graphValue) {
            case GraphName.allSongsPlayed:
                return PlayedAPI.allSongsPlayed(minDate, maxDate, filterSettings["minPlayed"], filterSettings["maxPlayed"], userId)
            case GraphName.topSongs:
                return PlayedAPI.topSongs(minDate, maxDate, filterSettings["amount"], filterSettings["artistName"], userId);
            case GraphName.topArtist:
                return PlayedAPI.topArtist(minDate, maxDate, filterSettings["amount"], userId);
            case GraphName.playedPerDay:
                return PlayedAPI.playedPerDay(minDate, maxDate, filterSettings["songName"], filterSettings["artistName"], userId);
        }
    }

    return (
        <>
            <ButtonWrapper onClick={handleTimeFrameClick} />
            <InputFieldWrapper update={handleInputFieldUpdate} inputFields={graph.inputFields} graphName={graph.name} userId={userId} />
            <GraphComponent dataPoints={data?.data.data} graphType={graph.type} />
        </>
    );
}

export default GraphWrapper;
