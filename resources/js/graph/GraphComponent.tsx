import {
    Chart as ChartJS,
    BarElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
} from 'chart.js';
import { Line } from "react-chartjs-2";
import { Bar } from "react-chartjs-2";
import { GraphDataType } from './GraphWrapper';
import { Played } from './Played';

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    Title,
    Tooltip,
    Legend
);

interface GraphComponentProps {
    dataPoints?: Played[];
    graphType: GraphDataType;
};

function GraphComponent({ dataPoints, graphType }: GraphComponentProps) {
    const { labels, dataPointValues } = processDataPoints(graphType, dataPoints);
    const colors = ["#6D78AD", "#51CDA0", "#DF7970", "#4C9CA0", "#AE7D99", "#C9D45C", "#5592AD", "#DF874D", "#52BCA8", "#8E7AA3", "#E3CB64", "#C77B85", "#C39762", "#8DD17E", "#B57952", "#FCC26C"];
    const lineColor = "#1DB954";

    function processDataPoints(graphType: GraphDataType, dataPoints?: Played[]) {
        let labels: string[] = [];
        let dataPointValues: number[] = [];

        if (dataPoints !== undefined) {
            if (graphType === GraphDataType.Bar) {
                dataPoints?.forEach((dataPoint: Played) => {
                    labels.push(dataPoint.x);
                    dataPointValues.push(+dataPoint.y);
                });
            } else {
                dataPoints?.forEach((dataPoint: Played) => {
                    labels.push(new Date(+dataPoint.x).toLocaleDateString());
                    dataPointValues.push(+dataPoint.y);
                });
            }
        }

        return {
            labels,
            dataPointValues
        }
    }

    function getDatasetOptionsBar() {
        return {
            labels,
            datasets: [
                {
                    data: dataPointValues,
                    borderColor: colors,
                    backgroundColor: colors
                },
            ],
        }
    }

    function getDatasetOptionsLine() {
        return {
            labels,
            datasets: [
                {
                    data: dataPointValues,
                    borderColor: lineColor,
                    backgroundColor: lineColor
                },
            ],
        }
    }

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
        scales: {
            x: {
                ticks: {
                    callback: (value: string | number) => {
                        const label = labels[+value];
                        const filter = ["(", "-", "|"];

                        for (let i = 0; i < filter.length; i++) {
                            if (label.indexOf(filter[i]) >= 0 && label.length >= 6) {
                                return label.substring(0, label.indexOf(filter[i]));
                            }
                        };
                        return label.substring(0, 20);
                    }
                }
            },
            y: {
                beginAtZero: true,
            }
        }
    };

    return (
        <>
            {graphType === GraphDataType.Bar &&
                <Bar options={options} data={getDatasetOptionsBar()} />
            }
            {graphType === GraphDataType.Line &&
                <Line options={options} data={getDatasetOptionsLine()} />
            }
        </>
    );
}

export default GraphComponent;
