import { SliderItem, SliderPositions, SliderItems } from "./SliderItem";
import "./slider.css";
import { useEffect, useState } from "react";
import { convertTime, msToTime, TimeFrame } from "../dates";
import ButtonWrapper from "../button/ButtonWrapper";
import { Cacher } from "../cacher";

function Slider() {
    const [sliderItems, setSliderItems] = useState<SliderItem[]>(SliderItems);
    const [timeFrame, setTimeFrame] = useState<TimeFrame>(TimeFrame.today);

    useEffect(() => {
        handleTimeFrameChange(timeFrame)
    }, []);

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

    const handleTimeFrameChange = async (timeFrame: TimeFrame) => {
        setTimeFrame(timeFrame);
        const cached = getCache(timeFrame);

        if (cached === false) {
            const updated = await getData(sliderItems, timeFrame);

            setCache(updated, timeFrame);
            setSliderItems(updated);
            return;
        }

        setSliderItems(cached);
    }

    const setCache = (data: SliderItem[], timeFrame: TimeFrame) => {
        let purged: any = {};

        for (let i = 0; i < data.length; i++) {
            const sliderItem = data[i];
            purged[sliderItem.name] = sliderItem.sliderItemData;
        }

        Cacher.setItem("sliderItems", purged, timeFrame);
    }

    const getCache = (timeFrame: TimeFrame) => {
        const cached = Cacher.getItem("sliderItems", true, timeFrame).value;

        if (cached === undefined) {
            return false;
        }

        if (Object.keys(cached).length <= 0) {
            return false;
        }

        const updated = sliderItems.map((sliderItem: SliderItem) => {
            return { ...sliderItem, sliderItemData: cached[sliderItem.name] }
        });

        return updated;
    }

    const getData = async (sliderItems: SliderItem[], timeFrame: TimeFrame) => {
        const { minDate, maxDate } = convertTime(timeFrame);
        return await Promise.all(sliderItems.map(async (sliderItem: SliderItem) => {
            try {
                const res = await sliderItem.apiFunction(minDate, maxDate);
                const countValue = sliderItem.name === "timeListened" ? msToTime(res.data[0].y) : res.data[0].y;
                const imgUrl = res.data[0].img_url === null ? sliderItem.defaultImgUrl : res.data[0].img_url;
                const sliderItemData = { ...sliderItem.sliderItemData, nameValue: res.data[0].label, countValue: countValue, imgUrl: imgUrl };

                return { ...sliderItem, sliderItemData: sliderItemData };
            } catch (e) {
                const countValue = sliderItem.name === "timeListened" ? "00:00:00" : "0";
                const sliderItemData = { ...sliderItem.sliderItemData, nameValue: "", countValue: countValue, imgUrl: sliderItem.defaultImgUrl };

                return { ...sliderItem, sliderItemData: sliderItemData };
            }
        }));
    }

    return (
        <>
            <div className="gallery mt-md-5">
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
                <ButtonWrapper onClick={handleTimeFrameChange} />
            </div>
            <div className="border-bottom border-white mt-5">
            </div>
        </>
    );
}

export default Slider;
