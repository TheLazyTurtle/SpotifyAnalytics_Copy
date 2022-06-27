import axios from "axios";
import { useState } from "react";

function RegisterPage() {
    const [error, setError] = useState<{ [id: string]: string }>({});
    const [inputValues, setInputValues] = useState({});

    function handleSubmit(event: any) {
        event.preventDefault();

        axios.post("/register", inputValues).then((result) => {
            if (result.status === 201) {
                window.location.href = "/addSpotifyTokens";
            }
        }).catch((error) => {
            setError(error.response.data.errors);
        });
    }

    const handleChange = (event: any) => {
        const name = event.target.name;
        const value = event.target.value;
        setInputValues(values => ({ ...values, [name]: value }));
    }

    return (
        <div className="text-center login-wrapper mt-sm-0">
            <div className="mx-auto col-sm-3 py-3 bg-white rounded-8">
                <h3>Register</h3>
                <form onSubmit={handleSubmit}>
                    <div className="form-outline">
                        <input className="mt-1 w-50 bg-custom-gray" type="text" name="firstName" placeholder="Firstname" onChange={handleChange} /> <br />
                        <div className="form-helper text-danger">{error["firstName"]?.[0]}</div>
                    </div>
                    <div className="form-outline">
                        <input className="mt-1 w-50 bg-custom-gray" type="text" name="lastName" placeholder="Lastname" onChange={handleChange} /> <br />
                        <div className="form-helper text-danger">{error["lastName"]?.[0]}</div>
                    </div>
                    <div className="form-outline">
                        <input className="mt-1 w-50 bg-custom-gray" type="text" name="username" placeholder="Username" onChange={handleChange} /> <br />
                        <div className="form-helper text-danger">{error["username"]?.[0]}</div>
                    </div>
                    <div className="form-outline">
                        <input className="mt-1 w-50 bg-custom-gray" type="email" name="email" placeholder="Email" onChange={handleChange} /> <br />
                        <div className="form-helper text-danger">{error["email"]?.[0]}</div>
                    </div>
                    <div className="form-outline">
                        <input className="mt-1 w-50 bg-custom-gray" type="password" name="password" placeholder="Password" onChange={handleChange} /> <br />
                        <div className="form-helper text-danger">{error["password"]?.[0]}</div>
                    </div>
                    <div className="form-outline">
                        <input className="mt-1 w-50 bg-custom-gray" type="password" name="password_confirmation" placeholder="Password" onChange={handleChange} /> <br />
                        <div className="form-helper text-danger">{error["password_confirmation"]?.[0]}</div>
                    </div>
                    <div className="form-outline">
                        <input className="mt-1 mx-2 bg-custom-gray" type="checkbox" name="acceptPrivacy" placeholder="Accept privacy" onChange={handleChange} />Accept the privacy (violation xD) <br />
                        <div className="form-helper text-danger">{error["acceptPrivacy"]?.[0]}</div>
                    </div>
                    <input className="btn btn-primary py-0 my-2 w-50 rounded-8" type="submit" value="Register" />
                </form>

                <p>Already have an account? Log in <a href="/login" className="text-decoration-none">here</a></p>
            </div>
        </div >
    );
}

export default RegisterPage;

