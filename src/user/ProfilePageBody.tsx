import { Graph, graphs } from "../graph/Graphs";
import GraphWrapper from "../graph/GraphWrapper";
import { PageType } from "./ProfilePage";
import { User } from "./User";

interface ProfilePageBodyProps {
    user: User;
    pageType: PageType;
};

function ProfilePageBody({ user, pageType }: ProfilePageBodyProps) {
    // TODO: THIS IS BIG BAD FIX WITH USER CHECKS ETC
    const admin = true;

    const toShow = function() {
        if (pageType === PageType.Personal) {
            return true;
        }
        if (user.user_id === undefined) {
            return false;
        }
        if (!user.private) {
            return true;
        }

        if (!user.following) {
            if (!admin) {
                return false;
            }
            return true;
        }

        return true;
    }

    return (
        <>
            {toShow() && new graphs().graphs.map((graph: Graph) => (
                < div key={graph.name} className="graph pt-3" id={graph.name} >
                    <GraphWrapper key={graph.name} name={graph.name} type={graph.type} value={graph.graphValue} inputFields={graph.inputFields} userID={user.user_id} />
                </div>
            ))}
        </>
    )
}

export default ProfilePageBody;

