export class Artist {
    artistID: string = "";
    name: string = "";
    url: string = "";
    img: string = "";

    constructor(initializer?: any) {
        if (!initializer) return;
        if (initializer.artistID) this.artistID = initializer.artistID;
        if (initializer.name) this.name = initializer.name;
        if (initializer.url) this.url = initializer.url;
        if (initializer.img) this.img = initializer.img;
    }
}
