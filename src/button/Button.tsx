import { TimeFrame } from "../dates";
import "../index.css";

interface ButtonProps {
    name: string;
    value: TimeFrame;
    onClick: (value: TimeFrame) => void;
}

function Button(props: ButtonProps) {
    return (
        <button className="btn btn-sm btn-primary mx-1 my-1" value={props.value} onClick={() => {props.onClick(props.value)}}>{props.name}</button>
    );
}

export default Button;
