import { UserAPI } from "../api/UserAPI";
import { PageType } from "./ProfilePage";
import { User } from "./User";

interface ProfilePageHeaderProps {
    user: User;
    pageType: PageType;
};

function Buttons(props: ProfilePageHeaderProps) {

    async function handleFollowage() {
        console.log(props.user)
        const result = await UserAPI.follow(props.user.user_id);

        if (result.success) {
            console.log("Handle follow")
            return window.location.reload();
        }
        console.log("To follow or not to follow");
    }

    function handleLogout() {
        console.log("IT'S A TRAP!! YOU CAN NEVER LEAVE AGAIN");
    }

    function openSettings() {
        console.log("You shall not EDIT!!!");
    }

    return (
        <div className="row">
            {props.pageType === PageType.Personal &&
                <>
                    <div className="col-4 col-md-2 mx-md-0">
                        <a className="btn btn-primary btn-skinny d-inline-block mx-2" onClick={openSettings}>Settings</a>
                    </div>
                    <div className="col-3 col-md-2 mx-2 mx-md-2">
                        <a className="btn btn-primary btn-skinny d-inline-block mx-2" onClick={handleLogout}>Logout</a>
                    </div>
                </>
            }
            {props.pageType === PageType.External &&
                // TODO: Add a check to see if a person is logged in. If they are not logged in than remove this button or give the user a login screen to log in
                <div className="col-12">
                    <button className="btn btn-primary btn-skinny d-inline-block mx-2" onClick={handleFollowage}>{props.user.following ? "Unfollow" : "Follow"}</button>
                </div>
            }
        </div>
    );
}

function ProfilePageHeader(props: ProfilePageHeaderProps) {

    // TODO: Extract elements into their own function (those functions don't even need to be exported)
    return (
        <div className="container p-0 px-md-5">
            <div key="info-wrapper" className="w-100 w-md-50 mx-auto">
                <div className="row small-row">
                    <div key="img-wrapper" className="col-md-3 p-0 mt-md-5">
                        <img className="user-img" src={props.user.img_url} />
                    </div>
                    <div key="text-wrapper" className="col-12 col-md-9 mt-3 mt-md-5">
                        <h1 className="text-white px-2">{props.user.username}</h1>
                        <div className="row">
                            <div className="col-4 col-md-2 mx-2">
                                <p className="text-white"><strong>{props.user.followers_count}</strong> Followers</p>
                            </div>
                            <div className="col-4 col-md-2">
                                <p className="text-white"><strong>{props.user.following_count}</strong> Following</p>
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
