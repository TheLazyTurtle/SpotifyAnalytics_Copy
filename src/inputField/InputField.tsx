import { useState } from "react";
import { AutocompleteAPI } from "./AutocompleteAPI";
import { inputField } from "./InputFieldWrapper";

interface InputFieldProps {
    inputField: inputField;
    onChange(name: string, value: string): void;
};

function InputField({ inputField, onChange }: InputFieldProps) {
    const { name, type, placeholder, startValue } = inputField;
    const [value, setValue] = useState<string>(startValue);
    const [data, setData] = useState<string[]>([]);

    const handleOnChange = async (event: any) => {
        const { value } = event.target;

        setValue(value);

        if (inputField.autocompleteUrl !== "") {
            // Update the graph and cache when input field is empty
            if (value.length === 0) {
                onChange(inputField.name, value)
            }

            const res = await AutocompleteAPI.autocomplete(inputField.autocompleteUrl, "11182819693", value, 10);
            setData(res);
        } else {
            onChange(inputField.name, value)
        }
    };

    const clickHandler = (event: any) => {
        const { innerHTML } = event.target;

        setData([])
        setValue(innerHTML)
        onChange(inputField.name, innerHTML)
    };

    return inputField.autocompleteUrl === "" ? normal(name, type, placeholder, value, handleOnChange) : autoComplete(name, type, placeholder, value, data, handleOnChange, clickHandler);
}

function normal(name: string, type: string, placeholder: string, value: string, handleOnChange: (event: any) => void) {
    return (
        <input className="form-control" name={name} type={type} placeholder={placeholder} value={value} onChange={handleOnChange} />
    );
}

function autoComplete(name: string, type: string, placeholder: string, value: string, data: string[], handleOnChange: (event: any) => void, clickHandler: (event: any) => void) {
    // TODO: The css needs to make the autocomplete thing hover above the graph instead of pushing everything down
    return (
        <section className="autocomplete-input-field">
            <input className="form-control" name={name} type={type} placeholder={placeholder} value={value} onChange={handleOnChange} autoComplete="off" />
            <div className="input-field-result-data">
                {value.length > 0 && data.map((d: string, index: number) => (
                    <p key={index} onClick={clickHandler}>{d}</p>
                ))}
            </div>
        </section>
    );
}

export default InputField;
