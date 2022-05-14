import { Artist } from "../artist/Artist";
import { Song } from "../song/Song";

export class Album {
    id: string | undefined;
    name: string = "";
    releaseDate: string = "";
    img: string = "";
    albumArtists: Artist[] = [];
    url: string = "";
    type: string = "";
    songs: Song[] = [];
    primaryArtistID: string = "";

    constructor(initializer?: any) {
        if (!initializer) return;
        if (initializer.id) this.id = initializer.id;
        if (initializer.name) this.name = initializer.name;
        if (initializer.releaseDate) this.releaseDate = initializer.releaseDate;
        if (initializer.img) this.img = initializer.img;
        if (initializer.albumArtists) this.albumArtists = initializer.albumArtists;
        if (initializer.url) this.url = initializer.url;
        if (initializer.type) this.type = initializer.type;
        if (initializer.songs) this.songs = initializer.song;
        if (initializer.primaryArtistID) this.primaryArtistID = initializer.primaryArtistID;
    }
}
