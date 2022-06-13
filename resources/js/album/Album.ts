import { Artist } from "../artist/Artist";
import { Song } from "../song/Song";

export type Album = {
    id: string | undefined;
    name: string;
    releaseDate: string;
    img_url: string;
    album_artist: Artist;
    url: string;
    type: string;
    songs: Song[];
    primaryArtist_id: string;
}
