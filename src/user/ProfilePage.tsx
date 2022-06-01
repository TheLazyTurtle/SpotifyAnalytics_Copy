import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { UserAPI } from "../api/UserAPI";
import { Graph, graphs } from "../graph/Graphs";
import GraphWrapper from "../graph/GraphWrapper";
import ProfilePageHeader from "./ProfilePageHeader";
import { User } from "./User";

export enum PageType {
    Personal,
    External,
};

// TODO: When a person goes to their own page using the external way then send them to their own page
// TODO: Make it that a non logged-in user can still view profile pages but will respect the private or public ness
function ProfilePage() {
    const [pageType, setPageType] = useState<PageType>(PageType.Personal);
    const [user, setUser] = useState<User>({} as User);
    const params = useParams();

    useEffect(() => {
        const username = params.username;

        if (username !== undefined) {
            setPageType(PageType.External);
            getExternalUser(username)
        } else {
            getUser();
        }
    }, []);

    async function getUser() {
        const user = await UserAPI.get();

        if (user.success) {
            user.data.is_own_account = 1;
            setUser(user.data)
        }
    }

    async function getExternalUser(username: string) {
        const user = await UserAPI.getExternal(username);

        if (user.success) {
            user.data.is_own_account = 0;
            setUser(user.data);
        }
    }

    return (
        <>
            <ProfilePageHeader user={user} pageType={pageType} />
            <div className="border-bottom border-white mt-5"></div>
            <section className="w-100">
                {/* Replace true with is admin check */}
                {(user.following || true) && graphs.map((graph: Graph) => (
                    <div key={graph.name} className="graph pt-3" id={graph.name}>
                        <GraphWrapper key={graph.name} name={graph.name} type={graph.type} value={graph.graphValue} inputFields={graph.inputFields} />
                    </div>
                ))}
            </section>
        </>
    );
}

export default ProfilePage;
