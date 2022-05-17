import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
} from 'chart.js';

import { Line } from "react-chartjs-2";

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
);

interface LineGraphProps {
    dataPoints: number[];
    labels: string[];
    options: {};
}

function LineGraph({dataPoints, labels, options}: LineGraphProps) {
    const lineColor = "#1DB954";
    const data = {
        labels,
        datasets: [
            {
                data: dataPoints,
                borderColor: lineColor,
                backgroundColor: lineColor,
            },
        ],
    }

    return <Line options={options} data={data} />;
}

export default LineGraph;
