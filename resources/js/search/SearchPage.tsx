import { PlayedAPI } from "../api/PlayedAPI";
import InputField from "../inputField/InputField";
import { inputField } from "../inputField/InputFieldWrapper";

function SearchPage() {
    const inputField: inputField = {
        name: "search",
        allowedInputType: "text",
        placeholder: "Search",
        filterValue: "",
        autocompleteFunction: PlayedAPI.search
    }

    return (
        <InputField onChange={() => { }} inputField={inputField} isComponent={false} />
    );
}

export default SearchPage;

