import "./Login.css";
import "../index.css";
import { useRef, useState } from "react";
import axios from "axios";

function LoginPage() {
    const [error, setError] = useState<string>();
    const usernameElement = useRef<HTMLInputElement | null>(null);
    const passwordElement = useRef<HTMLInputElement | null>(null);

    async function handleOnClick() {
        const username: any = usernameElement.current?.value
        const password: any = passwordElement.current?.value
        setError("");

        if (username === "" || password === "") {
            setError("Not all fields are filled");
            return;
        }

        axios.get('/sanctum/csrf-cookie').then(response => {
            if (response.status !== 204) return;

            axios.post("/login", { username, password }).then((result) => {
                if (result.status !== 200) return;

                window.location.href = getRedirectUrl();
            });
        });
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
                <form>
                    <input className="mt-1 w-50 bg-custom-gray" type="text" name="username" placeholder="Username" ref={usernameElement} /> <br />
                    <input className="mt-1 w-50 bg-custom-gray" type="password" name="password" placeholder="Password" ref={passwordElement} /> <br />
                    <input className="btn btn-primary py-0 my-2 w-50 rounded-8" type="button" onClick={handleOnClick} value="Login" />
                </form>

                {error && <p className="text-danger" role="alert">{error}</p>}
                <p>Don't have an account? Make one here <a href="/register" className="text-decoration-none">here</a></p>
            </div>
        </div>
    );
}

export default LoginPage;

