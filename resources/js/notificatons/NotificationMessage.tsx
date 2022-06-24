import { Notification } from "./Notification";
import axios from "axios";

interface NotificationMessageProps {
    notification: Notification;
    isComponent: boolean;
    updateList: () => void;
};

function NotificationMessage({ notification, isComponent, updateList }: NotificationMessageProps) {
    async function handleResponse(response: boolean) {
        const params = {
            notification_id: notification.id,
            response: response
        }

        axios.post("/api/notification/handle", params);
        updateList();
    }

    return (
        <div className="row py-2">
            <div className="col-3 d-md-none">
                {!isComponent &&
                    <img className="w-100 mh-50" src={notification.img_url} alt="Profile img" />
                }
            </div>
            <div className="col-6 col-md-9">
                <a href={`/${notification.username}`}>{notification.username}</a>
                {notification.notification_type_id === 0 &&
                    <span> wants to follow you</span>
                }
                {notification.notification_type_id === 1 &&
                    <span> is now following you</span>
                }
            </div >
            <div className="col-3">
                <i className="fas fa-check" onClick={() => handleResponse(true)}></i>
                {notification.notification_type_id === 0 &&
                    <i className="fas fa-ban p-2" onClick={() => handleResponse(false)}></i>
                }
            </div>
        </div >
    )
}

export default NotificationMessage;

