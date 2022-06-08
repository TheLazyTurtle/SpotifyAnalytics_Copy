export type User = {
    user_id: string;
    username: string;
    is_admin: boolean;
    img_url: string;
    jwt?: string;
    is_own_account: boolean;
    following?: boolean;
    followers_count?: string;
    following_count?: string;
    private: boolean;
    guest: boolean;
}
