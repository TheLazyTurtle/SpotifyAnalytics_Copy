import "./Login.css";
import "../index.css";
import { useState } from "react";
import axios from "axios";

function LoginPage() {
    const [error, setError] = useState<{ [id: string]: string }>({});
    const [inputValues, setInputValues] = useState({});

    async function handleSubmit(event: any) {
        event.preventDefault();

        axios.get('/sanctum/csrf-cookie').then(response => {
            if (response.status !== 204) return;

            axios.post("/login", inputValues).then((result) => {
                if (result.status !== 200) return;

                window.location.href = getRedirectUrl();
            }).catch((error) => {
                setError(error.response.data.errors);
            });
        });
    }

    const handleChange = (event: any) => {
        const name = event.target.name;
        const value = event.target.value;
        setInputValues(values => ({ ...values, [name]: value }));
    }

    function getRedirectUrl() {
        const searchParameters = window.location.search.replace("?", "");
        const parameters = searchParameters.split("&");

        // If there is no redirect then just send to home
        if (parameters.length === 0) {
            return "/";
        }

        // Else get redirect url
        for (let i = 0; i < parameters.length; i++) {
            const parameter = parameters[i].split("=");

            if (parameter[0] === "redirect") {
                return `/${parameter[1]}`;
            }
        }

        // If there is no redirect url parameter then just send to home again
        return "/";
    }

    return (
        <div className="text-center login-wrapper mt-sm-0">
            <div className="mx-auto col-sm-3 py-3 bg-white rounded-8">
                <h3>Login</h3>
                <form onSubmit={handleSubmit}>
                    <div className="form-outline">
                        <input className="mt-1 w-50 bg-custom-gray" type="text" name="username" placeholder="Username" onChange={handleChange} /> <br />
                        <div className="form-helper text-danger">{error["username"]?.[0]}</div>
                    </div>
                    <div className="form-outline">
                        <input className="mt-1 w-50 bg-custom-gray" type="password" name="password" placeholder="Password" onChange={handleChange} /> <br />
                        <div className="form-helper text-danger">{error["password"]?.[0]}</div>
                    </div>
                    <div className="form-outline">
                        <input id="remember" className="mt-1 mx-2 bg-custom-gray" type="checkbox" name="remember" onChange={handleChange} />
                        <label htmlFor="remember">Remember me</label><br />
                    </div>
                    <input className="btn btn-primary py-0 my-2 w-50 rounded-8" type="submit" value="Login" />
                </form>

                <p>Don't have an account? Make one <a href="/register" className="text-decoration-none">here</a></p>
            </div>
        </div>
    );
}

export default LoginPage;

