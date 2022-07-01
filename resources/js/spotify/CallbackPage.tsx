import axios from "axios";

function CallbackPage() {
    function getCode() {
        const searchParams = window.location.search.replace("?", "");
        let list = searchParams.split("&");

        return list.find((item: string) => {
            let keyValue = item.split("=");

            if (keyValue[0] === "code") return keyValue;
            return null;
        });
    }

    axios.post("/api/spotifyTokens/add", getCode()).then((result) => {
        if (result.status === 200) {
            window.location.href = "/";
        }
    }).catch(() => {
        window.location.href = "/";
    });

    return (
        <></>
    );
}

export default CallbackPage;
