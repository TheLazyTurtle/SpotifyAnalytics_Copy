import { useEffect, useState } from "react";
import { NotificationAPI } from "../api/NotificationAPI";
import { Notification } from "./Notification";
import NotificationMessage from "./NotificationMessage";

function NotificationsPopup() {
    const [loading, setLoading] = useState<boolean>(true);
    const [notifications, setNotifications] = useState<Notification[]>([]);

    useEffect(() => {
        getNotifications();
    }, []);

    async function getNotifications() {
        setLoading(true);
        const notifications = await NotificationAPI.getNotifications();

        if (notifications.success) {
            setNotifications(notifications.data);
        }

        setLoading(false);
    }

    function updateNotificationList(notificationToRemove: Notification) {
        setLoading(true);
        setNotifications(current =>
            current.filter(notification => {
                return notification !== notificationToRemove;
            }));
        setLoading(false);
    }

    return (
        <div className="w-10 bg-white floating-window">
            {!loading && notifications.map((notification: Notification, index: number) => (
                <NotificationMessage key={index} notification={notification} updateList={updateNotificationList} />
            ))}
        </div>
    );
}

export default NotificationsPopup; 
