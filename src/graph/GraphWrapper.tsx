import { useEffect, useState } from "react";
import Bar from "./BarGraph";
import Line from "./LineGraph";
import ButtonWrapper from "../button/ButtonWrapper";
import InputFieldWrapper, { inputField } from "../inputField/InputFieldWrapper";
import { TimeFrame, convertTime } from "../dates";
import { Cacher } from "../cacher";
import { PlayedAPI } from "../api/PlayedAPI";
import "./Graph.css";
import { FilterSetting } from "../inputField/FilterSetting";

export enum GraphType {
    Line,
    Bar
};

export enum GraphValue {
    allSongsPlayed = "allSongsPlayed",
    topSongs = "topSongs",
    topArtist = "topArtist",
    playedPerDay = "playedPerDay",
};

interface GraphWrapperProps {
    name: string;
    type: GraphType;
    value: GraphValue;
    inputFields: inputField[];
    userID?: string;
};

// TODO: Make this component more useable. It is kinda big
// TODO: Seems like caching is broken
//      It will always fetch and save in cache (WAIT... IT does it for the data on an EXTERNAL USER PAGE that means that it writes data to a cache that it shouldn't)
function GraphWrapper(props: GraphWrapperProps) {
    const [filterSettings, setFilterSettings] = useState<FilterSetting>({});
    const [timeFrame, setTimeFrame] = useState<TimeFrame>(TimeFrame.year);
    const [dataPoints, setDataPoints] = useState<number[]>([]);
    const [labels, setLabels] = useState<string[]>([]);
    const [error, setError] = useState<string | undefined>(undefined);

    const graphOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false,
            },
            title: {
                display: false,
            },
        },
    }

    // Get filtersettings
    useEffect(() => {
        if (props.userID === undefined) {
            const cachedFilterSettings = Cacher.getItem(`${props.name}-settings`) as FilterSetting;
            setFilterSettings(cachedFilterSettings);
            loadGraphData(cachedFilterSettings);
            return;
        }

        loadExternalGraphData({});

        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [props.value, timeFrame]);

    const handleTimeFrameClick = (value: TimeFrame) => {
        setTimeFrame(value);
    }

    function handleInputFieldUpdate(filterSettings: FilterSetting) {
        console.log("hey")
        setFilterSettings(filterSettings);

        if (props.userID === undefined) {
            loadGraphData(filterSettings, true);
            return
        }

        loadExternalGraphData(filterSettings);
    }

    async function loadExternalGraphData(filterSettings: FilterSetting) {
        let data = await chooseEndPoint(props.value, timeFrame, filterSettings, props.userID);

        if (data.success === true) {
            data = data.data;
            setError("");
        } else {
            data = JSON.parse("{}");
            setError("Failed to get for user");
        }

        processIncomingData(data);
    }

    async function loadGraphData(filterSettings: FilterSetting, force: boolean = false) {
        try {
            let data = Cacher.getItem(props.value, true, timeFrame);

            if (data.filterSettings !== filterSettings) {
                force = true;
            }

            if (Object.keys(data).length <= 0 || force) {
                data = await chooseEndPoint(props.value, timeFrame, filterSettings);

                if (data.success === true) {
                    Cacher.setItem(props.value, data.data, timeFrame, filterSettings);
                    data = data.data
                } else {
                    data = JSON.parse("{}");
                }
            }

            setError("");
            processIncomingData(data);
        } catch (e) {
            if (e instanceof Error) {
                console.log(e);
                setError(e.message);
            }
        }
    }

    async function chooseEndPoint(valueType: GraphValue, timeFrame: TimeFrame, filterSettings: FilterSetting, userID?: string) {
        const { minDate, maxDate } = convertTime(timeFrame);

        switch (valueType) {
            case GraphValue.allSongsPlayed:
                return PlayedAPI.allSongsPlayed(minDate, maxDate, filterSettings["minPlayed"], filterSettings["maxPlayed"], userID)
            case GraphValue.topSongs:
                return PlayedAPI.topSongs(minDate, maxDate, filterSettings["artistName"], filterSettings["amount"], userID);
            case GraphValue.topArtist:
                return PlayedAPI.topArtist(minDate, maxDate, filterSettings["amount"], userID);
            case GraphValue.playedPerDay:
                return PlayedAPI.playedPerDay(minDate, maxDate, filterSettings["songName"], filterSettings["artistName"], userID);
        }
    }

    function processIncomingData(data: any) {
        const dataPoints: number[] = []
        const labels: string[] = [];

        for (let i = 0; i < data.length; i++) {
            const played = data[i];

            dataPoints.push(played.y);
            if (props.type === GraphType.Line) {
                const date = new Date(played.label).toLocaleDateString();
                labels.push(date);
                continue;
            }
            // TODO: Do the label length thing on the graph and not here because otherwise it can be scuffed
            //      Ex. The name will be cut at the bottom of the screen (which is what we want)
            //      but then when you hover to see the full name we still get the cut off name (which sucks)
            labels.push(played.label.substring(0, 20));
        }

        setDataPoints(dataPoints);
        setLabels(labels);
    }

    return (
        <>
            {error && (
                <div className="row">
                    <div className="card large error">
                        <section>
                            <p> <span className="icon-alert inverse "></span> {error} </p>
                        </section>
                    </div>
                </div>
            )}
            <ButtonWrapper onClick={handleTimeFrameClick} />
            <InputFieldWrapper update={handleInputFieldUpdate} inputFields={props.inputFields} graphName={props.name} userID={props.userID} />
            {props.type === GraphType.Line ? <Line dataPoints={dataPoints} labels={labels} options={graphOptions} /> : <Bar dataPoints={dataPoints} labels={labels} options={graphOptions} />}
        </>
    );
}

export default GraphWrapper;
