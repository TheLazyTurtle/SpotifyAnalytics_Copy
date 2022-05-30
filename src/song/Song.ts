import { Artist } from "../artist/Artist";

export type Song = {
    id: string;
    name: string;
    length: number;
    url: string;
    img_url: string;
    preview_url: string;
    track_number: number;
    explicit: boolean;
    artists: Artist[];
}
