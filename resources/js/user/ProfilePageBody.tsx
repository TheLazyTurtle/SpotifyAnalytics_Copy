import { useContext } from "react";
import { LoggedInUserContext } from "../App";
import { Graph, graphs } from "../graph/Graphs";
import GraphWrapper from "../graph/GraphWrapper";
import { PageType } from "./ProfilePage";
import { User } from "./User";

interface ProfilePageBodyProps {
    user: User;
    pageType: PageType;
};

function ProfilePageBody({ user, pageType }: ProfilePageBodyProps) {
    const loggedInUser = useContext(LoggedInUserContext);

    const toShow = function() {
        if (pageType === PageType.Personal) {
            return true;
        }
        if (user.id === undefined) {
            return false;
        }
        if (!user.private) {
            return true;
        }

        if (!user.following) {
            if (!loggedInUser.is_admin) {
                return false;
            }
            return true;
        }

        return true;
    }

    function toLogin() {
        const currentPage = window.location.pathname.replace("/", "");
        return `/login?redirect=${currentPage}`;
    }

    return (
        <>
            {toShow() && new graphs().graphs.map((graph: Graph) => (
                < div key={graph.name} className="graph pt-3" id={graph.name} >
                    <GraphWrapper key={graph.name} name={graph.name} type={graph.type} value={graph.graphValue} inputFields={graph.inputFields} userID={user.id} />
                </div>
            ))}
            {!toShow() &&
                <div className="w-50 mx-auto">
                    <h3 className="text-white">This profile is private. Please {loggedInUser.guest ? <a href={toLogin()}>login</a> : <a>follow</a>}</h3>
                </div>
            }
        </>
    )
}

export default ProfilePageBody;

