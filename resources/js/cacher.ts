import { TimeFrame } from "./dates";
import { FilterSetting } from "./inputField/FilterSetting";

export class Cacher {
    static setItem(key: string, data: any, timeFrame: TimeFrame | null = null, filterSettings: FilterSetting | null = null) {
        const existingData = Cacher.getItem(key, false);

        // If there is no time frame than just save it
        if (timeFrame === null) {
            localStorage.setItem(key, JSON.stringify(data));
            return;
        }

        if (Object.keys(existingData).length <= 0) {
            const wrapper: any = {};

            if (filterSettings === null) {
                wrapper[timeFrame] = { dateAdded: new Date().getTime(), data };
            } else {
                wrapper[timeFrame] = { dateAdded: new Date().getTime(), data, filterSettings };
            }

            localStorage.setItem(key, JSON.stringify(wrapper));
            return;
        }

        if (filterSettings === null) {
            existingData[timeFrame] = { dateAdded: new Date().getTime(), data };
        } else {
            existingData[timeFrame] = { dateAdded: new Date().getTime(), data, filterSettings };
        }

        localStorage.setItem(key, JSON.stringify(existingData));
        return;
    };

    static getItem(key: string, timeDependend: boolean = false, timeFrame: TimeFrame | null = null) {
        if (timeDependend) {
            // If new hour has started update because the fetcher just ran
            var minutes = new Date().getMinutes();
            if (minutes >= 0 && minutes <= 3) {
                return JSON.parse("{}");
            }
        }

        const data = localStorage.getItem(key);
        const json = JSON.parse(data || "{}");

        if (timeFrame !== null) {
            if (json[timeFrame] === undefined) {
                return JSON.parse("{}");
            }

            if (new Date(json[timeFrame].dateAdded).getTime() + 3600000 <= new Date().getTime()) {
                return JSON.parse("{}");
            }
            return json[timeFrame];
        }

        return json;
    };
}
