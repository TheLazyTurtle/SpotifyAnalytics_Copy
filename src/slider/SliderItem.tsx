export type SliderItem = {
    name: string;
    apiUrl: string;
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
}

export const SliderItems: SliderItem[] = [
    {
        name: "topSongs",
        apiUrl: "/played/topSongs.php",
        defaultImgUrl: "https://fakeimg.pl/300/?text=No top song",
        index: 0,
        sliderItemData: {
            templateText: "Top song {{timeFrame}}: {{name}} - {{count}}",
            countValue: "0",
            nameValue: "no song",
            imgName: "topSongsImg"
        }
    },
    {
        name: "topArtist",
        apiUrl: "/artist/topArtist.php",
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
        name: "timeListened",
        apiUrl: "/played/timeListend.php",
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
        name: "amountSongs",
        apiUrl: "/played/amountSongs.php",
        defaultImgUrl: "./onRepeat.jpg",
        index: 3,
        sliderItemData: {
            templateText: "Total songs listend {{timeFrame}}: {{count}}",
            countValue: "0",
            nameValue: "",
            imgName: "amountSongs"
        }
    },
    {
        name: "amountNewSongs",
        apiUrl: "/played/amountNewSongs.php",
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
