import { Artist } from "../artist/Artist";

export type Song = {
    id: string;
    name: string;
    length: number;
    url: string;
    imgUrl: string;
    previewUrl: string;
    trackNumber: number;
    explicit: boolean;
    artists?: Artist[];
}
