import { useState } from "react";
import { Cacher } from "../cacher";
import { GraphName } from "../graph/GraphWrapper";
import { FilterSetting } from "./FilterSetting";
import InputField from "./InputField";

export type inputField = {
    name: string;
    allowedInputType: string;
    placeholder: string;
    filterValue: string;
    autocompleteFunction?(name: string, limit: string | number): any;
};

interface InputFieldWrapperProps {
    graphName: GraphName;
    update(filterSettings: FilterSetting): void;
    inputFields: inputField[];
    userId?: string;
};

function InputFieldWrapper({ update, inputFields, graphName }: InputFieldWrapperProps) {
    const [filterSettings, setFilterSettings] = useState<FilterSetting>({});
    const cachedFilterSettings = Cacher.getItem(`${graphName}-settings`);

    const handleInputChange = (name: string, value: string) => {
        let temp = filterSettings;
        temp[name] = value;
        setFilterSettings(temp);
        update(temp);
    }

    return (
        <div className="inputfield-wrapper">
            <div className="row small-row">
                {inputFields.map((inputField: inputField, index: number) => {
                    inputField.filterValue = cachedFilterSettings[inputField.name];

                    return (<div className="col-sm" key={index}>
                        <InputField key={inputField.name} inputField={inputField} onChange={handleInputChange} isComponent={true} parentGraphName={graphName} />
                    </div>);
                })}
            </div>
        </div>
    );
}

export default InputFieldWrapper;
