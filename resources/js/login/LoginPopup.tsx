import axios from "axios";
import { useState } from "react";
import { Button, Modal, } from "react-bootstrap";

// TODO: Place all login logic in one file
function LoginPopup() {
    const [show, setShow] = useState(true);
    const [error, setError] = useState<{ [id: string]: string }>({});
    const [inputValues, setInputValues] = useState({});

    function handleSubmit(event: any) {
        event.preventDefault();

        axios.get('/sanctum/csrf-cookie').then(response => {
            if (response.status !== 204) return;

            axios.post("/login", inputValues).then((result) => {
                if (result.status !== 200) return;

                window.location.reload();
            }).catch((error) => {
                setError(error.response.data.errors);
            });
        });

    }
    function handleChange(event: any) {
        const name = event.target.name;
        const value = event.target.value;
        setInputValues(values => ({ ...values, [name]: value }));

    }

    return (
        <Modal show={show} onHide={() => setShow(false)}>
            <Modal.Header closeButton>
                <Modal.Title>Login</Modal.Title>
            </Modal.Header>

            <Modal.Body>
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
                </form>
            </Modal.Body>
            <Modal.Footer>
                <Button variant="secondary" onClick={() => setShow(false)}>Back</Button>
                <input className="btn btn-primary py-0 my-2 w-50 rounded-8" type="submit" value="Login" />
                {/* <Button variant="primary" onClick={login}>Login</Button> */}
            </Modal.Footer>
        </Modal>
    );
}

export default LoginPopup;

