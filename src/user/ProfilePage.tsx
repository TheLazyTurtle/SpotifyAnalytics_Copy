import { useContext, useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { UserAPI } from "../api/UserAPI";
import { LoggedInUserContext } from "../App";
import ProfilePageBody from "./ProfilePageBody";
import ProfilePageHeader from "./ProfilePageHeader";
import { User } from "./User";

export enum PageType {
    Personal,
    External,
};

function ProfilePage() {
    const loggedInUser = useContext(LoggedInUserContext);
    const [pageType, setPageType] = useState<PageType>(PageType.External);
    const [user, setUser] = useState<User>({} as User);
    const params = useParams();

    useEffect(() => {
        const username = params.username;

        if (username === loggedInUser.username) {
            window.location.href = "/profile";
        }

        if (username === undefined) {
            setPageType(PageType.Personal);
            setUser(loggedInUser);
            return;
        }

        getUser(username);
    }, []);

    async function getUser(username?: string) {
        let user = await UserAPI.get(username);

        if (user.success) {
            setUser(user.data);
            return;
        }

        user = await UserAPI.getGuest(username);

        if (user.success) {
            setUser(user.data);
        }
    }

    return (
        <>
            <ProfilePageHeader user={user} pageType={pageType} />
            <div className="border-bottom border-white mt-5"></div>
            <section className="w-100">
                <ProfilePageBody user={user} pageType={pageType} />
            </section>
        </>
    );
}

export default ProfilePage;
