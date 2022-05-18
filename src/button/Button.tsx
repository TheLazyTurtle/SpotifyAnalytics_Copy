import { TimeFrame } from "../dates";
import "../index.css";

interface ButtonProps {
    name: string;
    value: TimeFrame;
    onClick: (value: TimeFrame) => void;
}

function Button(props: ButtonProps) {
    return (
        <button className="btn btn-primary" value={props.value} onClick={() => {props.onClick(props.value)}}>{props.name}</button>
    );
}

export default Button;
