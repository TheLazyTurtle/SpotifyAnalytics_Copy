import { NotificationAPI } from "../api/NotificationAPI";
import { Notification } from "./Notification";

interface NotificationMessageProps {
    notification: Notification;
    isComponent: boolean;
    updateList: (notification: Notification) => void;
};

function NotificationMessage({ notification, isComponent, updateList }: NotificationMessageProps) {
    async function handleAccept() {
        await NotificationAPI.handleRequest(notification.id, true);
        updateList(notification);
    }

    async function handleDeny() {
        await NotificationAPI.handleRequest(notification.id, false);
        updateList(notification);
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
                <i className="fas fa-check" onClick={handleAccept}></i>
                {notification.notification_type_id === 0 &&
                    <i className="fas fa-ban p-2" onClick={handleDeny}></i>
                }
            </div>
        </div >
    )
}

export default NotificationMessage;

