export enum TimeFrame {
    yesterday = "Yesterday",
    today = "Today",
    week = "This week",
    month = "This month",
    year = "This year",
    allTime = "All time"
};

// Converts the timeframe button values to actual dates
const convertTime = function (timeframe: TimeFrame): {minDate: string, maxDate: string} {
    let minDate: string = "2020-01-01"
    let maxDate:string = "2099-01-01"

    if (timeframe === TimeFrame.yesterday) {
        minDate = formatDate(
            0,
            0,
            true,
            new Date(new Date().setDate(new Date().getDate() - 1))
        )
        maxDate = formatDate()
    } else if (timeframe === TimeFrame.today) {
        minDate = formatDate()
        maxDate = formatDate(0, 0, false)
    } else if (timeframe === TimeFrame.week) {
        minDate = lastSunday()
        maxDate = formatDate(0, 0, false)
    } else if (timeframe === TimeFrame.month) {
        minDate = startMonth()
        maxDate = formatDate(0, 0, false)
    } else if (timeframe === TimeFrame.year) {
        minDate = startYear()
        maxDate = formatDate(0, 0, false)
    } else if (timeframe === TimeFrame.allTime) {
        minDate = "2020-01-01"
        maxDate = formatDate(0, 0, false)
    }

    return { minDate: minDate, maxDate: maxDate }
}

// Formats the days to a format SQL can use
function formatDate(
    plusDay = 0,
    plusMonth = 0,
    startOfDay = true,
    date = new Date()
) {
    let d = new Date(date),
        month = "" + (d.getMonth() + Number(plusMonth) + plusMonth + 1),
        day = "" + (d.getDate() + Number(plusDay)),
        year = d.getFullYear(),
        hour = "23:00:00"

    if (month.length < 2) {
        month = "0" + month
    }
    if (day.length < 2) {
        day = "0" + day
    }
    if (startOfDay) {
        hour = "00:00:00"
    }

    let time = [year, month, day].join("-")
    time += " " + hour
    return time
}

// Get the previouse sunday
function lastSunday(startOfDay = true) {
    let d = new Date()
    let day = d.getDay(),
        diff = d.getDate() - day + (day === 0 ? -6 : 0)
    return formatDate(0, 0, startOfDay, new Date(d.setDate(diff)))
}

// Get the start of the month date
function startMonth(startOfDay = true) {
    let d = new Date()
    let month = d.getMonth(),
        diff = d.getMonth() - month + (month === 0 ? -12 : 1)
    return formatDate(0, 0, startOfDay, new Date(d.setDate(diff)))
}

// Get the start of the year date
function startYear(startOfDay = true) {
    let d = new Date()
    let year = d.getFullYear() + "-01-01"
    return formatDate(0, 0, startOfDay, new Date(year))
}

function msToTime(s: string) {
    if (s === null) {
        return "00:00:00"
    }

    let t = parseInt(s)

    let ms = t % 1000
    t = (t - ms) / 1000
    let secs = t % 60
    t = (t - secs) / 60
    let mins = t % 60
    let hrs = (t - mins) / 60

    let minsOut = mins <= 9 ? "0" + mins : mins;
    let secsOut = secs <= 9 ? "0" + secs : secs;

    return hrs + ":" + minsOut + ":" + secsOut
}

export { convertTime, msToTime };
