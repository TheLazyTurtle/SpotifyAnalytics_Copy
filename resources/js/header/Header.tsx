import { useContext, useState } from "react";
import { PlayedAPI } from "../api/PlayedAPI";
import { LoggedInUserContext } from "../App";
import InputField from "../inputField/InputField";
import { inputField } from "../inputField/InputFieldWrapper";
import NotificationsPopup from "../notificatons/NotificationsPopup";

function Header() {
    const loggedInUser = useContext(LoggedInUserContext);
    const [showNotificationPopup, setNotificationPopup] = useState<boolean>(true);

    const inputField: inputField = {
        name: "search",
        type: "text",
        placeholder: "Search",
        startValue: "",
        autocompleteFunction: PlayedAPI.search
    }

    function toggleNotificationPopup() {
        setNotificationPopup(!showNotificationPopup);
    }

    function handleLogout() {
        const cookies = document.cookie.split(";");

        for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i];
            const eqPos = cookie.indexOf("=");
            const name = eqPos > -1 ? cookie.substring(0, eqPos) : cookie;
            document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
        }

        window.location.href = "/login";
    }

    return (
        <>
            {!loggedInUser.guest &&
                <div className="bg-dark w-100 py-2 px-2 d-md-block d-none">
                    <div className="row mx-auto w-75">
                        <div className="col-4 d-inline-block">
                            <a href="/" className="text-decoration-none">
                                <h3>Spotify Analytics</h3>
                            </a>
                        </div>
                        <div className="col-4 d-inline-block">
                            <InputField onChange={() => { }} inputField={inputField} isComponent={true} />
                        </div>
                        <div className="col-4 d-inline-block text-center text-custom-green">
                            <i onClick={toggleNotificationPopup} className="fas fa-envelope px-2"></i>
                            {showNotificationPopup && <NotificationsPopup />}
                            <i onClick={() => { window.location.href = "/profile" }} className="fas fa-user-alt px-2"></i>
                            <i onClick={handleLogout} className="fas fa-arrow-right px-2"></i>
                        </div>
                    </div>
                </div>
            }
        </>
    );
}

export default Header;
