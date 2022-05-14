import { Artist } from "../artist/Artist";

export class Song {
    id: string = '';
    name: string = '';
    length: number = 0;
    url: string = '';
    img: string = '';
    preview: string = '';
    trackNumber: number = 0;
    explicit: boolean = false;
    artists: Artist[] = [];

    constructor(initializer?: any) {
        if (!initializer) return;
        if (initializer.id) this.id = initializer.id;
        if (initializer.name) this.name = initializer.name;
        if (initializer.length) this.length = initializer.length;
        if (initializer.img) this.img = initializer.img;
        if (initializer.preview) this.preview = initializer.preview;
        if (initializer.trackNumber) this.trackNumber = initializer.trackNumber;
        if (initializer.explicit) this.explicit = initializer.explicit;
        if (initializer.artists) this.artists = initializer.artists;
    }
}
