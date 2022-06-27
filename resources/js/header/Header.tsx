import axios from "axios";
import { useState } from "react";
import { PlayedAPI } from "../api/PlayedAPI";
import InputField from "../inputField/InputField";
import { inputField } from "../inputField/InputFieldWrapper";
import NotificationsPopup from "../notificatons/NotificationsPopup";

function Header() {
    const [showNotificationPopup, setNotificationPopup] = useState<boolean>(false);

    const inputField: inputField = {
        name: "search",
        allowedInputType: "text",
        placeholder: "Search",
        filterValue: "",
        autocompleteFunction: PlayedAPI.search
    }

    function toggleNotificationPopup() {
        setNotificationPopup(!showNotificationPopup);
    }

    function handleLogout() {
        axios.post("/logout").then(() => {
            window.location.href = "/login";
        });
    }

    return (
        <>
            <div className="bg-dark w-100 py-2 px-2 d-md-block d-none">
                <div className="row mx-auto w-75">
                    <div className="col-4 d-inline-block">
                        <a href="/" className="text-decoration-none">
                            <h3>Spotify Analytics</h3>
                        </a>
                    </div>
                    <div className="col-4 d-inline-block">
                        <InputField onChange={() => { }} inputField={inputField} isComponent={true} isGlobalSearchField={true} />
                    </div>
                    <div className="col-4 d-inline-block text-center text-custom-green">
                        <i onClick={toggleNotificationPopup} className="fas fa-envelope px-2"></i>
                        {showNotificationPopup && <NotificationsPopup isComponent={true} />}
                        <a href="/profile"><i className="fas fa-user-alt px-2"></i></a>
                        <i onClick={handleLogout} className="fas fa-arrow-right px-2"></i>
                    </div>
                </div>
            </div>
        </>
    );
}

export default Header;
