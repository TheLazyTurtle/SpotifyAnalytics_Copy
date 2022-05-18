import { useState } from "react";
import { InputFieldModel } from "./InputFieldModel";

interface InputFieldProps {
    inputField: InputFieldModel;
    onChange(event: any): void;
}

function InputField({ inputField, onChange }: InputFieldProps) {
    const {name, type, placeholder, min, value} = inputField;
    // const [value, setValue] = useState<string>(startValue);

    const handleOnChange = (event: any) => {
        // const {value} = event.target;
        // setValue(value);

        onChange(event)
    }

    return (
        min === undefined ? <input className="form-control" name={name} type={type} placeholder={placeholder} value={value} onChange={handleOnChange} /> : <input className="form-control" name={value} type={type} placeholder={placeholder} value={value} min={min} onChange={handleOnChange} />
    );
}

export default InputField;
