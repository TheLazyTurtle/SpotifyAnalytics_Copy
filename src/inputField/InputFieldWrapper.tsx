import InputField from "./InputField";
import { InputFieldModel } from "./InputFieldModel";

interface InputFieldWrapperProps {
    inputFields: InputFieldModel[];
    onChange(event: any): void;
};

function InputFieldWrapper({ inputFields, onChange }: InputFieldWrapperProps) {
    return (
        <>
            {inputFields.map((inputField) => (
                <InputField key={inputField.name} inputField={inputField} onChange={onChange}/>
            ))}
        </>
    );
}

export default InputFieldWrapper;
