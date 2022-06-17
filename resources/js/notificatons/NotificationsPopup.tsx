import { useEffect, useState } from "react";
import { NotificationAPI } from "../api/NotificationAPI";
import { Notification } from "./Notification";
import NotificationMessage from "./NotificationMessage";

interface NotificationsPopupProps {
    isComponent: boolean;
};

function NotificationsPopup({ isComponent }: NotificationsPopupProps) {
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

    if (isComponent) {
        return (
            <div className="w-10 bg-white floating-window">
                {!loading && notifications.map((notification: Notification, index: number) => (
                    <NotificationMessage key={index} notification={notification} updateList={updateNotificationList} isComponent={isComponent} />
                ))}
            </div>
        );
    }

    return (
        <div className="mx-2 text-custom-green">
            {!loading && notifications.map((notification: Notification, index: number) => (
                <NotificationMessage key={index} notification={notification} updateList={updateNotificationList} isComponent={isComponent} />
            ))}
        </div>
    );
}

export default NotificationsPopup; 
