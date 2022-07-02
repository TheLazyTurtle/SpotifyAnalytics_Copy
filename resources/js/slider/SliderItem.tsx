import { useEffect, useState } from "react";
import { useQuery } from "react-query";
import { PlayedAPI } from "../api/PlayedAPI";
import { convertTime, msToTime, TimeFrame } from "../dates";
import { SliderItem, SliderItemName } from "./SliderItems";

interface SliderItemProps {
    sliderItem: SliderItem;
    timeFrame: TimeFrame;
    position: string;
};

function SliderItemComponent({ sliderItem, timeFrame, position }: SliderItemProps) {
    const [ready, setReady] = useState<boolean>();
    const { data } = useQuery(["sliderItemData", sliderItem.name, timeFrame], () => chooseEndpoint(sliderItem.name, timeFrame));

    useEffect(() => {
        if (data === undefined) {
            return;
        }

        setReady(false);
    }, [data]);


    useEffect(() => {
        if (data === undefined) {
            return;
        }

        const newData = data.data.data;

        if (sliderItem.name === SliderItemName.timeListened) {
            sliderItem.sliderItemData.countValue = msToTime(newData[0].y.toString() ?? "0");
        } else {
            sliderItem.sliderItemData.countValue = newData[0]?.y.toString() ?? "0";
        }

        sliderItem.sliderItemData.imgUrl = newData[0]?.imgUrl ?? sliderItem.defaultImgUrl;
        sliderItem.sliderItemData.nameValue = newData[0]?.x ?? "";
        setReady(true);

        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [ready]);

    async function chooseEndpoint(sliderItemName: SliderItemName, timeFrame: TimeFrame) {
        const { minDate, maxDate } = convertTime(timeFrame);

        switch (sliderItemName) {
            case SliderItemName.timeListened:
                return await PlayedAPI.timeListened(minDate, maxDate);
            case SliderItemName.topSongs:
                return await PlayedAPI.topSongs(minDate, maxDate, "1");
            case SliderItemName.topArtists:
                return await PlayedAPI.topArtist(minDate, maxDate, "1");
            case SliderItemName.amountSongs:
                return await PlayedAPI.amountSongs(minDate, maxDate);
            case SliderItemName.amountNewSongs:
                return await PlayedAPI.amountNewSongs(minDate, maxDate);
        }
    }

    return (
        <>
            <div key={sliderItem.name} className={`gallery-item gallery-item-${position}`}>
                <h5 id={sliderItem.name.toString()} className="gallery-header">
                    {
                        sliderItem.sliderItemData.templateText
                            .replace("{{timeFrame}}", timeFrame.toLowerCase())
                            .replace("{{count}}", sliderItem.sliderItemData.countValue)
                            .replace("{{name}}", sliderItem.sliderItemData.nameValue)
                    }
                </h5>
                <img id={sliderItem.sliderItemData.imgName} className="gallery-img" src={sliderItem.sliderItemData.imgUrl ?? sliderItem.defaultImgUrl} alt={sliderItem.sliderItemData.imgName} />
            </div>
        </>
    )
}

export default SliderItemComponent;
