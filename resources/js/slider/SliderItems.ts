export type SliderItem = {
    name: SliderItemName;
    defaultImgUrl: string;
    sliderItemData: SliderItemData;
    index: number;
};

export type SliderItemData = {
    templateText: string;
    countValue: string;
    nameValue: string;
    imgUrl?: string;
    imgName: string;
};

export enum SliderPositions {
    nextLeft,
    left,
    selected,
    right,
    nextRight
};

export enum SliderItemName {
    topSongs,
    topArtists,
    timeListened,
    amountSongs,
    amountNewSongs
}

export const sliderItems: SliderItem[] = [
    {
        name: SliderItemName.topSongs,
        defaultImgUrl: "https://fakeimg.pl/300/?text=No top song",
        index: 0,
        sliderItemData: {
            templateText: "Top song {{timeFrame}}: {{name}} - {{count}}",
            countValue: "0",
            nameValue: "no song",
            imgName: "topSongsImg",
        }
    },
    {
        name: SliderItemName.topArtists,
        defaultImgUrl: "https://fakeimg.pl/300/?text=No top artist",
        index: 1,
        sliderItemData: {
            templateText: "Top artist {{timeFrame}}: {{name}} - {{count}}",
            countValue: "0",
            nameValue: "no artist",
            imgName: "topArtistImg"
        }
    },
    {
        name: SliderItemName.timeListened,
        defaultImgUrl: "https://i.pinimg.com/736x/f9/4c/95/f94c9574933ce9404f323fb58f5e7f5c.jpg",
        index: 2,
        sliderItemData: {
            templateText: "Time listend {{timeFrame}}: {{count}}",
            countValue: "00:00:00",
            nameValue: "",
            imgName: "timeListenedImg"
        }
    },
    {
        name: SliderItemName.amountSongs,
        defaultImgUrl: "/storage/onRepeat.jpg",
        index: 3,
        sliderItemData: {
            templateText: "Total songs listend {{timeFrame}}: {{count}}",
            countValue: "0",
            nameValue: "",
            imgName: "amountSongs"
        }
    },
    {
        name: SliderItemName.amountNewSongs,
        defaultImgUrl: "https://fakeimg.pl/300/?text=No new songs",
        index: 4,
        sliderItemData: {
            templateText: "New songs {{timeFrame}}: {{count}}",
            countValue: "0",
            nameValue: "",
            imgName: "amountNewSongs"
        }
    },
];
