import { useEffect, useState } from "react";
import Bar from "./BarGraph";
import Line from "./LineGraph";
import ButtonWrapper from "../button/ButtonWrapper";
import InputFieldWrapper from "../inputField/InputFieldWrapper";
import { GraphAPI } from "./GraphAPI";
import { TimeFrame, convertTime} from "../dates";
import { InputFieldModel } from "../inputField/InputFieldModel";

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

// TODO: Make this component more useable. It is kinda big
function GraphWrapper(props: GraphWrapperProps) {
    const [filterSettings, setFilterSettings] = useState<{[id: string]: string | undefined}>({});
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

    const handleInputFieldChange = (event: any) => {
        const {name, value}: {name: string, value: string} = event.target;

        setFilterSettings((settings) => {
            const update = {...settings, [name]: value}
            writeFilterSettingsToCache(props.value, update)
            return update 
        });
    }

    async function loadGraphData(force: boolean = false) {
        setLoading(true);
        try {
            let data = getFromCache(props.value, timeFrame);

            if (data === null || force) {
                data = await chooseEndPoint(props.value, timeFrame, filterSettings);

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

    useEffect(() => {
        loadGraphData();
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [props.value, timeFrame])

    useEffect(() => {
        loadGraphData(true);
        //TODO: Reset filtersettings
        const filterSettings = getFilterSettingsFromCache(props.value);
        // setFilterSettings(filterSettings);

        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [filterSettings])

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

    function writeFilterSettingsToCache(value: GraphValue, filterSettings: {[id: string]: string | undefined}) {
        const name = `setting-${value}`;

        localStorage.setItem(name, JSON.stringify(filterSettings))
    }

    function getFilterSettingsFromCache(value: GraphValue) {
        const name = `setting-${value}`;

        return JSON.parse(localStorage.getItem(name) || "{}");
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

    async function chooseEndPoint(valueType: GraphValue, timeFrame: TimeFrame, filterSettings: {[id: string]: string | undefined}) {
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
            labels.push(played.label);
        }

        setDataPoints(dataPoints);
        setLabels(labels);
    }
    let test = new InputFieldModel();
    test.placeholder = "Artist";
    test.name = "artist";
    test.value = "Alan walker";
    test.type = "text";

    let test2 = new InputFieldModel();
    test2.placeholder = "Amount";
    test2.name = "amount";
    test2.value = "10";
    test2.type = "number";

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
                    <InputFieldWrapper inputFields={[test, test2]} onChange={handleInputFieldChange}/>
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
