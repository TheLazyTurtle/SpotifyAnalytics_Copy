import { TimeFrame } from "../dates";
import { SliderItem } from "./SliderItems";

interface SliderItemProps {
    sliderItem: SliderItem;
    timeFrame: TimeFrame;
    position: string;
};

function SliderItemComponent({ sliderItem, timeFrame, position }: SliderItemProps) {
    return (
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
    )
}

export default SliderItemComponent;
