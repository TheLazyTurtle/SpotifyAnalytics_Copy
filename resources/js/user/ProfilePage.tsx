import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { redirectToLogin, Response } from "../App";
import ProfilePageBody from "./ProfilePageBody";
import ProfilePageHeader from "./ProfilePageHeader";
import { User } from "./User";
import axios from "axios";

export enum PageType {
    Personal,
    External,
};

function ProfilePage() {
    const [pageType, setPageType] = useState<PageType>(PageType.External);
    const [user, setUser] = useState<User>({} as User);
    const params = useParams();

    useEffect(() => {
        getUser(params.username);
    }, [params.username]);

    async function getUser(username?: string) {
        axios.get<Response<User>>(`/api/user${username === undefined ? "" : "/" + username}`).then((response) => {
            if (response.status === 200) {
                if (response.data.data.isOwnAccount) {
                    setPageType(PageType.Personal);
                }
                setUser(response.data.data);
            } else {
                setUser({ guest: true } as User);
            }
        }).catch((error) => {
            if (error.response.status === 401) redirectToLogin();
            setUser({ guest: true } as User);
        });
        return;
    }

    return (
        <>
            {user.guest && <div>
                <h2 className="text-custom-green">User does no exists</h2>
            </div>}

            {user.id !== undefined &&
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
