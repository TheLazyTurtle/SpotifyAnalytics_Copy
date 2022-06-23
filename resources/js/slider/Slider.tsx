import { SliderItem, SliderPositions, sliderItems, SliderItemName } from "./SliderItems";
import "./slider.css";
import { useEffect, useState } from "react";
import { convertTime, msToTime, TimeFrame } from "../dates";
import ButtonWrapper from "../button/ButtonWrapper";
import SliderItemComponent from "./SliderItem";
import { useQuery } from "react-query";
import { PlayedAPI } from "../api/PlayedAPI";

function Slider() {
    const [sliderItemList, setSliderItemList] = useState<SliderItem[]>(sliderItems);
    const [timeFrame, setTimeFrame] = useState<TimeFrame>(TimeFrame.today);
    const { minDate, maxDate } = convertTime(timeFrame);
    const { data } = useQuery(["sliderItemData", timeFrame], () => PlayedAPI.sliderItemData(minDate, maxDate));

    useEffect(() => {
        if (data === undefined) {
            return;
        }

        const updatedList = sliderItemList.map((sliderItem: SliderItem) => {
            const newData = data[sliderItem.name];

            if (sliderItem.name === SliderItemName.timeListened) {
                sliderItem.sliderItemData.countValue = msToTime(newData?.y?.toString() ?? "0");
            } else {
                sliderItem.sliderItemData.countValue = newData?.y?.toString() ?? "0";
            }

            sliderItem.sliderItemData.imgUrl = newData?.imgUrl ?? sliderItem.defaultImgUrl;
            sliderItem.sliderItemData.nameValue = newData?.x ?? "";
            return sliderItem;
        });

        setSliderItemList(updatedList);

        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [data]);

    const prevItem = () => {
        const updated = sliderItemList.map((sliderItem: SliderItem) => {
            const index = sliderItem.index === 4 ? 0 : sliderItem.index + 1;
            return { ...sliderItem, index: index };
        });

        setSliderItemList(updated);
    }

    const nextItem = () => {
        const updated = sliderItemList.map((sliderItem: SliderItem) => {
            const index = sliderItem.index === 0 ? 4 : sliderItem.index - 1;
            return { ...sliderItem, index: index };
        });

        setSliderItemList(updated);
    }

    const handleTimeFrameChange = async (timeFrame: TimeFrame) => {
        setTimeFrame(timeFrame);
    }


    return (
        <>
            <div className="gallery mt-md-5">
                <div className="gallery-container">
                    {sliderItemList.map((sliderItem: SliderItem) => {
                        const position = SliderPositions[sliderItem.index];
                        return <SliderItemComponent key={sliderItem.name} sliderItem={sliderItem} position={position} timeFrame={timeFrame} />;
                    })}
                </div>
                <div className="gallery-controls">
                    <button className="gallery-controls-previous btn btn-primary" onClick={prevItem}>Previous</button>
                    <button className="gallery-controls-next btn btn-primary" onClick={nextItem}>Next</button>
                </div>
            </div>
            <div className="gallery-time-buttons">
                <ButtonWrapper onClick={handleTimeFrameChange} />
            </div>
            <div className="border-bottom border-white mt-5">
            </div>
        </>
    );
}

export default Slider;
