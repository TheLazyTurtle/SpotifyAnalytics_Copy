export type User = {
    id: string;
    firstName: string;
    lastName: string;
    email: string;
    username: string;
    isAdmin: boolean;
    imgUrl: string;
    isOwnAccount: boolean;
    following?: boolean;
    followersCount?: string;
    followingCount?: string;
    private: boolean;
    guest: boolean;
    hasFollowingRequest?: boolean;
}
