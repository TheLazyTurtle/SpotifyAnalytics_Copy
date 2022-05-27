import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
} from 'chart.js';

import { Bar } from "react-chartjs-2";

ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
);

interface BarGraphProps {
    dataPoints: number[];
    labels: string[];
    options: {};
}

function BarGraph({ dataPoints, labels, options }: BarGraphProps) {
    const colors = ["#6D78AD", "#51CDA0", "#DF7970", "#4C9CA0", "#AE7D99", "#C9D45C", "#5592AD", "#DF874D", "#52BCA8", "#8E7AA3", "#E3CB64", "#C77B85", "#C39762", "#8DD17E", "#B57952", "#FCC26C"]

    const data = {
        labels,
        datasets: [
            {
                data: dataPoints,
                borderColor: colors,
                backgroundColor: colors,
            },
        ],
    };

    return <Bar options={options} data={data} />;
}

export default BarGraph;

