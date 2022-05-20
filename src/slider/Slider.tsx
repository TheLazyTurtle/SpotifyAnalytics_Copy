import { SliderItem, SliderPositions, SliderItems } from "./SliderItem";
import "./slider.css";
import { useEffect, useState } from "react";
import { convertTime, msToTime, TimeFrame } from "../dates";
import ButtonWrapper from "../button/ButtonWrapper";
import { SliderAPI } from "./SliderAPI";

function Slider() {
    const [sliderItems, setSliderItems] = useState<SliderItem[]>(SliderItems);
    const [timeFrame, setTimeFrame] = useState<TimeFrame>(TimeFrame.today);

    const prevItem = () => {
        const updated = sliderItems.map((sliderItem: SliderItem) => {
            const index = sliderItem.index === 4 ? 0 : sliderItem.index + 1;
            return { ...sliderItem, index: index };
        });

        setSliderItems(updated);
    }

    const nextItem = () => {
        const updated = sliderItems.map((sliderItem: SliderItem) => {
            const index = sliderItem.index === 0 ? 4 : sliderItem.index - 1;
            return { ...sliderItem, index: index };
        });

        setSliderItems(updated);
    }

    const handleTimeFrameChange = async (value: TimeFrame) => {
        setTimeFrame(value);

        const {minDate, maxDate} = convertTime(value);

        const updated = await Promise.all(sliderItems.map(async (sliderItem: SliderItem) => {
            try {
                const res = await SliderAPI.getData(sliderItem.apiUrl, "11182819693", minDate, maxDate);
                const countValue = sliderItem.name === "timeListened" ? msToTime(res[0].y) : res[0].y;
                const sliderItemData = {...sliderItem.sliderItemData, nameValue: res[0].label, countValue: countValue, imgUrl: res[0].img};

                return {...sliderItem, sliderItemData: sliderItemData};
            } catch (Error) {
                const countValue = sliderItem.name === "timeListened" ? "00:00:00" : "0";
                const sliderItemData = {...sliderItem.sliderItemData, nameValue: "", countValue: countValue, imgUrl: sliderItem.defaultImgUrl};

                return {...sliderItem, sliderItemData: sliderItemData};
            }
        }));

        setSliderItems(updated);
    }

    return (
        <>
            <div className="gallery mt-5">
                <div className="gallery-container">
                    {sliderItems.map((sliderItem: SliderItem) => {
                        const position = SliderPositions[sliderItem.index];
                        const className = `gallery-item gallery-item-${position}`;
                        const sliderText = sliderItem.sliderItemData.templateText.replace("{{timeFrame}}", timeFrame.toLowerCase()).replace("{{count}}", sliderItem.sliderItemData.countValue).replace("{{name}}", sliderItem.sliderItemData.nameValue);
                        const img = sliderItem.sliderItemData.imgUrl === undefined ? sliderItem.defaultImgUrl : sliderItem.sliderItemData.imgUrl;

                        return (
                            <div key={sliderItem.name} className={className}>
                                <h5 id={sliderItem.name} className="gallery-header">{sliderText}</h5>
                                <img id={sliderItem.sliderItemData.imgName} className="gallery-img" src={img} alt={sliderItem.sliderItemData.imgName} />
                            </div>
                        )
                    })}
                </div>
                <div className="gallery-controls">
                    <button className="gallery-controls-previous btn btn-primary" onClick={prevItem}>Previous</button>
                    <button className="gallery-controls-next btn btn-primary" onClick={nextItem}>Next</button>
                </div>
            </div>
            <div className="gallery-time-buttons">
                <ButtonWrapper onClick={handleTimeFrameChange}/>
            </div>
        </>
    );
}

export default Slider;
