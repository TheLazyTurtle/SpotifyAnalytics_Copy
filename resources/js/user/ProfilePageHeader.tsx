import axios from "axios";
import { PageType } from "./ProfilePage";
import { User } from "./User";

interface ProfilePageHeaderProps {
    user: User;
    pageType: PageType;
};

function Buttons(props: ProfilePageHeaderProps) {
    const user = props.user;

    function handleLogout() {
        axios.post("/logout").then(() => {
            window.location.href = "/login";
        });
    }

    function openSettings() {
        console.log("You shall not EDIT!!!");
    }

    return (
        <div className="row">
            {props.pageType === PageType.Personal &&
                <>
                    <div className="col-4 col-md-2 mx-md-0">
                        <button className="btn btn-primary btn-skinny d-inline-block mx-2" onClick={openSettings}>Settings</button>
                    </div>
                    <div className="col-3 col-md-2 mx-2 mx-md-2">
                        <button className="btn btn-primary btn-skinny d-inline-block mx-2" onClick={handleLogout}>Logout</button>
                    </div>
                </>
            }
            {(props.pageType === PageType.External) &&
                followButton(user)
            }
        </div>
    );
}

async function updateFollowingStatus(following_user_id: string) {
    return await axios.post("/api/user/follow", { following_user_id }).then((result) => result.status === 200);
}

async function makeNotification(notification_type_id: number, receiver_user_id: string) {
    return await axios.post("/api/notification/create", { notification_type_id, receiver_user_id }).then((result) => result.status === 200);
}

async function removeNotification(receiver_user_id: string) {
    return await axios.post("/api/notification/delete", { receiver_user_id }).then((result) => result.status === 200);
}

// TODO: This should use button component
function followButton(user: User) {
    async function handleFollowage() {
        // Remove request
        if (user.hasFollowingRequest) {
            if (await removeNotification(user.id)) return window.location.reload();
        }

        if (user.following) {
            // Unfollowing
            if (await updateFollowingStatus(user.id)) return window.location.reload();
        } else {
            // Follow
            if (user.private) {
                if (await makeNotification(0, user.id)) return window.location.reload();
            }
            if (await updateFollowingStatus(user.id)) {
                if (await makeNotification(1, user.id)) {
                    return window.location.reload();
                }
            }
        }
    }

    function makeButtonText() {
        if (user.following) {
            return "Unfollow";
        }

        if (user.hasFollowingRequest) {
            return "Request pending";
        }

        return "Follow";
    }

    return (
        <div className="col-12">
            <button className="btn btn-primary btn-skinny d-inline-block mx-2" onClick={handleFollowage}>{makeButtonText()}</button>
        </div>
    );

}

function ProfilePageHeader(props: ProfilePageHeaderProps) {
    return (
        <div className="container p-0 px-md-5">
            <div key="info-wrapper" className="w-100 w-md-50 mx-auto">
                <div className="row small-row">
                    <div key="img-wrapper" className="col-md-3 p-0 mt-md-5">
                        <img className="user-img" src={props.user.imgUrl} alt={props.user.username} />
                    </div>
                    <div key="text-wrapper" className="col-12 col-md-9 mt-3 mt-md-5">
                        <h1 className="text-white px-2">{props.user.username}</h1>
                        <div className="row">
                            <div className="col-4 col-md-2 mx-2">
                                <p className="text-white"><strong>{props.user.followersCount}</strong> Followers</p>
                            </div>
                            <div className="col-4 col-md-2">
                                <p className="text-white"><strong>{props.user.followingCount}</strong> Following</p>
                            </div>
                        </div>
                        {(props.user.username !== undefined) && <Buttons user={props.user} pageType={props.pageType} />}
                    </div>
                </div>
            </div>
        </div>
    );
}

export default ProfilePageHeader;
