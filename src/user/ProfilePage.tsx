import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { UserAPI } from "../api/UserAPI";
import ProfilePageBody from "./ProfilePageBody";
import ProfilePageHeader from "./ProfilePageHeader";
import { User } from "./User";

export enum PageType {
    Personal,
    External,
};

// TODO: When a person goes to their own page using the external way then send them to their own page (this requires the global thingy or something to check if the user is logged in etc)
// TODO: Make it that a non logged-in user can still view profile pages but will respect the private or public ness (this should be done on the server)
function ProfilePage() {
    const [pageType, setPageType] = useState<PageType>(PageType.External);
    const [user, setUser] = useState<User>({} as User);
    const params = useParams();

    useEffect(() => {
        const username = params.username;

        if (username === undefined) {
            setPageType(PageType.Personal);
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
