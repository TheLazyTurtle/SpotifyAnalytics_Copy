import { useState } from "react";
import { inputField } from "./InputFieldWrapper";

interface InputFieldProps {
    inputField: inputField;
    onChange(name: string, value: string | undefined): void;
};

function InputField({ inputField, onChange }: InputFieldProps) {
    const { name, type, placeholder, startValue } = inputField;
    const [value, setValue] = useState<string>(startValue);
    const [data, setData] = useState<{}[]>([]);

    const handleOnChange = async (event: any) => {
        const { value } = event.target;

        setValue(value);

        if (inputField.autocompleteFunction !== undefined) {
            // Update the graph and cache when input field is empty
            if (value.length === 0) {
                onChange(inputField.name, undefined)
            }

            const res = await inputField.autocompleteFunction(value, 10);
            if (res.success) {
                // Item is either a artist or a song
                const names = res.data.map((item: any) => {
                    return {
                        name: item.name,
                        img: item?.img_url,
                        type: item?.type,
                        id: item?.artist_id
                    }
                });
                setData(names);
            } else {
                setData([]);
            }
        } else {
            if (value.length === 0) {
                onChange(inputField.name, undefined)
            } else {
                onChange(inputField.name, value)
            }
        }
    };

    const clickHandler = (event: any) => {
        const { innerHTML } = event.target;

        setData([])
        setValue(innerHTML)
        onChange(inputField.name, innerHTML)
    };

    const inputFieldData = value === undefined ? "" : value;
    return inputField.autocompleteFunction === undefined ? normal(name, type, placeholder, inputFieldData, handleOnChange) : autoComplete(name, type, placeholder, inputFieldData, data, handleOnChange, clickHandler);
}

function normal(name: string, type: string, placeholder: string, value: string, handleOnChange: (event: any) => void) {
    return (
        <input className="form-control" name={name} type={type} placeholder={placeholder} value={value} onChange={handleOnChange} />
    );
}

function autoComplete(name: string, type: string, placeholder: string, value: string, data: {}[], handleOnChange: (event: any) => void, clickHandler: (event: any) => void) {
    return (
        <section className="autocomplete-input-field">
            <input className="form-control" name={name} type={type} placeholder={placeholder} value={value} onChange={handleOnChange} autoComplete="off" />
            {data.length > 0 &&
                <div className="input-field-result-data border w-25 position-absolute background-base">
                    {value.length > 0 && data.map((item: {}, index: number) => autoCompleteRow(index, item, clickHandler))}
                </div>
            }
        </section>
    );
}

function autoCompleteRow(index: number, item: any, clickHandler: (event: any) => void) {
    if (item.type) {
        const href = item.id !== undefined ? `/artist/${item.id}` : `/${item.name}`;
        return (
            <div key={index}>
                <img alt={item.name} src={item.img} className="w-10 d-inline-block" />
                <p key={index} className="text-white px-3 d-inline-block" onClick={clickHandler}><a href={href}>{item.name}</a></p>
            </div>
        );
    }

    return (
        <p key={index} className="text-white px-3" onClick={clickHandler}>{item.name}</p>
    );
}

export default InputField;
