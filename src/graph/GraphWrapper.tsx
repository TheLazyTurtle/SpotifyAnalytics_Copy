import { useEffect, useState } from "react";
import Bar from "./BarGraph";
import Line from "./LineGraph";
import ButtonWrapper from "../button/ButtonWrapper";
import InputFieldWrapper, { inputField } from "../inputField/InputFieldWrapper";
import { GraphAPI } from "./GraphAPI";
import { TimeFrame, convertTime} from "../dates";
import "./Graph.css";
import { Cacher } from "../cacher";

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
    const [filterSettings, setFilterSetting] = useState<{[id: string]: string}>({});
    const [timeFrame, setTimeFrame] = useState<TimeFrame>(TimeFrame.year);
    const [dataPoints, setDataPoints] = useState<number[]>([]);
    const [labels, setLabels] = useState<string[]>([]); 
    const [error, setError] = useState<string | undefined>(undefined);

    const options = {
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

    const handleTimeFrameClick = (value: TimeFrame) => {
        setTimeFrame(value);
    }

    async function loadGraphData(filterSettings: {[id: string]: string}, force: boolean = false) {
        try {
            // TODO: Change this so it will only get the cache when it is not a force
            let data = Cacher.getItem(props.value, true, timeFrame);

            if (Object.keys(data).length <= 0 || force) {
                data = await chooseEndPoint(props.value, timeFrame, filterSettings);

                if (data !== null) {
                    Cacher.setItem(props.value, data, timeFrame);
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

    useEffect(() => {
        loadGraphData(filterSettings);
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [props.value, timeFrame])

    async function chooseEndPoint(valueType: GraphValue, timeFrame: TimeFrame, filterSettings: {[id: string]: string}) {
        const {minDate, maxDate} = convertTime(timeFrame);

        switch (valueType) {
            case GraphValue.allSongsPlayed:
                return GraphAPI.allSongsPlayed("11182819693", minDate, maxDate, filterSettings["minPlayed"], filterSettings["maxPlayed"])
            case GraphValue.topSongs:
                return GraphAPI.topSongs("11182819693", minDate, maxDate, filterSettings["artistName"]);
            case GraphValue.topArtist:
                return GraphAPI.topArtist("11182819693", minDate, maxDate, filterSettings["amount"]);
            case GraphValue.playedPerDay:
                return GraphAPI.playedPerDay("11182819693", minDate, maxDate, filterSettings["songName"], filterSettings["artistName"]);
        }
    }

    function processIncomingData(data: any) {
        const dataPoints: number[] = []
        const labels: string[] = [];

        for (let i = 0; i < data.length; i++) {
            const played = data[i];

            dataPoints.push(played.y);
            if (props.type === GraphType.Line) {
                const date = new Date(played.x).toLocaleDateString();
                labels.push(date);
                continue;
            }
            labels.push(played.label.substring(0, 20));
        }

        setDataPoints(dataPoints);
        setLabels(labels);
    }

    function handleUpdate(filterSettings: {[id: string]: string}) {
        setFilterSetting(filterSettings);
        loadGraphData(filterSettings, true);
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
            <InputFieldWrapper update={handleUpdate} inputFields={props.inputFields} graphName={props.name}/>
            {props.type === GraphType.Line ? <Line dataPoints={dataPoints} labels={labels} options={options} /> : <Bar dataPoints={dataPoints} labels={labels} options={options} />}
        </>
    );
}

export default GraphWrapper;
