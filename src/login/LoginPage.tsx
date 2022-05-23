import "./Login.css";
import "../index.css";
import { useRef, useState } from "react";
import { LoginAPI } from "./LoginAPI";

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

        const result = await LoginAPI.login(username, password);
        if (result instanceof(Error)) {
            setError("Error logging you in");
        }
        console.log(result)
    }

    return (
        <div className="text-center login-wrapper mt-sm-0">
            <div className="mx-auto col-sm-3 py-3 bg-white rounded-8">
                <h3>Login</h3>
                <input className="mt-1 w-50 bg-custom-gray" type="text" name="username" placeholder="Username" ref={usernameElement}/> <br />
                <input className="mt-1 w-50 bg-custom-gray" type="password" name="password" placeholder="Password" ref={passwordElement}/> <br />
                <button className="btn btn-primary py-0 my-2 w-50 rounded-8" onClick={handleOnClick}>Login</button>
                {error && <p className="text-danger" role="alert">{error}</p>}
                <p>Don't have an account? Make one here <a href="/register" className="text-decoration-none">here</a></p>
            </div>
        </div>
    );
}

export default LoginPage;

