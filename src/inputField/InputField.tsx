import { useState } from "react";
import { inputField } from "./InputFieldWrapper";

interface InputFieldProps {
    inputField: inputField;
    onChange(event: any): void;
};

function InputField({inputField, onChange}: InputFieldProps) {
    const {name, type, placeholder, startValue} = inputField;
    const [value, setValue] = useState<string | number>(startValue);

    const handleOnChange = (event: any) => {
        const {value} = event.target;
        setValue(value);

        onChange(event)
    }

    return (
        <input className="form-control" name={name} type={type} placeholder={placeholder} value={value} onChange={handleOnChange}/>
    );
}

export default InputField;
