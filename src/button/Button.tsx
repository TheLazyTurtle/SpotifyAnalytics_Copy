import "../index.css";

interface ButtonProps {
    name: string;
    value: string;
    onClick: () => void;
}

function Button(props: ButtonProps) {
    return (
        <button className="btn btn-primary" value={props.value} onClick={props.onClick}>{props.name}</button>
    );
}

export default Button;
