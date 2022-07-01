import { redirectToLogin } from "../App";
import { Graph, graphs } from "../graph/Graphs";
import GraphWrapper from "../graph/GraphWrapper";
import { PageType } from "./ProfilePage";
import { User } from "./User";

interface ProfilePageBodyProps {
    user: User;
    pageType: PageType;
};

function ProfilePageBody({ user, pageType }: ProfilePageBodyProps) {

    const toShow = function() {
        if (pageType === PageType.Personal) {
            return true;
        }
        if (!user.private) {
            return true;
        }
        if (user.following) {
            return true;
        }
        return false;
    }

    return (
        <>
            {toShow() && new graphs().graphs.map((graph: Graph) => (
                < div key={graph.name} className="graph pt-3" id={graph.name} >
                    <GraphWrapper key={graph.name} graph={graph} userId={user.isOwnAccount ? undefined : user.id} />
                </div>
            ))}
            {!toShow() &&
                <div className="w-50 mx-auto">
                    <h3 className="text-white">This profile is private. Please {user.guest ? <a href={redirectToLogin(true)}>login</a> : <a>follow</a>}</h3>
                </div>
            }
        </>
    )
}

export default ProfilePageBody;

