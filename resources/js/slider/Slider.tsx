import { SliderItem, SliderPositions, sliderItems } from "./SliderItems";
import "./slider.css";
import { useState } from "react";
import { TimeFrame } from "../dates";
import ButtonWrapper from "../button/ButtonWrapper";
import SliderItemComponent from "./SliderItem";

function Slider() {
    const [sliderItemList, setSliderItemList] = useState<SliderItem[]>(sliderItems);
    const [timeFrame, setTimeFrame] = useState<TimeFrame>(TimeFrame.today);

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
