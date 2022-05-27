import { useEffect, useState } from "react";
import { Cacher } from "../cacher";
import { FilterSetting } from "./FilterSetting";
import InputField from "./InputField";

export type inputField = {
    name: string;
    type: string;
    placeholder: string;
    startValue: string;
    autocompleteFunction?(name: string, limit: string | number): any;
};

interface InputFieldWrapperProps {
    graphName: string;
    update(filterSettings: FilterSetting): void;
    inputFields: inputField[];
};

function InputFieldWrapper({update, inputFields, graphName}: InputFieldWrapperProps) {
    const [filterSettings, setFilterSettings] = useState<FilterSetting>({});
    const [fields, setFields] = useState<inputField[]>(inputFields);
    const [isLoading, setIsLoading] = useState<boolean>(true);

    const onChange = (name: string, value: string) => {
        const updatedSettings = {...filterSettings, [name]: value};

        // TODO: See if we want to directly link a filtersetting to a timeFrame of a graph
        Cacher.setItem(`${graphName}-settings`, updatedSettings);
        setFilterSettings(updatedSettings);

        update(updatedSettings);
    }

    useEffect(() => {
        const cachedFilterSettings = Cacher.getItem(`${graphName}-settings`) as FilterSetting;
        setFilterSettings(cachedFilterSettings);

        const res = inputFields.map((inputField: inputField) => {
            if (Object.keys(cachedFilterSettings).length > 0) {
                inputField.startValue = cachedFilterSettings[inputField.name];
            }
            return inputField;
        });

        setFields(res);

        // TODO: Would love to have this a different thing
        // TODO: With a thing like this if we put it in the dependicies we might not have to do all the things in onChange the way we have it now
        // update(cachedFilterSettings);
        setIsLoading(false);

    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [graphName, inputFields])

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
