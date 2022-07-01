import { useQuery } from "react-query";
import { Notification } from "./Notification";
import axios from "axios";
import NotificationMessage from "./NotificationMessage";
import { Response } from "../App";

interface NotificationsPopupProps {
    isComponent: boolean;
};

function NotificationsPopup({ isComponent }: NotificationsPopupProps) {
    const { isLoading, data, refetch } = useQuery("Notifications", () => axios.get<Response<Notification[]>>(`/api/notification`).then((response) => response.data.data));

    function updateNotificationList() {
        refetch();
    }

    if (isComponent) {
        return (
            <div className="w-10 bg-white floating-window">
                {!isLoading && data?.map((notification: Notification, index: number) => (
                    <NotificationMessage key={index} notification={notification} updateList={updateNotificationList} isComponent={isComponent} />
                ))}
                {data?.length === 0 && <p>No notifications</p>}
            </div>
        );
    }

    return (
        <div className="mx-2 text-custom-green">
            {!isLoading && data?.map((notification: Notification, index: number) => (
                <NotificationMessage key={index} notification={notification} updateList={updateNotificationList} isComponent={isComponent} />
            ))}
            {data?.length === 0 && <p>No notifications</p>}
        </div>
    );
}

export default NotificationsPopup; 
