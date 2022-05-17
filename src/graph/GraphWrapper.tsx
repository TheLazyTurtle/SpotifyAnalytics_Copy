import { useEffect, useState } from "react";
import Bar from "./BarGraph";
import Line from "./LineGraph";
import { GraphAPI } from "./GraphAPI";

export enum GraphType {
    Line,
    Bar
};

export enum GraphValue {
    allSongsPlayed = "allSongsPlayed",
    topSongs = "topSongs",
    topArtist = "topArtist",
    playedPerDay = "playedPerDay",
}

interface GraphWrapperProps {
    type: GraphType;
    value: GraphValue;
}

// TODO: Figure out if we should put buttons here or somewhere else
// TODO: Figure out if we want to do filter settings here or somewhere else
function GraphWrapper(props: GraphWrapperProps) {
    const [dataPoints, setDataPoints] = useState<number[]>([]);
    const [labels, setLabels] = useState<string[]>([]);
    const [error, setError] = useState<string | undefined>(undefined);
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

    useEffect(() => {
        async function loadGraphData() {
            setLoading(true);
            try {
                let data = getFromCache(props.value);

                if (data === null) {
                    data = await chooseEndPoint(props.value);
                    writeToCache(data, props.value);
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
    }, [])

    function writeToCache(data: any, value: GraphValue) {
        data.unshift({dateAdded: new Date().getTime()})
        localStorage.setItem(value, JSON.stringify(data));
    }

    function getFromCache(value: GraphValue): JSON | null{
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

        console.log(new Date(json[0].dateAdded).getTime() + 1, new Date().getTime())
        if (new Date(json[0].dateAdded).getTime() + 1 >= new Date().getTime()) {
            console.log("update");
            return null;
        }

        return json;
    }

    async function chooseEndPoint(valueType: GraphValue) {
        switch (valueType) {
            case GraphValue.allSongsPlayed:
                return GraphAPI.allSongsPlayed("11182819693", 400)
            case GraphValue.topSongs:
                return GraphAPI.topSongs("11182819693");
            case GraphValue.topArtist:
                return GraphAPI.topArtist("11182819693");
            case GraphValue.playedPerDay:
                return GraphAPI.playedPerDay("11182819693");
        }
    }

    function processIncomingData(data: any) {
        const dataPoints: number[] = []
        const labels: string[] = [];

        if (props.type === GraphType.Bar) {
            data.map((played: any) => {
                dataPoints.push(played.y);
                labels.push(played.label);
            });
        } else {
            data.map((played: any) => {
                const date = new Date(played.x).toLocaleDateString();
                dataPoints.push(played.y);
                labels.push(date);
            });
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
                props.type === GraphType.Line ? <Line dataPoints={dataPoints} labels={labels} options={options} /> : <Bar dataPoints={dataPoints} labels={labels} options={options}/>
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
