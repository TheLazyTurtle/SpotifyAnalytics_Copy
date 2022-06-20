import { Artist } from "../artist/Artist";
import { Song } from "../song/Song";

export type Album = {
    id: string;
    name: string;
    releaseDate: string;
    albumArtist: Artist;
    url: string;
    imgUrl: string;
    type: string;
    songs: Song[];
}
