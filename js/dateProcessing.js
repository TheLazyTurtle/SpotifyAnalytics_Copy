// Converts the timeframe button values to actual dates
function convertTime(timeframe) {
    var minDate = "2020-01-01"
    var maxDate = "2099-01-01"

    if (timeframe == "yesterday") {
        minDate = formatDate(
            0,
            0,
            true,
            new Date().setDate(new Date().getDate() - 1)
        )
        maxDate = formatDate()
    } else if (timeframe == "today") {
        minDate = formatDate()
        maxDate = formatDate(0, 0, false)
    } else if (timeframe == "week") {
        minDate = lastSunday()
        maxDate = formatDate(0, 0, false)
    } else if (timeframe == "month") {
        minDate = startMonth()
        maxDate = formatDate(0, 0, false)
    } else if (timeframe == "year") {
        minDate = startYear()
        maxDate = formatDate(0, 0, false)
    } else if (timeframe == "allTime") {
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
    var d = new Date(date),
        month = "" + (d.getMonth() + Number(plusMonth) + plusMonth + 1),
        day = "" + (d.getDate() + Number(plusDay)),
        year = d.getFullYear(),
        hour = "T" + 23

    if (month.length < 2) {
        month = "0" + month
    }
    if (day.length < 2) {
        day = "0" + day
    }
    if (startOfDay) {
        hour = "T" + 00
    }

    time = [year, month, day].join("-")
    time += " " + hour
    return time
}

// Get the previouse sunday
function lastSunday(startOfDay = true) {
    d = new Date()
    var day = d.getDay(),
        diff = d.getDate() - day + (day == 0 ? -6 : 0)
    return formatDate(0, 0, startOfDay, new Date(d.setDate(diff)))
}

// Get the start of the month date
function startMonth(startOfDay = true) {
    d = new Date()
    var month = d.getMonth(),
        diff = d.getMonth() - month + (month == 0 ? -12 : 1)
    return formatDate(0, 0, startOfDay, new Date(d.setDate(diff)))
}

// Get the start of the year date
function startYear(startOfDay = true) {
    d = new Date()
    year = d.getFullYear() + "-01-01"
    return formatDate(0, 0, startOfDay, new Date(year))
}
