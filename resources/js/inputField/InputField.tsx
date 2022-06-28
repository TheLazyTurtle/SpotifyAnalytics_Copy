import debounce from "lodash/debounce";
import { useCallback, useEffect, useState } from "react";
import { Cacher } from "../cacher";
import { GraphName } from "../graph/GraphWrapper";
import { AutocompleteItem } from "./AutocompleteItem";
import { inputField } from "./InputFieldWrapper";

interface InputFieldProps {
    inputField: inputField;
    isComponent: boolean;
    onChange(name: string, value: string): void;
    isGlobalSearchField?: boolean;
    parentGraphName?: GraphName;
    userId?: string;
};

// TODO: Make this component a but more usable
function InputField({ inputField, isComponent, isGlobalSearchField, onChange, parentGraphName, userId }: InputFieldProps) {
    const { name, allowedInputType, placeholder, filterValue } = inputField;
    const [filterSetting, setFilterSetting] = useState(filterValue);
    const [autocompleteSuggestions, setAutocompleteSuggestions] = useState<AutocompleteItem[]>([]);

    useEffect(() => {
        onChange(inputField.name, filterValue);
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    async function handleOnInputChange(event: any) {
        const { value } = event.target;
        setFilterSetting(value);

        if (inputField.autocompleteFunction !== undefined && value.length > 0) {
            handleAutocompleteInput(value);
            return;
        }

        setAutocompleteSuggestions([]);
        handleUpdateData(value);
    };

    // eslint-disable-next-line react-hooks/exhaustive-deps
    const handleAutocompleteInput = useCallback(
        debounce(async (value) => {
            if (inputField.autocompleteFunction === undefined) {
                return;
            }

            const autoCompleteSuggestionResult = await inputField.autocompleteFunction(value, 10, userId);

            if (autoCompleteSuggestionResult.status !== 200) {
                setAutocompleteSuggestions([]);
                return;
            }

            setAutocompleteSuggestions(autoCompleteSuggestionResult.data.data);
        }, 500),
        []
    );

    // eslint-disable-next-line react-hooks/exhaustive-deps
    const handleUpdateData = useCallback(
        debounce(async (value) => {
            if (value === undefined) {
                return;
            }

            const filterSettingValue = value.length > 0 ? value : undefined;

            // Set cache
            if (parentGraphName !== undefined && userId === undefined) {
                const currentCacheValue = Cacher.getItem(`${parentGraphName}-settings`);
                const updatedCacheValue = { ...currentCacheValue, [name]: filterSettingValue };
                Cacher.setItem(`${parentGraphName}-settings`, updatedCacheValue);
            }

            onChange(inputField.name, filterSettingValue);
        }, 500),
        []
    );

    function clickHandler(event: any) {
        const { innerHTML } = event.target;

        setAutocompleteSuggestions([])
        setFilterSetting(innerHTML)
        handleUpdateData(innerHTML);
    };

    const inputFieldText = filterSetting === undefined ? "" : filterSetting;
    return inputField.autocompleteFunction === undefined ? normal(name, allowedInputType, placeholder, inputFieldText, handleOnInputChange) : autoComplete(name, allowedInputType, placeholder, inputFieldText, autocompleteSuggestions, isComponent, handleOnInputChange, clickHandler, isGlobalSearchField);
}

function normal(name: string, allowedInputType: string, placeholderText: string, value: string, handleOnChange: (event: any) => void) {
    return (
        <input className="form-control" name={name} type={allowedInputType} placeholder={placeholderText} value={value} onChange={handleOnChange} />
    );
}

function autoComplete(name: string, allowedInputType: string, placeholderText: string, inputFieldText: string, autoCompleteSuggestions: AutocompleteItem[], isComponent: boolean, handleOnChange: (event: any) => void, clickHandler: (event: any) => void, isGlobalSearchField?: boolean) {
    return (
        <section className="autocomplete-input-field">
            <input className="form-control" name={name} type={allowedInputType} placeholder={placeholderText} value={inputFieldText} onChange={handleOnChange} autoComplete="off" />
            {(autoCompleteSuggestions.length > 0 && inputFieldText.length > 0 && isComponent) &&
                <>
                    {isGlobalSearchField ? (
                        <div className="input-field-result-data w-25 border position-absolute background-base">
                            {inputFieldText.length > 0 && autoCompleteSuggestions.map((item: AutocompleteItem, index: number) => autoCompleteRow(index, item, true, clickHandler))}
                        </div>
                    ) : (
                        <div className="input-field-result-data col-5 row small-row rounded-8 mx-1 border position-absolute background-base">
                            {inputFieldText.length > 0 && autoCompleteSuggestions.map((item: AutocompleteItem, index: number) => autoCompleteRow(index, item, false, clickHandler))}
                        </div>
                    )}
                </>
            }
            {(autoCompleteSuggestions.length > 0 && !isComponent) &&
                <div className="input-field-result-data border position-absolute background-base">
                    {inputFieldText.length > 0 && autoCompleteSuggestions.map((item: AutocompleteItem, index: number) => autoCompleteRow(index, item, true, clickHandler))}
                </div>
            }
        </section>
    );
}

function autoCompleteRow(index: number, item: AutocompleteItem, isSearch: boolean, clickHandler: (event: any) => void) {
    if (isSearch) {
        const href = item.artist_id === undefined ? `/${item.name}` : `/artist/${item.artist_id}`;

        return (
            <div key={index}>
                <img alt={item.name} src={item.imgUrl} className="w-10 d-inline-block" />
                <p key={index} className="text-white px-3 d-inline-block" onClick={clickHandler}><a href={href}>{item.name}</a></p>
            </div>
        );
    }

    return (
        <p key={index} className="text-white px-3" onClick={clickHandler}>{item.name}</p>
    );
}

export default InputField;
