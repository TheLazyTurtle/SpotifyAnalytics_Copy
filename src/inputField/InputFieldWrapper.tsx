import { useEffect, useState } from "react";
import InputField from "./InputField";

export type inputField = {
    name: string;
    type: string;
    placeholder: string;
    startValue: string;
    autocompleteUrl: string;
};

interface InputFieldWrapperProps {
    graphName: string;
    update(filterSettings: {[id: string]: string}): void;
    inputFields: inputField[];
};

function InputFieldWrapper({update, inputFields, graphName}: InputFieldWrapperProps) {
    const [filterSettings, setFilterSettings] = useState<{[id: string]: string}>({});
    const [fields, setFields] = useState<inputField[]>(inputFields);
    const [isLoading, setIsLoading] = useState<boolean>(true);

    const onChange = (name: string, value: string) => {
        const updatedSettings = {...filterSettings, [name]: value};

        writeFilterSettingsToCache(graphName, updatedSettings);
        setFilterSettings(updatedSettings);

        update(updatedSettings);
    }

    useEffect(() => {
        const cachedFilterSettings = getFilterSettingsFromCache(graphName);
        setFilterSettings(cachedFilterSettings);

        const res = inputFields.map((inputField: inputField) => {
            inputField.startValue = cachedFilterSettings[inputField.name];
            return inputField;
        });

        setFields(res);

        // TODO: Would love to have this a different thing
        // TODO: With a thing like this if we put it in the dependicies we might not have to do all the things in onChange the way we have it now
        // update(cachedFilterSettings);
        setIsLoading(false);

    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [graphName, inputFields])

    function writeFilterSettingsToCache(graphName: string, filterSettings: {[id: string]: string | undefined}) {
        const name = `${graphName}-settings`

        localStorage.setItem(name, JSON.stringify(filterSettings))
    }

    function getFilterSettingsFromCache(graphName: string) {
        const name = `${graphName}-settings`;

        return JSON.parse(localStorage.getItem(name) || "{}");
    }

    return (
        <div className="inputfield-wrapper">
            <div className="row">
                {!isLoading && fields.map((inputField: inputField, index: number) => (
                    <div className="col-sm" key={index}>
                        <InputField key={inputField.name} inputField={inputField} onChange={onChange} />
                    </div>
                ))}
            </div>
        </div>
    );
}

export default InputFieldWrapper;
