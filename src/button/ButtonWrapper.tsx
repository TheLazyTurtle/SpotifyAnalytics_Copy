import { TimeFrame } from "../dates";
import Button from "./Button";

interface ButtonWrapperProps {
    onClick: (value: TimeFrame) => void;
}

function ButtonWrapper({onClick}: ButtonWrapperProps) {
    return (
        <>
        {
            (Object.keys(TimeFrame) as Array<keyof typeof TimeFrame>).map((timeFrame) => (
                <Button key={timeFrame} name={TimeFrame[timeFrame]} value={TimeFrame[timeFrame]} onClick={onClick}/>
            ))
        }
        </>
    );
}

export default ButtonWrapper;
