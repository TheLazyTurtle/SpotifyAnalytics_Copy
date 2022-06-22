export type User = {
    id: string;
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
