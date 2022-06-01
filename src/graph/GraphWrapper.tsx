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
};

// TODO: Make this component more useable. It is kinda big
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

    useEffect(() => {
        const cachedFilterSettings = Cacher.getItem(`${props.name}-settings`) as FilterSetting;
        setFilterSettings(cachedFilterSettings);
        loadGraphData(cachedFilterSettings);

        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [props.value, timeFrame]);

    const handleTimeFrameClick = (value: TimeFrame) => {
        setTimeFrame(value);
    }

    function handleUpdate(filterSettings: FilterSetting) {
        setFilterSettings(filterSettings);
        loadGraphData(filterSettings, true);
    }

    async function loadGraphData(filterSettings: FilterSetting, force: boolean = false) {
        try {
            // TODO: Change this so it will only get the cache when it is not a force
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

    async function chooseEndPoint(valueType: GraphValue, timeFrame: TimeFrame, filterSettings: { [id: string]: string }) {
        const { minDate, maxDate } = convertTime(timeFrame);

        switch (valueType) {
            case GraphValue.allSongsPlayed:
                return PlayedAPI.allSongsPlayed(minDate, maxDate, filterSettings["minPlayed"], filterSettings["maxPlayed"])
            case GraphValue.topSongs:
                return PlayedAPI.topSongs(minDate, maxDate, filterSettings["artistName"]);
            case GraphValue.topArtist:
                return PlayedAPI.topArtist(minDate, maxDate, filterSettings["amount"]);
            case GraphValue.playedPerDay:
                return PlayedAPI.playedPerDay(minDate, maxDate, filterSettings["songName"], filterSettings["artistName"]);
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
            <InputFieldWrapper update={handleUpdate} inputFields={props.inputFields} graphName={props.name} />
            {props.type === GraphType.Line ? <Line dataPoints={dataPoints} labels={labels} options={graphOptions} /> : <Bar dataPoints={dataPoints} labels={labels} options={graphOptions} />}
        </>
    );
}

export default GraphWrapper;
