import axios from "axios";
import { useState } from "react";

function RegisterPage() {
    const [error, setError] = useState();
    const [inputValues, setInputValues] = useState({});

    function handleSubmit(event: any) {
        event.preventDefault();

        axios.post("/register", inputValues).then((result) => {
            console.log(result)
        }).catch((error) => {
            console.log(error.response.data.errors);
            setError(error.response.data.errors)
        });
    }

    const handleChange = (event: any) => {
        const name = event.target.name;
        const value = event.target.value;
        setInputValues(values => ({ ...values, [name]: value }))
    }

    return (
        <div className="text-center login-wrapper mt-sm-0">
            <div className="mx-auto col-sm-3 py-3 bg-white rounded-8">
                <h3>Login</h3>
                <form onSubmit={handleSubmit}>
                    <input className="mt-1 w-50 bg-custom-gray" type="text" name="firstName" placeholder="Firstname" onChange={handleChange} /> <br />
                    <input className="mt-1 w-50 bg-custom-gray" type="text" name="lastName" placeholder="Lastname" onChange={handleChange} /> <br />
                    <input className="mt-1 w-50 bg-custom-gray" type="text" name="username" placeholder="Username" onChange={handleChange} /> <br />
                    <input className="mt-1 w-50 bg-custom-gray" type="email" name="email" placeholder="Email" onChange={handleChange} /> <br />
                    <input className="mt-1 w-50 bg-custom-gray" type="password" name="password" placeholder="Password" onChange={handleChange} /> <br />
                    <input className="mt-1 w-50 bg-custom-gray" type="password" name="repeatPassword" placeholder="Password" onChange={handleChange} /> <br />
                    <input className="mt-1 w-50 bg-custom-gray" type="checkbox" name="acceptPrivacy" placeholder="Accept privacy" onChange={handleChange} /> <br />
                    <input className="btn btn-primary py-0 my-2 w-50 rounded-8" type="submit" value="Login" />
                </form>

                {error && <p className="text-danger" role="alert">{error}</p>}
                <p>Already have an account? Log in <a href="/login" className="text-decoration-none">here</a></p>
            </div>
        </div>
    );
}

export default RegisterPage;

