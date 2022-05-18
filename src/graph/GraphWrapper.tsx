import { useEffect, useState } from "react";
import Bar from "./BarGraph";
import Line from "./LineGraph";
import { GraphAPI } from "./GraphAPI";
import ButtonWrapper from "../button/ButtonWrapper";
import { TimeFrame, convertTime} from "../dates";

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
    type: GraphType;
    value: GraphValue;
};

// TODO: Figure out if we should put buttons here or somewhere else
// TODO: Figure out if we want to do filter settings here or somewhere else
function GraphWrapper(props: GraphWrapperProps) {
    const [timeFrame, setTimeFrame] = useState<TimeFrame>(TimeFrame.year);
    const [dataPoints, setDataPoints] = useState<number[]>([]);
    const [labels, setLabels] = useState<string[]>([]); const [error, setError] = useState<string | undefined>(undefined);
    const [loading, setLoading] = useState(false);

    const options = {
        responsive: true,
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

    useEffect(() => {
        async function loadGraphData() {
            setLoading(true);
            try {
                let data = getFromCache(props.value, timeFrame);

                if (data === null) {
                    data = await chooseEndPoint(props.value, timeFrame);

                    if (data !== null) {
                        writeToCache(data, props.value, timeFrame);
                    } else {
                        data = JSON.parse("{}");
                    }
                }

                setError("");
                processIncomingData(data);
            } catch (e) {
                if (e instanceof Error) {
                    setError(e.message);
                }
            } finally {
                setLoading(false);
            }
        }

        loadGraphData();
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [props.value, timeFrame])

    function writeToCache(data: any, value: GraphValue, timeFrame: TimeFrame) {
        const existingData = localStorage.getItem(value);
        const wrapper: any = {}
        wrapper[timeFrame] = { dateAdded: new Date().getTime(), data };

        if (existingData === null) {
            localStorage.setItem(value, JSON.stringify(wrapper));
            return;
        }

        const json = JSON.parse(existingData);
        json[timeFrame] = { dateAdded: new Date().getTime(), data };
        localStorage.setItem(value, JSON.stringify(json));
    }

    function getFromCache(value: GraphValue, timeFrame: TimeFrame): JSON | null {
        // If the hour has just changed update because your data has also just updated
        var minutes = new Date().getMinutes();
        if (minutes >= 0 && minutes <= 3) {
            return null;
        }

        const data = localStorage.getItem(value);

        if (data === null) {
            return null;
        }

        const json = JSON.parse(data);

        if (json[timeFrame] === undefined) {
            return null;
        }

        if (new Date(json[timeFrame].dateAdded).getTime() + 3600000 <= new Date().getTime()) {
            return null;
        }

        return json[timeFrame]["data"];
    }

    async function chooseEndPoint(valueType: GraphValue, timeFrame: TimeFrame) {
        const {minDate, maxDate} = convertTime(timeFrame);

        switch (valueType) {
            case GraphValue.allSongsPlayed:
                return GraphAPI.allSongsPlayed("11182819693", minDate, maxDate, 400)
            case GraphValue.topSongs:
                return GraphAPI.topSongs("11182819693", minDate, maxDate);
            case GraphValue.topArtist:
                return GraphAPI.topArtist("11182819693", minDate, maxDate);
            case GraphValue.playedPerDay:
                return GraphAPI.playedPerDay("11182819693", minDate, maxDate);
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
            labels.push(played.label);
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
            {!loading && !error &&
                <section>
                    <ButtonWrapper onClick={handleTimeFrameClick} />
                    {props.type === GraphType.Line ? <Line dataPoints={dataPoints} labels={labels} options={options} /> : <Bar dataPoints={dataPoints} labels={labels} options={options} />}
                </section>
            }
            {loading && (
                <div className="center-page">
                    <span className="spinner primary"></span>
                    <p>Loading...</p>
                </div>
            )}
        </>
    );
}

export default GraphWrapper;
