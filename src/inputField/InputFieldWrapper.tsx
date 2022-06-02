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
    userID?: string;
};

function InputFieldWrapper({ update, inputFields, graphName, userID }: InputFieldWrapperProps) {
    const [filterSettings, setFilterSettings] = useState<FilterSetting>({});
    const [fields, setFields] = useState<inputField[]>(inputFields);
    const [isLoading, setIsLoading] = useState<boolean>(true);

    useEffect(() => {
        if (userID === undefined) {
            const cachedFilterSettings = Cacher.getItem(`${graphName}-settings`) as FilterSetting;
            setFilterSettings(cachedFilterSettings);

            const res = inputFields.map((inputField: inputField) => {
                if (Object.keys(cachedFilterSettings).length > 0) {
                    inputField.startValue = cachedFilterSettings[inputField.name];
                }
                return inputField;
            });

            setFields(res);

            setIsLoading(false);
        } else {
            setFields(inputFields);
            setIsLoading(false);
        }

        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [graphName, inputFields])

    const onChange = (name: string, value: string) => {
        if (userID !== undefined) {
            return;
        }

        const updatedSettings = { ...filterSettings, [name]: value };

        Cacher.setItem(`${graphName}-settings`, updatedSettings);
        setFilterSettings(updatedSettings);

        update(updatedSettings);
    }


    return (
        <div className="inputfield-wrapper">
            <div className="row small-row">
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
