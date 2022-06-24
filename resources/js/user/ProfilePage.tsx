import { useContext, useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { LoggedInUserContext, Response } from "../App";
import ProfilePageBody from "./ProfilePageBody";
import ProfilePageHeader from "./ProfilePageHeader";
import { User } from "./User";
import axios from "axios";

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
        if (loggedInUser.guest) {
        }
        const url = loggedInUser.guest ? `/api/user/guest/${username}` : `/api/user/${username}`;

        axios.get<Response<User>>(url).then((response) => {
            if (response.status === 200) {
                setUser(response.data.data);
            } else {
                setUser({ guest: true } as User);
            }
        });
        return;
    }

    return (
        <>
            {user.guest && <div>
                <h2 className="text-custom-green">User does no exists</h2>
            </div>}

            {!user.guest &&
                <>
                    <ProfilePageHeader user={user} pageType={pageType} />
                    <div className="border-bottom border-white mt-5"></div>
                    <section className="w-100">
                        <ProfilePageBody user={user} pageType={pageType} />
                    </section>
                </>
            }
        </>
    );
}

export default ProfilePage;
