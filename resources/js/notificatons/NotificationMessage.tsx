import { NotificationAPI } from "../api/NotificationAPI";
import { Notification } from "./Notification";

interface NotificationMessageProps {
    notification: Notification
    updateList: (notification: Notification) => void;
};

function NotificationMessage({ notification, updateList }: NotificationMessageProps) {
    async function handleAccept() {
        await NotificationAPI.handleRequest(notification.id, true);
        updateList(notification);
    }

    async function handleDeny() {
        await NotificationAPI.handleRequest(notification.id, false);
        updateList(notification);
    }

    return (
        <div className="row">
            <div className="col-8">
                <a href={`/${notification.username}`}>{notification.username}</a>
                {notification.notification_type_id === 0 &&
                    <span> wants to follow you</span>
                }
                {notification.notification_type_id === 1 &&
                    <span> is now following you</span>
                }
            </div >
            <div className="col-4">
                <i className="fas fa-check" onClick={handleAccept}></i>
                {notification.notification_type_id === 0 &&
                    <i className="fas fa-ban p-2" onClick={handleDeny}></i>
                }
            </div>
        </div >
    )
}

export default NotificationMessage;

